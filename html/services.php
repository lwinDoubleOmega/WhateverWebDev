<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "../php/database.php";


// Fetch all users
$users_result = $conn->query("SELECT id, full_name, email, role FROM users ORDER BY id DESC");

// Fetch total products
$products_result = $conn->query("SELECT * FROM products ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/darkmode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../javaScript/script.js"></script>
    <script src="../javaScript/darkmode.js"></script>
    <title>Edamame</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Poppins:wght@300;400;500&display=swap"
        rel="stylesheet">

</head>

<body>

    <!-- NAV -->
    <nav>
        <div><strong>EDAMAME</strong></div>
        <ul>
            <li><a href="../index.html">Home</a></li>
            <li><a href="services.html" class="active">Fragrances</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact Us</a></li>
        </ul>
        <div class="nav-icons">
            <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode"><i
                    class="fas fa-moon"></i></button>
            <a href="profile.php"><div class="profile-icon" aria-label="Profile"><i class="fas fa-circle-user"></i></div></a>
    </div>
    

</div>

    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-text">
            <h1>Create Your Signature Scent</h1>
            <a href="own_signature_scent.html"><button>Start Crafting</button></a>
        </div>
        <img src="../images/ulysse-pointcheval--j6LLsAehUo-unsplash.jpg" alt="Perfume">
    </section>

    <!-- PRODUCTS -->
    <section class="featured-products">
        <h2 class="moreProjectLabel">More Products</h2>
        <div class="product-grid">

             <!--YSL MYSELF-->
            <?php while ($row = $products_result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="flip-card">
                        <div class="flip-card-inner">
                            <!-- FRONT -->
                            <div class="flip-card-front">
                                <img src="<?= $row['image_url'] ?>"
                                    alt="<?= $row['name'] ?>"
                                    class="product-img">
                            </div>
                            <!-- BACK -->
                            <div class="flip-card-back">
                                <p class="title"><?= $row['name'] ?></p>
                                <small><?= $row['description'] ?></small>
                            </div>
                        </div>
                    </div>
                    <h3 class="product-name"><?= $row['name'] ?></h3>
                    <p class="price">$<?= $row['price'] ?></p>
                    <button class="add-btn"
                        onclick="addToCart('<?= $row['name'] ?>', <?= $row['price'] ?>, '<?= $row['image_url'] ?>')">
                        Add to Cart
                    </button>
                </div>

            <?php endwhile; ?>

    </section>




    <!-- ABOUT -->
    <section class="section about">
        <img src="../images/beautinow-niche-perfume-0sHorINihAI-unsplash.jpg" alt="About Scentaris">
        <div class="about-text">
            <h2>About the Brand</h2>
            <p>Scentaris is a modern fragrance house dedicated to timeless elegance and refined sophistication. Our
                scents combine emotion, luxury and mystery.</p>
        </div>
    </section>





    <footer>
        © 2026 Edamame. All rights reserved.
    </footer>

</body>

</html>