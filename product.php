<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Orinuts</title>
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
        .produk-meja {
            filter: drop-shadow(-2px 10px 3px rgba(0, 0, 0, 0.3));
        }

        .txt {
            filter: drop-shadow(12px 12px 5px rgba(0, 0, 0, 0.2));
        }

        .trapr {
            clip-path: polygon(0% 25%, 100% 0%, 100% 100%, 0% 75%);
        }

        .trapl {
            clip-path: polygon(0% 0%, 100% 25%, 100% 100%, 0% 100%);
        }

        .traprfull {
            clip-path: polygon(0% 25%, 100% 0%, 100% 100%, 0% 100%);
        }

        header {
            background: rgba(220, 213, 185, 0.15);
        }

        .imgl {
            filter: drop-shadow(-24px 50px 5px rgba(0, 0, 0, 0.3));
        }

        .imgr {
            filter: drop-shadow(24px 50px 5px rgba(0, 0, 0, 0.3));
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
    </style>
</head>

<body class="bg-[#F4EFD8] w-full">
    <header id="navbar" class="fixed left-0 right-0 top-0 z-50 py-2 transition-all duration-300 backdrop-blur-sm">
        <div class="container mx-auto px-6 md:px-16 flex justify-between md:items-end items-center">
            <div>
                <img src="content/logo.png" alt="logo" class="h-14" />
            </div>

            <nav class="hidden lg:flex py-1 px-2 rounded-full bg-white/30 backdrop-blur-md">
                <a href="index.php" class="rounded-full py-2 px-8 font-semibold">HOME</a>
                <a href="about.php" class="rounded-full py-2 px-8 font-semibold hover:text-green-700">ABOUT US</a>
                <a href="product.php"
                    class="bg-white rounded-full py-2 px-8 font-semibold hover:text-green-700">PRODUCT</a>
                <a href="contact.php" class="rounded-full py-2 px-8 font-semibold hover:text-green-700">CONTACT</a>
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

            <a href="index.php"
                class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">HOME</a>
            <a href="about.php" class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">ABOUT
                US</a>
            <a href="#"
                class="block py-3 text-white font-semibold hover:text-green-800 border-b border-white/30">PRODUCT</a>
            <a href="contact.php" class="block py-3 text-white font-semibold hover:text-green-800">CONTACT</a>
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

    <div class="flex justify-center items-center relative mt-35">
        <div
            class="txt flex flex-col justify-center items-center font-merienda font-extrabold text-7xl text-[#491A0B] absolute top-10 right-110 tracking-[12px]">
            <span>OUR</span>
            <span>PRODUCT</span>
        </div>
        <img src="content/kacang/biji_labu.png" alt="bijilabu"
            class="floating-nut absolute h-auto w-20 rotate-180 -top-20 left-123" />
        <img src="content/kacang/kuaci.png" alt="kuaci"
            class="floating-nut absolute h-auto w-30 -rotate-33 top-20 left-20" />
        <img src="content/kacang/almond1-removebg-preview.png" alt="almond"
            class="floating-nut absolute h-auto w-40 -top-5 right-23 -rotate-15 blur-[2px]" />
        <img src="content/kacang/kismis.png" alt="kismis"
            class="floating-nut absolute h-auto w-20 top-85 left-25 rotate-45" />
        <img src="content/kacang/mente.png" alt="mente"
            class="floating-nut absolute h-auto w-80 top-60 right-5 rotate-180 blur-[1px]" />
        <img src="content/trapesium-removebg-preview.png" alt="trapsium"
            class="absolute h-auto w-full top-110 blur-[2px] z-10" />
        <img src="content/fotomodelproduct/2951ce65-1619-4760-9f4c-750de500c541_removalai_preview.png    " alt="woman"
            class="absolute h-auto -top-7 left-55 z-10" />
        <img src="content/product/Orinuts_4_Mighty_Nuts_200g-removebg-preview.png" alt="produc1"
            class="produk-meja absolute h-auto w-50 top-85 z-20" />
        <img src="content/product/Orimond_Honey_Butter_Almond_75g-removebg-preview.png" alt="produc2"
            class="produk-meja absolute h-auto w-35 top-110 right-160 z-20" />
        <img src="content/product/Orimond_seaweed.png" alt="produc3"
            class="produk-meja absolute h-auto w-40 top-95 right-148 z-10" />
        <div class="w-full bg-gray-500 h-9 absolute top-169 z-40 blur-3xl"></div>
    </div>
        <!-- product -->
    <div id="product-content-container" class="mt-[45rem]">
        <!-- blur -->
            <div class="absolute w-100 h-100 -left-10  bg-[#82BBC9] rounded-full blur-[100px] z-0">
            </div>
            <div class="absolute w-100 h-100 right-30 top-290 bg-[#82BBC9]  rounded-full blur-[100px] -z-10">
            </div>  
            <div class="absolute w-100 h-100 left-10 top-665 bg-[#F876C1] rounded-full blur-[100px] z-0"></div>
            <div class="absolute w-100 h-100 right-30 top-580  bg-[#F876C1] rounded-full blur-[100px] -z-10"></div>
            <div class="absolute w-100 h-100 left-50 -bottom-890 bg-[#ECE856] rounded-full blur-[100px] z-0"></div>
            <div class="absolute w-100 h-100 right-30 -bottom-810 bg-[#0300A6] opacity-90 rounded-full blur-[100px] -z-10"></div>
            <div class="absolute w-100 h-100 -left-25 -bottom-1200 bg-[#78B0F1] rounded-full blur-[100px] z-0"></div>
            <div class="absolute w-100 h-100 right-65 -bottom-1140 bg-[#78B0F1] rounded-full blur-[100px] -z-10"></div>
            <div class="absolute w-100 h-100 left-25 -bottom-1490 bg-[#FA7268] rounded-full blur-[100px] z-0"></div>
            <div class="absolute w-100 h-100 right-30 -bottom-1550 bg-[#FA7268] rounded-full blur-[100px] -z-10"></div>
        <!-- diskon -->
        <img src="content/diskon.png" alt="diskon" class="absolute top-212 w-80 -rotate-15 h-auto z-20 left-37" />
        <img src="content/diskon.png" alt="diskon" class="absolute top-403 w-80 rotate-15 h-auto z-20 right-25" />

        <!-- p1 -->
        <div class="flex flex-col md:flex-row items-center justify-center  px-30 h-165 py-55 space-x-10 pt-70">
            <img src="content/product/Orinuts_Roasted_Cashew_Original_200g-removebg-preview.png" alt="Roasted Cashew"
                class="imgl h-135" />
            <div>
                <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px]">
                    Roasted Cashew
                </h2>
                <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2">
                    Kacang mete, Almond, Sunflower, pumpkin,<br />
                    cranberry, gojiberry, golden raisin.
                </p>
                <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px]">
                    <li>Premium Quality.</li>
                    <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                    <li>Rasa FRESH & ENAK.</li>
                    <li>Sudah matang & siap dimakan.</li>
                    <li>Kacang & biji-bijian dipanggang.</li>
                </ul>
                <hr class="py-3" />
                <div class="flex justify-between items-center p-5">
                    <h3 class="text-2xl font-semibold text-black font-poppins">
                        Rp. 70,000-135,000
                    </h3>
                    <a href="#"
                        class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                        <span>order now</span>
                        <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                            class="w-6 h-6 brightness-0 invert" />
                    </a>
                </div>
            </div>
        </div>
