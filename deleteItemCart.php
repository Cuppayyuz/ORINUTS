<?php 
session_start();

$pdo = require 'koneksi.php';

$query = $pdo->prepare("DELETE FROM cart WHERE id=:id");
$query->execute(['id' => $_GET['id']]);
header("location: keranjang.php");