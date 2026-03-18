<?php
session_start();
include "database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id       = $_SESSION['user_id'];
    $name          = trim($_POST['name'] ?? '');
    $concentration = $_POST['concentration'] ?? null;
    $size_ml       = (int)($_POST['size_ml'] ?? 0);
    $description   = 'Top: ' . $_POST['top_note'] . ' | Heart: ' . $_POST['heart_note'] . ' | Base: ' . $_POST['base_note'];

    $stmt = $conn->prepare("INSERT INTO products (user_id, type, name, description, concentration, size_ml) VALUES (?, 'custom', ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $user_id, $name, $description, $concentration, $size_ml);

    if ($stmt->execute()) {
        header("Location: ../html/own_signature_scent.php?saved=1");
    } else {
        header("Location: ../html/own_signature_scent.php?error=save_failed");
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>