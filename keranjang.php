<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orinuts - Keranjang Saya</title>
    <link rel="stylesheet" href="src/outputail.css" />

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

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
                    colors: {
                        'orinuts-brown': '#6D2817',
                        'orinuts-cream': '#F4EFD8',
                        'orinuts-brown-light': '#682F08', // Dari border Anda
                        'orinuts-amber': '#fbbf24', // Contoh warna amber
                    }
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

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="bg-orinuts-cream w-full text-stone-800">
    <header
        id="navbar"
        class="fixed left-0 right-0 top-0 z-50 py-2 transition-all duration-300 backdrop-blur-sm">
        <div
            class="container mx-auto px-6 md:px-16 flex justify-between items-center">
            <div>
                <img src="content/logo.png" alt="logo" class="h-14" />
            </div>

            <nav
                class="hidden lg:flex py-1 px-2 rounded-full bg-white/30 backdrop-blur-md">
                <a href="index.html" class="rounded-full py-2 px-8 font-semibold">HOME</a>
                <a
                    href="about.html"
                    class="rounded-full py-2 px-8 font-semibold hover:text-green-700">ABOUT US</a>
                <a
                    href="product.html  "
                    class="rounded-full py-2 px-8 font-semibold hover:text-green-700">PRODUCT</a>
                <a
                    href="contact.html"
                    class="rounded-full py-2 px-8 font-semibold hover:text-green-700">CONTACT</a>
            </nav>

            <div class="hidden lg:flex items-center space-x-4">
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
        class="lg:hidden fixed top-0 right-0 h-screen w-3/4 max-w-sm bg-amber-800 transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-8">
        </div>
    </div>

    <main class="container mx-auto px-6 md:px-16 mt-28 mb-16">
        <h1 class="text-3xl font-bold text-stone-900 mb-8">Keranjang Saya</h1>

        <form id="cart-form" action="proses_checkout.php" method="POST">
            <div class="flex flex-col lg:flex-row lg:space-x-12">
                <div class="lg:w-2/3 w-full">

                    <div
                        class="flex items-center border-b-2 border-orinuts-brown-light pb-4 mb-4 text-sm font-medium">
                        <label class="flex flex-grow items-center space-x-3">
                            <input
                                type="checkbox"
                                id="select-all"
                                class="rounded border-gray-400 focus:ring-amber-800 text-amber-800" />
                            <span>Select All</span>
                        </label>
                        <span class="w-100 text-center">Jumlah</span>
                        <span class="w-24 text-right">Total</span>
                        <span class="w-12"></span>
                    </div>
                    <div id="cart-items-container" class="space-y-6">

                        <?php
                        if (empty($_SESSION['user'])) {

                            echo 'hai';
                        } else {
                            // Ambil data
                            $pdo = require 'koneksi.php';
                            $query = $pdo->prepare("SELECT * FROM cart WHERE user_id=:uid");
                            $query->execute([
                                'uid' => $_SESSION['user']['id']
                            ]);
                            $products = $query->fetchAll();
                            foreach ($products as $product) {
                        ?>
                                <div
                                    class="cart-item flex items-center py-6 border-b border-orinuts-brown-light"
                                    data-price="<?= $product['price'] ?>">
                                    <div class="flex flex-grow items-center">
                                        <input
                                            type="checkbox"
                                            name="item_ids[]"
                                            value="<?= $product['id'] ?>"
                                            class="item-checkbox rounded border-gray-400 focus:ring-amber-800 text-amber-800" />
                                        <img
                                            src="data:image/*;base64, <?= base64_encode($product['img']) ?>"
                                            alt="Product"
                                            class="w-16 h-16 sm:w-20 sm:h-20 object-contain rounded-md mx-4" />
                                        <div class="flex-grow">
                                            <h3 class="font-bold text-stone-800">
                                                <?= $product['product_name'] ?>
                                            </h3>
                                            <p class="text-sm text-gray-500"><?= $product['varian'] ?> gram</p>
                                            <p
                                                class="item-price text-sm font-semibold text-stone-800">
                                                Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="w-100 flex justify-center">
                                        <div
                                            class="flex items-center border border-gray-300 rounded-full">
                                            <button
                                                type="button"
                                                class="minus-btn px-3 py-1 text-lg font-bold text-gray-600 focus:outline-none">
                                                -
                                            </button>
                                            <input
                                                type="number"
                                                name="quantity[<?= $product['id'] ?>]"
                                                value="<?= $product['qty'] ?>"
                                                min="1"
                                                class="quantity-input w-10 text-center border-0 p-0 focus:ring-0 font-medium bg-transparent" />
                                            <button
                                                type="button"
                                                class="plus-btn px-3 py-1 text-lg font-bold text-gray-600 focus:outline-none">
                                                +
                                            </button>
                                        </div>
                                    </div>

                                    <span
                                        class="row-total w-24 text-right font-semibold text-stone-800"></span>

                                    <div class="w-12 flex justify-end">
                                        <button
                                            type="button"
                                            class="delete-btn text-red-500 hover:text-red-900">
                                            <svg
                                                class="w-5 h-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <!-- end -->
                    </div>


                    <a
                        href="all-product.php"
                        class="inline-flex items-center space-x-2 font-medium text-orinuts-brown hover:bg-amber-100 rounded-full py-2 px-5 transition-colors duration-200 mt-8">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali belanja</span>
                    </a>
                </div>

                <div class="lg:w-1/3 w-full mt-12 lg:mt-0">
                    <div class="border-2 border-stone-500 rounded-lg p-6">
                        <h2 class="text-xl font-bold text-stone-900 mb-6">
                            Pembayaran
                        </h2>

                        <div class="flex justify-between items-center mb-4">
                            <span
                                id="current-payment-method"
                                class="font-semibold text-stone-700">sistem<br />Cash On Delivery</span>
                            <img
                                id="current-payment-logo"
                                src="content/"
                                alt="Metode Pembayaran"
                                class="h-6" />
                        </div>

                        <button
                            id="ubah-pembayaran-btn"
                            type="button"
                            class="w-full bg-white text-stone-800 font-bold py-2 px-4 rounded-lg mb-6 hover:bg-gray-100 transition-colors">
                            Ubah metode pembayaran
                        </button>

                        <hr class="my-6 border-gray-300" />

                        <div class="space-y-4 font-medium text-stone-700">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span
                                    id="summary-subtotal"
                                    class="font-semibold text-stone-800">Rp0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Proteksi produk</span>
                                <span
                                    id="summary-protection"
                                    class="font-semibold text-stone-800">Rp1.000</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pengiriman</span>
                                <span
                                    id="summary-shipping"
                                    class="font-semibold text-stone-800">Rp8.000</span>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-300" />

                        <div
                            class="flex justify-between text-xl font-bold text-stone-900">
                            <span>TOTAL</span>
                            <span id="summary-total">Rp1.500</span>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-orinuts-brown text-white font-bold py-3 px-6 rounded-lg mt-8 hover:bg-opacity-90 transition-colors">
                            Bayar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <div
        id="payment-modal"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 hidden">
        <div
            id="modal-overlay"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div
            class="relative z-10 w-full max-w-md bg-white rounded-lg shadow-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-stone-900">
                    Pilih Metode Pembayaran
                </h3>
                <button id="close-modal-btn" class="text-gray-400 hover:text-gray-600">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-3">
                <button
                    data-method="Qris"
                    data-logo-src="content/qris-logo.png"
                    class="payment-option-btn flex justify-between items-center w-full p-4 border rounded-lg hover:border-amber-800 focus:border-amber-800 focus:ring-2 focus:ring-amber-200 transition-colors">
                    <span class="font-medium">Qris</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>

                <button
                    data-method="Cash On Delivery"
                    data-logo-src="content/cod-logo.png"
                    class="payment-option-btn flex justify-between items-center w-full p-4 border rounded-lg hover:border-amber-800 focus:border-amber-800 focus:ring-2 focus:ring-amber-200 transition-colors">
                    <span class="font-medium">Cash On Delivery</span>
                    <span class="font-bold text-red-600">COD</span>
                </button>

                <div>
                    <button
                        id="transfer-bank-btn"
                        class="flex justify-between items-center w-full p-4 border rounded-lg hover:border-amber-800 transition-colors">
                        <span class="font-medium">Transfer Bank</span>
                        <svg
                            id="transfer-arrow"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transition-transform"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="transfer-bank-options" class="pl-4 mt-2 space-y-2 hidden">
                        <button data-method="Transfer BCA" data-logo-src="content/bca-logo.png" class="payment-option-btn flex justify-between items-center w-full p-3 border rounded-lg hover:border-amber-800">
                            <span>BCA</span>
                            <span class="text-sm font-bold text-blue-600">BCA</span>
                        </button>
                        <button data-method="Transfer BNI" data-logo-src="content/bni-logo.png" class="payment-option-btn flex justify-between items-center w-full p-3 border rounded-lg hover:border-amber-800">
                            <span>BNI</span>
                            <span class="text-sm font-bold text-orange-500">BNI</span>
                        </button>
                        <button data-method="Transfer BRI" data-logo-src="content/bri-logo.png" class="payment-option-btn flex justify-between items-center w-full p-3 border rounded-lg hover:border-amber-800">
                            <span>BRI</span>
                            <span class="text-sm font-bold text-blue-800">BRI</span>
                        </button>
                        <button data-method="Transfer Mandiri" data-logo-src="content/mandiri-logo.png" class="payment-option-btn flex justify-between items-center w-full p-3 border rounded-lg hover:border-amber-800">
                            <span>Mandiri</span>
                            <span class="text-sm font-bold text-blue-900">Mandiri</span>
                        </button>
                        <button data-method="Transfer BSI" data-logo-src="content/bsi-logo.png" class="payment-option-btn flex justify-between items-center w-full p-3 border rounded-lg hover:border-amber-800">
                            <span>BSI</span>
                            <span class="text-sm font-bold text-green-700">BSI</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Script Navbar & Hamburger (Tidak Berubah)
        (function() {
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

            function openMenu() {
                mobileMenu.classList.remove("translate-x-full");
                hamburgerButton.classList.add("open");
            }

            function closeMenu() {
                mobileMenu.classList.add("translate-x-full");
                hamburgerButton.classList.remove("open");
            }

            hamburgerButton.addEventListener("click", () => {
                if (mobileMenu.classList.contains("translate-x-full")) {
                    openMenu();
                } else {
                    closeMenu();
                }
            });

            document.addEventListener("click", (event) => {
                if (
                    !mobileMenu.contains(event.target) &&
                    !hamburgerButton.contains(event.target)
                ) {
                    if (!mobileMenu.classList.contains("translate-x-full")) {
                        closeMenu();
                    }
                }
            });
        })();

        // --- 👇 Fungsionalitas Keranjang & Modal ---
        document.addEventListener("DOMContentLoaded", () => {

            // === 1. ELEMEN KERANJANG ===
            const cartContainer = document.getElementById(
                "cart-items-container"
            );
            const selectAllCheckbox = document.getElementById("select-all");

            // Elemen Ringkasan (Summary)
            const subtotalEl = document.getElementById("summary-subtotal");
            const protectionEl = document.getElementById("summary-protection");
            const shippingEl = document.getElementById("summary-shipping");
            const totalEl = document.getElementById("summary-total");

            // Biaya Tetap
            const SHIPPING_COST = 8000;
            const PROTECTION_COST = 1000;

            // === 2. FUNGSI KERANJANG ===

            function formatRupiah(number) {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                }).format(number);
            }

            function calculateTotals() {
                let currentSubtotal = 0;
                let itemsSelected = 0;

                const cartItems = document.querySelectorAll(".cart-item");

                cartItems.forEach((item) => {
                    const checkbox = item.querySelector(".item-checkbox");

                    if (checkbox.checked) {
                        itemsSelected++;
                        const price = item.getAttribute("data-price");
                        const quantity = parseInt(
                            item.querySelector(".quantity-input").value
                        );
                        const rowTotal = price * quantity;
                        item.querySelector(".row-total").textContent =
                            formatRupiah(rowTotal);
                        currentSubtotal += rowTotal;
                    } else {
                        const price = parseFloat(
                            item.getAttribute("data-price")
                        );
                        const quantity = parseInt(
                            item.querySelector(".quantity-input").value
                        );
                        item.querySelector(".row-total").textContent =
                            formatRupiah(price * quantity);
                    }
                });

                const currentProtection = itemsSelected > 0 ? PROTECTION_COST : 0;
                const currentShipping = itemsSelected > 0 ? SHIPPING_COST : 0;
                const finalSubtotal = itemsSelected > 0 ? currentSubtotal : 0;
                const grandTotal =
                    finalSubtotal +
                    currentProtection +
                    currentShipping;

                subtotalEl.textContent = formatRupiah(finalSubtotal);
                protectionEl.textContent = formatRupiah(currentProtection);
                shippingEl.textContent = formatRupiah(currentShipping);
                totalEl.textContent = formatRupiah(grandTotal < 0 ? 0 : grandTotal);

                // Update status checkbox "Select All"
                const totalItemsInCart = cartItems.length;
                if (totalItemsInCart === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                    return;
                }

                if (itemsSelected === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (itemsSelected === totalItemsInCart) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }

            function handleCartClick(event) {
                const target = event.target;
                const cartItem = target.closest(".cart-item");
                if (!cartItem) return;

                const quantityInput = cartItem.querySelector(".quantity-input");
                let currentQuantity = parseInt(quantityInput.value);

                if (target.closest(".plus-btn")) {
                    currentQuantity++;
                    quantityInput.value = currentQuantity;
                    calculateTotals();
                }

                if (target.closest(".minus-btn")) {
                    if (currentQuantity > 1) {
                        currentQuantity--;
                        quantityInput.value = currentQuantity;
                        calculateTotals();
                    }
                }

                if (target.closest(".delete-btn")) {
                    cartItem.remove();
                    calculateTotals();
                }

                if (target.classList.contains("item-checkbox")) {
                    calculateTotals();
                }
            }

            function handleQuantityInput(event) {
                if (event.target.classList.contains("quantity-input")) {
                    if (
                        parseInt(event.target.value) < 1 ||
                        event.target.value === ""
                    ) {
                        event.target.value = 1;
                    }
                    calculateTotals();
                }
            }

            function handleSelectAll() {
                const cartItems = document.querySelectorAll(".item-checkbox");
                cartItems.forEach((checkbox) => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                calculateTotals();
            }

            // === 3. EVENT LISTENERS KERANJANG ===
            cartContainer.addEventListener("click", handleCartClick);
            cartContainer.addEventListener("input", handleQuantityInput);
            selectAllCheckbox.addEventListener("change", handleSelectAll);

            // Kalkulasi awal
            document.querySelectorAll(".item-checkbox").forEach(cb => cb.checked = false);
            calculateTotals();

            // === 4. [BARU] LOGIKA MODAL PEMBAYARAN ===

            // Elemen Modal
            const openModalBtn = document.getElementById("ubah-pembayaran-btn");
            const closeModalBtn = document.getElementById("close-modal-btn");
            const paymentModal = document.getElementById("payment-modal");
            const modalOverlay = document.getElementById("modal-overlay");

            // Elemen Accordion
            const transferBtn = document.getElementById("transfer-bank-btn");
            const transferOptions = document.getElementById("transfer-bank-options");
            const transferArrow = document.getElementById("transfer-arrow");

            // Elemen Pilihan di Modal
            const paymentOptionButtons = document.querySelectorAll(".payment-option-btn");

            // Elemen Tampilan di Ringkasan
            const currentMethodText = document.getElementById("current-payment-method");
            const currentMethodLogo = document.getElementById("current-payment-logo");

            // Fungsi Buka/Tutup Modal
            function openModal() {
                paymentModal.classList.remove("hidden");
                paymentModal.classList.add("flex");
            }

            function closeModal() {
                paymentModal.classList.add("hidden");
                paymentModal.classList.remove("flex");
            }

            // Event Listener Buka/Tutup Modal
            openModalBtn.addEventListener("click", openModal);
            closeModalBtn.addEventListener("click", closeModal);
            modalOverlay.addEventListener("click", closeModal);

            // Event Listener Accordion Transfer Bank
            transferBtn.addEventListener("click", (e) => {
                e.preventDefault(); // Mencegah tombol submit form
                transferOptions.classList.toggle("hidden");
                transferArrow.classList.toggle("rotate-180");
            });

            // Event Listener untuk SETIAP tombol pilihan pembayaran
            paymentOptionButtons.forEach(button => {
                button.addEventListener("click", () => {
                    const methodName = button.getAttribute("data-method");
                    const logoSrc = button.getAttribute("data-logo-src");

                    // 1. Update teks di ringkasan
                    // Memecah "sistem" dan "COD" menjadi dua baris jika itu yg diinginkan
                    if (methodName.toLowerCase() === 'cash on delivery') {
                        currentMethodText.innerHTML = "sistem<br/>Cash On Delivery";
                    } else {
                        currentMethodText.innerHTML = methodName;
                    }

                    // 2. Update logo di ringkasan
                    currentMethodLogo.src = logoSrc;

                    // 3. (Opsional) Tambahkan style 'selected' di modal
                    // Hapus 'selected' dari semua tombol
                    paymentOptionButtons.forEach(btn => btn.classList.remove('border-amber-800', 'ring-2', 'ring-amber-200'));
                    // Tambahkan 'selected' ke tombol yg diklik
                    button.classList.add('border-amber-800', 'ring-2', 'ring-amber-200');

                    // 4. Tutup modal
                    closeModal();
                });
            });

        });
    </script>
</body>

</html>