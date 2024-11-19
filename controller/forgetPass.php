<?php
include('../model/registerLogin.php');
include('../lib/email_configuration.php'); // Ensure this file contains SMTP settings
session_start();

$funObj = new User();

if (isset($_POST['reset_btn'])) {
    $email = trim($_POST['email']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email address.";
        $_SESSION['message_type'] = "error";
        header('Location: ../forgetpass.php');
        exit();
    }

    // Check if email exists
    $query = $funObj->isUserExist($email);
    if ($query->num_rows > 0) { // Ensure method returns rows properly
        // Generate token and expiration
        $token = bin2hex(random_bytes(32));
        $expiration = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token in the database
        $update = $funObj->forgot_password($token, $expiration, $email);

        if ($update) {
            // Send email with reset link
            $resetLink = "http://localhost/dental_clinic/reset_password.php?token=" . urlencode($token);
            $subject = "Password Reset Request";
            $message = "Hello,\n\nWe received a request to reset your password. Please click the link below to reset your password:\n\n" . $resetLink . "\n\nIf you did not request this, please ignore this email.";
            
            // Replace this with proper SMTP email sending
            $headers = "From: noreply@rs-dentalclinic.com\r\n";
            $headers .= "Reply-To: noreply@rs-dentalclinic.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            if (mail($email, $subject, $message, $headers)) {
                $_SESSION['message'] = "Password reset link has been sent to your email.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to send the reset email.";
                $_SESSION['message_type'] = "error";
            }
        } else {
            $_SESSION['message'] = "An error occurred while processing your request. Please try again.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Email address not found.";
        $_SESSION['message_type'] = "error";
    }

    header('Location: ../forgetpass.php');
    exit();
}
?>
