<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../html/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $stock       = trim($_POST['stock'] ?? '');


    if (empty($name) || empty($price) || empty($stock)) {
        header("Location: ../html/create_product.html?error=required");
        exit();
    }

    if (!is_numeric($price) || $price < 0) {
        header("Location: ../html/create_product.html?error=invalid_price");
        exit();
    }

    if (!is_numeric($stock) || $stock < 0) {
        header("Location: ../html/create_product.html?error=invalid_stock");
        exit();
    }

$image_url = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    $allowed_exts  = ['jpg', 'jpeg', 'png', 'webp'];

    $file_type = mime_content_type($_FILES['image']['tmp_name']); // check real mime, not what browser sends
    $file_size = $_FILES['image']['size'];
    $ext       = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($file_type, $allowed_types) || !in_array($ext, $allowed_exts)) {
        header("Location: ../html/create_product.html?error=invalid_image");
        exit();
    }

    if ($file_size > 2 * 1024 * 1024) {
        header("Location: ../html/create_product.html?error=image_size");
        exit();
    }

    $filename   = uniqid('product_', true) . '.' . $ext;
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/midtermTrkay/images/products/';
    $image_url  = 'images/products/' . $filename;

    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);

        $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename   = uniqid('product_', true) . '.' . $ext;
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/midtermTrkay/images/products/';
        $image_url  = 'images/products/' . $filename;

        // if (!is_dir($upload_dir)) {
        //     mkdir($upload_dir, 0755, true);
        // }


        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
        $image_path = '../images/' . $filename;
    }

    $concentration = !empty($_POST['concentration']) ? trim($_POST['concentration']) : null;
    $size_ml       = !empty($_POST['size_ml']) ? (int)trim($_POST['size_ml']) : null;

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, concentration, size_ml, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssdisis", $name, $description, $price, $stock, $concentration, $size_ml, $image_url);

    if ($stmt->execute()) {
        header("Location: ../html/admin_products.php?success=1");
    } else {
        header("Location: ../html/create_product.html?error=db_error");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../html/create_product.html");
    exit();
}
