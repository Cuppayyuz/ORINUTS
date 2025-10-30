<?php
header('Content-Type: application/json');

if (isset($_POST['email'])) {
    $pdo = require 'koneksi.php';
    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $_POST['email']]);
    $count = $stmt->fetchColumn();
    
    echo json_encode(['exists' => ($count > 0)]);
} else {
    echo json_encode(['error' => 'No email provided']);
}
?>