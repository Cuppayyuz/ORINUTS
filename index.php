<?php
session_start();


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="src/outputail.css" />

  <script>
    tailwind.config = {
      theme: {
        extend: {
          screens: {
            sm: "max-width: 480px",
            md: "768px",
            lg: "1024px",
            xl: "1440px",
          },
          // Tambahkan warna kustom di sini jika perlu
        },
      },
    };
  </script>

  <style>
    /* Style Asli Anda */
    body {
      overflow-x: hidden;
    }

    .foto {
      filter: drop-shadow(5px 5px 5px rgba(0, 0, 0, 0.5));
    }

    .kotak {
      filter: drop-shadow(12px 20px 7px rgba(0, 0, 0, 0.5));
    }

    .bigpro {
      filter: drop-shadow(70px 60px 7px rgba(0, 0, 0, 0.3));
    }

    /* BARU: Menambahkan perspective untuk 3D */
    .product-image-wrapper {
      perspective: 1000px;
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

    /* =================================
        CSS BARU UNTUK ANIMASI
        =================================
      */

    /* Transisi untuk teks (fade & scale) */
    #hero-title,
    #hero-description {
      transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    /* BARU: Transisi 3D untuk gambar utama */
    #main-product-image {
      transition: transform 0.4s ease-in-out, opacity 0.3s ease-in-out;
      transform-style: preserve-3d;
    }

    /* Transisi untuk kacang (HANYA opacity) - KEMBALI SEPERTI SEMULA */
    #nut-1,
    #nut-2,
    #nut-3,
    #nut-4,
    #nut-5,
    #nut-6 {
      transition: opacity 0.3s ease-in-out;
    }

    .is-changing {
      opacity: 0;
    }

    /* BARU: Terapkan scale pada elemen teks */
    #hero-title.is-changing,
    #hero-description.is-changing {
      transform: scale(0.95);
    }

    /* BARU: Terapkan EFEK 3D pada gambar produk */
    #main-product-image.is-changing {
      opacity: 0;
      transform: scale(0.8) rotateY(70deg);
    }

    /* Transisi untuk warna background. */
    #hero-section,
    #navbar,
    #footer-bg,
    #wave-image,
    #hero-blur-circle {
      transition: all 0.5s ease-in-out;
    }

    /* Transisi untuk warna heading */
    #healthy-heading {
      transition: color 0.5s ease-in-out;
    }

    /* =================================
        ANIMASI KACANG (KEMBALI SEPERTI SEMULA)
        =================================
      */
    @keyframes float-normal {

      0%,
      100% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-15px);
        /* Gerak ke atas 15px */
      }
    }

    @keyframes float-rotate-60 {

      0%,
      100% {
        transform: rotate(60deg) translateY(0px);
        /* Posisi asli */
      }

      50% {
        transform: rotate(60deg) translateY(-15px);
        /* Gerak ke atas 15px */
      }
    }

    @keyframes float-rotate-neg-45 {

      0%,
      100% {
        transform: rotate(-45deg) translateY(0px);
        /* Posisi asli */
      }

      50% {
        transform: rotate(-45deg) translateY(-15px);
        /* Gerak ke atas 15px */
      }
    }

    #nut-1 {
      animation: float-rotate-60 5s ease-in-out infinite;
      animation-delay: 0.5s;
    }

    #nut-2 {
      animation: float-normal 6s ease-in-out infinite;
      animation-delay: 0.2s;
    }

    #nut-3 {
      animation: float-normal 4.5s ease-in-out infinite;
      animation-delay: 1s;
    }

    #nut-4 {
      animation: float-rotate-60 5.5s ease-in-out infinite;
      animation-delay: 0.7s;
    }

    #nut-5 {
      animation: float-normal 7s ease-in-out infinite;
      animation-delay: 0.3s;
    }

    #nut-6 {
      animation: float-rotate-neg-45 6.5s ease-in-out infinite;
      animation-delay: 0.1s;
    }
  </style>
</head>

