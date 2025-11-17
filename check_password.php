<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['valid' => false]);
    exit;
}

$pdo = require 'koneksi.php';
$oldPass = $_POST['old_pass'] ?? '';

$query = $pdo->prepare("SELECT password FROM users WHERE id = :id");
$query->execute(['id' => $_SESSION['user']['id']]);
$user = $query->fetch();

$isValid = (sha1($oldPass) === $user['password']);

echo json_encode(['valid' => $isValid]);
?>