<?php
session_start();
$pdo = require 'koneksi.php';

if (!isset($_SESSION['user'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location='login.php';</script>";
    exit;
}

if (empty($_GET['id']) || empty($_GET['qty'])) {
    die("Invalid request");
}

$product_id = $_GET['id'];
$qty = intval($_GET['qty']);
$user_id = $_SESSION['user']['id'];

// 1. Ambil produk berdasarkan ID
$sql = "SELECT * FROM products WHERE id = :id";
$query = $pdo->prepare($sql);
$query->execute(['id' => $product_id]);
$product = $query->fetch(PDO::FETCH_ASSOC);


$kode_produk = $product['produk_kode'];
$product_name = $product['nama_produk'];
$price = $product['harga'];
$varian = $product['varian'];
$img = $product['image1']; // BLOB

// 2. Cek apakah produk sudah ada di cart user
$sql = "SELECT * FROM cart WHERE user_id = :uid AND kode_produk = :kode";
$query = $pdo->prepare($sql);
$query->execute([
    'uid'  => $user_id,
    'kode' => $kode_produk
]);
$cartItem = $query->fetch(PDO::FETCH_ASSOC);

// 3. Jika produk sudah ada → update qty
if ($cartItem) {
    $sql = "UPDATE cart SET qty = qty + :qty WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->execute([
        'qty' => $qty,
        'id'  => $cartItem['id']
    ]);

} else {
    // 4. Jika belum ada → insert baru
    $sql = "INSERT INTO cart 
            (user_id, kode_produk, product_name, price, varian, qty, img) 
            VALUES 
            (:uid, :kode, :name, :price, :varian, :qty, :img)";
    
    $query = $pdo->prepare($sql);
    $query->execute([
        'uid'    => $user_id,
        'kode'   => $kode_produk,
        'name'   => $product_name,
        'price'  => $price,
        'varian' => $varian,
        'qty'    => $qty,
        'img'    => $img
    ]);
}
$url = $_GET['url'];
header("location: $url.php?id=$product_id&cart=success");
exit;
?>
