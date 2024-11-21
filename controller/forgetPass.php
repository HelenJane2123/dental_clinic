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
        $token = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 12);
        $expiration = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token in the database
        $update = $funObj->forgot_password($token, $expiration, $email);

        if ($update) {
           // Generate the reset link
            $resetLink = "http://localhost/dental_clinic/reset_password.php?token=" . urlencode($token);

            // Email subject
            $subject = "Password Reset Request";

            // HTML Email message
            $message = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f9f9f9;
                        color: #333;
                        line-height: 1.6;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        width: 100%;
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        overflow: hidden;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }
                    .email-header {
                        background-color: #007bff;
                        color: #ffffff;
                        text-align: center;
                        padding: 20px;
                        font-size: 24px;
                    }
                    .email-body {
                        padding: 20px;
                    }
                    .email-body p {
                        margin: 10px 0;
                    }
                    .email-body a {
                        display: inline-block;
                        margin: 20px 0;
                        padding: 10px 20px;
                        background-color: #28a745;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 5px;
                        font-size: 16px;
                    }
                    .email-body a:hover {
                        background-color: #218838;
                    }
                    .email-footer {
                        text-align: center;
                        font-size: 12px;
                        color: #888;
                        margin: 20px 0;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="email-header">
                        Roselle Santander Dental Clinic
                    </div>
                    <div class="email-body">
                        <p>Hello,</p>
                        <p>We received a request to reset your password. Please click the button below to reset your password:</p>
                        <a href="' . $resetLink . '" target="_blank">Reset Password</a>
                        <p>If the button above does not work, copy and paste the following link into your browser:</p>
                        <p>' . $resetLink . '</p>
                        <p>If you did not request this, please ignore this email or contact us if you have concerns.</p>
                        <p>Thank you,<br>Roselle Santander Dental Clinic Team</p>
                    </div>
                    <div class="email-footer">
                        &copy; ' . date("Y") . ' Roselle Santander Dental Clinic. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ';

            // Headers for HTML email
            $headers = "From: rosellesantander@rs-dentalclinic.com\r\n";
            $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            // Send the email
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
