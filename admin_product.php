<?php
session_start();
if (!$_SESSION['admin']) {
  header('Location: index.php');
  exit();
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

    <div id="page-form" class="page-content hidden bg-[#F7F7F7] p-6 rounded-xl shadow-lg max-w-4xl">
      <h2 id="form-title" class="text-2xl font-bold text-[#8D5A40] mb-6">
        Tambah Produk Baru
      </h2>

      <form id="formProduk">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk (Maks. 3)</label>

              <div id="foto-uploader"
                class="border-2 border-dashed border-gray-400 p-6 text-center rounded-lg cursor-pointer hover:border-[#8D5A40] transition duration-150">
                <span class="text-[#8D5A40] text-xl">+</span>
                <p class="text-sm text-gray-500">Klik untuk Tambah Foto</p>
                <input type="file" id="foto-input" class="hidden" multiple accept="image/*" />
              </div>

              <div id="foto-preview-container" class="mt-4 flex flex-wrap gap-4"></div>
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
              <input type="number" id="harga_jual" name="harga_jual"
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
          <button type="submit"
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

    // Fungsi untuk menangani saat file dipilih
    function handleFotoSelection(event) {
      const newFiles = Array.from(event.target.files);
      const totalFiles = selectedFiles.length + newFiles.length;

      // Batasi jumlah file
      if (totalFiles > 3) {
        alert("Anda hanya dapat mengunggah maksimal 3 foto.");
        event.target.value = null; // Bersihkan input
        return;
      }

      selectedFiles = selectedFiles.concat(newFiles);
      renderFotoPreviews(); // Panggil fungsi untuk menampilkan pratinjau

      // Bersihkan input agar bisa pilih file yg sama jika (setelah) dihapus
      event.target.value = null;
    }

    // Fungsi untuk menampilkan pratinjau foto
    function renderFotoPreviews() {
      const previewContainer = document.getElementById(
        "foto-preview-container"
      );
      previewContainer.innerHTML = ""; // Kosongkan dulu

      selectedFiles.forEach((file, index) => {
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
                onclick="removeFoto(${index})"
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
    function removeFoto(index) {
      selectedFiles.splice(index, 1); // Hapus file dari array
      renderFotoPreviews(); // Gambar ulang pratinjau
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
      if (pageId === "form") {
        document.getElementById("addbtn").classList.add("hidden");
        const title = document.getElementById("form-title");
        const form = document.getElementById("formProduk");
        form.reset();

        // --- MODIFIKASI: Reset foto saat form dibuka/dibatalkan ---
        selectedFiles = []; // Kosongkan array file
        renderFotoPreviews(); // Bersihkan pratinjau di UI
        // --- AKHIR MODIFIKASI ---

        if (isEdit) {
          title.textContent = "Edit Produk (ID: " + productId + ")";
        } else {
          title.textContent = "Tambah Produk Baru";
        }
      }
    }

    // Fungsi editProduk (Tetap)
    function editProduk(productId) {
      showPage("form", true, productId);
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

    // Fungsi submit form (Tetap)
    document
      .getElementById("formProduk")
      .addEventListener("submit", function(e) {

        console.log(
          "Form submitted! Data siap dikirim ke Backend.",
          selectedFiles // Anda bisa lihat file yang tersimpan di console
        );
        // Setelah pengiriman berhasil, panggil showPage('daftar');
      });

    // Tampilkan halaman default ('daftar') saat pertama kali dimuat (DIMODIFIKASI)
    document.addEventListener("DOMContentLoaded", () => {
      showPage("daftar");

      // --- MODIFIKASI: Tambahkan event listener untuk foto ---
      const fotoUploader = document.getElementById("foto-uploader");
      const fotoInput = document.getElementById("foto-input");

      // 1. Memicu input file saat area uploader diklik
      fotoUploader.addEventListener("click", () => {
        fotoInput.click();
      });

      // 2. Menangani file yang dipilih
      fotoInput.addEventListener("change", handleFotoSelection);
      // --- AKHIR MODIFIKASI ---
    });
  </script>
</body>

</html>