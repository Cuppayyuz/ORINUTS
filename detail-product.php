<?php
session_start();

// input ulasan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Cek apakah user sudah login
        if (!isset($_SESSION['user'])) {
            echo "<script>alert('Anda harus login dahulu!');</script>";
        } else {
            // Ambil gambar jika ada
            $image = !empty($_FILES['image']['tmp_name']) ? file_get_contents($_FILES['image']['tmp_name']) : null;
            
            $pdo = require 'koneksi.php';
            $sql = "INSERT INTO reviews (fullname, ulasan, image, rating, tanggal, produk_id, user_id) 
                    VALUES (:fullname, :ulasan, :image, :rating, NOW(), :produk_id, :user_id)";
            $query = $pdo->prepare($sql);
            
            try {
                $query->execute([
                    'fullname' => trim($_POST['fullname']),
                    'ulasan' => trim($_POST['ulasan']),
                    'image' => $image,
                    'rating' => (int)$_POST['rating'],
                    'produk_id' => $_GET['id'],
                    'user_id' => $_SESSION['user']['id']
                ]);
                
                // Redirect untuk mencegah resubmit saat refresh
                header("Location: detail-product.php?id=" . $_GET['id'] . "&success=1");
                exit();
                
            } catch (PDOException $e) {
                echo "<script>alert('Gagal menyimpan ulasan: " . $e->getMessage() . "');</script>";
            }
        }
}

// Tampilkan pesan sukses jika ada
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Ulasan berhasil dikirim!'); window.location.href='detail-product.php?id=" . $_GET['id'] . "';</script>";
}

// rating
function getRatingBreakdown($productId)
{
    $pdo = require 'koneksi.php';
    $sql = "
        SELECT 
            rating,
            COUNT(*) as count,
            ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM reviews WHERE produk_id = ?))) as percentage
        FROM reviews
        WHERE produk_id = ?
        GROUP BY rating
        ORDER BY rating DESC
    ";

    $query = $pdo->prepare($sql);
    $query->execute([$productId, $productId]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $breakdown = [];
    for ($i = 5; $i >= 1; $i--) {
        $breakdown[$i] = [
            'rating' => $i,
            'count' => 0,
            'percentage' => 0
        ];
    }

    foreach ($results as $row) {
        $breakdown[$row['rating']] = $row;
    }

    return $breakdown;
}

// rata rata rating
$pdo = require 'koneksi.php';
$sqlrata = "
    SELECT 
        ROUND(AVG(rating), 1) as avg_rating,
        COUNT(*) as total_reviews
    FROM reviews 
    WHERE produk_id = ?
";
$queryrata = $pdo->prepare($sqlrata);
$queryrata->execute([$_GET['id']]);
$rataRata = $queryrata->fetch();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Produk - Orinuts Roasted Cashew</title>
    <link rel="stylesheet" href="src/outputail.css" />
    <style>
        /* Gaya tambahan yang sudah Anda sediakan */
        header {
            background: rgba(220, 213, 185, 0.15);
        }

        #hamburger-button span {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        #hamburger-button.open span:nth-child(1) {
            transform: translateY(10px) rotate(45deg);
        }

        #hamburger-button.open span:nth-child(2) {
            opacity: 0;
        }

        #hamburger-button.open span:nth-child(3) {
            transform: translateY(-10px) rotate(-45deg);
        }

        /* Gaya untuk bintang */
        .star-filled {
            color: #FFC700;
        }

        .star-empty {
            color: #D3D3D3;
        }
    </style>
</head>