<!-- p2 -->
        <div class="trapr w-[100%] h-225 z-0 bg-[#EDCF3E]">
            <div class="flex flex-col md:flex-row items-center justify-center pl-75 space-x-10 px-30 py-50">
                <div>
                    <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px]">
                        Duo Mix
                    </h2>
                    <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2">
                        Kacang mete, Almond, Sunflower, pumpkin,<br />
                        cranberry, gojiberry, golden raisin.
                    </p>
                    <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px]">
                        <li>Premium Quality.</li>
                        <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                        <li>Rasa FRESH & ENAK.</li>
                        <li>Sudah matang & siap dimakan.</li>
                        <li>Kacang & biji-bijian dipanggang.</li>
                    </ul>
                    <hr />
                    <div class="flex justify-between items-center p-5">
                        <h3 class="text-2xl font-semibold text-black font-poppins">
                            Rp. 70,000-135,000
                        </h3>

                        <a href="#"
                            class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                            <span>order now</span>
                            <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                                class="w-6 h-6 brightness-0 invert" />
                        </a>
                    </div>
                </div>
                <img src="content/product/Orinuts_Roasted_Duo_Mix_200g-removebg-preview.png" alt="Sweet Mix"
                    class="imgr h-130" />
            </div>
        </div>
<!-- p3 -->
        <div class="flex flex-col md:flex-row items-center justify-center space-x-10 pr-60 h-125    ">
            <img src="content/product/Orinuts_Heart_Healthy_Mix_200.png" alt="Hearth Healthy Mix"
                class="imgl h-125 " />
            <div>
                <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px]">
                    Hearth Healthy Mix
                </h2>
                <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2">
                    Kacang mete, Almond, Sunflower, pumpkin,<br />
                    cranberry, gojiberry, golden raisin.
                </p>
                <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px]">
                    <li>Premium Quality.</li>
                    <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                    <li>Rasa FRESH & ENAK.</li>
                    <li>Sudah matang & siap dimakan.</li>
                    <li>Kacang & biji-bijian dipanggang.</li>
                </ul>
                <hr class="py-3" />
                <div class="flex justify-between items-center p-5">
                    <h3 class="text-2xl font-semibold text-black font-poppins">
                        Rp. 70,000-135,000
                    </h3>
                    <a href="#"
                        class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                        <span>order now</span>
                        <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                            class="w-6 h-6 brightness-0 invert" />
                    </a>
                </div>
            </div>
        </div>
        <!-- p4 -->
        <div class="trapr w-[100%] h-225 z-0 bg-gradient-to-b to-[#D8140F] from-[#ED7B01]">
            <div class="flex flex-col md:flex-row items-center justify-center pl-75 space-x-10 px-20 py-50">
                <div>
                    <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px] text-white">
                        Orithin Chocolate
                    </h2>
                    <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2 text-white">
                        Kacang mete, Almond, Sunflower, pumpkin,<br />
                        cranberry, gojiberry, golden raisin.
                    </p>
                    <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px] text-white">
                        <li>Premium Quality.</li>
                        <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                        <li>Rasa FRESH & ENAK.</li>
                        <li>Sudah matang & siap dimakan.</li>
                        <li>Kacang & biji-bijian dipanggang.</li>
                    </ul>
                    <hr class="text-white"/>
                    <div class="flex justify-between items-center p-5 ">
                        <h3 class="text-2xl font-semibold font-poppins text-white">
                            Rp. 70,000-135,000
                        </h3>

                        <a href="#"
                            class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                            <span>order now</span>
                            <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                                class="w-6 h-6 brightness-0 invert" />
                        </a>
                    </div>
                </div>
                <img src="content/product/Orithin_Chocolate-removebg-preview.png" alt="Orithin Chocolate"
                    class="imgr w-125 h-auto" />
            </div>
        </div>
