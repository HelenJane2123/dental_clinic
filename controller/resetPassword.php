<?php
session_start();
include('../config/database.php'); // Ensure your database connection
include('../model/registerLogin.php');

$funObj = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
        $_SESSION['message_type'] = "error";
        header('Location: ../reset_password.php?token=' . urlencode($token));
        exit();
    }

    // Validate token
    $query = $funObj->reset_password($token);
    if ($query) {
        // Update password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        if ($funObj->update_password($hashedPassword, $token)) {
            $_SESSION['message'] = "Your password has been reset successfully.";
            $_SESSION['message_type'] = "success";
            header('Location: ../login.php');
            exit();
        } else {
            $_SESSION['message'] = "Failed to reset password. Please try again.";
            $_SESSION['message_type'] = "error";
            header('Location: ../reset_password.php?token=' . urlencode($token));
            exit();
        }
    } else {
        $_SESSION['message'] = "Invalid or expired token.";
        $_SESSION['message_type'] = "error";
        header('Location: ../forget_password.php');
        exit();
    }
}
?>