<body class="bg-[#F4EFD8] w-full min-h-screen pt-20">
    <header id="navbar"
        class="fixed left-0 right-0 top-0 z-50 py-2 transition-all duration-300 bg-[#DCD5B9]/90 backdrop-blur-sm">
        <div class="container mx-auto px-6 md:px-16 flex justify-between items-center">
            <div>
                <img src="/content/logo.png" alt="logo" class="h-14" />
            </div>

            <nav class="hidden lg:flex py-1 px-2 rounded-full bg-white/30 backdrop-blur-md">
                <a href="/index.html" class=" rounded-full py-2 px-8 font-semibold">HOME</a>
                <a href="/about.html" class=" rounded-full py-2 px-8 font-semibold hover:text-green-700">ABOUT
                    US</a>
                <a href="/p_orinuts.html"
                    class=" bg-white rounded-full py-2 px-8 font-semibold hover:text-green-700">PRODUCT</a>
                <a href="/contact.html"
                    class="rounded-full py-2 px-8 font-semibold hover:text-green-700">CONTACT</a>
            </nav>

            <div class="hidden lg:flex items-center space-x-4">
                <a href="#">
                    <img src="/content/icon/shopping-cart.svg" alt="cart" class="h-7 w-7" />
                </a>
                <?php if (!isset($_SESSION['user'])) { ?>
                    <a href="login.php" class="bg-white rounded-full py-2 px-8 font-semibold">Login</a>
                <?php } else { ?>
                    <a href="profile_user.php?id=<?php echo htmlspecialchars($_SESSION['user']['id']); ?>">
                        <?php
                        $pdo = require 'koneksi.php';
                        $query = $pdo->prepare("SELECT profile FROM users WHERE id=:id");
                        $query->execute([
                            'id' => $_SESSION['user']['id']
                        ]);
                        $user = $query->fetch();
                        $base64 = base64_encode($user['profile']);
                        echo "<img src= 'data:image/*;base64, $base64' class=' w-12 rounded-full' alt='Profile Picture'>";
                        ?>
                    </a>
                <?php } ?>
            </div>

            <div class="lg:hidden flex items-center">
                <button id="hamburger-button"
                    class="relative z-[60] w-8 h-6 flex flex-col justify-between items-center focus:outline-none">
                    <span class="block w-full h-0.5 bg-green-700"></span>
                    <span class="block w-full h-0.5 bg-green-700"></span>
                    <span class="block w-full h-0.5 bg-green-700"></span>
                </button>
            </div>
        </div>
    </header>

    <div id="mobile-menu"
        class="lg:hidden fixed top-0 right-0 h-screen w-3/4 max-w-sm bg-amber-800 transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-white">Menu</h2>
                <button id="close-menu" class="text-white hover:text-gray-300 text-3xl">
                    &times;
                </button>
            </div>

            <a href="#"
                class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">HOME</a>
            <a href="#"
                class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">ABOUT
                US</a>
            <a href="#"
                class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">PRODUCT</a>
            <a href="#" class="block py-3 text-white font-semibold hover:text-green-800">CONTACT</a>
            <hr class="my-6 border-white/30" />
            <div class="space-y-4">
                <a href="#" class="flex items-center space-x-3 py-3 text-white font-semibold hover:text-green-800">
                    <img src="/content/icon/shopping-cart.svg" alt="cart" class="h-6 w-6"
                        style="filter: brightness(0) invert(1)" />
                    <span>Keranjang</span>
                </a>
                <a href="#"
                    class="block w-full text-center bg-white hover:bg-gray-200 text-green-700 px-4 py-2 rounded-full font-bold">
                    Login
                </a>
            </div>
        </div>
    </div>
    <!-- Main -->
    <?php
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $pdo = require 'koneksi.php';
        $query = $pdo->prepare("SELECT * FROM products WHERE id=:id");
        $query->execute(['id' => $_GET['id']]);
        $data = $query->fetch();
    ?>
        <?php if (empty($data)) { ?>
            <!-- 404 page -->
            <main class="min-h-[calc(100vh-80px)] w-full flex items-center justify-center px-4">
                <section class="text-center max-w-2xl lg:max-w-4xl mx-auto">
                    <div class="mb-8 lg:mb-12 relative">
                        <div class="absolute inset-0 blur-3xl opacity-10">
                            <div class="w-48 h-48 lg:w-80 lg:h-80 mx-auto bg-gradient-to-r from-amber-200 to-amber-300 rounded-full"></div>
                        </div>
                        <div class="relative">
                            <svg class="w-48 h-48 lg:w-80 lg:h-80 mx-auto text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-7xl lg:text-9xl font-bold text-amber-800 mb-4 lg:mb-6">
                        404
                    </h1>
                    <h2 class="text-2xl lg:text-4xl font-bold text-gray-800 mb-3 lg:mb-5">
                        Produk Tidak Ditemukan
                    </h2>
                    <p class="text-base lg:text-xl text-gray-700 mb-8 lg:mb-12 max-w-md lg:max-w-2xl mx-auto">
                        Maaf, produk yang Anda cari tidak tersedia atau mungkin telah dihapus dari katalog kami.
                    </p>
                    <div class="flex justify-center items-center">
                        <a href="all-product.php" class="px-8 lg:px-12 py-3 lg:py-4 bg-amber-800 text-white font-semibold rounded-full hover:bg-amber-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 text-base lg:text-lg">
                            Lihat Semua Produk
                        </a>
                    </div>
                </section>
            </main>
        <?php } else { ?>
            <main class="container mx-auto px-6 md:px-16 pt-8 pb-16">
                <div class="mb-6">
                    <a href="all-product.php" class="text-sm text-gray-600 hover:text-green-700 flex items-center space-x-1">
                        <span class="text-xl">&larr;</span>
                        <span>PRODUCT</span>
                    </a>
                </div>

                <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20">
                    <div class="space-y-4">
                        <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                            <img src="data:image/*;base64, <?= base64_encode($data['image1']) ?>" alt="<?= $data['nama_produk'] ?>"
                                class="w-full h-auto rounded-lg object-contain" />
                        </div>
                        <div class="flex space-x-4 justify-center">
                            <img src="content/product/Orinuts_4_Mighty_Nuts_200g-removebg-preview.png" alt="Thumbnail 1"
                                class="w-20 h-20 md:w-24 md:h-24 object-cover border-2 border-green-700 p-1 rounded-lg cursor-pointer" />

                        </div>
                    </div>

                    <div class="space-y-6">
                        <h1 id="product-title" class="text-3xl md:text-4xl font-bold text-gray-800"><?= $data['nama_produk'] ?></h1>

                        <div class="space-y-2">
                            <p id="product-price" class="text-4xl font-extrabold text-green-700">Rp<?= number_format($data['harga'], 0, ',', '.') ?></p>
                        </div>

                        <div class="flex items-center space-x-6">
                            <div class="flex items-center border border-gray-300 rounded-full bg-white">
                                <button id="decrement"
                                    class="p-3 text-xl font-bold text-gray-600 hover:text-green-700 rounded-l-full">&minus;</button>
                                <input type="number" id="quantity" value="1" min="1"
                                    class="w-10 text-center border-x border-gray-200 focus:outline-none text-lg bg-white" />
                                <button id="increment"
                                    class="p-3 text-xl font-bold text-gray-600 hover:text-green-700 rounded-r-full">&plus;</button>
                            </div>

                            <button
                                class="flex-1 max-w-xs py-3 px-6 bg-amber-800 text-white rounded-full font-bold shadow-md hover:bg-amber-700 transition duration-150 flex items-center justify-center space-x-2">
                                <img src="/content/icon/shopping-cart.svg" alt="Cart Icon" class="h-5 w-5 invert" />
                                <span>Add to cart</span>
                            </button>
                            <button
                                class="hidden md:block py-3 px-6 border-2 border-amber-800 text-amber-800 rounded-full font-bold shadow-md hover:bg-amber-100 transition duration-150">
                                Buy now
                            </button>
                            <button id="wishlist-button" class="text-gray-400 hover:text-red-500 transition duration-150">
                                <svg id="wishlist-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>

                        <button
                            class="block md:hidden w-full py-3 px-6 border-2 border-amber-800 text-amber-800 rounded-full font-bold shadow-md hover:bg-amber-100 transition duration-150 mt-4">
                            Buy now
                        </button>

                        <div class="border-t border-gray-300 pt-6 space-y-4">
                            <details open class="group">
                                <summary
                                    class="flex justify-between items-center cursor-pointer font-semibold text-lg text-gray-800 py-2 border-b border-gray-200">
                                    Detail
                                    <span
                                        class="transition-transform duration-300 transform group-open:rotate-180 text-xl">
                                        &#9660;
                                    </span>
                                </summary>
                                <p><?= $data['deskripsi'] ?></p>
                            </details>

                            <details class="group">
                                <summary
                                    class="flex justify-between items-center cursor-pointer font-semibold text-lg text-gray-800 py-2 border-b border-gray-200">
                                    Shipping
                                    <span
                                        class="transition-transform duration-300 transform group-open:rotate-180 text-xl">
                                        &#9660;
                                    </span>
                                </summary>
                                <div class="pt-3 text-gray-700 space-y-3 text-sm">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-green-600 font-bold">Disc 20%</span>
                                        </div>
                                        <div class="flex items-center space-x-2 text-gray-500">
                                            <img src="content/icon/locate.png" alt="Location Icon" class="h-4 w-4" />
                                            <span>Lokasi: <span class="font-semibold text-gray-700">Surabaya</span></span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center space-x-2">
                                            <img src="/content/icon/delivery.png" alt="Delivery Icon" class="h-5 w-5" />
                                            <span>Delivery est: <span class="font-semibold">2 - 3 working days</span></span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <img src="/content/icon/kalender.png" alt="Calendar Icon" class="h-5 w-5" />
                                            <span><span class="font-semibold">11 - 13 September</span></span>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>
                </section>

                <section class="mt-16 pt-10 border-t border-gray-300">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Rating & Review</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="flex flex-col items-center md:items-start space-y-2">
                            <p class="text-6xl font-extrabold text-green-700"><?= $rataRata['avg_rating'] ?><span class="text-3xl text-gray-500">/5</span>
                            </p>
                            <div class="flex space-x-0.5 text-2xl">
                                <?php
                                $fullStars = floor($rataRata['avg_rating']);
                                $halfStar = ($rataRata['avg_rating'] - $fullStars) >= 0.5;

                                for ($i = 0; $i < $fullStars; $i++) echo '★';
                                if ($halfStar) echo '⯨';
                                for ($i = 0; $i < (5 - round($rataRata['avg_rating'])); $i++) echo '☆';
                                ?>
                            </div>
                            <p class="text-gray-500 text-sm">Based on <span class="font-semibold"><?= $rataRata['total_reviews'] ?></span> reviews</p>
                        </div>

                        <div class="space-y-2 col-span-1 md:col-span-2">

                            <?php
                            $ratingBreakdown = getRatingBreakdown($_GET['id']);
                            foreach ($ratingBreakdown as $data): ?>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold"><?= $data['rating'] ?> Star</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-green-700 h-2.5 rounded-full" style="width: <?= $data['percentage'] ?>%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600"><?= $data['percentage'] ?>%</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <details class="group">
                            <summary class="flex items-center cursor-pointer font-semibold text-lg text-gray-800 py-2 border-b border-gray-200">
                                <span class="mr-2 transition-transform duration-300 transform group-open:rotate-180 text-xl">
                                    &#9660;
                                </span>
                                Tulis Ulasan
                            </summary>
                            <!-- Form Input Ulasan -->
                            <form id="reviewForm" class="pt-6 space-y-6" method="post" action="" enctype="multipart/form-data">

                                <!-- Rating Stars -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Rating <span class="text-red-500">*</span></label>
                                    <div class="flex space-x-2">
                                        <button type="button" class="rating-star text-4xl text-gray-300 hover:text-yellow-500 transition-colors" data-rating="1">&#9733;</button>
                                        <button type="button" class="rating-star text-4xl text-gray-300 hover:text-yellow-500 transition-colors" data-rating="2">&#9733;</button>
                                        <button type="button" class="rating-star text-4xl text-gray-300 hover:text-yellow-500 transition-colors" data-rating="3">&#9733;</button>
                                        <button type="button" class="rating-star text-4xl text-gray-300 hover:text-yellow-500 transition-colors" data-rating="4">&#9733;</button>
                                        <button type="button" class="rating-star text-4xl text-gray-300 hover:text-yellow-500 transition-colors" data-rating="5">&#9733;</button>
                                    </div>
                                    <input type="hidden" name="rating" id="ratingInput" value="0">
                                    <p id="ratingError" class="text-red-500 text-sm mt-1 hidden">Silakan pilih rating</p>
                                </div>

                                <!-- Nama Reviewer -->
                                <div>
                                    <label for="reviewerName" class="block text-sm font-semibold text-gray-800 mb-2">Nama <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        id="reviewerName"
                                        name="fullname"
                                        placeholder="Masukkan nama Anda"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                                    <p id="nameError" class="text-red-500 text-sm mt-1 hidden">Nama wajib diisi</p>
                                </div>

                                <!-- Ulasan Text -->
                                <div>
                                    <label for="reviewText" class="block text-sm font-semibold text-gray-800 mb-2">Ulasan <span class="text-red-500">*</span></label>
                                    <textarea
                                        id="reviewText"
                                        name="ulasan"
                                        rows="4"
                                        placeholder="Tulis ulasan Anda tentang produk ini..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none"></textarea>
                                    <p id="reviewError" class="text-red-500 text-sm mt-1 hidden">Ulasan minimal 10 karakter</p>
                                </div>

                                <!-- Upload Gambar (Hidden Input) -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Foto Produk (Opsional)</label>

                                    <!-- Hidden File Input -->
                                    <input
                                        type="file"
                                        id="reviewImage"
                                        name="image"
                                        accept="image/jpeg,image/jpg,image/png,image/webp"
                                        class="hidden" />

                                    <!-- Custom Upload Button -->
                                    <button
                                        type="button"
                                        id="uploadBtn"
                                        class="flex items-center space-x-2 px-6 py-2 border-2 border-dashed border-gray-300 rounded-lg hover:border-amber-500 hover:bg-amber-50 transition-all duration-200">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span class="text-gray-700 font-medium">Pilih Gambar</span>
                                    </button>

                                    <!-- Image Preview Container -->
                                    <div id="imagePreviewContainer" class="mt-4 hidden">
                                        <div class="relative inline-block">
                                            <img id="imagePreview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                                            <button
                                                type="button"
                                                id="removeImageBtn"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2" id="imageName"></p>
                                    </div>
                                    <p id="imageError" class="text-red-500 text-sm mt-1 hidden">Format gambar tidak valid (JPG, PNG, WEBP). Maksimal 2MB</p>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4">
                                    <button
                                        type="submit"
                                        id="submitBtn"
                                        name="submitBtn"
                                        value="submit"
                                        class="w-full bg-amber-800 text-white font-bold py-3 px-6 rounded-full hover:bg-amber-700 transition-all duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                        Kirim Ulasan
                                    </button>
                                </div>

                            </form>


                        </details>
                    </div>
                    <div class="mt-8 space-y-4">
                        <h3 class="text-xl font-bold text-gray-800">Review</h3>

                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-0.5 text-lg">
                                    <span class="star-filled">&#9733;</span>
                                    <span class="star-filled">&#9733;</span>
                                    <span class="star-filled">&#9733;</span>
                                    <span class="star-filled">&#9733;</span>
                                    <span class="star-filled">&#9733;</span>
                                </div>
                                <p class="text-xs text-gray-500">Via Shopee, 2 hari lalu</p>
                            </div>
                            <img src="content/product/Orimond_bubble_gum.png" alt="Foto Ulasan" class=" w-20">
                            <p class="mt-2 text-gray-700">enak... dan renyah... esta repeat order juga disini. packing aman
                                dan pengiriman cepat.</p>
                            <p class="text-xs text-gray-500 mt-1">oleh: <span class="font-semibold">customer****</span></p>
                        </div>

                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-0.5 text-lg">
                                    <span class="star-filled">&#9733;</span>
                                    <span class="star-filled">&#9733;</span>
                                    <span class="star-filled">&#9733;</span>
                                    <span class="star-filled">&#9733;</span>
                                    <span class="star-filled">&#9733;</span>
                                </div>
                                <p class="text-xs text-gray-500">Via Tokopedia, 4 hari lalu</p>
                            </div>
                            <p class="mt-2 text-gray-700">Kacangnya fresh banget, rasanya pas. Anak-anak suka! Sudah order
                                berkali-kali.</p>
                            <p class="text-xs text-gray-500 mt-1">oleh: <span class="font-semibold">user_toped_A</span></p>
                        </div>

                        <div class="text-center pt-4">
                            <button class="text-green-700 font-semibold hover:text-green-800">Lihat Semua Review
                                (15)</button>
                        </div>
                    </div>
                </section>

                <section class="mt-16 pt-10 border-t border-gray-300">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8 text-center">PRODUK LAINNYA</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div class="bg-white p-4 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                            <a href="nyoba.html" class="block cursor-pointer">
                                <img src="/content/roasted_cashew_card.png" alt="Roasted Cashew"
                                    class="w-full h-auto object-cover mb-4 rounded-lg" />
                                <h3 class="text-lg font-bold text-gray-800">Roasted Cashew (200g)</h3>
                                <div class="flex space-x-0.5 text-sm text-yellow-500 my-1">
                                    <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span>
                                </div>
                                <p class="text-gray-500 line-through text-sm">35.000</p>
                                <p class="text-xl font-bold text-green-700">30.000</p>
                            </a>
                        </div>

                        <div class="bg-white p-4 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                            <a href="/product-detail.html?id=energy-booster-mix" class="block cursor-pointer">
                                <img src="/content/energy_booster_card.png" alt="Energy Booster Mix"
                                    class="w-full h-auto object-cover mb-4 rounded-lg" />
                                <h3 class="text-lg font-bold text-gray-800">Energy Booster Mix</h3>
                                <div class="flex space-x-0.5 text-sm text-yellow-500 my-1">
                                    <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span class="star-empty">&#9733;</span>
                                </div>
                                <p class="text-gray-500 line-through text-sm">135.000</p>
                                <p class="text-xl font-bold text-green-700">120.000</p>
                            </a>
                        </div>

                        <div class="bg-white p-4 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                            <a href="/product-detail.html?id=almond-roasted" class="block cursor-pointer">
                                <img src="/content/almond_roasted_card.png" alt="Almond Roasted"
                                    class="w-full h-auto object-cover mb-4 rounded-lg" />
                                <h3 class="text-lg font-bold text-gray-800">Almond Roasted</h3>
                                <div class="flex space-x-0.5 text-sm text-yellow-500 my-1">
                                    <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span>
                                </div>
                                <p class="text-gray-500 line-through text-sm">45.000</p>
                                <p class="text-xl font-bold text-green-700">40.000</p>
                            </a>
                        </div>
                    </div>
                </section>
            </main>
        <?php } ?>
    <?php } ?>
    <img src="content/sret/sret-krem.png" alt="sret krem" class="rotate-180 w-full mb-[3px]">
    <footer class="bg-[#F4E9BB] py-10 ">
        <div
            class="container mx-auto px-6 md:px-16 flex flex-col lg:flex-row justify-between items-start space-y-8 lg:space-y-0">
            <div class="lg:w-1/3 space-y-4">
                <img src="/content/logo.png" alt="Orinuts Logo" class="h-14" />
                <p class="text-gray-600 text-sm max-w-sm">
                    The nuts & seeds in Orinuts products are carefully selected and roasted, providing a pure and
                    healthy taste in every bite.
                </p>
                <div class="flex space-x-3">
                    <img src="/content/icon/instagram.svg" alt="Instagram" class="h-6 w-6 opacity-70" />
                    <img src="/content/icon/facebook.svg" alt="Facebook" class="h-6 w-6 opacity-70" />
                    <img src="/content/icon/twitter.svg" alt="Twitter" class="h-6 w-6 opacity-70" />
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-gray-800">Main office</h4>
                <p class="text-gray-600 text-sm max-w-xs">
                    Sentra Prima Tekno Blok F/23, Duren Seribu, Kec. Bojongsari, Depok, Jawa Barat 16518
                </p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-gray-800">Main menu</h4>
                <nav class="flex flex-col space-y-1 text-sm">
                    <a href="#" class="text-gray-600 hover:text-green-700">HOME</a>
                    <a href="#" class="text-gray-600 hover:text-green-700">ABOUT US</a>
                    <a href="#" class="text-gray-600 hover:text-green-700">PRODUCT</a>
                    <a href="#" class="text-gray-600 hover:text-green-700">CONTACT</a>
                </nav>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-gray-800">Official Online Store</h4>
                <p class="text-gray-600 text-sm">Official Tokopedia</p>
                <p class="text-gray-600 text-sm">Official Shopee</p>
                <p class="text-gray-600 text-sm">+62 812-921-999</p>
            </div>
        </div>
    </footer>

    <script>
        // ============== REVIEW FORM VALIDATION ==============

        const reviewForm = document.getElementById('reviewForm');
        const ratingInput = document.getElementById('ratingInput');
        const reviewerName = document.getElementById('reviewerName');
        const reviewText = document.getElementById('reviewText');
        const reviewImage = document.getElementById('reviewImage');
        const submitBtn = document.getElementById('submitBtn');

        // Rating stars functionality
        const ratingStars = document.querySelectorAll('.rating-star');
        let selectedRating = 0;

        ratingStars.forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.dataset.rating);
                ratingInput.value = selectedRating;

                // Update star colors
                ratingStars.forEach((s, index) => {
                    if (index < selectedRating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-500');
                    } else {
                        s.classList.remove('text-yellow-500');
                        s.classList.add('text-gray-300');
                    }
                });

                // Hide error when rating is selected
                document.getElementById('ratingError').classList.add('hidden');
                validateForm();
            });
        });

        // Image upload functionality
        const uploadBtn = document.getElementById('uploadBtn');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const imageName = document.getElementById('imageName');
        const removeImageBtn = document.getElementById('removeImageBtn');

        uploadBtn.addEventListener('click', () => {
            reviewImage.click();
        });

        reviewImage.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    document.getElementById('imageError').classList.remove('hidden');
                    reviewImage.value = ''; // Clear input
                    return;
                }

                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    document.getElementById('imageError').textContent = 'Ukuran file maksimal 2MB';
                    document.getElementById('imageError').classList.remove('hidden');
                    reviewImage.value = ''; // Clear input
                    return;
                }

                // Hide error and show preview
                document.getElementById('imageError').classList.add('hidden');

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imageName.textContent = file.name;
                    imagePreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        removeImageBtn.addEventListener('click', () => {
            reviewImage.value = ''; // Clear file input
            imagePreviewContainer.classList.add('hidden');
            imagePreview.src = '';
            imageName.textContent = '';
            document.getElementById('imageError').classList.add('hidden');
        });

        // Validation functions
        function validateRating() {
            const isValid = selectedRating > 0;
            if (!isValid) {
                document.getElementById('ratingError').classList.remove('hidden');
            } else {
                document.getElementById('ratingError').classList.add('hidden');
            }
            return isValid;
        }

        function validateName() {
            const name = reviewerName.value.trim();
            const isValid = name.length > 0;
            if (!isValid) {
                document.getElementById('nameError').classList.remove('hidden');
            } else {
                document.getElementById('nameError').classList.add('hidden');
            }
            return isValid;
        }

        function validateReview() {
            const review = reviewText.value.trim();
            const isValid = review.length >= 10;
            if (!isValid) {
                document.getElementById('reviewError').classList.remove('hidden');
            } else {
                document.getElementById('reviewError').classList.add('hidden');
            }
            return isValid;
        }

        function validateForm() {
            const isRatingValid = validateRating();
            const isNameValid = validateName();
            const isReviewValid = validateReview();

            const isFormValid = isRatingValid && isNameValid && isReviewValid;
            submitBtn.disabled = !isFormValid;

            return isFormValid;
        }

        // Real-time validation
        reviewerName.addEventListener('input', validateForm);
        reviewText.addEventListener('input', validateForm);

        // Form submission
        reviewForm.addEventListener('submit', function(e) {

            if (validateForm()) {

                const formData = new FormData(this);


                console.log('Form submitted with:');
                console.log('Rating:', formData.get('rating'));
                console.log('Name:', formData.get('reviewer_name'));
                console.log('Review:', formData.get('review_text'));
                console.log('Image:', formData.get('review_image'));

                // Here you would typically send the data to your PHP backend
                // For now, just show success message
                alert('Ulasan berhasil dikirim!');
                e.target.submit();

                // Reset form
                reviewForm.reset();
                selectedRating = 0;
                ratingStars.forEach(s => {
                    s.classList.remove('text-yellow-500');
                    s.classList.add('text-gray-300');
                });
                imagePreviewContainer.classList.add('hidden');
                submitBtn.disabled = true;
            }
        });

        // Initial validation
        validateForm();
        // Helper function untuk format Rupiah
        const formatRupiah = (angka) => {
            return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        };

        // ============== 1. LOGIC NAVBAR & QUANTITY START ==============

        // Script untuk shadow navbar
        const navbar = document.getElementById("navbar");
        window.addEventListener("scroll", () => {
            if (window.scrollY > 50) {
                navbar.classList.add("shadow-lg");
            } else {
                navbar.classList.remove("shadow-lg");
            }
        });

        // Script Hamburger Menu
        const hamburgerButton = document.getElementById("hamburger-button");
        const mobileMenu = document.getElementById("mobile-menu");
        const closeMenuButton = document.getElementById("close-menu");

        function openMenu() {
            mobileMenu.classList.remove("translate-x-full");
        }

        function closeMenu() {
            mobileMenu.classList.add("translate-x-full");
        }

        hamburgerButton.addEventListener("click", openMenu);
        closeMenuButton.addEventListener("click", closeMenu);

        // Script untuk Quantity Input
        const quantityInput = document.getElementById("quantity");
        const incrementButton = document.getElementById("increment");
        const decrementButton = document.getElementById("decrement");

        incrementButton.addEventListener("click", () => {
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });

        decrementButton.addEventListener("click", () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        quantityInput.addEventListener('change', (e) => {
            if (parseInt(e.target.value) < 1 || isNaN(parseInt(e.target.value))) {
                e.target.value = 1;
            }
        });
    </script>
</body>

</html>