<?php
session_start();
if (!$_SESSION['admin']) {
  header('Location: index.php');
  exit();
}
// Cek kode unik
$pdo = require 'koneksi.php';
$stmt = $pdo->query("SELECT produk_kode FROM products ORDER BY produk_kode DESC LIMIT 1");
$last = $stmt->fetchColumn();

if (!$last) {
  $newCode = "P001";
} else {
  $number = (int) substr($last, 1);
  $number++;

  $newCode = "P" . str_pad($number, 3, "0", STR_PAD_LEFT);
}

$error = '';
$success = '';

// Handle Tambah Produk
if (isset($_POST['submitProduk'])) {
  try {
    $image1 = !empty($_FILES['image']['tmp_name'][0]) ? file_get_contents($_FILES['image']['tmp_name'][0]) : null;
    $image2 = !empty($_FILES['image']['tmp_name'][1]) ? file_get_contents($_FILES['image']['tmp_name'][1]) : null;
    $image3 = !empty($_FILES['image']['tmp_name'][2]) ? file_get_contents($_FILES['image']['tmp_name'][2]) : null;

    $sql = "INSERT INTO products (produk_kode, nama_produk, harga, varian, kategori, deskripsi, stok, image1, image2, image3) 
            VALUES (:produk_kode, :nama_produk, :harga, :varian, :kategori, :deskripsi, :stok, :image1, :image2, :image3)";
    $query = $pdo->prepare($sql);
    $query->execute([
      "produk_kode" => $newCode,
      "nama_produk" => $_POST['nama_produk'],
      "harga" => $_POST['harga_jual'],
      "varian" => (int)$_POST['berat'],
      "kategori" => $_POST['kategori'],
      "deskripsi" => $_POST['deskripsi'],
      "stok" => $_POST['stok'],
      "image1" => $image1,
      "image2" => $image2,
      "image3" => $image3
    ]);

    header('Location: admin_product.php?success=tambah');
    exit();
  } catch (Exception $e) {
    $error = 'Gagal menyimpan produk: ' . $e->getMessage();
  }
}

// Handle Edit Produk
if (isset($_POST['submitEdit'])) {
  try {
    $productId = $_POST['product_id'];

    // Cek apakah ada gambar baru
    $updateImage = "";
    $params = [
      "nama_produk" => $_POST['nama_produk'],
      "harga" => $_POST['harga_jual'],
      "varian" => $_POST['berat'],
      "kategori" => $_POST['kategori'],
      "deskripsi" => $_POST['deskripsi'],
      "stok" => $_POST['stok'],
      "id" => $productId
    ];

    if (!empty($_FILES['image']['tmp_name'][0])) {
      $image1 = file_get_contents($_FILES['image']['tmp_name'][0]);
      $updateImage .= ", image1 = :image1";
      $params['image1'] = $image1;
    }
    if (!empty($_FILES['image']['tmp_name'][1])) {
      $image2 = file_get_contents($_FILES['image']['tmp_name'][1]);
      $updateImage .= ", image2 = :image2";
      $params['image2'] = $image2;
    }
    if (!empty($_FILES['image']['tmp_name'][2])) {
      $image3 = file_get_contents($_FILES['image']['tmp_name'][2]);
      $updateImage .= ", image3 = :image3";
      $params['image3'] = $image3;
    }

    $sql = "UPDATE products SET 
            nama_produk = :nama_produk, 
            harga = :harga, 
            varian = :varian, 
            kategori = :kategori, 
            deskripsi = :deskripsi, 
            stok = :stok
            {$updateImage}
            WHERE id = :id";

    $query = $pdo->prepare($sql);
    $query->execute($params);

    header('Location: admin_product.php?success=edit');
    exit();
  } catch (Exception $e) {
    $error = 'Gagal mengupdate produk: ' . $e->getMessage();
  }
}

