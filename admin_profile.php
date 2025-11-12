<?php
session_start();
if (!$_SESSION['admin']) {
  header('Location: index.php');
  exit();
}
// Fetch data admin
$pdo = require 'koneksi.php';
$sql = 'SELECT * FROM admins WHERE id=:id';
$query = $pdo->prepare($sql);
$query->execute([
  'id' => $_SESSION['admin']['id']
]);
$admin = $query->fetch();
$base64 = base64_encode($admin['profile']);

// ganti profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['submitProfile']) && isset($_FILES['profile']) && $_FILES['profile']['error'] === 0) {
    $pdo = require 'koneksi.php';
    $sql = 'UPDATE admins SET profile=:profile WHERE id = :id';
    $query = $pdo->prepare($sql);
    $query->execute(array(
      'profile' => file_get_contents($_FILES['profile']['tmp_name']),
      'id' => $_SESSION['admin']['id']
    ));
    unset($_FILES);
    echo "<script>alert('Profile berhasil diubah');</script>";
  }
}

// ganti username dan email
$error = '';
if (isset($_POST['submitUser'])) {
  $pdo = require 'koneksi.php';
  if ($_POST['username'] === '' || $_POST['email'] === '') {
    // Jika kedua field kosong, tidak perlu melakukan update
    header('Location: admin_profile.php');
    exit();
  }
  $sqlEmail = "SELECT count(*) FROM admins WHERE email=:email and id!=:id";
  $queryEmail = $pdo->prepare($sqlEmail);
  $queryEmail->execute(array(
    'email' => $_POST['email'],
    'id' => $_SESSION['admin']['id']
  ));
  $count = $queryEmail->fetchColumn();
  if ($count > 0) {
    $error = 'Email sudah digunakan oleh admin lain.';
  } else {
    // Lanjutkan dengan update jika email belum digunakan
    $error = '';
    $sql = 'UPDATE admins SET username=:username, email=:email WHERE id = :id';
    $query = $pdo->prepare($sql);
    $query->execute(array(
      'username' => $_POST['username'],
      'email' => $_POST['email'],
      'id' => $_SESSION['admin']['id']
    ));
    // update session
    $_SESSION['admin']['username'] = $_POST['username'];
    $_SESSION['admin']['email'] = $_POST['email'];
  }
}
// ganti password
$errorPass = '';
if (!empty($_POST['old_password']) && !empty($_POST['password'])) {
  if (sha1($_POST['old_password']) != $admin['password']) {
    $errorPass = "Password Lama Salah!";
  } else {
    if ($_POST['password'] != $_POST['confirm_password']) {
      $errorPass = 'Konfirmasi Password tidak sama dengan Password Baru!';
    } else {
      $errorPass = '';
      $sqlPass = 'UPDATE admins SET password=:password WHERE id = :id';
      $queryPass = $pdo->prepare($sqlPass);
      $queryPass->execute(array(
        'password' => sha1($_POST['password']),
        'id' => $_SESSION['admin']['id']
      ));
      echo "<script>alert('Password Berhasil diganti!');</script>";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Admin - Orinuts</title>
  <link rel="stylesheet" href="src/outputail.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

  <style>
    .sidebar-bg {
      /* Ganti dengan path gambar almond Anda jika ada */
      background-image: url("content/almond-bg.png");
      background-size: cover;
      background-position: bottom;
      background-repeat: no-repeat;
    }
  </style>
</head>

<body class="bg-gray-50 flex min-h-screen">
  <aside
    class="bg-white p-5 text-[#8B4513] overflow-y-auto shadow-lg rounded-r-3xl min-h-screen flex-shrink-0 w-64 flex flex-col justify-between sidebar-bg">
    <div class="top-section">
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
          <a href="admin_product.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-box-open w-4"></i> Produk
            </li>
          </a>
          <a href="admin_message.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-comment w-4"></i> Message
              <span
                class="ml-auto bg-red-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full">2</span>
            </li>
          </a>
          <a href="admin_order.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-shopping-cart w-4"></i> Order
            </li>
          </a>
          <a href="profile-admin.html">
            <li
              class="nav-item font-bold p-2.5 flex items-center gap-3 cursor-pointer text-[#8B4513] bg-[#D2A278] rounded-r-3xl"
              style="background-color: #d2a278">
              <i class="fas fa-user-circle w-4"></i> Profile
            </li>
          </a>
        </ul>
      </nav>
    </div>
    <div class="h-20"></div>
  </aside>

  <main class="flex-1 p-8">
    <header class="flex justify-between items-center mb-6 border-b pb-4">
      <h1 class="text-2xl font-semibold text-[#8B4513]">HALAMAN ADMIN!!</h1>
      <div
        class="p-2 border rounded-full text-[#8B4513] cursor-pointer hover:bg-gray-100 transition">
        <i class="fas fa-user-circle text-xl"></i>
      </div>
    </header>

    <div class="bg-white p-8 rounded-lg shadow-lg">
      <h2 class="text-xl font-bold text-blue-800 mb-8 border-b pb-2">
        Informasi Tentang Anda
      </h2>




      <div class="flex justify-between gap-12 mb-8">

        <form action="" method="post" enctype="multipart/form-data">
          <div class="flex flex-col items-center">
            <div class="relative w-40 h-40 mb-4">
              <div
                id="avatar-preview"
                class="w-full h-full bg-gray-300 rounded-full flex items-center justify-center text-gray-500 text-6xl overflow-hidden">
                <?php echo "<img src= 'data:image/*;base64, $base64' class='fa' alt='Profile Picture'>" ?>
              </div>
              <button
                type="button"
                id="edit-avatar-btn"
                class="absolute bottom-1 right-1 bg-orange-400 p-2 rounded-full shadow-md hover:bg-orange-500 transition">
                <i class="fas fa-pencil-alt text-white text-xs"></i>
              </button>
              <input
                type="file"
                id="avatar-upload"
                name="profile"
                class="hidden"
                accept="image/*" />
            </div>
          </div>
          <div class="flex justify-end mt-8">
            <button
              type="submit"
              name="submitProfile"
              value="submitProfile"
              class="px-6 py-2 bg-[#8B4513] text-white font-semibold rounded-md shadow-lg hover:bg-[#A0522D] transition">
              Ganti Profile
            </button>
          </div>
        </form>
        <form action="" method="post">
          <div class="md:col-span-2 grid grid-cols-2 gap-x-6 gap-y-6">
            <div>
              <label
                for="first_name"
                class="block text-sm font-medium text-gray-700 mb-1">Username</label>
              <input
                type="text"
                name="username"
                id="username"
                placeholder="<?php echo htmlspecialchars($admin['username']); ?>"
                class="w-full p-2 border border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] bg-gray-100" />
            </div>

            <div>
              <label
                for="email"
                class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input
                type="text"
                name="email"
                id="email"
                placeholder="<?php echo htmlspecialchars($admin['email']); ?>"
                class="w-full p-2 border border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] bg-gray-100" />
            </div>
            <?php if ($error): ?>
              <div class="col-span-2 text-red-600 font-semibold">
                <?php echo htmlspecialchars($error); ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="flex justify-end mt-10">
            <button
              type="submit"
              name="submitUser"
              class="px-6 py-2 bg-[#8B4513] text-white font-semibold rounded-md shadow-lg hover:bg-[#A0522D] transition">
              Ubah Informasi
            </button>
          </div>
        </form>
        <form action="" method="post">
          <div class="md:col-span-2 flex flex-col gap-x-6 gap-y-6">
            <div>
              <label
                for="old_password"
                class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
              <input
                type="password"
                name="old_password"
                id="old_password"
                placeholder="Password Lama"
                class="w-full p-2 border border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] bg-gray-100" />
            </div>
            <div class="flex gap-3">
              <div>
                <label
                  for="password"
                  class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                  type="password"
                  name="password"
                  id="password"
                  placeholder="Password Baru"
                  class="w-full p-2 border border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] bg-gray-100" />
              </div>
              <div>
                <label
                  for="Confirm_password"
                  class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input
                  type="password"
                  name="confirm_password"
                  id="confirm_password"
                  placeholder="Konfirmasi Password Baru"
                  class="w-full p-2 border border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] bg-gray-100" />
              </div>
            </div>
          </div>
          <?php if ($errorPass): ?>
              <div class="col-span-2 text-red-600 font-semibold">
                <?php echo htmlspecialchars($errorPass); ?>
              </div>
            <?php endif; ?>
          <div class="flex justify-end mt-10">
            <button
              type="submit"
              name="submitPassword"
              class="px-6 py-2 bg-[#8B4513] text-white font-semibold rounded-md shadow-lg hover:bg-[#A0522D] transition">
              Ubah Password
            </button>
          </div>
        </form>
      </div>



      <div class="flex justify-end mt-10">
        <button
          id="logout-btn"
          class="bg-[#A52A2A] text-white font-semibold px-6 py-3 rounded-lg shadow-xl hover:bg-[#8B0000] transition flex items-center gap-2">
          Logout <i class="fas fa-sign-out-alt"></i>
        </button>
      </div>
    </div>
  </main>

  <div
    id="logout-modal"
    class="fixed inset-0 bg-opacity-50 hidden items-center justify-center z-50">
    <form action="logout.php" method="post">
      <div
        class="bg-white p-6 rounded-lg shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0"
        id="modal-content">
        <h3
          class="text-xl font-bold text-[#8B4513] mb-4 flex items-center gap-2">
          <i class="fas fa-exclamation-triangle text-red-500"></i> Konfirmasi
          Logout
        </h3>
        <p class="text-gray-600 mb-6">
          Apakah Anda yakin ingin keluar dari halaman admin?
        </p>
        <div class="flex justify-end gap-3">
          <button
            id="cancel-logout-btn"
            class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-md hover:bg-gray-300 transition">
            Batal
          </button>
          <button
            id="confirm-logout-btn"
            type="submit"
            class="px-4 py-2 bg-[#A52A2A] text-white font-semibold rounded-md hover:bg-[#8B0000] transition">
            Ya, Keluar
          </button>
        </div>
      </div>
    </form>
  </div>


  <script>
    // ===============================================
    // Fungsionalitas Upload Avatar
    // (Tetap sama)
    // ===============================================
    const editAvatarBtn = document.getElementById("edit-avatar-btn");
    const avatarUpload = document.getElementById("avatar-upload");
    const avatarPreview = document.getElementById("avatar-preview");

    editAvatarBtn.addEventListener("click", () => {
      avatarUpload.click();
    });

    avatarUpload.addEventListener("change", function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.createElement("img");
          img.src = e.target.result;
          img.className = "w-full h-full object-cover";

          avatarPreview.innerHTML = "";
          avatarPreview.appendChild(img);
        };
        reader.readAsDataURL(file);
      }
    });

    // ===============================================
    // Fungsionalitas Logout dengan Modal Kustom
    // (Tetap sama)
    // ===============================================
    const logoutBtn = document.getElementById("logout-btn");
    const logoutModal = document.getElementById("logout-modal");
    const confirmLogoutBtn = document.getElementById("confirm-logout-btn");
    const cancelLogoutBtn = document.getElementById("cancel-logout-btn");
    const modalContent = document.getElementById("modal-content");

    function showModal() {
      logoutModal.classList.remove("hidden");
      logoutModal.classList.add("flex");
      setTimeout(() => {
        modalContent.classList.remove("scale-95", "opacity-0");
        modalContent.classList.add("scale-100", "opacity-100");
      }, 10);
    }

    function hideModal() {
      modalContent.classList.remove("scale-100", "opacity-100");
      modalContent.classList.add("scale-95", "opacity-0");
      setTimeout(() => {
        logoutModal.classList.remove("flex");
        logoutModal.classList.add("hidden");
      }, 300);
    }

    logoutBtn.addEventListener("click", showModal);
    cancelLogoutBtn.addEventListener("click", hideModal);

    confirmLogoutBtn.addEventListener("click", function() {
      hideModal();
      window.location.href = "index.html";
    });

    logoutModal.addEventListener("click", function(e) {
      if (e.target === logoutModal) {
        hideModal();
      }
    });
  </script>
</body>

</html>