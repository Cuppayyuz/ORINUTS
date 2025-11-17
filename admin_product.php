<?php
session_start();
if (!$_SESSION['admin']) {
  header('Location: index.php');
  exit();
}
var_dump($_FILES['image']);
// Tambah produk
$error = '';
$showForm = false;

if (!empty($_POST) && isset($_POST['submitProduk'])) {
  $showForm = true; // Tetap tampilkan form jika ada error
  
  if (empty($_POST['nama_produk'])) {
    $error = 'Nama produk harus diisi';
  } else if ($_FILES['image']['error'][0] !== 0) {
    $error = 'Gambar minimal harus 1';
  } else if (empty($_POST['harga_jual'])) {
    $error = 'Harga jual harus diisi';
  } else if (empty($_POST['stok'])) {
    $error = 'Stok harus diisi';
  } else if (empty($_POST['berat'])) {
    $error = 'Varian berat harus dipilih';
  } else {
    try {
      $pdo = require 'koneksi.php';
      
      // Proses gambar
      $image1 = !empty($_FILES['image']['tmp_name'][0]) ? file_get_contents($_FILES['image']['tmp_name'][0]) : null;
      $image2 = !empty($_FILES['image']['tmp_name'][1]) ? file_get_contents($_FILES['image']['tmp_name'][1]) : null;
      $image3 = !empty($_FILES['image']['tmp_name'][2]) ? file_get_contents($_FILES['image']['tmp_name'][2]) : null;
      
      $sql = "INSERT INTO products (nama_produk, harga, varian, kategori, deskripsi, stok, image1, image2, image3) 
              VALUES (:nama_produk, :harga, :varian, :kategori, :deskripsi, :stok, :image1, :image2, :image3)";
      $query = $pdo->prepare($sql);
      $query->execute([
        "nama_produk" => $_POST['nama_produk'],
        "harga" => $_POST['harga_jual'],
        "varian" => $_POST['berat'],
        "kategori" => $_POST['kategori'],
        "deskripsi" => $_POST['deskripsi'],
        "stok" => $_POST['stok'],
        "image1" => $image1,
        "image2" => $image2,
        "image3" => $image3
      ]);
      
      // Redirect setelah berhasil
      header('Location: admin_product.php?success=1');
      exit();
    } catch (Exception $e) {
      $error = 'Gagal menyimpan produk: ' . $e->getMessage();
    }
  }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Produk - OurNuts (Desain Baru)</title>
  <link rel="stylesheet" href="src/outputail.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    /* CSS Tambahan untuk Latar Belakang Sidebar dan Warna */
    .sidebar-bg {
      /* Ganti URL_GAMBAR_Kacang dengan path gambar kacang yang kamu gunakan */
      background-image: url();
      background-size: cover;
      background-position: bottom center;
    }

    /* Override warna fokus input Tailwind agar sesuai dengan skema */
    /* Catatan: Nilai --tw-ring-color di sini adalah warna kustom yang sama (#8d5a40) */
    .focus-ring-primary-custom:focus {
      --tw-ring-color: #8d5a40;
      /* Cokelat tua untuk fokus */
      outline: 2px solid #8d5a40;
      outline-offset: 2px;
    }

    /* Semua Custom CSS untuk Navigasi Miring Dihapus karena menggunakan kode navbar yang baru. */
  </style>
</head>

<body class="bg-[#F5F5F5] flex min-h-screen">
  <aside class="bg-white p-5 text-[#8B4513] overflow-y-auto shadow-lg rounded-r-3xl min-h-screen flex-shrink-0 w-63">
    <div class="logo mb-6">
      <img src="content/logo.png" alt="Logo Orinuts" class="w-28" />
    </div>
    <nav>
      <ul class="space-y-1 text-sm">
        <a href="admin_dashboard.php">
          <li
            class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
            <i class="fas fa-home w-4"></i> Beranda
          </li>
        </a>

        <li
          class="nav-item font-bold p-2.5 flex items-center gap-3 cursor-pointer text-[#8B4513] bg-[#D2A278] rounded-r-3xl">
          <i class="fas fa-box-open w-4"></i> Produk
        </li>
        <a href="admin_message.php">
          <li class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
            <i class="fas fa-comment w-4"></i> Message
            <span class="ml-auto bg-red-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full">2</span>
          </li>
        </a>
        </li>
        <a href="admin_order.php">
          <li
            class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
            <i class="fas fa-shopping-cart w-4"></i> Order
          </li>
        </a>
        <a href="admin_profile.php">
          <li
            class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
            <i class="fas fa-user-circle w-4"></i> Profile
          </li>
        </a>
      </ul>
    </nav>
  </aside>
  <main class="flex-1 p-8">
    <header class="flex justify-between items-center mb-6 p-4 bg-[#8D5A40] rounded-xl shadow-md text-white">
      <h1 class="text-xl font-light tracking-widest">HALAW ADMIN!!</h1>
      <div class="flex items-center">
        <span class="text-white text-xl ml-4">👤</span>
      </div>
    </header>
    
    <?php if (isset($_GET['success'])): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        Produk berhasil ditambahkan!
      </div>
    <?php endif; ?>
    
    <div class="flex justify-end" id="addbtn">
      <button onclick="showPage('form')"
        class="bg-[#8D5A40] text-white px-6 py-3 rounded-full hover:bg-[#8D5A40]/90 transition duration-150 shadow-lg flex items-center">
        <span class="text-xl mr-2">+</span> Tambah Produk
      </button>
    </div>
    <div id="page-daftar" class="page-content bg-[#F7F7F7] p-6 rounded-xl shadow-lg mt-5">
      <div
        class="grid grid-cols-7 gap-4 font-bold uppercase text-xs text-[#8D5A40] border-b-2 border-[#8D5A40] pb-3 mb-4">
        <div class="col-span-1">Picture</div>
        <div class="col-span-3">Nama Produk</div>
        <div class="col-span-1 text-center">Pembayaran</div>
        <div class="col-span-1 text-center">Stok</div>
        <div class="col-span-1 text-center">Aksi</div>
      </div>

      <div id="product-list" class="space-y-3 pb-20">
        <div
          class="grid grid-cols-7 gap-4 items-center bg-[#D0A37D] bg-opacity-70 p-3 rounded-lg shadow-sm text-[#8D5A40] font-medium">
          <div class="col-span-1">
            <img src="https://dummyimage.com/40x40/fff/8D5A40&text=Bag" alt="Produk"
              class="w-10 h-10 object-cover rounded shadow" />
          </div>
          <div class="col-span-3">Orinuts Roasted Cashew</div>
          <div class="col-span-1 text-center">COD</div>
          <div class="col-span-1 text-center">67</div>
          <div class="col-span-1 flex justify-center space-x-2 text-sm">
            <button onclick="editProduk(1)" class="text-green-800 hover:text-green-600 flex items-center">
              <span class="mr-1">📝</span> edit
            </button>
            <button onclick="hapusProduk(1)" class="text-red-700 hover:text-red-500 flex items-center">
              <span class="mr-1">🗑️</span> hapus
            </button>
          </div>
        </div>

        <div
          class="grid grid-cols-7 gap-4 items-center bg-[#D0A37D] bg-opacity-40 p-3 rounded-lg shadow-sm text-[#8D5A40] font-medium">
          <div class="col-span-1">
            <img src="https://dummyimage.com/40x40/fff/8D5A40&text=Bag" alt="Produk"
              class="w-10 h-10 object-cover rounded shadow" />
          </div>
          <div class="col-span-3">Orinuts Roasted Almond</div>
          <div class="col-span-1 text-center">Transfer</div>
          <div class="col-span-1 text-center">50</div>
          <div class="col-span-1 flex justify-center space-x-2 text-sm">
            <button onclick="editProduk(2)" class="text-green-800 hover:text-green-600 flex items-center">
              <span class="mr-1">📝</span> edit
            </button>
            <button onclick="hapusProduk(2)" class="text-red-700 hover:text-red-500 flex items-center">
              <span class="mr-1">🗑️</span> hapus
            </button>
          </div>
        </div>
      </div>
    </div>

    <div id="page-form-edit" class="page-content hidden bg-[#F7F7F7] p-6 rounded-xl shadow-lg max-w-4xl">
      <h2 id="form-title" class="text-2xl font-bold text-[#8D5A40] mb-6">
        Edit Produk
      </h2>

      <form id="formEdit" action="" method="post" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk (Maks. 3)</label>

              <div id="foto-uploader-edit"
                class="foto-uploader border-2 border-dashed border-gray-400 p-6 text-center rounded-lg cursor-pointer hover:border-[#8D5A40] transition duration-150">
                <span class="text-[#8D5A40] text-xl">+</span>
                <p class="text-sm text-gray-500">Klik untuk Tambah Foto</p>
                <input type="file" name="image[]" id="foto-input-edit" class="hidden foto-input" multiple accept="image/*" />
              </div>

              <div id="foto-preview-container-edit" class="mt-4 flex flex-wrap gap-4"></div>
            </div>
            <div class="mb-4">
              <label for="nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
              <input type="text" id="nama" name="nama_produk"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
            </div>
            <div class="mb-4">
              <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
              <select id="kategori" name="kategori"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom">
                <option>Orinuts</option>
                <option>Orithin</option>
                <option>Orimond</option>
                <option>Rumah Mente</option>
              </select>
            </div>
          </div>

          <div>
            <div class="mb-4">
              <label for="harga_jual" class="block text-sm font-medium text-gray-700">Harga Jual</label>
              <input type="number" id="harga_jual" name="harga"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
            </div>
            <div class="mb-4">
              <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
              <input type="number" id="stok" name="stok"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Varian Berat</label>
              <div class="flex flex-wrap gap-2">
                <label
                  class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                  <input type="radio" name="berat" value="75gr" class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                  <span class="text-sm">75 gram</span>
                </label>
                <label
                  class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                  <input type="radio" name="berat" value="200gr" class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                  <span class="text-sm">200 gram</span>
                </label>
                <label
                  class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                  <input type="radio" name="berat" value="500gr" class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                  <span class="text-sm">500 gram</span>
                </label>
              </div>
            </div>
            <div class="mb-4">
              <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
              <textarea id="deskripsi" name="deskripsi" rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom"></textarea>
            </div>
          </div>
        </div>
        <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end">
          <button type="button"
            class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition duration-150 mr-3"
            onclick="showPage('daftar')">
            Batal
          </button>
          <button type="submit" name="submitEdit" value="edit"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-150">
            Simpan Produk
          </button>
        </div>
      </form>
    </div>

    <div id="page-form" class="page-content hidden bg-[#F7F7F7] p-6 rounded-xl shadow-lg max-w-4xl">
      <h2 id="form-title" class="text-2xl font-bold text-[#8D5A40] mb-6">
        Tambah Produk Baru
      </h2>

      <form id="formProduk" action="" method="post" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk (Maks. 3)</label>

              <div id="foto-uploader"
                class="foto-uploader border-2 border-dashed border-gray-400 p-6 text-center rounded-lg cursor-pointer hover:border-[#8D5A40] transition duration-150">
                <span class="text-[#8D5A40] text-xl">+</span>
                <p class="text-sm text-gray-500">Klik untuk Tambah Foto</p>
                <input type="file" name="image[]" id="foto-input" class="hidden foto-input" multiple accept="image/*" />
              </div>

              <div id="foto-preview-container" class="mt-4 flex flex-wrap gap-4"></div>
            </div>
            <div class="mb-4">
              <label for="nama-tambah" class="block text-sm font-medium text-gray-700">Nama Produk</label>
              <input type="text" id="nama-tambah" name="nama_produk" value="<?php echo isset($_POST['nama_produk']) ? htmlspecialchars($_POST['nama_produk']) : ''; ?>"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
            </div>
            <div class="mb-4">
              <label for="kategori-tambah" class="block text-sm font-medium text-gray-700">Kategori</label>
              <select id="kategori-tambah" name="kategori"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom">
                <option <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Orinuts') ? 'selected' : ''; ?>>Orinuts</option>
                <option <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Orithin') ? 'selected' : ''; ?>>Orithin</option>
                <option <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Orimond') ? 'selected' : ''; ?>>Orimond</option>
                <option <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Rumah Mente') ? 'selected' : ''; ?>>Rumah Mente</option>
              </select>
            </div>
          </div>

          <div>
            <div class="mb-4">
              <label for="harga_jual-tambah" class="block text-sm font-medium text-gray-700">Harga Jual</label>
              <input type="number" id="harga_jual-tambah" name="harga_jual" value="<?php echo isset($_POST['harga_jual']) ? htmlspecialchars($_POST['harga_jual']) : ''; ?>"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
            </div>
            <div class="mb-4">
              <label for="stok-tambah" class="block text-sm font-medium text-gray-700">Stok</label>
              <input type="number" id="stok-tambah" name="stok" value="<?php echo isset($_POST['stok']) ? htmlspecialchars($_POST['stok']) : ''; ?>"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom" />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Varian Berat</label>
              <div class="flex flex-wrap gap-2">
                <label
                  class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                  <input type="radio" name="berat" value="75gr" <?php echo (isset($_POST['berat']) && $_POST['berat'] == '75gr') ? 'checked' : ''; ?> class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                  <span class="text-sm">75 gram</span>
                </label>
                <label
                  class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                  <input type="radio" name="berat" value="200gr" <?php echo (isset($_POST['berat']) && $_POST['berat'] == '200gr') ? 'checked' : ''; ?> class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                  <span class="text-sm">200 gram</span>
                </label>
                <label
                  class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                  <input type="radio" name="berat" value="500gr" <?php echo (isset($_POST['berat']) && $_POST['berat'] == '500gr') ? 'checked' : ''; ?> class="text-[#8D5A40] focus:ring-[#8D5A40]" />
                  <span class="text-sm">500 gram</span>
                </label>
              </div>
            </div>
            <div class="mb-4">
              <label for="deskripsi-tambah" class="block text-sm font-medium text-gray-700">Deskripsi</label>
              <textarea id="deskripsi-tambah" name="deskripsi" rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-[#8D5A40] focus-ring-primary-custom"><?php echo isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : ''; ?></textarea>
            </div>
          </div>
        </div>
        <?php if ($error): ?>
          <p class='text-red-600 font-bold mb-4'><?php echo $error; ?></p>
        <?php endif; ?>
        <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end">
          <button type="button"
            class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition duration-150 mr-3"
            onclick="showPage('daftar')">
            Batal
          </button>
          <button type="submit" name="submitProduk" value="produk"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-150">
            Simpan Produk
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    // === FUNGSI FOTO (TAMBAHAN) ===
    let selectedFiles = []; // Variabel global untuk simpan file
    let selectedFilesEdit = []; // Untuk form edit

    // Fungsi untuk menangani saat file dipilih
    function handleFotoSelection(event, formType = 'add') {
      const newFiles = Array.from(event.target.files);
      const currentFiles = formType === 'add' ? selectedFiles : selectedFilesEdit;
      const totalFiles = currentFiles.length + newFiles.length;

      // Batasi jumlah file
      if (totalFiles > 3) {
        alert("Anda hanya dapat mengunggah maksimal 3 foto.");
        event.target.value = null; // Bersihkan input
        return;
      }

      if (formType === 'add') {
        selectedFiles = selectedFiles.concat(newFiles);
      } else {
        selectedFilesEdit = selectedFilesEdit.concat(newFiles);
      }
      
      renderFotoPreviews(formType); // Panggil fungsi untuk menampilkan pratinjau

      // Bersihkan input agar bisa pilih file yg sama jika (setelah) dihapus
      event.target.value = null;
    }

    // Fungsi untuk menampilkan pratinjau foto
    function renderFotoPreviews(formType = 'add') {
      const containerId = formType === 'add' ? 'foto-preview-container' : 'foto-preview-container-edit';
      const previewContainer = document.getElementById(containerId);
      const files = formType === 'add' ? selectedFiles : selectedFilesEdit;
      
      previewContainer.innerHTML = ""; // Kosongkan dulu

      files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const previewElement = document.createElement("div");
          // styling untuk pratinjau
          previewElement.className = "relative w-24 h-24 shadow-md";

          previewElement.innerHTML = `
              <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover rounded-lg">
              
              <button 
                type="button" 
                class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold shadow-lg hover:bg-red-700 transition" 
                onclick="removeFoto(${index}, '${formType}')"
              >
                &times; 
              </button>
            `;
          previewContainer.appendChild(previewElement);
        };
        reader.readAsDataURL(file);
      });
    }

    // Fungsi untuk menghapus foto
    function removeFoto(index, formType = 'add') {
      if (formType === 'add') {
        selectedFiles.splice(index, 1); // Hapus file dari array
      } else {
        selectedFilesEdit.splice(index, 1);
      }
      renderFotoPreviews(formType); // Gambar ulang pratinjau
    }
    // === AKHIR FUNGSI FOTO ===

    // Fungsi utama showPage (DIMODIFIKASI)
    function showPage(pageId, isEdit = false, productId = null) {
      // Sembunyikan semua halaman konten
      document.querySelectorAll(".page-content").forEach((div) => {
        div.classList.add("hidden");
      });
      document.getElementById("addbtn").classList.remove("hidden");

      // Tampilkan halaman yang diminta
      const pageElement = document.getElementById("page-" + pageId);
      if (pageElement) {
        pageElement.classList.remove("hidden");
      }

      // Logika untuk Form
      if (pageId === 'form') {
        document.getElementById("addbtn").classList.add("hidden");
        const form = document.getElementById("formProduk");
        form.reset();

        // --- MODIFIKASI: Reset foto saat form dibuka/dibatalkan ---
        selectedFiles = []; // Kosongkan array file
        renderFotoPreviews('add'); // Bersihkan pratinjau di UI
        // --- AKHIR MODIFIKASI ---

      } else if (pageId === 'form-edit') {
        document.getElementById("addbtn").classList.add("hidden");
        const formEdit = document.getElementById("formEdit");
        formEdit.reset();

        // --- MODIFIKASI: Reset foto saat form dibuka/dibatalkan ---
        selectedFilesEdit = []; // Kosongkan array file
        renderFotoPreviews('edit'); // Bersihkan pratinjau di UI
        // --- AKHIR MODIFIKASI ---

      }
    }

    // Fungsi editProduk (Tetap)
    function editProduk(productId) {
      showPage("form-edit", true, productId);
    }

    // Fungsi hapusProduk (Tetap)
    function hapusProduk(productId) {
      if (
        confirm(
          "Apakah Anda yakin ingin menghapus produk ID: " + productId + "?"
        )
      ) {
        console.log(
          "Siap mengirim permintaan DELETE ke Backend untuk ID: " + productId
        );
      }
    }

    // Tampilkan halaman default ('daftar') saat pertama kali dimuat (DIMODIFIKASI)
    document.addEventListener("DOMContentLoaded", () => {
      <?php if ($showForm): ?>
        // Jika ada error, tampilkan form
        showPage('form');
      <?php else: ?>
        showPage('daftar');
      <?php endif; ?>

      // --- MODIFIKASI: Tambahkan event listener untuk foto ---
      const fotoUploader = document.getElementById("foto-uploader");
      const fotoInput = document.getElementById("foto-input");
      
      const fotoUploaderEdit = document.getElementById("foto-uploader-edit");
      const fotoInputEdit = document.getElementById("foto-input-edit");

      // 1. Memicu input file saat area uploader diklik - Form Tambah
      if (fotoUploader && fotoInput) {
        fotoUploader.addEventListener("click", () => {
          fotoInput.click();
        });
        
        // 2. Menangani file yang dipilih - Form Tambah
        fotoInput.addEventListener("change", (e) => handleFotoSelection(e, 'add'));
      }
      
      // 1. Memicu input file saat area uploader diklik - Form Edit
      if (fotoUploaderEdit && fotoInputEdit) {
        fotoUploaderEdit.addEventListener("click", () => {
          fotoInputEdit.click();
        });
        
        // 2. Menangani file yang dipilih - Form Edit
        fotoInputEdit.addEventListener("change", (e) => handleFotoSelection(e, 'edit'));
      }
      // --- AKHIR MODIFIKASI ---
    });
  </script>
</body>

</html>