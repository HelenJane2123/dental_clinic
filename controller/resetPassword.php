<?php
include('../model/registerLogin.php');
session_start();

$funObj = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required fields are set
    if (isset($_POST['token'], $_POST['password'], $_POST['confirm_password'])) {
        $token = trim($_POST['token']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validate the token
        $user = $funObj->validate_reset_token($token);

        if (!$user) {
            $_SESSION['message'] = "Invalid or expired token.";
            $_SESSION['message_type'] = "error";
            header('Location: ../forgetpass.php'); // Redirect to forget password page
            exit();
        }

        // Validate passwords match
        if ($password !== $confirm_password) {
            $_SESSION['message'] = "Passwords do not match.";
            $_SESSION['message_type'] = "error";
            header('Location: ../reset_password.php?token=' . urlencode($token));
            exit();
        }

        // Validate password strength
        $password_regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/";
        if (!preg_match($password_regex, $password)) {
            $_SESSION['message'] = "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.";
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

        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update password in the database
        $update = $funObj->update_password($hashed_password, $token); // Assume this updates the password using the token

        if ($update) {
            // If update is successful
            $_SESSION['message'] = "Password has been successfully updated. You can now log in.";
            $_SESSION['message_type'] = "success";
            header('Location: ../login.php'); // Redirect to login page
            exit();
        } else {
            // If update fails
            $_SESSION['message'] = "Failed to reset password. Please try again.";
            $_SESSION['message_type'] = "error";
            header('Location: ../reset_password.php?token=' . urlencode($token));
            exit();
        }
    } else {
        // If required POST parameters are missing
        $_SESSION['message'] = "Invalid request. Missing required data.";
        $_SESSION['message_type'] = "error";
        header('Location: ../forgetpass.php');
        exit();
    }
} else {
    // If request is not POST
    $_SESSION['message'] = "Invalid request method.";
    $_SESSION['message_type'] = "error";
    header('Location: ../forgetpass.php');
    exit();
}
?>
