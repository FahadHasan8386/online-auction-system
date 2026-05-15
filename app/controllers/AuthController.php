<?php
// Authentication Controller
// Location: OnlineAuctionSystem/controllers/authController.php

require_once '../config/database.php';

session_start();

// Make $conn global to ensure it's accessible
global $conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['action'])) {

        $action = $_POST['action'];

        // ================= LOGIN =================

        if ($action == 'login') {

            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $errors = [];

            // Validation
            if (empty($email)) {
                $errors[] = "Email is required";
            }

            if (empty($password)) {
                $errors[] = "Password is required";
            }

            if (empty($errors)) {

                $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";

                $stmt = mysqli_prepare($conn, $sql);

                if (!$stmt) {
                    $_SESSION['error'] = "Database error: " . mysqli_error($conn);
                    header("Location: ../views/shared/login.php");
                    exit();
                }

                mysqli_stmt_bind_param($stmt, "s", $email);

                if (!mysqli_stmt_execute($stmt)) {
                    $_SESSION['error'] = "Database error: " . mysqli_stmt_error($stmt);
                    header("Location: ../views/shared/login.php");
                    exit();
                }

                $result = mysqli_stmt_get_result($stmt);

                $user = mysqli_fetch_assoc($result);

                if ($user && password_verify($password, $user['password_hash'])) {

                    // Check account status
                    if ($user['is_active'] == 0) {

                        $_SESSION['error'] = "Your account is deactivated";

                        header("Location: ../views/shared/login.php");
                        exit();
                    }

                    // Session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect by role
                    if ($user['role'] == 'buyer') {

                        header("Location: ../views/buyer/buyerdashboard.php");

                    } elseif ($user['role'] == 'seller') {

                        header("Location: ../views/seller/sellerDashboard.php");

                    } elseif ($user['role'] == 'moderator') {

                        header("Location: ../views/moderator/moderatorDashboard.php");

                    } elseif ($user['role'] == 'admin') {

                        header("Location: ../views/admin/adminDashboard.php");
                    }

                    exit();

                } else {

                    $_SESSION['error'] = "Invalid email or password";

                    header("Location: ../views/shared/login.php");

                    exit();
                }

            } else {

                $_SESSION['errors'] = $errors;

                header("Location: ../views/shared/login.php");

                exit();
            }

        }

        // ================= REGISTER =================

        elseif ($action == 'register') {

            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            $errors = [];

            // Validation
            if (empty($name)) {
                $errors[] = "Name is required";
            }

            if (empty($email)) {
                $errors[] = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            }

            if (empty($phone)) {
                $errors[] = "Phone is required";
            }

            if (empty($password)) {
                $errors[] = "Password is required";
            } elseif (strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters";
            }

            if ($password != $confirm_password) {
                $errors[] = "Passwords do not match";
            }

            // Check email exists
            if (empty($errors)) {

                $checkSql = "SELECT id FROM users WHERE email = ?";

                $checkStmt = mysqli_prepare($conn, $checkSql);

                if (!$checkStmt) {
                    $_SESSION['error'] = "Database error: " . mysqli_error($conn);
                    header("Location: ../views/shared/register.php");
                    exit();
                }

                mysqli_stmt_bind_param($checkStmt, "s", $email);

                if (!mysqli_stmt_execute($checkStmt)) {
                    $_SESSION['error'] = "Database error: " . mysqli_stmt_error($checkStmt);
                    header("Location: ../views/shared/register.php");
                    exit();
                }

                $checkResult = mysqli_stmt_get_result($checkStmt);

                if (mysqli_num_rows($checkResult) > 0) {

                    $errors[] = "Email already exists";
                }
            }

            // Insert user
            if (empty($errors)) {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users 
                        (name, email, phone, bio, password_hash, role, seller_verified, is_active, reputation_score, created_at)
                        VALUES (?, ?, ?, ?, ?, 'buyer', 0, 1, 0, NOW())";

                $stmt = mysqli_prepare($conn, $sql);

                if (!$stmt) {
                    $_SESSION['error'] = "Database error: " . mysqli_error($conn);
                    header("Location: ../views/shared/register.php");
                    exit();
                }

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssss",
                    $name,
                    $email,
                    $phone,
                    $bio,
                    $hashed_password
                );

                if (mysqli_stmt_execute($stmt)) {

                    $_SESSION['success'] = "Registration successful! Please login.";

                    header("Location: ../views/shared/login.php");

                    exit();

                } else {

                    $_SESSION['error'] = "Registration failed: " . mysqli_stmt_error($stmt);

                    header("Location: ../views/shared/register.php");

                    exit();
                }

            } else {

                $_SESSION['errors'] = $errors;

                header("Location: ../views/shared/register.php");

                exit();
            }
        }
    }
}
?>