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

    // Step 1: Save custom perfume to products
    $stmt = $conn->prepare("INSERT INTO products (user_id, type, name, description, concentration, size_ml) VALUES (?, 'custom', ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $user_id, $name, $description, $concentration, $size_ml);
    $stmt->execute();
    $product_id = $conn->insert_id;
    $stmt->close();

    // Step 2: Get or create cart for user
    $cart = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
    $cart->bind_param("i", $user_id);
    $cart->execute();
    $cart->store_result();

    if ($cart->num_rows > 0) {
        $cart->bind_result($cart_id);
        $cart->fetch();
    } else {
        $cart->close();
        $new_cart = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $new_cart->bind_param("i", $user_id);
        $new_cart->execute();
        $cart_id = $conn->insert_id;
        $new_cart->close();
    }
    if (isset($cart) && $cart) $cart->close();

    // Step 3: Add to cart_items
    $item = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, unit_price) VALUES (?, ?, 1, 0.00)");
    $item->bind_param("ii", $cart_id, $product_id);

    if ($item->execute()) {
        header("Location: ../html/own_signature_scent.php?added=1");
    } else {
        header("Location: ../html/own_signature_scent.php?error=cart_failed");
    }

    $item->close();
    $conn->close();
    exit();
}
?>