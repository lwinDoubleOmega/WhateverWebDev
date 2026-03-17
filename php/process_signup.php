<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize inputs
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($password)) {
        die("Error: All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    if (strlen($password) < 8) {
        die("Error: Password must be at least 8 characters.");
    }

    // Check for duplicate email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Error: Email is already registered.");
    }
    $check->close();

    // Hash password and insert user
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $password_hash);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../html/login.html");
        exit();
    } else {
        error_log("DB Insert Error: " . $stmt->error); // log privately
        die("Registration failed. Please try again.");
    }
}
?>