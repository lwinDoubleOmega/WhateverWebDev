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
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        nav {
            background: #1a1a1a;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo {
            font-size: 1.4rem;
            font-weight: bold;
            letter-spacing: 2px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 0.9rem;
        }

        .admin-wrapper {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-banner {
            background: #1a1a1a;
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-banner h2 {
            margin: 0;
            font-size: 1.3rem;
        }

        .welcome-banner span {
            opacity: 0.6;
            font-size: 0.9rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .stat-card i {
            font-size: 2rem;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 1.8rem;
        }

        .stat-card p {
            margin: 5px 0 0;
            color: #888;
            font-size: 0.85rem;
        }

        .section {
            background: white;
            border-radius: 10px;
            padding: 25px 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .section h3 {
            margin-top: 0;
            font-size: 1.1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th,
        td {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        th {
            color: #888;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fafafa;
        }

        .badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-admin {
            background: #1a1a1a;
            color: white;
        }

        .badge-user {
            background: #e8e8e8;
            color: #555;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .add-btn {


            display: block;
            width: 180px;
            margin-bottom: 15px;
            padding: 12px;
            border: none;
            border-radius: 10px;

            background: #111;
            color: #fff;

            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;

            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            background: #333;
            transform: translateY(-2px);
        }
    </style>
    <!-- css end -->
</head>

<body>

    <nav>
        <div class="logo">EDAMAME</div>
        <div>
            <span style="opacity:0.5; font-size:0.85rem;">Logged in as <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong></span>
            <a href="../php/logout.php" class="logout-btn" style="margin-left:20px;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="admin-wrapper">

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div>
                <h2><i class="fas fa-shield-alt"></i> Admin Panel</h2>
                <span>Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?></span>
            </div>
            <span><?= date("l, F j Y") ?></span>
        </div>

        <!-- Stats -->
        <div class="stats">
            <?php
            $total_users  = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
            $total_admins = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='admin'")->fetch_assoc()['c'];
            $total_orders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
            $total_products = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
            ?>
            <a href="admin_profile.php">
                <div class="stat-card users">
                    <i class="fas fa-users"></i>
                    <h3><?= $total_users ?></h3>
                    <p>Total Users</p>
                </div>
            </a>
            <a href="admin_products.php">
                <div class="red">
                    <div class="stat-card ">
                        <i class="fa-solid fa-spray-can-sparkles" style="color: rgb(250, 0, 0);"></i>
                        <h3><?= $total_products ?></h3>
                        <p>products</p>
                    </div>
                </div>
            </a>

            <div class="stat-card admins">
                <i class="fas fa-user-shield "></i>
                <h3><?= $total_admins ?></h3>
                <p>Admins</p>
            </div>
            <a href="admin_orders.php">
                <div class="stat-card orders">
                    <i class="fa-solid fa-cart-arrow-down"></i>
                    <h3><?= $total_orders ?></h3>
                    <p>Orders</p>
                </div>
            </a>
        </div>

        <!-- add product button -->
        <div style="display:flex; justify-content:flex-end; margin-bottom:15px;">
            <a href="admin-add-product.php">
                <button class="add-btn">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </a>
        </div>
        <div class="section">
            <h3><i class="fa-solid fa-spray-can-sparkles"></i> All Perfumes </h3>
<!-- Users Table -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['concentration']) ?></td>
                            <td><?= htmlspecialchars($row['price']) ?></td>
                            <td><?= htmlspecialchars($row['stock']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>