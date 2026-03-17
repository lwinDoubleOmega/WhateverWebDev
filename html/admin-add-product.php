<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "../php/database.php";

// Only admins allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../html/login.html");
    exit();
}

// Fetch all users
$users_result = $conn->query("SELECT id, full_name, email, role FROM users ORDER BY id DESC");
// Fetch total orders
$orders_result = $conn->query("SELECT *  FROM orders ORDER BY id DESC");
// Fetch total products
$products_result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — EDAMAME</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/darkmode.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../javaScript/darkmode.js"></script>
    <!-- css  start -->
    <link rel="stylesheet" href="../css/admin-add-product.css">
    <!-- css end -->
</head>

<body>
    <nav>
        <div class="logo">EDAMAME</div>

        <!-- CENTER -->
        <div class="nav-center">
            <span>Logged in as <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong></span>
            <a href="../php/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
            <p>Add a new fragrance to the EDAMAME collection</p>
        </div>

        <div class="card">
            <div id="alert-box"></div>

            <form action="../php/create_product.php" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Product Name <span>*</span></label>
                    <input type="text" name="name" placeholder="e.g. Sakura Mist" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Describe the scent, notes, mood..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Price (฿) <span>*</span></label>
                        <input type="number" name="price" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Stock <span>*</span></label>
                        <input type="number" name="stock" placeholder="0" min="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Concentration</label>
                    <select name="concentration">
                        <option value="">— Select concentration —</option>
                        <option value="EDC">Eau de Cologne</option>
                        <option value="EDT">Eau de Toilette</option>
                        <option value="EDP">Eau de Parfum</option>
                        <option value="Parfum">Parfum</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Product Image</label>
                    <div class="image-upload">
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" onchange="previewImage(event)">
                        <i class="fas fa-cloud-arrow-up"></i>
                        <p>Click to upload — JPG, PNG, WEBP up to 2MB</p>
                    </div>
                    <img id="preview" src="" alt="Preview">
                </div>

                <hr class="divider">

                <div class="form-actions">
                    <a href="admin_profile.html" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Product
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        // Show error/success messages from URL params
        const params = new URLSearchParams(window.location.search);
        const messages = {
            required: "Name, price, and stock are required.",
            invalid_price: "Price must be a valid positive number.",
            invalid_stock: "Stock must be a valid positive number.",
            invalid_image: "Image must be JPG, PNG, or WEBP.",
            image_size: "Image must be under 2MB.",
            db_error: "Database error. Please try again."
        };

        const alertBox = document.getElementById('alert-box');

        if (params.get('success') === '1') {
            alertBox.innerHTML = `<div class="alert alert-success"><i class="fas fa-circle-check"></i> Product created successfully!</div>`;
        } else if (params.get('error') && messages[params.get('error')]) {
            alertBox.innerHTML = `<div class="alert alert-error"><i class="fas fa-circle-exclamation"></i> ${messages[params.get('error')]}</div>`;
        }

        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        }
    </script>

</body>

</html>