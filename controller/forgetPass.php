<?php
session_start(); // Start the session
include('../model/registerLogin.php');
include('../lib/email_configuration.php');

$funObj = new User();

if (isset($_POST['reset_btn'])) {
    $email = trim($_POST['email']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email address.";
        $_SESSION['message_type'] = "error";
        header('Location: ../forget_password.php');
        exit();
    }

    // Check if email exists
    $query = $funObj->isUserExist($email);
    if ($query) {
        // Generate token and expiration
        $token = bin2hex(random_bytes(32)); // Secure random token
        $expiration = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token in the database
        $update = $pdo->prepare("UPDATE users SET reset_token = :token, token_expiration = :expiration WHERE email = :email");
        $update->execute(['token' => $token, 'expiration' => $expiration, 'email' => $email]);

        // Send email with reset link
        $resetLink = "http://localhost/dental_clinic/reset_password.php?token=" . urlencode($token);
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n" . $resetLink . "\n\nIf you did not request this, please ignore this email.";
        $headers = "From: rosellesantander@rs-dentalclinic.com";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['message'] = "Password reset link has been sent to your email.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to send the reset email.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Email address not found.";
        $_SESSION['message_type'] = "error";
    }

    header('Location: ../forget_password.php');
    exit();
}
?>
