<?php

if (!empty($_POST)) {
  $pdo = require 'koneksi.php';
  $sql = "INSERT INTO users (username, fullname, email, password, provinsi, kabupaten, alamat_lengkap, no_telp) VALUES (:username, :fullname, :email, :password, :provinsi, :kabupaten, :alamat_lengkap, :no_telp)";
  $query = $pdo->prepare($sql);
  $query->execute([
    "username" => $_POST['username'],
    "fullname" => $_POST['fullname'],
    "email" => $_POST['email'],
    "password" => sha1($_POST['password']),
    "provinsi" => $_POST['provinsi'],
    "kabupaten" => $_POST['kabupaten'],
    "alamat_lengkap" => $_POST['alamat'],
    "no_telp" => $_POST['no_telp']
  ]);
  echo "<script>alert('Registrasi berhasil! Silakan login dengan akun Anda.');</script>";
  echo "<script>window.location.href = 'login.php';</script>";
}


?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Multi-Step Register - Gaya Login</title>
    <link rel="stylesheet" href="src/outputail.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />

    <style>
      /* Gaya kustom yang TIDAK DAPAT menjadi utilitas Tailwind: */
      .imgl {
        filter: drop-shadow(-24px 40px 5px rgba(0, 0, 0, 0.3));
      }
      .imgr {
        filter: drop-shadow(20px 15px 5px rgba(0, 0, 0, 0.3));
      }

      /* GAYA TOMBOL DISABLED (MENGGUNAKAN CSS BIASA) */
      .btn-disabled {
        opacity: 0.6;
        cursor: not-allowed;
        box-shadow: none;
      }

      /* GAYA IKON PASSWORD (MENGGUNAKAN CSS BIASA) */
      .password-input-wrapper {
        position: relative;
        width: 100%;
      }
      .password-toggle-icon {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: rgb(107 114 128 / 1);
        z-index: 20;
        font-size: 1.125rem;
      }
      .password-toggle-icon:hover {
        color: #a7483d;
      }

      /* Kelas untuk pembungkus input di Langkah 2 (w-2/3) */
      .full-input-container {
        width: 100%; /* Default: Mobile (100% lebar) */
      }
      @media (min-width: 768px) {
        /* md: */
        .full-input-container {
          width: 66.666667%; /* Desktop: w-2/3 */
        }
      }
    </style>
  </head>
  <body class="bg-[#F4EFD8] flex justify-center items-center min-h-screen">
    <div
      class="register-container flex w-full sm:w-[85%] max-w-[1000px] shadow-2xl rounded-lg overflow-hidden"
    >
      <div
        id="panel-kiri"
        class="image-area hidden md:block md:w-[40%] bg-[#cb3d3d] p-10 relative overflow-hidden"
      >
        <div class="absolute inset-0 flex justify-center items-end">
          <img
            src="content/kacang/mente1-removebg-preview.png"
            alt="Kacang Mente Kiri"
            class="absolute w-32 h-auto z-100 bottom-75 -rotate-70 left-27"
          />
          <img
            src="content/kacang/mente1-removebg-preview.png"
            alt="Kacang Mente Kecil"
            class="absolute w-24 h-auto bottom-4 z-30 rotate-145 left-41"
          />
          <img
            src="content/product/ori-merah-removebg-preview.png"
            alt="Produk Orinuts Merah"
            class="imgr absolute h-auto w-48 bottom-15 -right-10 z-20"
          />
          <img
            src="content/product/ori-4mighty-removebg-preview.png"
            alt="Energy Booster Mix"
            classl="imgl absolute h-auto w-80 bottom-15 -right-48 transform -translate-x-1/2 z-10"
          />
        </div>
      </div>

      <div
        class="form-area w-full md:w-[60%] bg-white p-10 sm:p-12 md:p-16 lg:p-20 flex flex-col items-center justify-center relative"
      >
        <div
          class="flex justify-center items-center gap-8 mb-10 relative z-10 top-4 w-full px-10 sm:px-0"
        >
          <div
            class="absolute top-1/2 left-0 right-0 h-[2px] bg-gray-300 z-5 -translate-y-1/2"
          >
            <div
              id="step-line-fill"
              class="absolute top-0 left-0 h-full w-0 bg-[#a7483d] transition-all duration-300 ease-in-out"
            ></div>
          </div>

          <div
            id="step-1-icon"
            class="step-icon w-8 h-8 rounded-full flex items-center justify-center border-2 border-[#a7483d] bg-[#a7483d] text-white transition-colors duration-300 z-10 text-sm"
          >
            1
          </div>
          <div
            id="step-2-icon"
            class="step-icon w-8 h-8 rounded-full flex items-center justify-center border-2 border-gray-400 bg-white text-gray-400 transition-colors duration-300 z-10 text-sm"
          >
            2
          </div>
          <div
            id="step-3-icon"
            class="step-icon w-8 h-8 rounded-full flex items-center justify-center border-2 border-gray-400 bg-white text-gray-400 transition-colors duration-300 z-10 text-sm"
          >
            3
          </div>
        </div>

        <form id="registerForm" class="w-full mt-16" method="post" action="">
          <div id="step-1" class="step-content">
            <h2
              class="text-3xl sm:text-4xl text-[#a76657] font-semibold mb-8 tracking-widest uppercase font-reglog text-center"
            >
              REGISTER
            </h2>

            <div
              class="social-login-buttons flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mb-8 w-full"
            >
              <button
                type="button"
                onclick="handleSocialSignUp('Google')"
                class="flex-1 flex items-center justify-center gap-2 py-2 px-4 border-2 border-gray-200 rounded-md text-sm hover:bg-gray-50 transition duration-150 shadow-sm hover:border-[#a7483d] hover:shadow-md"
              >
                <img
                  src="content/icon/google.png"
                  alt="Google"
                  class="w-5 h-auto"
                />
                <p class="font-reglog">sign up with google</p>
              </button>
              <button
                type="button"
                onclick="handleSocialSignUp('Facebook')"
                class="flex-1 flex items-center justify-center gap-2 py-2 px-4 border-2 border-gray-200 rounded-md text-sm hover:bg-gray-50 transition duration-150 shadow-sm hover:border-[#a7483d] hover:shadow-md"
              >
                <img
                  src="content/icon/facebook.png"
                  alt="Facebook"
                  class="w-5 h-auto"
                />
                <p class="font-reglog">sign up with facebook</p>
              </button>
            </div>

            <div class="space-y-6">
              <div class="input-group relative">
                <input
                  name="username"
                  type="text"
                  id="username"
                  placeholder="Username"
                  class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md"
                  onblur="validateField('username'); checkStepButton(1)"
                  oninput="clearErrorStyle('username'); checkStepButton(1)"
                />
                <p id="error-username" class="text-sm text-red-500 mt-1 hidden">
                  Pesan Error
                </p>
              </div>
              <div class="input-group relative">
                <input
                  name="email"
                  type="email"
                  id="email"
                  placeholder="Email"
                  class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md"
                  onblur="validateField('email'); checkStepButton(1)"
                  oninput="debounceEmailCheck(this)"
                />
                <p id="error-email" class="text-sm text-red-500 mt-1 hidden">
                  Pesan Error
                </p>
              </div>
              <div class="input-group relative">
                <div class="password-input-wrapper">
                  <input
                    name="password"
                    type="password"
                    id="password"
                    placeholder="Password"
                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md pr-10"
                    onblur="validateField('password'); checkStepButton(1)"
                    oninput="clearErrorStyle('password'); checkStepButton(1)"
                  />
                  <span
                    class="password-toggle-icon"
                    onclick="togglePasswordVisibility('password')"
                    ><i class="fas fa-eye"></i
                  ></span>
                </div>
                <p id="error-password" class="text-sm text-red-500 mt-1 hidden">
                  Pesan Error
                </p>
              </div>
              <div class="input-group relative">
                <div class="password-input-wrapper">
                  <input
                    type="password"
                    id="confirmPassword"
                    placeholder="Confirm Password"
                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md pr-10"
                    onblur="validateField('confirmPassword'); checkStepButton(1)"
                    oninput="clearErrorStyle('confirmPassword'); checkStepButton(1)"
                  />
                  <span
                    class="password-toggle-icon"
                    onclick="togglePasswordVisibility('confirmPassword')"
                    ><i class="fas fa-eye"></i
                  ></span>
                </div>
                <p
                  id="error-confirmPassword"
                  class="text-sm text-red-500 mt-1 hidden"
                >
                  Pesan Error
                </p>
              </div>
            </div>

            <div class="mt-8">
              <button
                type="button"
                id="btn-step-1"
                onclick="nextStep(1)"
                class="create-account-btn w-full bg-[#a7483d] text-white py-3 rounded-md text-lg font-semibold transition duration-300 shadow-lg btn-disabled"
                disabled
              >
                Create account
              </button>
              <p class="text-sm text-center text-red-500 mt-2">
                *Data yang diisi harus lengkap
              </p>

              <p class="login-prompt mt-6 text-sm text-gray-600">
                Sudah mempunyai akun?
                <a
                  href="login.php"
                  class="login-link text-blue-700 font-semibold hover:underline"
                  >Login</a
                >
              </p>
            </div>
          </div>

          <div id="step-2" class="step-content hidden">
            <h2 class="text-2xl text-[#a76657] font-semibold mb-6 text-center">
              Lengkapi Data Diri
            </h2>

            <div class="space-y-4">
              <div
                class="flex flex-col md:flex-row items-start md:items-center space-y-1 md:space-y-0 space-x-0 md:space-x-4"
              >
                <label class="w-full md:w-1/3 font-medium text-gray-700"
                  >Nama Lengkap*</label
                >
                <div class="full-input-container">
                  <input
                    name="fullname"
                    type="text"
                    id="fullName"
                    required
                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md"
                    placeholder="Nama Lengkap Anda"
                    onblur="validateField('fullName'); checkStepButton(2)"
                    oninput="clearErrorStyle('fullName'); checkStepButton(2)"
                  />
                  <p
                    id="error-fullName"
                    class="text-sm text-red-500 mt-1 hidden"
                  >
                    Pesan Error
                  </p>
                </div>
              </div>
              <div
                class="flex flex-col md:flex-row items-start md:items-center space-y-1 md:space-y-0 space-x-0 md:space-x-4"
              >
                <label class="w-full md:w-1/3 font-medium text-gray-700"
                  >Provinsi*</label
                >
                <div class="full-input-container">
                  <input
                    name="provinsi"
                    type="text"
                    id="provinsi"
                    required
                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md"
                    placeholder="Contoh: DKI Jakarta"
                    onblur="validateField('provinsi'); checkStepButton(2)"
                    oninput="clearErrorStyle('provinsi'); checkStepButton(2)"
                  />
                  <p
                    id="error-provinsi"
                    class="text-sm text-red-500 mt-1 hidden"
                  >
                    Pesan Error
                  </p>
                </div>
              </div>
              <div
                class="flex flex-col md:flex-row items-start md:items-center space-y-1 md:space-y-0 space-x-0 md:space-x-4"
              >
                <label class="w-full md:w-1/3 font-medium text-gray-700"
                  >Kabupaten/Kota*</label
                >
                <div class="full-input-container">
                  <input
                    name="kabupaten"
                    type="text"
                    id="kota"
                    required
                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md"
                    placeholder="Contoh: Jakarta Selatan"
                    onblur="validateField('kota'); checkStepButton(2)"
                    oninput="clearErrorStyle('kota'); checkStepButton(2)"
                  />
                  <p id="error-kota" class="text-sm text-red-500 mt-1 hidden">
                    Pesan Error
                  </p>
                </div>
              </div>
              <div
                class="flex flex-col md:flex-row items-start space-y-1 md:space-y-0 space-x-0 md:space-x-4"
              >
                <label class="w-full md:w-1/3 font-medium text-gray-700 pt-3"
                  >Alamat*</label
                >
                <div class="full-input-container">
                  <textarea
                    name="alamat"
                    id="address"
                    required
                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md pt-2 h-16 resize-none"
                    placeholder="Jalan, RT/RW, Kode Pos"
                    onblur="validateField('address'); checkStepButton(2)"
                    oninput="clearErrorStyle('address'); checkStepButton(2)"
                  ></textarea>
                  <p
                    id="error-address"
                    class="text-sm text-red-500 mt-1 hidden"
                  >
                    Pesan Error
                  </p>
                </div>
              </div>
              <div
                class="flex flex-col md:flex-row items-start md:items-center space-y-1 md:space-y-0 space-x-0 md:space-x-4"
              >
                <label class="w-full md:w-1/3 font-medium text-gray-700"
                  >No. Telp*</label
                >
                <div class="full-input-container">
                  <input
                    name="no_telp"
                    type="tel"
                    id="phone"
                    required
                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md"
                    placeholder="Contoh: 081234567890"
                    onblur="validateField('phone'); checkStepButton(2)"
                    oninput="clearErrorStyle('phone'); checkStepButton(2)"
                  />
                  <p id="error-phone" class="text-sm text-red-500 mt-1 hidden">
                    Pesan Error
                  </p>
                </div>
              </div>
            </div>

            <div class="flex justify-between mt-8">
              <button
                type="button"
                onclick="prevStep(2)"
                class="bg-gray-400 text-white py-2 px-6 rounded-md font-semibold hover:bg-gray-500 transition duration-150 shadow-md"
              >
                Kembali
              </button>
              <button
                type="button"
                id="btn-step-2"
                onclick="nextStep(2)"
                class="bg-[#a7483d] text-white py-2 px-6 rounded-md font-semibold transition duration-150 shadow-md btn-disabled"
                disabled
              >
                Lanjut
              </button>
            </div>
          </div>

          <div id="step-3" class="step-content hidden">
            <h2
              class="text-3xl text-[#a76657] font-semibold mb-8 tracking-widest uppercase font-reglog text-center"
            >
              KONFIRMASI DATA
            </h2>

            <div class="space-y-4 max-w-md mx-auto">
              <div class="flex items-center mb-6">
                <div
                  class="w-16 h-16 bg-[#a7483d] rounded-full flex items-center justify-center mr-4 shadow-lg"
                >
                  <svg
                    class="h-8 w-8 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </div>
                <span
                  id="display-username"
                  class="text-xl font-bold text-[#a76657]"
                ></span>
              </div>

              <div class="flex items-center">
                <label class="w-1/3 font-medium text-gray-700"
                  >Nama Lengkap</label
                >
                <input
                  type="text"
                  id="display-fullName"
                  readonly
                  class="block w-2/3 border-0 border-b-2 border-gray-400 bg-transparent py-1.5 px-0 text-gray-900 sm:text-base cursor-default"
                />
              </div>
              <div class="flex items-center">
                <label class="w-1/3 font-medium text-gray-700">Email</label>
                <input
                  type="email"
                  id="display-email"
                  readonly
                  class="block w-2/3 border-0 border-b-2 border-gray-400 bg-transparent py-1.5 px-0 text-gray-900 sm:text-base cursor-default"
                />
              </div>
              <div class="flex items-center">
                <label class="w-1/3 font-medium text-gray-700">No. Telp</label>
                <input
                  type="tel"
                  id="display-phone"
                  readonly
                  class="block w-2/3 border-0 border-b-2 border-gray-400 bg-transparent py-1.5 px-0 text-gray-900 sm:text-base cursor-default"
                />
              </div>
              <div class="flex items-start">
                <label class="w-1/3 font-medium text-gray-700">Alamat</label>
                <textarea
                  id="display-address"
                  readonly
                  class="block w-2/3 h-auto resize-none border-0 border-b-2 border-gray-400 bg-transparent py-1.5 px-0 text-gray-900 sm:text-base cursor-default"
                ></textarea>
              </div>
            </div>

            <div class="flex justify-between mt-12">
              <button
                type="button"
                onclick="prevStep(3)"
                class="bg-gray-400 text-white py-2 px-6 rounded-md font-semibold hover:bg-gray-500 transition duration-150 shadow-md"
              >
                Ubah Data
              </button>
              <button
                type="submit"
                id="btn-step-3"
                onclick="return validateStep(3)"
                class="bg-[#a7483d] text-white py-2 px-6 rounded-md text-lg font-semibold hover:bg-opacity-90 transition duration-150 shadow-xl btn-disabled"
                disabled
              >
                Selesai & Daftar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script>
      let currentStep = 1;
      const totalSteps = 3;
      
      // Fungsi debounce untuk menunda pengecekan email
      let emailCheckTimeout;
      function debounceEmailCheck(input) {
        clearErrorStyle('email');
        clearTimeout(emailCheckTimeout);
        emailCheckTimeout = setTimeout(() => {
          if (input.value.trim() !== '') {
            validateField('email');
          }
          checkStepButton(1);
        }, 500);
      }

      function togglePasswordVisibility(id) {
        const inputWrapper = document.getElementById(id).parentElement;
        const passwordInput = document.getElementById(id);
        const toggleIcon = inputWrapper.querySelector(
          ".password-toggle-icon i"
        );

        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          toggleIcon.classList.remove("fa-eye");
          toggleIcon.classList.add("fa-eye-slash");
        } else {
          passwordInput.type = "password";
          toggleIcon.classList.remove("fa-eye-slash");
          toggleIcon.classList.add("fa-eye");
        }
      }

      function handleSocialSignUp(provider) {
        alert(
          `Anda memilih Sign Up dengan ${provider}. Mensimulasikan pengambilan data...`
        );
        const mockData = {
          fullName: "Senku Ishigami",
          email: "senku.ishigami@science.com",
          username: "senku_10b",
        };
        document.getElementById("username").value = mockData.username;
        document.getElementById("email").value = mockData.email;
        document.getElementById("fullName").value = mockData.fullName;

        currentStep = 2;
        updateUI();
        checkStepButton(2);
      }

      /* --- FUNGSI VALIDASI VISUAL --- */

      function applyErrorStyle(id, message) {
        const input = document.getElementById(id);
        const errorElement = document.getElementById(`error-${id}`);

        input.classList.add(
          "border-red-500",
          "focus:ring-red-500",
          "focus:border-red-500",
          "ring-red-500"
        );
        input.classList.remove(
          "border-gray-300",
          "focus:ring-[#a7483d]",
          "focus:border-[#a7483d]"
        );
        input.classList.remove("focus:shadow-md");

        if (errorElement) {
          errorElement.textContent = message;
          errorElement.classList.remove("hidden");
        }
      }

      function clearErrorStyle(id) {
        const input = document.getElementById(id);
        const errorElement = document.getElementById(`error-${id}`);

        input.classList.remove(
          "border-red-500",
          "focus:ring-red-500",
          "focus:border-red-500",
          "ring-red-500"
        );
        input.classList.add(
          "border-gray-300",
          "focus:ring-[#a7483d]",
          "focus:border-[#a7483d]"
        );

        if (errorElement) {
          errorElement.classList.add("hidden");
          errorElement.textContent = "";
        }
      }

      function validateField(id, isSilent = false) {
        const input = document.getElementById(id);
        if (!input || input.disabled || input.readOnly) return true;

        let errorMessage = "";
        let isFieldValid = true;
        // Regex untuk nama/lokasi: Memungkinkan huruf, spasi, tanda hubung, koma, titik.
        const nameRegex = /^[a-zA-Z\s.,'-]+$/;

        // Cek kosong (selalu diperiksa)
        if (input.value.trim() === "") {
          errorMessage = `${input.placeholder || "Bidang ini"} wajib diisi.`;
          isFieldValid = false;
        }

        // Cek Format (Nama Lengkap, Provinsi, Kota) - HANYA HURUF
        else if (id === "fullName" || id === "provinsi" || id === "kota") {
          if (!nameRegex.test(input.value.trim())) {
            errorMessage = `Hanya huruf dan spasi yang diperbolehkan.`;
            isFieldValid = false;
          }
        }

        // Cek format Email
        else if (input.type === "email") {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(input.value.trim())) {
            errorMessage = "Format Email tidak valid.";
            isFieldValid = false;
          } else {
            // Cek email sudah terdaftar atau belum menggunakan AJAX
            return new Promise((resolve) => {
              const formData = new FormData();
              formData.append('email', input.value.trim());
              
              fetch('check_email.php', {
                method: 'POST',
                body: formData
              })
              .then(response => response.json())
              .then(data => {
                if (data.exists) {
                  applyErrorStyle('email', 'Email sudah terdaftar.');
                  resolve(false);
                } else {
                  clearErrorStyle('email');
                  resolve(true);
                }
              })
              .catch(error => {
                console.error('Error:', error);
                resolve(true); // Lanjutkan jika ada error koneksi
              });
            });
          }
        }
        // Cek format Telepon
        else if (input.id === "phone") {
          const phoneRegex = /^\+?[0-9]{8,15}$/;
          if (!phoneRegex.test(input.value.trim())) {
            errorMessage = "Nomor Telepon harus 8-15 digit angka.";
            isFieldValid = false;
          }
        }

        // Cek panjang Password
        else if (input.id === "password") {
          if (input.value.length < 6) {
            errorMessage = "Password minimal harus 6 karakter.";
            isFieldValid = false;
          }
        }

        if (!isFieldValid) {
          if (!isSilent) applyErrorStyle(id, errorMessage);
          return false;
        }

        if (!isSilent) clearErrorStyle(id);
        return true;
      }

      // Fungsi untuk mengaktifkan/menonaktifkan tombol
      async function checkStepButton(step) {
        const btn = document.getElementById(`btn-step-${step}`);
        if (!btn) return;

        try {
          const isFormValid = await validateStep(step, true);
          if (isFormValid) {
            btn.disabled = false;
            btn.classList.remove("btn-disabled");
            btn.classList.add("hover:bg-opacity-90");
          } else {
            btn.disabled = true;
            btn.classList.add("btn-disabled");
            btn.classList.remove("hover:bg-opacity-90");
          }
        } catch (error) {
          console.error('Error in validation:', error);
          btn.disabled = true;
          btn.classList.add("btn-disabled");
          btn.classList.remove("hover:bg-opacity-90");
        }
      }

      async function validateStep(step, isSilent = false) {
        let isValid = true;
        const currentStepElement = document.getElementById(`step-${step}`);

        const inputsToValidate = currentStepElement.querySelectorAll(
          'input:required, textarea:required, input[type="email"], input[type="tel"]'
        );

        for (const input of inputsToValidate) {
          const result = await validateField(input.id, isSilent);
          if (result === false) {
            isValid = false;
          }
        }

        // Validasi tambahan (Hanya untuk Langkah 1)
        if (step === 1) {
          const password = document.getElementById("password").value;
          const confirmPassword =
            document.getElementById("confirmPassword").value;

          if (
            !validateField("password", isSilent) ||
            !validateField("confirmPassword", isSilent)
          ) {
            isValid = false;
          } else if (password !== confirmPassword) {
            if (!isSilent) {
              applyErrorStyle("confirmPassword", "Password tidak cocok!");
            }
            isValid = false;
          }
        }

        // Validasi Total (Hanya untuk Langkah 3)
        if (step === 3) {
          const step1Valid = validateStep(1);
          const step2Valid = validateStep(2);

          if (!step1Valid || !step2Valid) {
            if (!step1Valid) currentStep = 1;
            else currentStep = 2;
            updateUI();
            return false;
          }
        }

        return isValid;
      }

      /* --- FUNGSI UTAMA --- */

      function updateUI() {
        document
          .querySelectorAll(".step-content")
          .forEach((el) => el.classList.add("hidden"));
        document
          .getElementById(`step-${currentStep}`)
          .classList.remove("hidden");

        const panelKiri = document.getElementById("panel-kiri");
        const formArea = document.querySelector(".form-area");
        if (currentStep === 1) {
          panelKiri.classList.remove("hidden");
          formArea.classList.remove("w-full");
          formArea.classList.add("md:w-[60%]");
        } else {
          panelKiri.classList.add("hidden");
          formArea.classList.add("w-full");
          formArea.classList.remove("md:w-[60%]");
        }

        for (let i = 1; i <= totalSteps; i++) {
          const icon = document.getElementById(`step-${i}-icon`);
          icon.classList.remove(
            "bg-[#a7483d]",
            "border-[#a7483d]",
            "text-white",
            "bg-white",
            "border-gray-400",
            "text-gray-400"
          );
          if (i <= currentStep) {
            icon.classList.add(
              "bg-[#a7483d]",
              "border-[#a7483d]",
              "text-white"
            );
            icon.textContent = i < currentStep ? "✓" : i;
          } else {
            icon.classList.add("bg-white", "border-gray-400", "text-gray-400");
            icon.textContent = i;
          }
        }
        const fillWidth = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.getElementById("step-line-fill").style.width = `${fillWidth}%`;

        document
          .querySelectorAll("input, textarea")
          .forEach((input) => clearErrorStyle(input.id));

        if (currentStep === 3) {
          fillConfirmationData();
          checkStepButton(3);
        } else {
          checkStepButton(currentStep);
        }
      }

      function fillConfirmationData() {
        const username = document.getElementById("username").value;
        const email = document.getElementById("email").value;
        const fullName = document.getElementById("fullName").value;
        const phone = document.getElementById("phone").value;
        const addressDetail = document.getElementById("address").value;
        const kota = document.getElementById("kota").value;
        const provinsi = document.getElementById("provinsi").value;

        document.getElementById("display-username").textContent =
          username || "N/A";
        document.getElementById("display-fullName").value = fullName || "N/A";
        document.getElementById("display-email").value = email || "N/A";
        document.getElementById("display-phone").value = phone || "N/A";
        document.getElementById(
          "display-address"
        ).value = `${addressDetail}, ${kota}, ${provinsi}`;
      }

      async function nextStep(stepFrom) {
        const isValid = await validateStep(stepFrom);
        if (isValid) {
          if (currentStep < totalSteps) {
            currentStep++;
            updateUI();
          }
        }
      }

      function prevStep(stepFrom) {
        if (currentStep > 1) {
          currentStep--;
          updateUI();
        }
      }

      document.addEventListener("DOMContentLoaded", () => {
        updateUI();
        checkStepButton(1);
      });

      document
        .getElementById("registerForm")
        .addEventListener("submit", function (event) {
          if (
            currentStep === 3 &&
            !document.getElementById("btn-step-3").disabled
          ) {
            const formData = {
              /* Kumpulkan data */
            };
            
          }
        });
    </script>
  </body>
</html>
