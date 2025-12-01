<?php
session_start();
if (!$_SESSION['admin']) {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Admin - Orinuts</title>
    <link rel="stylesheet" href="src/outputail.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body class="flex min-h-screen bg-[#FDF5E6]">
    <aside
        class="bg-white text-[#8B4513] overflow-y-auto shadow-lg rounded-r-3xl min-h-screen fixed z-10"
        style="width: 250px;">
        <div class="fixed p-5 w-63">
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
                    <a href="admin_product.php">
                        <li class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
                            <i class="fas fa-box-open w-4"></i> Produk
                        </li>
                    </a>
                    <a href="admin_message.php">
                        <li class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
                            <i class="fas fa-comment w-4"></i> Message
                            <span class="ml-auto bg-red-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full">2</span>
                        </li>
                    </a>
                    <li class="nav-item font-bold p-2.5 flex items-center gap-3 cursor-pointer  bg-[#D2A278] rounded-r-3xl shadow-md" style="background-color: #D2A278;">
                        <i class="fas fa-shopping-cart w-4"></i> Order
                    </li>
                    <a href="admin_profile.php">
                        <li class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
                            <i class="fas fa-user-circle w-4"></i> Profile
                        </li>
                    </a>
                </ul>
            </nav>
            <div class="absolute bottom-0 left-0 right-0 h-40 overflow-hidden rounded-r-3xl pointer-events-none">
                <img src="content/almond-background.png" alt="Almond Background" class="object-cover w-full h-full opacity-10" />
            </div>
        </div>
    </aside>

    <main class="flex-1 p-8 ml-[250px]">

        <header class="flex justify-between items-center mb-8 bg-[#D2A278] p-4 rounded-xl shadow-md">
            <h1 class="text-white text-2xl font-bold uppercase tracking-wider">HALAMAN ADMIN</h1>
            <div class="text-white">
                <i class="fas fa-user-circle text-3xl cursor-pointer hover:text-gray-200 transition"></i>
            </div>
        </header>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-xl font-semibold text-[#8B4513] mb-4">Daftar Pesanan Terbaru</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#FDF5E6]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#8B4513] uppercase tracking-wider w-1/12">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#8B4513] uppercase tracking-wider w-3/12">Nama Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#8B4513] uppercase tracking-wider w-3/12">Total Pembelian</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#8B4513] uppercase tracking-wider w-2/12">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[#8B4513] uppercase tracking-wider w-3/12">Lengkap</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        <?php
                        // transaksi
                        $pdo = require 'koneksi.php';
                        $query = $pdo->prepare("SELECT * FROM transactions ORDER BY id DESC");
                        $query->execute();
                        $transaksi = $query->fetchAll();

                        foreach ($transaksi as $trans) {
                        ?>
                            <tr class="bg-white hover:bg-[#FDF5E6] transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">#<?= $trans['kode_transaksi'] ?></td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700"><?= $trans['nama_user'] ?></td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">Rp. <?= number_format($trans['total_harga'], 0, ',', '.') ?></td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-red-600 font-medium"><?= date("d/m/Y", strtotime($trans['tanggal'])) ?></td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a
                                        class="text-[#8B4513] hover:text-[#D2A278] font-semibold py-1 px-3 rounded-full transition-colors flex items-center gap-1 group"
                                        href="?tid=<?= $trans['id'] ?>">
                                        <i class="fas fa-eye w-4 group-hover:text-[#D2A278]"></i> lihat detail
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php if (isset($_GET['tid'])): ?>

        <?php
        // ambil transaksi
        $queryTransaksi = $pdo->prepare("SELECT * FROM transactions WHERE id= ?");
        $queryTransaksi->execute([$_GET['tid']]);
        $trans = $queryTransaksi->fetch();
        // ambil item transaksi
        $queryItem = $pdo->prepare("SELECT * FROM transaction_items WHERE transaksi_id= ?");
        $queryItem->execute([$_GET['tid']]);
        $items = $queryItem->fetchAll();
        ?>
        <div id="detailModal" class="fixed inset-0 bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-2xl max-w-xl w-full relative transform transition-all duration-300 scale-95 opacity-0" id="modalContent">

                <a
                    class="absolute top-3 right-3 text-gray-600 hover:text-red-500 text-2xl"
                    href="admin_order.php">
                    &times;
                </a>

                <div class="flex flex-col items-center border-b pb-4 mb-4">
                    <img src="content/logo.png" alt="Logo Orinuts" class="w-24 mb-3" />
                    <h3 class="text-xl font-bold text-[#8B4513]">DETAIL PESANAN</h3>
                </div>

                <div class="text-sm text-gray-700 space-y-1 mb-4">
                    <p><strong>ID Pesanan:</strong> <span id="modal-order-id" class="font-medium text-[#D2A278]">#<?= $trans['kode_transaksi'] ?></span></p>
                    <p><strong>Nama Customer:</strong> <span id="modal-customer-name"><?= $trans['nama_user'] ?></span></p>
                    <p><strong>Tanggal/Jam:</strong> <span id="modal-datetime"><?= date("d/m/Y H:i", strtotime($trans['tanggal'])) ?> WIB</span></p>
                    <p><strong>Alamat Pengiriman:</strong> <span id="modal-address"><?= $trans['alamat_user'] ?></span></p>
                </div>

                <h4 class="font-semibold text-md text-[#8B4513] mt-4 mb-2">Daftar Produk:</h4>
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm">
                        <thead class="bg-[#FDF5E6]">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase w-1/12">No.</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase w-5/12">Produk</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase w-2/12">Qty</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase w-3/12">Harga Satuan</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-600 uppercase w-3/12">Total</th>
                            </tr>
                        </thead>
                        <tbody id="modal-product-list" class="divide-y divide-gray-100">
                            <?php for ($i = 0; $i < count($items); $i++) { ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-2 whitespace-nowrap text-xs font-medium text-gray-900"><?= $i+1 ?></td>
                                <td class="px-3 py-2 text-xs text-gray-700"><?= $items[$i]['nama_produk'] ?></td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-700 text-right"><?= $items[$i]['qty'] ?></td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-700 text-right">Rp. <?= number_format($items[$i]['harga'],0,',','.') ?></td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900 font-medium text-right">Rp. <?= number_format($items[$i]['total'],0,',','.') ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 space-y-1 text-sm text-gray-700 border-t pt-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Subtotal Produk:</span>
                        <span id="modal-subtotal" class="font-medium">Rp. <?= number_format($trans['total_harga'],0,',','.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Biaya Pengiriman (JNE Reg):</span>
                        <span id="modal-shipping-cost">Rp. 15.000</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-[#8B4513] border-t mt-2 pt-2">
                        <span>TOTAL PEMBAYARAN:</span>
                        <span id="modal-total">Rp. <?= number_format(($trans['total_harga']+15000),0,',','.') ?></span>
                    </div>
                </div>

                <div class="mt-6 border-t pt-4 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500">Metode Pembayaran:</p>
                        <p class="font-bold text-sm text-gray-800" id="modal-payment-method"><?= $trans['metode_pembayaran'] ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 text-right">Status Pesanan:</p>
                        <p id="modal-status" class="text-base font-bold text-white px-3 py-1 rounded-full text-center">
                            <?=  $trans['status'] == 'complete' ? "<span class='bg-green-500 px-3 py-1 rounded-full shadow'>{$trans['status']}</span>" :
                                "<span class='bg-yellow-500 px-3 py-1 rounded-full shadow'>{$trans['status']}</span>"
                            ?>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    <?php endif ?>

    <script>
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');
        const productList = document.getElementById('modal-product-list');


        function formatRupiah(number) {
            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(number) + ',00';
        }

        function showDetailModal() {
            // Tampilkan Modal dengan animasi
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function hideDetailModal() {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        modal.addEventListener('click', (e) => {
            if (e.target.id === 'detailModal') {
                window.location = "admin_order.php";
            }
        });

        <?php if (isset($_GET['tid'])): ?>
            showDetailModal();
        <?php endif ?>

        <?php if (!isset($_GET['tid'])): ?>
            hideDetailModal();
        <?php endif ?>
    </script>
</body>

</html>