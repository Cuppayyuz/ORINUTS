<?php
session_start();
$pdo = require 'koneksi.php';

$user_id = $_SESSION['user']['id'];
$tid = $_GET['tid'];

// 1. Ambil data transaksi milik user
$stmt = $pdo->prepare("
    SELECT * FROM transactions 
    WHERE id = :tid AND user_id = :uid
");
$stmt->execute([
    'tid' => $tid,
    'uid' => $user_id
]);

$trans = $stmt->fetch();

if (!$trans) {
    die("Transaksi tidak ditemukan");
} else if ($trans['status'] == 'complete') {
    header('location: all-product.php');
}

// 2. Ambil item transaksi
$stmt2 = $pdo->prepare("
    SELECT * FROM transaction_items 
    WHERE transaksi_id = :tid
");
$stmt2->execute(['tid' => $tid]);
$items = $stmt2->fetchAll();

// 3. Update terjual per item
foreach ($items as $item) {
    $upd = $pdo->prepare("
        UPDATE products 
        SET terjual = terjual + :qty 
        WHERE produk_kode = :kode
    ");
    $upd->execute([
        'qty' => $item['qty'],
        'kode'=> $item['kode_produk']
    ]);
}

// 4. Update status transaksi
$pdo->prepare("
    UPDATE transactions 
    SET status = 'complete'
    WHERE id = :tid
")->execute(['tid' => $tid]);

echo "<script>alert('Pesanan selesai!');window.location='riwayat.php';</script>";
