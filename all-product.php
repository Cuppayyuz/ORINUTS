<?php
session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orinuts</title>
    <link href="src/outputail.css" rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        sm: "640px",
                        md: "768px",
                        lg: "1024px",
                        xl: "1440px",
                    },
                },
            },
        };
    </script>
    <style>
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

        .category-filter-btn.active-filter {
            background-color: #78350f;
            /* bg-amber-800 */
            color: white;
        }
    </style>
</head>

<body class="bg-[#F0A27C] w-full">
    <header id="navbar"
        class="fixed left-0 right-0 top-0 z-50 py-2 transition-all duration-300  backdrop-blur-sm">
        <div class="container mx-auto px-6 md:px-16 flex justify-between items-center">
            <div>
                <img src="content/logo.png" alt="logo" class="h-14" />
            </div>

            <nav class="hidden lg:flex py-1 px-2 rounded-full bg-white/30 backdrop-blur-md">
                <a href="index.html" class="rounded-full py-2 px-8 font-semibold">HOME</a>
                <a href="about.html" class="rounded-full py-2 px-8 font-semibold hover:text-green-700">ABOUT US</a>
                <p
                    class="bg-white rounded-full py-2 px-8 font-semibold hover:text-green-700">PRODUCT</p>
                <a href="contact.html" class="rounded-full py-2 px-8 font-semibold hover:text-green-700">CONTACT</a>
            </nav>

            <div class="hidden lg:flex items-center space-x-4">
                <a href="#">
                    <img src="content/icon/shopping-cart.svg" alt="cart" class="h-7 w-7" />
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
                    <span class="block w-full h-0.5 bg-white"></span>
                    <span class="block w-full h-0.5 bg-white"></span>
                    <span class="block w-full h-0.5 bg-white"></span>
                </button>
            </div>
        </div>
    </header>

    <div id="mobile-menu"
        class="lg:hidden fixed top-0 right-0 h-screen w-3/4 max-w-sm bg-amber-800 transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-white">Menu</h2>
                <button id="close-menu" class="text-white hover:text-gray-300">
                    ✕
                </button>
            </div>

            <a href="#"
                class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">HOME</a>
            <a href="#" class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">ABOUT
                US</a>
            <a href="#"
                class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">PRODUCT</a>
            <a href="#" class="block py-3 text-white font-semibold hover:text-green-800">CONTACT</a>
            <hr class="my-6 border-white/30" />
            <div class="space-y-4">
                <a href="#" class="flex items-center space-x-3 py-3 text-white font-semibold hover:text-green-800">
                    <img src="content/icon/shopping-cart.svg" alt="cart" class="h-6 w-6"
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

    <div class="container mx-auto px-6 md:px-16 mt-28">
        <section class="flex justify-center">
            <div class="relative w-full md:w-2/3 lg:w-1/2">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <img src="content/icon/search.png" alt="search" class="h-6 w-6" />
                </div>
                <input type="text" id="search-input" placeholder="Cari produk..."
                    class="h-12 w-full bg-white rounded-3xl pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-amber-600 shadow-sm" />
            </div>
        </section>

        <section class="mt-6">
            <div class="relative h-12 flex items-center">
                <button id="category-toggle"
                    class="absolute left-0 top-0 h-12 w-48 bg-white rounded-full flex items-center justify-center font-semibold shadow-sm transition-all duration-300 z-20 group">
                    <span>Category</span>
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300" id="category-arrow" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <div id="category-list"
                    class="flex items-center h-12 bg-white rounded-full shadow-sm z-10 transform scale-x-0 opacity-0 origin-left transition-all duration-500 ease-in-out">
                    <div class="flex items-center pl-52 pr-4 whitespace-nowrap">
                        <button data-filter="all"
                            class="category-filter-btn active-filter px-4 py-1 rounded-full font-medium">
                            All
                        </button>
                        <button data-filter="orinuts"
                            class="category-filter-btn px-4 py-1 rounded-full font-medium text-gray-700 hover:bg-gray-100">
                            Orinuts
                        </button>
                        <button data-filter="orimond"
                            class="category-filter-btn px-4 py-1 rounded-full font-medium text-gray-700 hover:bg-gray-100">
                            Orimond
                        </button>
                        <button data-filter="orithin"
                            class="category-filter-btn px-4 py-1 rounded-full font-medium text-gray-700 hover:bg-gray-100">
                            Orithin
                        </button>
                        <button data-filter="rumah mente"
                            class="category-filter-btn px-4 py-1 rounded-full font-medium text-gray-700 hover:bg-gray-100">
                            Rumah Mente
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-10">
            <h1 id="category-display" class="font-bold text-3xl">All</h1>
        </section>

        <section class="mt-6 mb-20">
            <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $pdo = require 'koneksi.php';
                $sql = "SELECT * FROM products";
                $query = $pdo->prepare($sql);
                $query->execute();
                while ($produk = $query->fetch()) {
                    $base64 = base64_encode($produk['image1']);
                ?>
                    <div class="product-card bg-stone-50 rounded-xl shadow-lg p-4 flex flex-col transition-all duration-300 hover:shadow-xl"
                        data-category="<?= strtolower($produk['kategori']) ?>">
                        <img src="data:image/*;base64, <?= $base64 ?>"
                            alt="<?= $produk['nama_produk'] ?>" class="w-full h-56 object-contain rounded-t-lg" />
                        <div class="mt-4 flex-grow">
                            <h3 class="text-xl font-bold text-amber-800"><?= $produk['nama_produk'] ?></h3>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-lg font-semibold text-gray-900">Rp. <?= number_format($produk['harga'], 0, ',', '.') ?></p>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.847 5.698h5.998c.969 0 1.371 1.24.588 1.81l-4.853 3.53a.997.997 0 00-.364 1.118l1.847 5.698c.3.921-.755 1.688-1.539 1.118l-4.852-3.53a.997.997 0 00-1.176 0l-4.852 3.53c-.784.57-1.838-.197-1.539-1.118l1.847-5.698a.997.997 0 00-.364-1.118L.503 10.435c-.783-.57-.38-1.81.588-1.81h5.998L9.049 2.927z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">5.0</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 flex space-x-2">
                            <a href="detail-product.php?id=<?= $produk['id'] ?>"
                                class="flex-1 bg-gray-200 text-gray-800 py-2.5 px-4 rounded-full text-sm font-semibold flex items-center justify-center space-x-1.5 hover:bg-gray-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <span>lihat produk</span>
                            </a>
                            <button
                                class="flex-1 bg-amber-800 text-white py-2.5 px-4 rounded-full text-sm font-semibold flex items-center justify-center space-x-1.5 hover:bg-amber-900 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                                    </path>
                                </svg>
                                <span>order now</span>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <!-- End Loop -->


            </div>
        </section>
    </div>

    <img src="content/sret/sret-orange.png" alt="sret orenge" class="w-full -bottom-1 relative pt-20" />

    <footer>
        <div class="w-full p-10 bg-[#ea7003] text-white">
            <img src="/content/logo.png" alt="Orinuts Logo" class="w-32 mb-6" />
            <div class="flex justify-between items-start pt-6 text-sm">
                <div class="w-full md:w-1/4 pr-8 text-white">
                    <p class="leading-relaxed">
                        the No.1 Healthy Snack in Indonesia. We provide <br />
                        premium quality roasted nut snacks, crafted <br />
                        without salt, sugar, preservatives, or MSG — <br />
                        delivering a pure and healthy taste in every bite.
                    </p>
                    <div class="flex space-x-3 pt-5">
                        <a href=""><img src="content/icon/instagram.svg" alt="Instagram"
                                class="w-7 h-7 rounded-full border border-gray-100 p-1 brightness-100 bg-white" /></a>
                        <a href=""><img src="content/icon/twitter.svg" alt="Twitter"
                                class="w-7 h-7 rounded-full border border-gray-100 p-1 brightness-100 bg-white" /></a>
                        <a href=""><img src="content/icon/facebook.svg" alt="Facebook"
                                class="w-7 h-7 rounded-full border border-gray-100 p-1 brightness-100 bg-white" /></a>
                        <a href=""><img src="content/icon/mail.svg" alt="Email"
                                class="w-7 h-7 rounded-full border border-gray-100 p-1 brightness-100 bg-white" /></a>
                    </div>
                </div>
                <div class="flex justify-between w-full md:w-3/4">
                    <div class="w-1/3 text-white">
                        <h2 class="font-bold text-lg pb-4 uppercase">main office</h2>
                        <p class="leading-relaxed">
                            Bavarian Village A 7,<br />
                            Darmo Permai Baru III,<br />
                            Surabaya, Indonesia 60188
                        </p>
                    </div>
                    <div class="w-1/3 text-white">
                        <h2 class="font-bold text-lg pb-4 uppercase">main menu</h2>
                        <a href="" class="block leading-loose hover:underline">HOME</a>
                        <a href="" class="block leading-loose hover:underline">ABOUT US</a>
                        <a href="" class="block leading-loose hover:underline">PRODUCT</a>
                        <a href="" class="block leading-loose hover:underline">CONTACT</a>
                    </div>
                    <div class="w-1/3 text-white">
                        <h2 class="font-bold text-lg pb-4 uppercase">
                            Official Online Store
                        </h2>
                        <p class="leading-loose">
                            Official Shopee <br />
                            Official Tokopedia <br />
                            <br />
                            +62 816-521-369
                        </p>
                    </div>
                </div>
            </div>
            <hr class="mt-12 mb-4 border-white " />
            <p class="text-sm text-white mt-2">
                Orinuts © 2025 All Rights Reserved. | Privacy Policy Term of Service
            </p>
        </div>
    </footer>

    <script>
        (function() {
            // State Management
            let currentCategory = 'all';
            let searchTimeout = null;

            // DOM Elements
            const navbar = document.getElementById("navbar");
            const hamburgerButton = document.getElementById("hamburger-button");
            const mobileMenu = document.getElementById("mobile-menu");
            const closeMenuButton = document.getElementById("close-menu");
            const categoryToggle = document.getElementById("category-toggle");
            const categoryList = document.getElementById("category-list");
            const categoryArrow = document.getElementById("category-arrow");
            const filterButtons = document.querySelectorAll(".category-filter-btn");
            const productGrid = document.getElementById("product-grid");
            const searchInput = document.getElementById("search-input");
            const categoryDisplay = document.getElementById("category-display");
            const productCards = document.querySelectorAll(".product-card");

            // Navbar Shadow on Scroll
            window.addEventListener("scroll", () => {
                navbar.classList.toggle("shadow-lg", window.scrollY > 50);
            });

            // Mobile Menu
            hamburgerButton.addEventListener("click", () => mobileMenu.classList.remove("translate-x-full"));
            closeMenuButton.addEventListener("click", () => mobileMenu.classList.add("translate-x-full"));

            // Category Toggle
            categoryToggle.addEventListener("click", () => {
                const isOpen = categoryList.classList.contains("scale-x-100");
                categoryList.classList.toggle("scale-x-0", isOpen);
                categoryList.classList.toggle("opacity-0", isOpen);
                categoryList.classList.toggle("scale-x-100", !isOpen);
                categoryList.classList.toggle("opacity-100", !isOpen);
                categoryArrow.style.transform = isOpen ? "rotate(0deg)" : "rotate(180deg)";
            });

            // Filter Products (Show/Hide)
            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                productCards.forEach(card => {
                    const productName = card.querySelector('h3').textContent.toLowerCase();
                    const productCategory = card.getAttribute('data-category');

                    // Cek kategori
                    const matchCategory = currentCategory === 'all' || productCategory === currentCategory;

                    // Cek search term
                    const matchSearch = searchTerm === '' || productName.includes(searchTerm);

                    // Tampilkan atau sembunyikan
                    if (matchCategory && matchSearch) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                // Tampilkan pesan jika tidak ada hasil
                let noResultMsg = document.getElementById('no-result-message');
                if (visibleCount === 0) {
                    if (!noResultMsg) {
                        noResultMsg = document.createElement('p');
                        noResultMsg.id = 'no-result-message';
                        noResultMsg.className = 'col-span-full text-center text-gray-600 py-8';
                        noResultMsg.textContent = 'Produk tidak ditemukan.';
                        productGrid.appendChild(noResultMsg);
                    }
                } else {
                    if (noResultMsg) {
                        noResultMsg.remove();
                    }
                }
            }

            // Search Input Event (Debounced)
            searchInput.addEventListener("input", () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterProducts, 300);
            });

            // Category Filter
            filterButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    currentCategory = button.getAttribute("data-filter");

                    // Update Active State
                    filterButtons.forEach((btn) => {
                        btn.classList.remove("active-filter", "bg-amber-800", "text-white");
                        btn.classList.add("text-gray-700", "hover:bg-gray-100");
                    });
                    button.classList.add("active-filter", "bg-amber-800", "text-white");
                    button.classList.remove("text-gray-700", "hover:bg-gray-100");

                    // Update Category Display
                    categoryDisplay.textContent = button.textContent.trim();

                    // Filter products
                    filterProducts();

                    // Close Category Menu
                    categoryList.classList.remove("scale-x-100", "opacity-100");
                    categoryList.classList.add("scale-x-0", "opacity-0");
                    categoryArrow.style.transform = "rotate(0deg)";
                });
            });
        })();
    </script>
</body>

</html>