<body>
  <div
    id="hero-section"
    class="bg-[#aaf334] w-full"
    data-color-class="bg-[#aaf334]">
    <header
      id="navbar"
      class="fixed left-0 right-0 top-0 z-50 py-2 transition-all duration-300 bg-[#aaf334]/80 backdrop-blur-sm"
      data-color-class="bg-[#aaf334]/80">
      <div
        class="container mx-auto px-6 md:px-16 flex justify-between items-center">
        <div>
          <img src="content/logo.png" alt="logo" class="h-14" />
        </div>

        <nav
          class="hidden lg:flex py-1 px-2 rounded-full bg-white/30 backdrop-blur-md">
          <a
            href="index.php"
            class="bg-white rounded-full py-2 px-8 font-semibold">HOME</a>
          <a
            href="about.php"
            class="rounded-full py-2 px-8 font-semibold hover:text-green-700">ABOUT US</a>
          <a
            href="product.php"
            class="rounded-full py-2 px-8 font-semibold hover:text-green-700">PRODUCT</a>
          <a
            href="contact.php"
            class="rounded-full py-2 px-8 font-semibold hover:text-green-700">CONTACT</a>
        </nav>

        <div class="hidden lg:flex items-center space-x-4">
          <a href="keranjang.php" class="relative">
            <img
              src="content/icon/shopping-cart.svg"
              alt="cart"
              class="h-7 w-7" />
            <?php
            if (isset($_SESSION['user'])) {
              $pdo = require 'koneksi.php';
              $sql = "SELECT COUNT(*) FROM cart WHERE user_id = ?";
              $query = $pdo->prepare($sql);
              $query->execute([$_SESSION['user']['id']]);
              $count = $query->fetchColumn();
              if ($count > 0) {
                echo "<span class='absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center'>$count</span>";
              }
            }
            ?>
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
          <button
            id="hamburger-button"
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
          href=""
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
            <div class="relative">
            <img
              src="content/icon/shopping-cart.svg"
              alt="cart"
              class="h-6 w-6">
              <?php
            if (isset($_SESSION['user'])) {
              $pdo = require 'koneksi.php';
              $sql = "SELECT COUNT(*) FROM cart WHERE user_id = ?";
              $query = $pdo->prepare($sql);
              $query->execute([$_SESSION['user']['id']]);
              $count = $query->fetchColumn();
              if ($count > 0) {
                echo "<span class='absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center'>$count</span>";
              }
            }
            ?>
            </div>
            <span>Keranjang</span>
          </a>
            <?php if (!isset($_SESSION['user'])) { ?>
              <a href="login.php" class="bg-white rounded-full py-2 px-8 font-semibold">Login</a>
            <?php } else { ?>
              <a href="profile_user.php?id=<?php echo htmlspecialchars($_SESSION['user']['id']); ?>" class="flex items-center gap-3">
                <?php
                $pdo = require 'koneksi.php';
                $query = $pdo->prepare("SELECT profile, fullname FROM users WHERE id=:id");
                $query->execute([
                  'id' => $_SESSION['user']['id']
                ]);
                $user = $query->fetch();
                $base64 = base64_encode($user['profile']);
                ?>
                <img src= 'data:image/*;base64, <?=  $base64 ?>' class=' w-12 rounded-full' alt='Profile Picture'>
                <p class=' text-xl'><?= $user['fullname'] ?></p>
              </a>
            <?php } ?>
        </div>
      </div>
    </div>

    <section class="relative overflow-hidden pb-20 pt-32 lg:pt-20">
      <div
        class="container mx-auto px-4 md:px-8 lg:px-24 flex flex-col lg:flex-row items-center justify-between relative">
        <div
          class="lg:w-1/2 text-center lg:text-left pt-10 lg:pt-20 relative z-20">
          <div
            class="flex justify-center lg:justify-start items-center space-x-1 mb-10">
            <img
              src="content/icon/star.png"
              alt="star"
              class="w-4 h-4 lg:w-5 lg:h-5" />
            <img
              src="content/icon/star.png"
              alt="star"
              class="w-4 h-4 lg:w-5 lg:h-5" />
            <img
              src="content/icon/star.png"
              alt="star"
              class="w-4 h-4 lg:w-5 lg:h-5" />
            <img
              src="content/icon/star.png"
              alt="star"
              class="w-4 h-4 lg:w-5 lg:h-5" />
            <img
              src="content/icon/star.png"
              alt="star"
              class="w-4 h-4 lg:w-5 lg:h-5" />
          </div>
          <h1
            id="hero-title"
            class="hero-title text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 tracking-wide text-gray-50 ">
            ANTI OXIDANT <br />MIX
          </h1>
          <p
            id="hero-description"
            class="hero-description text-base lg:text-lg mb-8 leading-relaxed text-white">
            Campuran kacang dan buah kering kaya vitamin & <br />
            antioksidan, pas untuk camilan sehat setiap hari.
          </p>
          <div
            class="flex flex-col sm:flex-row justify-center lg:justify-start items-center space-y-4 sm:space-y-0 sm:space-x-5">
            <a
              href="keranjang.php"
              class="btn-primary rounded-full py-3 px-10 text-lg font-semibold shadow-lg bg-white">ORDER NOW</a>
            <a
              href="all-product.php"
              class="text-white text-lg underline underline-offset-15 hover:opacity-80 transition-opacity font-semibold">SEE ALL PRODUCT -></a>
          </div>

          <div
            class="flex justify-center lg:justify-start items-center space-x-7 mt-12">
            <button id="btn-hijau" class="cursor-pointer">
              <img
                id="thumb-hijau"
                src="content/product/Orinuts_Anti_Oxidant_Mix_200gr-removebg-preview.png"
                alt="product-green"
                class="w-16 lg:w-20 h-auto product-thumbnail" />
            </button>
            <button id="btn-almond" class="cursor-pointer">
              <img
                id="thumb-almond"
                src="content/product/Orinuts_Roasted_Almond_Original_200g-removebg-preview.png"
                alt="product-cream"
                class="w-16 lg:w-20 h-auto product-thumbnail" />
            </button>
            <button id="btn-cashew" class="cursor-pointer">
              <img
                id="thumb-cashew"
                src="content/product/Orinuts_Roasted_Cashew_Original_200g-removebg-preview.png"
                alt="product-blue"
                class="w-16 lg:w-20 h-auto product-thumbnail" />
            </button>
          </div>
        </div>

        <div
          class="mt-12 lg:mt-0 lg:w-1/2 flex justify-center items-center relative h-96 lg:h-auto product-image-wrapper">
          <div
            id="hero-blur-circle"
            class="absolute w-80 h-80 lg:w-120 lg:h-120 bg-[#72C84A] rounded-full blur-3xl z-0"
            data-color-class="bg-[#72C84A]"></div>

          <img
            id="main-product-image"
            src="content/product/Orinuts_Anti_Oxidant_Mix_200gr-removebg-preview.png"
            alt="Anti Oxidant Mix"
            class="bigpro relative z-10 w-60 md:w-80 lg:w-[500px] drop-shadow-3xl" />

          <img
            id="nut-1"
            src="content/kacang/almond1-removebg-preview.png"
            alt="almond"
            class="floating-nut absolute w-16 md:w-20 lg:w-34 z-10 -top-2 left-3 sm:-top-4 sm:left-5 lg:left-32 rotate-38 " />
          <img
            id="nut-2"
            src="content/kacang/kismis.png"
            alt="cashew"
            class="floating-nut-2 absolute w-10 md:w-12 lg:w-18 z-20 bottom-15 -left-50 sm:bottom-12 sm:left-10 lg:-bottom-3 lg:left-45 rotate-35" />
          <img
            id="nut-3"
            src="content/kacang/biji_labu.png"
            alt="pistachio"
            class="floating-nut-3 absolute w-10 md:w-12 lg:w-16 z-5 -top-10 -right-4 sm:-top-1 sm:-right-4 lg:right-30" />
          <img
            id="nut-4"
            src="content/kacang/almond1-removebg-preview.png"
            alt="walnut"
            class="floating-nut-4 absolute w-16 md:w-20 lg:w-30 z-20 bottom-12 right-0 lg:-bottom-5 lg:right-24 rotate-38" />
          <img
            id="nut-5"
            src="content/kacang/kuaci.png"
            alt="pecan"
            class="floating-nut-5 absolute w-12 md:w-16 lg:w-30 z-5 top-40 -left-2 sm:left-0 lg:left-20" />
          <img
            id="nut-6"
            src="content/kacang/mente.png"
            alt="mente"
            class="floating-nut-6 absolute w-16 md:w-20 lg:w-40 z-5 bottom-0 right-8 sm:right-10 lg:bottom-30 lg:right-20 -rotate-45" />
        </div>
      </div>
    </section>
  </div>

  <img
    id="wave-image"
    src="content/sret/sret-hijau.png"
    alt="garis"
    class="w-full -z-10" />

  <section class="px-4">
    <h1
      id="healthy-heading"
      class="text-center text-3xl md:text-4xl font-bold py-7 text-[#38761d]"
      data-color-class="text-[#38761d]">
      No. 1 Healthy Snack in Indonesia
    </h1>
    <div class="flex flex-wrap justify-center items-center gap-4 py-10">
      <img
        src="content/hijau/palm-oil-removebg-preview.png"
        alt="Palm Oil Free"
        class="h-20 md:h-24" />
      <img
        src="content/hijau/vegan-removebg-preview.png"
        alt="Vegan"
        class="h-20 md:h-24" />
      <img
        src="content/hijau/natural-removebg-preview.png"
        alt="Natural"
        class="h-20 md:h-24" />
      <img
        src="content/hijau/sugar-removebg-preview.png"
        alt="Sugar Free"
        class="h-20 md:h-24" />
      <img
        src="content/hijau/salt-removebg-preview.png"
        alt="Salt Free"
        class="h-20 md:h-24" />
      <img
        src="content/hijau/hala-removebg-preview.png"
        alt="Halal"
        class="h-20 md:h-24" />
    </div>
    <div
      class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16 pb-10">
      <img
        src="content/foto-mid1.png"
        alt="foto-mid1"
        class="foto w-60 md:w-90 drop-shadow-2xl" />
      <img
        src="content/foto-mid2-removebg-preview.png"
        alt="foto-mid2"
        class="foto w-60 md:w-90" />
      <img
        src="content/foto-mid3-removebg-preview.png"
        alt="foto-mid3"
        class="foto w-60 md:w-90" />
    </div>
  </section>

  <div
    id="counter-section"
    class="bg-[#aaf334] w-full py-10 md:h-44"
    data-color-class="bg-[#aaf334]">
    <div
      class="flex flex-col md:flex-row justify-around items-center h-full gap-8 md:gap-0">
      <div class="text-center">
        <h1 class="text-white font-bold text-5xl md:text-8xl pb-1.5">1K+</h1>
        <p class="text-white font-bold text-xl md:text-3xl">produk terjual</p>
      </div>
      <div class="text-center">
        <h1 class="text-white font-bold text-5xl md:text-8xl pb-1.5">300+</h1>
        <p class="text-white font-bold text-xl md:text-3xl">
          orang mengunjungi
        </p>
      </div>
      <div class="text-center">
        <h1 class="text-white font-bold text-5xl md:text-8xl pb-1.5">780+</h1>
        <p class="text-white font-bold text-xl md:text-3xl">orang menyukai</p>
      </div>
    </div>
  </div>

  <h1
    class="testimoni text-amber-950 font-bold text-4xl md:text-6xl text-center pt-20">
    Testimoni
  </h1>
  <div
    class="flex flex-col md:flex-row justify-center gap-8 px-6 md:px-12 pt-14 pb-32">
    <?php
    $pdo = require 'koneksi.php';
    $sql = "SELECT reviews.*, users.fullname, users.profile, products.image1 FROM reviews INNER JOIN products ON reviews.produk_id = products.id INNER JOIN users ON reviews.user_id = users.id WHERE rating=5 LIMIT 3";
    $query = $pdo->prepare($sql);
    $query->execute();
    $testimoni = $query->fetchAll();

    foreach ($testimoni as $testi) {
    ?>
      <div
        class="kotak p-4 bg-gray-100 rounded-2xl shadow-xl w-full md:w-[32%] flex-shrink-0">
        <div class="flex justify-between items-start">
          <div class="w-2/3 flex flex-col space-y-2">
            <div class="flex items-center space-x-2">
              <img
                src="data:image/*; base64, <?= base64_encode($testi['profile']) ?>"
                alt="user"
                class="w-8 h-8 rounded-full bg-gray-200" />
              <p class="font-bold text-base"><?= $testi['fullname'] ?></p>
            </div>
            <div class="flex items-center space-x-1">
              <img src="content/icon/star.png" alt="star" class="w-4 h-4" />
              <img src="content/icon/star.png" alt="star" class="w-4 h-4" />
              <img src="content/icon/star.png" alt="star" class="w-4 h-4" />
              <img src="content/icon/star.png" alt="star" class="w-4 h-4" />
              <img src="content/icon/star.png" alt="star" class="w-4 h-4" />
              <h1 class="font-extrabold text-lg ml-1">5.0</h1>
            </div>
            <div>
              <p class="font-extrabold text-sm leading-snug">
                <?= $testi['ulasan'] ?>
              </p>
            </div>
          </div>
          <div class="w-1/3 flex justify-end">
            <img
              src="data:image/*;base64,  <?= base64_encode($testi['image1']) ?>"
              alt="product-img"
              class="w-16 h-auto object-contain mt-6" />
          </div>
        </div>
      </div>
      <!-- End loop -->
    <?php } ?>
  </div>

  <img
    id="wave-image-footer"
    src="content/sret/sret-hijau.png"
    alt="sret-foot"
    class="w-full rotate-180 -bottom-1 relative" />
  <footer>
    <div
      id="footer-bg"
      class="w-full p-10 bg-[#aaf334] text-[#38761d]"
      data-color-class="bg-[#aaf334]">
      <img src="content/logo.png" alt="Orinuts Logo" class="w-32 mb-6" />
      <div
        class="flex flex-col md:flex-row justify-between items-start pt-6 text-sm gap-8 md:gap-0">
        <div class="w-full md:w-1/4 pr-8 text-[#491A0B]">
          <p class="leading-relaxed">
            the No.1 Healthy Snack in Indonesia. We provide premium quality
            roasted nut snacks, crafted without salt, sugar, preservatives, or
            MSG — delivering a pure and healthy taste in every bite.
          </p>
          <div class="flex space-x-3 pt-5">
            <a href="https://www.instagram.com/orinuts.official?igsh=em1tazcxOWFqNGpm"><img
                src="content/icon/instagram.svg"
                alt="Instagram"
                class="w-7 h-7 rounded-full border border-[#38761d] p-1"
                style="
                    filter: invert(30%) sepia(20%) saturate(600%)
                      hue-rotate(60deg) brightness(50%) contrast(100%);
                  " /></a>
            <a href="https://wa.me/62816521369"><img
                src="content/icon/whatsapp.png"
                alt="Whatsapp"
                class="w-7 h-7 rounded-full border border-[#38761d] p-1"
                style="
                    filter: invert(30%) sepia(20%) saturate(600%)
                      hue-rotate(60deg) brightness(50%) contrast(100%);
                  " /></a>
            <a href="https://www.facebook.com/share/16N9e3564B/"><img
                src="content/icon/facebook.svg"
                alt="Facebook"
                class="w-7 h-7 rounded-full border border-[#38761d] p-1"
                style="
                    filter: invert(30%) sepia(20%) saturate(600%)
                      hue-rotate(60deg) brightness(50%) contrast(100%);
                  " /></a>
            <a href="mailto:orinuts.official@gmail.com"><img
                src="content/icon/mail.svg"
                alt="Email"
                class="w-7 h-7 rounded-full border border-[#38761d] p-1"
                style="
                    filter: invert(30%) sepia(20%) saturate(600%)
                      hue-rotate(60deg) brightness(50%) contrast(100%);
                  " /></a>
          </div>
        </div>
        <div
          class="flex flex-col sm:flex-row justify-between w-full md:w-3/4 gap-8 sm:gap-4">
          <div class="w-full sm:w-1/3 text-[#491A0B]">
            <h2 class="font-bold text-lg pb-4 uppercase">main office</h2>
            <p class="leading-relaxed">
              Bavarian Village A 7,<br />
              Darmo Permai Baru III,<br />
              Surabaya, Indonesia 60188
            </p>
          </div>
          <div class="w-full sm:w-1/3 text-[#491A0B]">
            <h2 class="font-bold text-lg pb-4 uppercase">main menu</h2>
            <a href="index.php" class="block leading-loose hover:underline">HOME</a>
            <a href="about.php" class="block leading-loose hover:underline">ABOUT US</a>
            <a href="product.php" class="block leading-loose hover:underline">PRODUCT</a>
            <a href="contact.php" class="block leading-loose hover:underline">CONTACT</a>
          </div>
          <div class="w-full sm:w-1/3 text-[#491A0B]">
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
      <hr class="mt-12 mb-4 border-[#38761d]" />
      <p class="text-center md:text-left text-sm text-[#38761d] mt-2">
        Orinuts © 2025 All Rights Reserved. | Privacy Policy Term of Service
      </p>
    </div>
  </footer>

  <script>
    // ===================================
    // Script Navbar & Mobile Menu (Asli)
    // ===================================
    const navbar = document.getElementById("navbar");
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        navbar.classList.add("shadow-lg");
      } else {
        navbar.classList.remove("shadow-lg");
      }
    });

    const hamburgerButton = document.getElementById("hamburger-button");
    const mobileMenu = document.getElementById("mobile-menu");
    const closeMenuButton = document.getElementById("close-menu");

    function openMenu() {
      mobileMenu.classList.remove("translate-x-full");
      hamburgerButton.classList.add("open");
    }

    function closeMenu() {
      mobileMenu.classList.add("translate-x-full");
      hamburgerButton.classList.remove("open");
    }

    hamburgerButton.addEventListener("click", openMenu);
    closeMenuButton.addEventListener("click", closeMenu);

    // ===================================================
    // KODE ANIMASI PRODUK BARU
    // ===================================================

    // 1. Database Produk
    const productData = {
      hijau: {
        title: "ANTI OXIDANT <br />MIX",
        description: "Campuran kacang dan buah kering kaya vitamin & <br /> antioksidan, pas untuk camilan sehat setiap hari.",
        mainImage: "content/product/Orinuts_Anti_Oxidant_Mix_200gr-removebg-preview.png",
        waveImage: "content/sret/sret-hijau.png",
        bgColor: "bg-[#aaf334]",
        navColor: "bg-[#aaf334]/80",
        counterColor: "bg-[#aaf334]",
        footerColor: "bg-[#aaf334]",
        headingColor: "text-[#38761d]",
        blurColor: "bg-[#72C84A]",
        // KEMBALI: 'nuts' hanya berisi daftar gambar
        nuts: [{
            src: "content/kacang/almond1-removebg-preview.png",
            cls: "floating-nut absolute w-16 md:w-20 lg:w-34 z-10 -top-2 left-3 sm:-top-4 sm:left-5 lg:left-32 rotate-38"
          },
          {
            src: "content/kacang/kismis.png",
            cls: "floating-nut-2 absolute w-10 md:w-12 lg:w-18 z-20 bottom-15 -left-50 sm:bottom-12 sm:left-10 lg:-bottom-3 lg:left-45 rotate-35"
          },
          {
            src: "content/kacang/biji_labu.png",
            cls: "floating-nut-3 absolute w-10 md:w-12 lg:w-16 z-5 -top-10 -right-4 sm:-top-1 sm:-right-4 lg:right-30"
          },
          {
            src: "content/kacang/almond1-removebg-preview.png",
            cls: "floating-nut-4 absolute w-16 md:w-20 lg:w-30 z-20 bottom-12 right-0 lg:-bottom-5 lg:right-24 rotate-38"
          },
          {
            src: "content/kacang/kuaci.png",
            cls: "floating-nut-5 absolute w-12 md:w-16 lg:w-30 z-5 top-40 -left-2 sm:left-0 lg:left-20"
          },
          {
            src: "content/kacang/mente.png",
            cls: "floating-nut-6 absolute w-16 md:w-20 lg:w-40 z-5 bottom-0 right-8 sm:right-10 lg:bottom-30 lg:right-20 -rotate-45"
          },
        ],
      },
      almond: {
        title: "PREMIUM ROASTED <br />ALMOND",
        description: "Almond panggang premium, renyah dan penuh nutrisi. <br /> Sempurna untuk energi instan tanpa rasa bersalah.",
        mainImage: "content/product/Orinuts_Roasted_Almond_Original_200g-removebg-preview.png",
        waveImage: "content/sret/sret-krem.png",
        bgColor: "bg-[#F4E9BB]",
        navColor: "bg-[#F4E9BB]",
        counterColor: "bg-[#F4E9BB]",
        footerColor: "bg-[#F4E9BB]",
        headingColor: "text-amber-800",
        blurColor: "bg-[#F4E9BB]",
        nuts: [{
            src: "content/kacang/almond1-removebg-preview.png",
            cls: "floating-nut absolute w-16 md:w-20 lg:w-34 z-10 -top-2 left-3 sm:-top-4 sm:left-5 lg:left-32 rotate-38"
          },
          {
            src: "content/kacang/almond1-removebg-preview.png",
            cls: "floating-nut absolute w-10 md:w-12 lg:w-24 z-20 bottom-15 -left-50 sm:bottom-12 sm:left-10 lg:-bottom-3 lg:left-45 rotate-35"
          },
          {
            src: "content/kacang/almond1-removebg-preview.png",
            cls: "floating-nut-3 absolute w-10 md:w-12 lg:w-24 z-5 -top-10 -right-4 sm:-top-1 sm:-right-4 lg:right-30"
          },
          {
            src: "content/kacang/almond1-removebg-preview.png",
            cls: "floating-nut-4 absolute w-16 md:w-20 lg:w-30 z-20 bottom-12 right-0 lg:-bottom-5 lg:right-24 rotate-38"
          },
          {
            src: "content/kacang/almond1-removebg-preview.png",
            cls: "floating-nut-5 absolute w-12 md:w-16 lg:w-30 z-5 top-40 -left-2 sm:left-0 lg:left-20"
          },
          {
            src: "content/kacang/almond1-removebg-preview.png",
            cls: "floating-nut-6 absolute w-16 md:w-20 lg:w-40 z-5 bottom-0 right-8 sm:right-10 lg:bottom-30 lg:right-20 -rotate-45"
          },
        ],
      },
      cashew: {
        title: "SAVORY ROASTED <br />CASHEW",
        description: "Kacang mete panggang gurih pilihan. <br /> Tekstur lembut dan rasa yang kaya, favorit semua orang.",
        mainImage: "content/product/Orinuts_Roasted_Cashew_Original_200g-removebg-preview.png",
        waveImage: "content/sret/sret-biru.png",
        bgColor: "bg-[#7AB6C4]",
        navColor: "bg-[#7AB6C4]/80",
        counterColor: "bg-[#7AB6C4]",
        footerColor: "bg-[#7AB6C4]",
        headingColor: "text-blue-900",
        blurColor: "bg-cyan-400",
        nuts: [{
            src: "content/kacang/mente.png",
            cls: "floating-nut absolute w-16 md:w-20 lg:w-60 z-10 -top-2 left-3 lg:-top-12 sm:left-5 lg:left-18 rotate-30"
          },
          {
            src: "content/kacang/mente.png",
            cls: "floating-nut absolute w-10 md:w-12 lg:w-46 z-20 bottom-15 -left-50  sm:left-10 lg:-bottom-4 lg:left-30 rotate-180"
          },
          {
            src: "content/kacang/mente.png",
            cls: "floating-nut-3 absolute w-10 md:w-12 lg:w-48 z-5 -top-1 -right-4 lg:-top-10 sm:-right-4 lg:right-24"
          },
          {
            src: "content/kacang/mente.png",
            cls: "floating-nut-4 absolute w-16 md:w-20 lg:w-38 z-20 bottom-12 right-0 lg:-bottom-5 lg:right-24 rotate-38"
          },
          {
            src: "content/kacang/mente.png",
            cls: "floating-nut-5 absolute w-12 md:w-16 lg:w-38 z-5 top-40 -left-2 sm:left-0 lg:left-20 "
          },
          {
            src: "content/kacang/mente.png",
            cls: "floating-nut-6 absolute w-16 md:w-20 lg:w-46   z-5 bottom-0 right-8 sm:right-10 lg:bottom-30 lg:right-20 -rotate-45"
          },
        ],
      },
    };

    // 2. Selektor Elemen
    const heroSection = document.getElementById("hero-section");
    const counterSection = document.getElementById("counter-section");
    const footerBg = document.getElementById("footer-bg");
    const waveImage = document.getElementById("wave-image");
    const waveImageFooter = document.getElementById("wave-image-footer");
    const heroTitle = document.getElementById("hero-title");
    const heroDesc = document.getElementById("hero-description");
    const mainImage = document.getElementById("main-product-image");
    const healthyHeading = document.getElementById("healthy-heading");
    const heroBlurCircle = document.getElementById("hero-blur-circle");

    const nuts = [
      document.getElementById("nut-1"),
      document.getElementById("nut-2"),
      document.getElementById("nut-3"),
      document.getElementById("nut-4"),
      document.getElementById("nut-5"),
      document.getElementById("nut-6"),
    ];

    // KEMBALI: 'animatedElements' berisi teks dan gambar utama
    const animatedElements = [heroTitle, heroDesc, mainImage];

    // 3. Tombol Pemicu
    const btnHijau = document.getElementById("btn-hijau");
    const btnAlmond = document.getElementById("btn-almond");
    const btnCashew = document.getElementById("btn-cashew");

    let isAnimating = false;

    // 4. Fungsi Utama untuk Update Tampilan
    function updateProduct(productId) {
      if (isAnimating) return;
      isAnimating = true;

      const data = productData[productId];
      if (!data) {
        isAnimating = false;
        return;
      }

      // --- Fase 1: Fade Out ---
      animatedElements.forEach((el) => el.classList.add("is-changing"));
      nuts.forEach((el) => el.classList.add("is-changing")); // Kacang fade out
      waveImage.classList.add("is-changing");
      waveImageFooter.classList.add("is-changing");

      // --- Fase 2: Ganti Konten ---
      setTimeout(() => {
        // Ganti teks dan gambar utama
        heroTitle.innerHTML = data.title;
        heroDesc.innerHTML = data.description;
        mainImage.src = data.mainImage;
        waveImage.src = data.waveImage;
        waveImageFooter.src = data.waveImage;

        // KEMBALI: Logika 'nuts' hanya mengganti 'src'
        nuts.forEach((nut, index) => {
          // Cek apakah data untuk index ini ada (untuk menghindari error)
          if (data.nuts[index]) {
            nut.src = data.nuts[index].src;
            nut.className = data.nuts[index].cls; // INI YANG MENGUBAH CLASS/POSISI

            // Pastikan animasi fade tetap berjalan, kita tambahkan class 'is-changing' kembali 
            // sebentar jika diperlukan, tapi karena kita me-replace className sepenuhnya, 
            // kita tidak perlu menambahkan class transisi lagi di sini karena posisi langsung berubah.
            // Namun, untuk transisi opacity yang halus saat masuk:
            nut.classList.add('opacity-0');

            // Trigger reflow agar browser sadar ada perubahan sebelum transisi masuk
            void nut.offsetWidth;

            nut.classList.remove('opacity-0');
            nut.style.transition = "opacity 0.5s ease-in-out";
          }
        });

        // --- Ganti Warna Tema ---
        heroSection.classList.remove(heroSection.dataset.colorClass);
        heroSection.classList.add(data.bgColor);
        heroSection.dataset.colorClass = data.bgColor;

        navbar.classList.remove(navbar.dataset.colorClass);
        navbar.classList.add(data.navColor);
        navbar.dataset.colorClass = data.navColor;

        counterSection.classList.remove(counterSection.dataset.colorClass);
        counterSection.classList.add(data.counterColor);
        counterSection.dataset.colorClass = data.counterColor;

        footerBg.classList.remove(footerBg.dataset.colorClass);
        footerBg.classList.add(data.footerColor);
        footerBg.dataset.colorClass = data.footerColor;

        healthyHeading.classList.remove(healthyHeading.dataset.colorClass);
        healthyHeading.classList.add(data.headingColor);
        healthyHeading.dataset.colorClass = data.headingColor;

        heroBlurCircle.classList.remove(heroBlurCircle.dataset.colorClass);
        heroBlurCircle.classList.add(data.blurColor);
        heroBlurCircle.dataset.colorClass = data.blurColor;

        // --- Fase 3: Fade In ---
        animatedElements.forEach((el) => el.classList.remove("is-changing"));
        nuts.forEach((el) => el.classList.remove("is-changing")); // Kacang fade in
        waveImage.classList.remove("is-changing");
        waveImageFooter.classList.remove("is-changing");

        isAnimating = false;
      }, 400); // Durasi disamakan dengan transisi 3D (0.4s)
    }

    // 5. Pasang Event Listeners
    btnHijau.addEventListener("click", () => updateProduct("hijau"));
    btnAlmond.addEventListener("click", () => updateProduct("almond"));
    btnCashew.addEventListener("click", () => updateProduct("cashew"));
  </script>
</body>

</html>