<!-- p5 -->
        <div class="flex flex-col md:flex-row items-center justify-center space-x-10 pr-60 h-100">
            <img src="content/product/Orithin_Original-removebg-preview.png" alt="Orithin Original"
                class="imgl w-125 h-auto drop-shadow-lg" />
            <div>
                <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px]">
                    Orithin Original
                </h2>
                <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2">
                    Kacang mete, Almond, Sunflower, pumpkin,<br />
                    cranberry, gojiberry, golden raisin.
                </p>
                <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px]">
                    <li>Premium Quality.</li>
                    <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                    <li>Rasa FRESH & ENAK.</li>
                    <li>Sudah matang & siap dimakan.</li>
                    <li>Kacang & biji-bijian dipanggang.</li>
                </ul>
                <hr class="py-3" />
                <div class="flex justify-between items-center p-5">
                    <h3 class="text-2xl font-semibold text-black font-poppins">
                        Rp. 70,000-135,000
                    </h3>

                    <a href="#"
                        class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                        <span>order now</span>
                        <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                            class="w-6 h-6 brightness-0 invert" />
                    </a>
                </div>
            </div>
        </div>
        <!-- p6 -->
        <div class="trapr w-[100%] h-225 z-0 bg-gradient-to-bl from-white to-[#F3B9CA]">
            <div class="flex flex-col md:flex-row items-center justify-center pl-75 space-x-10 px-20 py-50">
                <div>
                    <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px]">
                        Orimond Himalayan Salt
                    </h2>
                    <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2">
                        Kacang mete, Almond, Sunflower, pumpkin,<br />
                        cranberry, gojiberry, golden raisin.
                    </p>
                    <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px]">
                        <li>Premium Quality.</li>
                        <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                        <li>Rasa FRESH & ENAK.</li>
                        <li>Sudah matang & siap dimakan.</li>
                        <li>Kacang & biji-bijian dipanggang.</li>
                    </ul>
                    <hr />
                    <div class="flex justify-between items-center p-5">
                        <h3 class="text-2xl font-semibold text-black font-poppins">
                            Rp. 70,000-135,000
                        </h3>

                        <a href="#"
                            class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                            <span>order now</span>
                            <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                                class="w-6 h-6 brightness-0 invert" />
                        </a>
                    </div>
                </div>
                <img src="content/product/Orimond_Himalayan_Salt_Almond_75g-removebg-preview.png" alt="Orimond Himalayan Salt"
                    class="imgr w-125 h-auto drop-shadow-lg" />
            </div>
        </div>
        <!-- p7 -->
        <div class="flex flex-col md:flex-row items-center justify-center space-x-10 pr-60 h-100">
            <img src="content/product/Orimond_bubble_gum.png" alt="Orimond Bubble Gum"
                class="imgl w-125 h-auto " />
            <div>
                <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px]">
                    Orimond Bubble Gum
                </h2>
                <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2">
                    Kacang mete, Almond, Sunflower, pumpkin,<br />
                    cranberry, gojiberry, golden raisin.
                </p>
                <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px]">
                    <li>Premium Quality.</li>
                    <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                    <li>Rasa FRESH & ENAK.</li>
                    <li>Sudah matang & siap dimakan.</li>
                    <li>Kacang & biji-bijian dipanggang.</li>
                </ul>
                <hr class="py-3" />
                <div class="flex justify-between items-center p-5">
                    <h3 class="text-2xl font-semibold text-black font-poppins">
                        Rp. 70,000-135,000
                    </h3>

                    <a href="#"
                        class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                        <span>order now</span>
                        <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                            class="w-6 h-6 brightness-0 invert" />
                    </a>
                </div>
            </div>
        </div>
        <!-- p8 -->
        <div class="trapr w-[100%] h-225 z-0 bg-[#BBDB53]">
            <div class="flex flex-col md:flex-row items-center justify-center pl-75 space-x-10 px-20 py-50">
                <div>
                    <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px]">
                        Rumah Mente Pumpkin Seed
                    </h2>
                    <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2">
                        Kacang mete, Almond, Sunflower, pumpkin,<br />
                        cranberry, gojiberry, golden raisin.
                    </p>
                    <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px]">
                        <li>Premium Quality.</li>
                        <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                        <li>Rasa FRESH & ENAK.</li>
                        <li>Sudah matang & siap dimakan.</li>
                        <li>Kacang & biji-bijian dipanggang.</li>
                    </ul>
                    <hr />
                    <div class="flex justify-between items-center p-5">
                        <h3 class="text-2xl font-semibold text-black font-poppins">
                            Rp. 70,000-135,000
                        </h3>

                        <a href="#"
                            class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                            <span>order now</span>
                            <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                                class="w-6 h-6 brightness-0 invert" />
                        </a>
                    </div>
                </div>
                <img src="content/product/RumahMente_Pumpkin_seed.png" alt="Rumah Mente Pumpkin Seed"
                    class="imgr w-125 h-auto drop-shadow-lg" />
            </div>
        </div>
        <!-- p9 -->
        <div class="flex flex-col md:flex-row items-center justify-center space-x-10 px-20 h-120 ">
            <img src="content/product/RumahMente_Roasted_Cashew_Sweet___Spicy_75g-removebg-preview.png" alt="Rumah Mente Sweet & Spicy"
                class="imgl w-125 h-auto drop-shadow-lg" />
            <div>
                <h2 class="text-4xl font-extrabold mb-6 font-inter uppercase tracking-[7px]">
                    Rumah Mente Sweet & Spicy
                </h2>
                <p class="font-medium font-poppins tracking-[1px] text-[18px] uppercase mt-2">
                    Kacang mete, Almond, Sunflower, pumpkin,<br />
                    cranberry, gojiberry, golden raisin.
                </p>
                <ul class="list-disc list-inside font-bold font-poppins py-5 tracking-[1px]">
                    <li>Premium Quality.</li>
                    <li>Tahan 1 tahun dalam kemasan (sebelum kemasan dibuka).</li>
                    <li>Rasa FRESH & ENAK.</li>
                    <li>Sudah matang & siap dimakan.</li>
                    <li>Kacang & biji-bijian dipanggang.</li>
                </ul>
                <hr class="py-3" />
                <div class="flex justify-between items-center p-5">
                    <h3 class="text-2xl font-semibold text-black font-poppins">
                        Rp. 70,000-135,000
                    </h3>

                    <a href="#"
                        class="flex items-center space-x-3 bg-[#4A2311] text-white font-bold py-3 px-6 rounded-full font-inter">
                        <span>order now</span>
                        <img src="content/icon/shopping-cart.svg" alt="shopping cart"
                            class="w-6 h-6 brightness-0 invert" />
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <img src="content/sret/sret-pink.png" alt="sret-pink" class="w-full -bottom-1 relative pt-30 ">
    <footer>
        <div class="w-full p-10 bg-[#FA7369] text-white">
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
            <hr class="mt-12 mb-4 border-white" />
            <p class="text-sm text-white mt-2">
                Orinuts © 2025 All Rights Reserved. | Privacy Policy Term of Service
            </p>
        </div>
    </footer>

    <script>
        // Script untuk shadow navbar (tidak diubah)
        const navbar = document.getElementById("navbar");
        window.addEventListener("scroll", () => {
            if (window.scrollY > 50) {
                navbar.classList.add("shadow-lg");
            } else {
                navbar.classList.remove("shadow-lg");
            }
        });

        // Script hamburger (tidak diubah)
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

        // 👇 [DIHAPUS] Script untuk sistem Tab Product Dihapus
        // document.addEventListener('DOMContentLoaded', () => { ... });
    </script>
</body>

</html>