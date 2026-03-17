<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "database.php";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../html/profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        header("Location: ../html/login.html?error=empty");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../html/login.html?error=invalid_email");
        exit();
    }

    // Look up user by email
    $stmt = $conn->prepare("SELECT id, full_name, password_hash, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $full_name, $password_hash, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $password_hash)) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['user_id']   = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email']     = $email;
            $_SESSION['role']      = $role;

            $stmt->close();
            $conn->close();

            // Redirect based on role
            if ($role === 'admin') {
                header("Location: ../html/admin_profile.php");
            } else {
                header("Location: ../html/profile.php");
            }
            exit();
        } else {
            // Wrong password
            header("Location: ../html/login.html?error=invalid_credentials");
            exit();
        }
    } else {
        // Email not found — same error message as wrong password (security best practice)
        header("Location: ../html/login.html?error=invalid_credentials");
        exit();
    }
} else {
    // Not a POST request
    header("Location: ../html/login.html");
    exit();
}