// Handle Hapus Produk
if (isset($_GET['hapus'])) {
  try {
    $sql = "DELETE FROM products WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->execute(['id' => $_GET['hapus']]);

    header('Location: admin_product.php?success=hapus');
    exit();
  } catch (Exception $e) {
    $error = 'Gagal menghapus produk';
  }
}

// Ambil semua produk
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Ambil data produk untuk edit
$editProduct = null;
if (isset($_GET['edit'])) {
  $sql = "SELECT * FROM products WHERE id = :id";
  $query = $pdo->prepare($sql);
  $query->execute(['id' => $_GET['edit']]);
  $editProduct = $query->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Produk - OurNuts</title>
  <link rel="stylesheet" href="src/outputail.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    .sidebar-bg {
      background-image: url();
      background-size: cover;
      background-position: bottom center;
    }

    .focus-ring-primary-custom:focus {
      --tw-ring-color: #8d5a40;
      outline: 2px solid #8d5a40;
      outline-offset: 2px;
    }
  </style>
</head>

<body class="bg-[#F5F5F5] flex min-h-screen">
  <!-- Sidebar -->
  <aside class="bg-white p-5 text-[#8B4513] overflow-y-auto shadow-lg rounded-r-3xl min-h-screen flex-shrink-0 w-63">
    <div class="logo mb-6">
      <img src="content/logo.png" alt="Logo Orinuts" class="w-28" />
    </div>
    <nav>
      <ul class="space-y-1 text-sm">
        <a href="admin_dashboard.php">
          <li class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
            <i class="fas fa-home w-4"></i> Beranda
          </li>
        </a>
        <li class="nav-item font-bold p-2.5 flex items-center gap-3 cursor-pointer text-[#8B4513] bg-[#D2A278] rounded-r-3xl">
          <i class="fas fa-box-open w-4"></i> Produk
        </li>
        <a href="admin_message.php">
          <li class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
            <i class="fas fa-comment w-4"></i> Message
            <span class="ml-auto bg-red-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full">2</span>
          </li>
        </a>
        <a href="admin_order.php">
          <li class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
            <i class="fas fa-shopping-cart w-4"></i> Order
          </li>
        </a>
        <a href="admin_profile.php">
          <li class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
            <i class="fas fa-user-circle w-4"></i> Profile
          </li>
        </a>
      </ul>
    </nav>
  </aside>

  <main class="flex-1 p-8">
    <!-- Header -->
    <header class="flex justify-between items-center mb-6 p-4 bg-[#8D5A40] rounded-xl shadow-md text-white">
      <h1 class="text-xl font-light tracking-widest">HALAW ADMIN!!</h1>
      <div class="flex items-center">
        <span class="text-white text-xl ml-4">👤</span>
      </div>
    </header>

    <!-- Alert Success -->
    <?php if (isset($_GET['success'])): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?php
        if ($_GET['success'] == 'tambah') echo 'Produk berhasil ditambahkan!';
        else if ($_GET['success'] == 'edit') echo 'Produk berhasil diupdate!';
        else if ($_GET['success'] == 'hapus') echo 'Produk berhasil dihapus!';
        ?>
      </div>
    <?php endif; ?>

    <!-- Alert Error -->
    <?php if ($error): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <!-- Tombol Tambah Produk -->
    <?php if (!isset($_GET['tambah']) && !isset($_GET['edit'])): ?>
      <div class="flex justify-end mb-5">
        <a href="?tambah=1" class="bg-[#8D5A40] text-white px-6 py-3 rounded-full hover:bg-[#8D5A40]/90 transition duration-150 shadow-lg flex items-center">
          <span class="text-xl mr-2">+</span> Tambah Produk
        </a>
      </div>
    <?php endif; ?>

    <!-- Daftar Produk -->
    <?php if (!isset($_GET['tambah']) && !isset($_GET['edit'])): ?>
      <div class="bg-[#F7F7F7] p-6 rounded-xl shadow-lg">
        <div class="grid grid-cols-7 gap-4 font-bold uppercase text-xs text-[#8D5A40] border-b-2 border-[#8D5A40] pb-3 mb-4">
          <div class="col-span-1">Picture</div>
          <div class="col-span-3">Nama Produk</div>
          <div class="col-span-1 text-center">Harga</div>
          <div class="col-span-1 text-center">Stok</div>
          <div class="col-span-1 text-center">Aksi</div>
        </div>

        <div class="space-y-3 pb-20">
          <?php foreach ($products as $index => $product): ?>
            <div class="grid grid-cols-7 gap-4 items-center <?php echo $index % 2 == 0 ? 'bg-[#D0A37D] bg-opacity-70' : 'bg-[#D0A37D] bg-opacity-40'; ?> p-3 rounded-lg shadow-sm text-[#8D5A40] font-medium">
              <div class="col-span-1">
                <?php if ($product['image1']): ?>
                  <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['nama_produk']); ?>" class="w-10 h-10 object-cover rounded shadow" />
                <?php else: ?>
                  <img src="https://dummyimage.com/40x40/fff/8D5A40&text=No+Img" alt="No Image" class="w-10 h-10 object-cover rounded shadow" />
                <?php endif; ?>
              </div>
              <div class="col-span-3"><?php echo htmlspecialchars($product['nama_produk']); ?></div>
              <div class="col-span-1 text-center">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></div>
              <div class="col-span-1 text-center"><?php echo $product['stok']; ?></div>
              <div class="col-span-1 flex justify-center space-x-2 text-sm">
                <a href="?edit=<?php echo $product['id']; ?>" class="text-green-800 hover:text-green-600 flex items-center">
                  <span class="mr-1">📝</span> edit
                </a>
                <a href="?hapus=<?php echo $product['id']; ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="text-red-700 hover:text-red-500 flex items-center">
                  <span class="mr-1">🗑️</span> hapus
                </a>
              </div>
            </div>
          <?php endforeach; ?>

          <?php if (empty($products)): ?>
            <div class="text-center text-gray-500 py-8">
              Belum ada produk. Silakan tambah produk baru.
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Form Tambah/Edit Produk -->
    <?php if (isset($_GET['tambah']) || isset($_GET['edit'])): ?>
      <div class="bg-[#F7F7F7] p-6 rounded-xl shadow-lg max-w-4xl">
        <h2 class="text-2xl font-bold text-[#8D5A40] mb-6">
          <?php echo isset($_GET['edit']) ? 'Edit Produk' : 'Tambah Produk Baru'; ?>
        </h2>

        <form id="formProduk" action="" method="post" enctype="multipart/form-data">
          <?php if (isset($_GET['edit'])): ?>
            <input type="hidden" name="product_id" value="<?php echo $editProduct['id']; ?>">
          <?php endif; ?>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom Kiri -->
            <div>
              <!-- Foto Produk -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk (Maks. 3)</label>
                <div id="foto-uploader" class="border-2 border-dashed border-gray-400 p-6 text-center rounded-lg cursor-pointer hover:border-[#8D5A40] transition duration-150">
                  <span class="text-[#8D5A40] text-xl">+</span>
                  <p class="text-sm text-gray-500">Klik untuk Tambah Foto</p>
                  <input type="file" name="image[]" id="foto-input" class="hidden" multiple accept="image/*" />
                </div>
                <div id="foto-preview-container" class="mt-4 flex flex-wrap gap-4"></div>
                <span id="error-foto" class="text-red-600 text-sm hidden">Minimal 1 foto harus diupload</span>
              </div>

              <!-- Nama Produk -->
              <div class="mb-4">
                <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk *</label>
                <input type="text" id="nama_produk" name="nama_produk" value="<?php echo $editProduct ? htmlspecialchars($editProduct['nama_produk']) : ''; ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
                <span id="error-nama" class="text-red-600 text-sm hidden">Nama produk harus diisi</span>
              </div>

              <!-- Kategori -->
              <div class="mb-4">
                <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori *</label>
                <select id="kategori" name="kategori" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom">
                  <option value="Orinuts" <?php echo ($editProduct && $editProduct['kategori'] == 'Orinuts') ? 'selected' : ''; ?>>Orinuts</option>
                  <option value="Orithin" <?php echo ($editProduct && $editProduct['kategori'] == 'Orithin') ? 'selected' : ''; ?>>Orithin</option>
                  <option value="Orimond" <?php echo ($editProduct && $editProduct['kategori'] == 'Orimond') ? 'selected' : ''; ?>>Orimond</option>
                  <option value="Rumah Mente" <?php echo ($editProduct && $editProduct['kategori'] == 'Rumah Mente') ? 'selected' : ''; ?>>Rumah Mente</option>
                </select>
              </div>
            </div>

            <!-- Kolom Kanan -->
            <div>
              <!-- Harga Jual -->
              <div class="mb-4">
                <label for="harga_jual" class="block text-sm font-medium text-gray-700">Harga Jual *</label>
                <input type="number" id="harga_jual" name="harga_jual" value="<?php echo $editProduct ? $editProduct['harga'] : ''; ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
                <span id="error-harga" class="text-red-600 text-sm hidden">Harga harus diisi dan lebih dari 0</span>
              </div>

              <!-- Stok -->
              <div class="mb-4">
                <label for="stok" class="block text-sm font-medium text-gray-700">Stok *</label>
                <input type="number" id="stok" name="stok" value="<?php echo $editProduct ? $editProduct['stok'] : ''; ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
                <span id="error-stok" class="text-red-600 text-sm hidden">Stok harus diisi dan tidak boleh negatif</span>
              </div>

              <!-- Varian Berat -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Varian Berat *</label>
                <div class="flex flex-wrap gap-2">
                  <label class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                    <input type="radio" name="berat" value="75" <?php echo ($editProduct && $editProduct['varian'] == '75gr') ? 'checked' : ''; ?> class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                    <span class="text-sm">75 gram</span>
                  </label>
                  <label class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                    <input type="radio" name="berat" value="200" <?php echo ($editProduct && $editProduct['varian'] == '200gr') ? 'checked' : ''; ?> class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                    <span class="text-sm">200 gram</span>
                  </label>
                  <label class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                    <input type="radio" name="berat" value="500" <?php echo ($editProduct && $editProduct['varian'] == '500gr') ? 'checked' : ''; ?> class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                    <span class="text-sm">500 gram</span>
                  </label>
                </div>
                <span id="error-berat" class="text-red-600 text-sm hidden">Pilih salah satu varian berat</span>
              </div>

              <!-- Deskripsi -->
              <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom"><?php echo $editProduct ? htmlspecialchars($editProduct['deskripsi']) : ''; ?></textarea>
              </div>
            </div>
          </div>

          <!-- Tombol Aksi -->
          <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end">
            <a href="admin_product.php" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition duration-150 mr-3">
              Batal
            </a>
            <button type="submit" name="<?php echo isset($_GET['edit']) ? 'submitEdit' : 'submitProduk'; ?>" id="btnSubmit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-150 disabled:bg-gray-300 disabled:cursor-not-allowed">
              Simpan Produk
            </button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </main>

  <script>
    // State Management
    let selectedFiles = [];
    const isEditMode = <?php echo isset($_GET['edit']) ? 'true' : 'false'; ?>;

    // DOM Elements
    const fotoUploader = document.getElementById("foto-uploader");
    const fotoInput = document.getElementById("foto-input");
    const previewContainer = document.getElementById("foto-preview-container");
    const btnSubmit = document.getElementById("btnSubmit");

    // Error Elements
    const errors = {
      foto: document.getElementById("error-foto"),
      nama: document.getElementById("error-nama"),
      harga: document.getElementById("error-harga"),
      stok: document.getElementById("error-stok"),
      berat: document.getElementById("error-berat")
    };

    // Input Elements
    const inputs = {
      nama: document.getElementById("nama_produk"),
      harga: document.getElementById("harga_jual"),
      stok: document.getElementById("stok"),
      berat: document.getElementsByName("berat")
    };

    // Event Listeners untuk Upload Foto
    if (fotoUploader && fotoInput) {
      fotoUploader.addEventListener("click", () => fotoInput.click());
      fotoInput.addEventListener("change", handleFotoSelection);
    }

    // Event Listeners untuk Validasi
    if (inputs.nama) inputs.nama.addEventListener("input", validateForm);
    if (inputs.harga) inputs.harga.addEventListener("input", validateForm);
    if (inputs.stok) inputs.stok.addEventListener("input", validateForm);
    if (inputs.berat) {
      Array.from(inputs.berat).forEach(radio => {
        radio.addEventListener("change", validateForm);
      });
    }

    // Handle File Selection
    function handleFotoSelection(event) {
      const newFiles = Array.from(event.target.files);
      const totalFiles = selectedFiles.length + newFiles.length;

      if (totalFiles > 3) {
        alert("Maksimal 3 foto!");
        event.target.value = null;
        return;
      }

      selectedFiles = selectedFiles.concat(newFiles);
      updateInputFiles();
      renderFotoPreviews();
      validateForm();
    }

    // Update Input Files dengan DataTransfer
    function updateInputFiles() {
      const dt = new DataTransfer();
      selectedFiles.forEach(file => dt.items.add(file));
      fotoInput.files = dt.files;
    }

    // Render Photo Previews
    function renderFotoPreviews() {
      previewContainer.innerHTML = "";

      selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const div = document.createElement("div");
          div.className = "relative w-24 h-24 shadow-md";
          div.innerHTML = `
            <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover rounded-lg">
            <button type="button" onclick="removeFoto(${index})" 
              class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold shadow-lg hover:bg-red-700 transition">
              &times;
            </button>
          `;
          previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }

    // Remove Photo
    function removeFoto(index) {
      selectedFiles.splice(index, 1);
      updateInputFiles();
      renderFotoPreviews();
      validateForm();
    }

    // Validasi Form
    function validateForm() {
      let isValid = true;

      // Validasi Foto (hanya untuk mode tambah)
      if (!isEditMode) {
        if (selectedFiles.length === 0) {
          showError(errors.foto);
          isValid = false;
        } else {
          hideError(errors.foto);
        }
      }

      // Validasi Nama
      if (!inputs.nama.value.trim()) {
        showError(errors.nama);
        isValid = false;
      } else {
        hideError(errors.nama);
      }

      // Validasi Harga
      if (!inputs.harga.value || inputs.harga.value <= 0) {
        showError(errors.harga);
        isValid = false;
      } else {
        hideError(errors.harga);
      }

      // Validasi Stok
      if (!inputs.stok.value || inputs.stok.value < 0) {
        showError(errors.stok);
        isValid = false;
      } else {
        hideError(errors.stok);
      }

      // Validasi Berat
      const beratChecked = Array.from(inputs.berat).some(radio => radio.checked);
      if (!beratChecked) {
        showError(errors.berat);
        isValid = false;
      } else {
        hideError(errors.berat);
      }

      // Enable/Disable Button
      btnSubmit.disabled = !isValid;

      return isValid;
    }

    // Helper Functions
    function showError(element) {
      if (element) element.classList.remove("hidden");
    }

    function hideError(element) {
      if (element) element.classList.add("hidden");
    }

    // Validasi saat halaman dimuat
    if (btnSubmit) {
      window.addEventListener("DOMContentLoaded", validateForm);
    }
  </script>
</body>

</html>