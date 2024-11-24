<?php
include('../model/AdminDashboard.php');
session_start();

$funObj = new Admin();

if (isset($_POST['token'], $_POST['password'], $_POST['confirm_password'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the token
    $user = $funObj->validate_reset_token($token);

    if (!$user) {
        $_SESSION['message'] = "Invalid or expired token.";
        $_SESSION['message_type'] = "error";
        header('Location: ../forgot-password.php'); // Redirect to the forget password page
        exit();
    }

    // Validate passwords
    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
        $_SESSION['message_type'] = "error";
        header('Location: ../reset_password.php?token=' . urlencode($token));
        exit();
    }

    // Password condition: at least 8 characters, one uppercase, one lowercase, one digit, one special character
    $password_regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/";
    if (!preg_match($password_regex, $password)) {
        $_SESSION['message'] = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
        $_SESSION['message_type'] = "error";
        header('Location: ../reset_password.php?token=' . urlencode($token));
        exit();
    }

    // Check if the new password is the same as the old password
    $stored_password = $funObj->get_current_password_token($token);
    if (password_verify($password, $stored_password)) {
        $_SESSION['message'] = "New password cannot be the same as the current password.";
        $_SESSION['message_type'] = "error";
        header('Location: ../reset_password.php?token=' . urlencode($token));
        exit();
    }


    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update = $funObj->update_reset_password($hashed_password, $token); // Assume you have an `update_password` function
    
    if ($update) {
        $_SESSION['message'] = "Password has been successfully updated. Please login.";
        $_SESSION['message_type'] = "success";
        header('Location: ../index.php');
        exit();
    } else {
        $_SESSION['message'] = "Failed to reset password. Please try again.";
        $_SESSION['message_type'] = "error";
        header('Location: ../reset_password.php?token=' . urlencode($token));
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "error";
    header('Location: ../forgot-password.php');
    exit();
}
?>
