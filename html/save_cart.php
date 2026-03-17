<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "../php/database.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Please log in first."
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['cart']) || !is_array($data['cart']) || count($data['cart']) === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Cart is empty."
    ]);
    exit();
}

$cart = $data['cart'];

$conn->begin_transaction();

try {
    // 1. Find existing cart for this user
    $stmt = $conn->prepare("SELECT id FROM carts WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $cart_id = $row['id'];

        // Optional: clear old cart items before saving new ones
        $deleteStmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $deleteStmt->bind_param("i", $cart_id);
        $deleteStmt->execute();
    } else {
        // 2. Create new cart
        $insertCart = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $insertCart->bind_param("i", $user_id);
        $insertCart->execute();
        $cart_id = $insertCart->insert_id;
    }

    // 3. Insert cart items
    $insertItem = $conn->prepare("
        INSERT INTO cart_items (cart_id, product_id, quantity, unit_price)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($cart as $item) {
        $product_id = (int)$item['product_id'];
        $quantity = (int)$item['quantity'];
        $unit_price = (float)$item['price'];

        $insertItem->bind_param("iiid", $cart_id, $product_id, $quantity, $unit_price);
        $insertItem->execute();
    }

    $conn->commit();

    echo json_encode([
        "success" => true,
        "message" => "Cart saved."
    ]);

} catch (Exception $e) {
    $conn->rollback();

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>