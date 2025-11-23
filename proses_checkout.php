<?php
session_start();
$pdo = require 'koneksi.php';
$user_id = $_SESSION['user']['id'];

if (empty($_POST['item_ids'])) {
    die("Tidak ada produk yang dipilih.");
}

$selected = $_POST['item_ids'];


$in = str_repeat('?,', count($selected) - 1) . '?';
$sql = "SELECT * FROM cart WHERE user_id = ? AND id IN ($in)";
$stmt = $pdo->prepare($sql);
$stmt->execute(array_merge([$user_id], $selected));
$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart)) {
    die("Produk tidak ditemukan / tidak valid.");
}


$stmt = $pdo->query("SELECT kode_transaksi FROM transactions ORDER BY id DESC LIMIT 1");
$last = $stmt->fetchColumn();

if (!$last) $kode = "TX001";
else {
    $num = (int) substr($last, 2);
    $kode = "TX" . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
}


$total = 0;

foreach ($cart as $c) {
    $id_cart = $c['id'];
    $qty = isset($_POST['quantity'][$id_cart]) ? (int)$_POST['quantity'][$id_cart] : 1;

    if ($qty < 1) $qty = 1;

    $total += $c['price'] * $qty;
}


$sql = "INSERT INTO transactions 
    (user_id, kode_transaksi, nama_user, alamat_user, metode_pembayaran, total_harga)
    VALUES
    (:uid, :kode, :nama, :alamat, :metode, :total)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'uid'    => $user_id,
    'kode'   => $kode,
    'nama'   => $_SESSION['user']['username'],
    'alamat' => $_SESSION['user']['alamat'],
    'metode' => "COD",
    'total'  => $total
]);

$transaksi_id = $pdo->lastInsertId();

$sql2 = "INSERT INTO transaction_items 
    (transaksi_id, kode_produk, nama_produk, varian, qty, harga, total, img)
    VALUES 
    (:tid, :kode, :nama, :varian, :qty, :harga, :total, :img)";

$stmt2 = $pdo->prepare($sql2);

foreach ($cart as $c) {
    $id_cart = $c['id'];
    $qty = $_POST['quantity'][$id_cart];

    $stmt2->execute([
        'tid'   => $transaksi_id,
        'kode'  => $c['kode_produk'],
        'nama'  => $c['product_name'],
        'varian'=> $c['varian'],
        'qty'   => $qty,
        'harga' => $c['price'],
        'total' => $c['price'] * $qty,
        'img'   => $c['img']
    ]);
}

$sql = "DELETE FROM cart WHERE user_id = ? AND id IN ($in)";
$stmt = $pdo->prepare($sql);
$stmt->execute(array_merge([$user_id], $selected));

echo "<script>alert('Checkout berhasil!'); window.location='keranjang.php';</script>";
exit;
