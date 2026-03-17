<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "../php/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/darkmode.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../javaScript/darkmode.js"></script>
</head>

<body>

    <!-- NAV -->
    <nav>
        <div class="logo"><strong>EDAMAME</strong></div>
        <ul>
            <li><a href="../index.html">Home</a></li>
            <li><a href="services.html">Fragrances</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="Contact.html">Contact Us</a></li>
        </ul>
        <div class="nav-icons">
            <button class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon"></i>
            </button>
            <div class="profile-icon">
                <i class="fas fa-circle-user"></i>
            </div>
        </div>
    </nav>

    <!-- PROFILE -->
    <div class="profile-container">
        <div class="profile-card">

            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>

            <h2>
                <?php echo $user['full_name']; ?>
            </h2>
            <p>
                <?php echo $user['email']; ?>
            </p>

            <div class="profile-info">
                <div>
                    <span>Full Name</span>
                    <p>
                        <?php echo $user['full_name']; ?>
                    </p>
                </div>

                <div>
                    <span>Email</span>
                    <p>
                        <?php echo $user['email']; ?>
                    </p>
                </div>
            </div>

            <form action="../php/logout.php" method="POST">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

</body>

</html>