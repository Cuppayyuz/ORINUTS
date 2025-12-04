<?php
session_start();
$error = '';
if (!empty($_POST)) {
  if (!isset($_SESSION['user'])) {
    echo "<script>alert('Anda harus login sebelum mengirim pesan!')</script>";
  } else {
    if ($_POST['message'] === '') {
      $error = 'Pesan tidak boleh kosong!';
    } else {
      $pdo = require 'koneksi.php';
      $query = $pdo->prepare("INSERT INTO messages (message, time, id_user) VALUES (:message, now(), :id_user)");
      $query->execute([
        'message' => $_POST['message'],
        'id_user' => $_SESSION['user']['id']
      ]);
      $msg = json_encode("Pesanmu: " . $_POST['message'] . " berhasil dikirim ke Admin");
      echo "<script>
          alert($msg);
          window.location.href = window.location.href;
      </script>";

      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>contact</title>
  <link rel="stylesheet" href="src/outputail.css" />
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

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input[type="number"] {
      -moz-appearance: textfield;
    }

    /* === PERUBAHAN CSS DIMULAI DI SINI === */

    /* Kelas dasar untuk ikon yang menggunakan mask */
    .icon-mask {
      -webkit-mask-size: contain;
      mask-size: contain;
      -webkit-mask-repeat: no-repeat;
      mask-repeat: no-repeat;
      -webkit-mask-position: center;
      mask-position: center;
    }

    /* Kelas spesifik untuk ikon email */
    .icon-mail {
      background-color: #FA7A6F;
      /* Warna merah/pink sesuai border */
      -webkit-mask-image: url('content/icon/mail.svg');
      mask-image: url('content/icon/mail.svg');
    }

    /* Kelas spesifik untuk ikon store */
    .icon-store {
      background-color: #F877C1;
      /* Warna pink sesuai border */
      -webkit-mask-image: url('content/icon/store.svg');
      mask-image: url('content/icon/store.svg');
    }

    /* === PERUBAHAN CSS SELESAI === */
  </style>
</head>

<body class="bg-[#F4EFD8] w-full">
  <header id="navbar" class="fixed left-0 right-0 top-0 z-50 py-2 transition-all duration-300 backdrop-blur-sm">
    <div class="container mx-auto px-6 md:px-16 flex justify-between items-center">
      <div>
        <img src="content/logo.png" alt="logo" class="h-14" />
      </div>

      <nav class="hidden lg:flex py-1 px-2 rounded-full bg-white/30 backdrop-blur-md">
        <a href="index.php" class="rounded-full py-2 px-8 font-semibold">HOME</a>
        <a href="about.php" class="rounded-full py-2 px-8 font-semibold hover:text-green-700">ABOUT US</a>
        <a href="product.php" class="rounded-full py-2 px-8 font-semibold hover:text-green-700">PRODUCT</a>
        <p class="bg-white rounded-full py-2 px-8 font-semibold hover:text-green-700">
          CONTACT
        </p>
      </nav>

      <div class="hidden lg:flex items-center space-x-4">
        <a href="keranjang.php">
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

  <div
    id="mobile-menu"
    class="lg:hidden fixed top-0 right-0 h-full w-3/4 max-w-sm bg-white transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
    <div class="p-8">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-black">Menu</h2>
        <button id="close-menu" class="text-black hover:text-gray-300">
          ✕
        </button>
      </div>

      <a
        href="index.html"
        class="block py-3 text-black font-semibold hover:text-green-800 border-b border-black">HOME</a>
      <a
        href="about.php"
        class="block py-3 text-black font-semibold hover:text-green-800 border-b border-black">ABOUT US</a>
      <a
        href="product.php"
        class="block py-3 text-black font-semibold hover:text-green-800 border-b border-black">PRODUCT</a>
      <a
        href="contact.php"
        class="block py-3 text-black font-semibold hover:text-green-800">CONTACT</a>
      <hr class="my-6 border-black" />
      <div class="space-y-4">
        <a
          href="keranjang.php"
          class="flex items-center space-x-3 py-3 text-black font-semibold ">
          <img
            src="content/icon/shopping-cart.svg"
            alt="cart"
            class="h-6 w-6">
          <span>Keranjang</span>
        </a>
        <a
          href="login.php"
          class="block w-full text-center bg-black text-white px-4 py-2 rounded-full font-bold">
          Login
        </a>
      </div>
    </div>
  </div>

  <section class="relative flex items-center w-full min-h-screen overflow-hidden">
    <img src="content/fotomockup/ENERGY BOOSTER MIX 7A.jpg" alt="Latar belakang produk Orinuts"
      class="absolute inset-0 w-full h-full pt-19 object-cover z-0" />

    <div class="absolute inset-0 bg-gradient-to-r from-[#C6B5A4] to-transparent z-10"></div>

    <div class="container relative mx-auto px-6 md:px-16 lg:px-50 z-20">
      <div class="max-w-lg mx-auto text-center">
        <h2 class="font-inter text-6xl md:text-8xl font-bold text-white"
          style="text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4)">
          Contact Us
        </h2>

        <p class="mt-6 text-base font-semibold text-white md:text-lg leading-relaxed font-poppins">
          Punya pertanyaan? Kami siap membantu! Hubungi kami melalui formulir,
          email, atau media sosial kami.
        </p>
      </div>
    </div>
  </section>

  <section class="relative w-full py-16 lg:py-24 overflow-hidden">

    <div class_="" absolute="" top-0="" left-0="" w-1="" 2="" h-1="" bg-orange-300="" 20="" blur-3xl=""
      -translate-x-1="" 4="" -translate-y-1="" z-0="" opacity-50="" aria-hidden="true"></div>

    <div class="container mx-auto px-6 md:px-16 relative z-10">

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">

        <div class="flex flex-col space-y-6">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <div
              class="bg-white rounded-3xl shadow-lg p-6 flex flex-col items-center justify-center text-center border-2 border-[#FA7A6F] min-h-[180px]">
              <div class="w-10 h-10 icon-mask icon-mail"></div>
              <p class="mt-3 font-semibold text-pink-500">Email</p>
              <p class="mt-1 font-semibold text-gray-700 text-sm">orinuts.official@gmail.com</p>
            </div>
            <div
              class="bg-white rounded-3xl shadow-lg p-6 flex flex-col items-center justify-center text-center border-2 border-[#AAF335] min-h-[180px]">
              <img src="content/icon/wa.png" alt="WhatsApp" class="w-10 h-10 " />
              <p class="mt-3 font-semibold text-green-500">WhatsApp</p>
              <p class="mt-1 font-semibold text-gray-700 text-sm">+62 816-521-369</p>
            </div>
          </div>

          <div
            class="bg-white rounded-3xl shadow-lg p-6 flex flex-col items-center justify-center text-center border-2 border-[#F877C1]">
            <img src="content/icon/shopping-bag.png" alt="Store" class="w-10 h-10 " />
            <p class="mt-3 font-semibold text-pink-500">store</p>
            <p class="mt-1 font-semibold text-gray-700 text-sm">orinuts official @shopee id</p>
            <p class="mt-1 font-semibold text-gray-700 text-sm">orinuts official @tokopedia id</p>
          </div>
          <div class="rounded-3xl shadow-lg w-full overflow-hidden">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d989.4189616679389!2d112.69222086956995!3d-7.277675899545579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fc18402c4a49%3A0xe27100c485d0e776!2sPerumahan%20Bavarian%20Village!5e0!3m2!1sid!2sid!4v1761877014298!5m2!1sid!2sid"
              width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade" class="w-full "></iframe>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl p-8 lg:p-10">
          <h3 class="text-3xl font-bold text-gray-800">Send us message</h3>
          <p class="mt-2 text-sm text-gray-500">
            Apapun yang ingin Anda sampaikan pertanyaan, masukan, atau sekadar salam tuliskan di bawah ini.
          </p>
          <?php
          if ($error) {
            echo "<p class=' text-red-500 font-bold'>" . $error . "</p>";
          }
          ?>
          <form class=" space-y-6 h-auto" action="" method="post">

            <div>
              <label for="message" class="block text-lg font-semibold text-gray-700 mb-2">Message</label>
              <textarea id="message" name="message" rows="6"
                class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white p-4 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500"></textarea>
            </div>

            <div>
              <button type="submit"
                class="w-full bg-slate-400 text-white font-bold py-4 px-6 rounded-full hover:bg-slate-500 transition duration-300 ease-in-out shadow-lg">
                Send Message
              </button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </section>
  <img src="content/sret/sret-orange.png" alt="sret orange " class="w-full -bottom-1 relative" />
  <footer>
    <div class="w-full p-10 bg-[#ea7003] text-white">
      <img src="content/logo.png" alt="Orinuts Logo" class="w-32 mb-6" />

      <div class="flex justify-between items-start pt-6 text-sm">
        <div class="w-full md:w-1/4 pr-8 text-white">
          <p class="leading-relaxed">
            the No.1 Healthy Snack in Indonesia. We provide <br />
            premium quality roasted nut snacks, crafted <br />
            without salt, sugar, preservatives, or MSG — <br />
            delivering a pure and healthy taste in every bite.
          </p>

          <div class="flex space-x-3 pt-5">
            <a href="https://www.instagram.com/orinuts.official?igsh=em1tazcxOWFqNGpm"><img src="content/icon/instagram.svg" alt="Instagram"
                class="w-7 h-7 rounded-full border border-gray-100 p-1 brightness-100 bg-white" /></a>
            <a href="https://wa.me/62816521369"><img src="content/icon/whatsapp.png" alt="WhatsApp"
                class="w-7 h-7 rounded-full border border-gray-100 p-1 brightness-100 bg-white" /></a>
            <a href="https://www.facebook.com/share/16N9e3564B/"><img src="content/icon/facebook.svg" alt="Facebook"
                class="w-7 h-7 rounded-full border border-gray-100 p-1 brightness-100 bg-white" /></a>
            <a href="mailto:orinuts.official@gmail.com "><img src="content/icon/mail.svg" alt="Email"
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
            <a href="index.php" class="block leading-loose hover:underline">HOME</a>
            <a href="about.php" class="block leading-loose hover:underline">ABOUT US</a>
            <a href="product.php" class="block leading-loose hover:underline">PRODUCT</a>
            <a href="contact.php" class="block leading-loose hover:underline">CONTACT</a>
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

      <hr class="mt-12 mb-4 border-white" />

      <p class="text-sm text-white mt-2">
        Orinuts © 2025 All Rights Reserved. | Privacy Policy Term of Service
      </p>
    </div>
  </footer>
  <script>
    const navbar = document.getElementById("navbar");
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        navbar.classList.add("shadow-lg");
      } else {
        navbar.classList.remove("shadow-lg");
      }
    });

    // ... Script Hamburger ...
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
  </script>
</body>

</html>