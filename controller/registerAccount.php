<?php
include('../model/registerLogin.php'); 
include('../lib/email_config.php');
session_start();   

// initializing variables
$email_address = "";
$errors = array(); 
$funObj = new User(); 

if (isset($_POST['register'])) {
    // Receive all input values from the form
    $first_name       = $_POST['first_name'];
    $last_name        = $_POST['last_name'];
    $email_address    = $_POST['email'];
    $mobile_number    = $_POST['contact_number'];
    $username         = $_POST['username'];
    $password_1       = $_POST['password'];
    $password_2       = $_POST['confirm_password'];
    $agree_terms      = isset($_POST['iAgree']) ? 1 : 0;
    $date_created     = date("Y-m-d h:i:sa");
    $member_id        = "M-" . rand(1000, 9999);

    // Check if email or username already exists
    $result_email = $funObj->isUserExist($email_address);
    if ($result_email->num_rows > 0) {
        $_SESSION['message'] = "Email already exists.";
        $_SESSION['message_type'] = 'danger';
        $_SESSION['form_data'] = $_POST;
        header('Location: ../signup.php');
        exit();
    }

    $result_username = $funObj->isUserName($username);
    if ($result_username->num_rows > 0) {
        $_SESSION['message'] = "Username already exists.";
        $_SESSION['message_type'] = 'danger';
        $_SESSION['form_data'] = $_POST;
        header('Location: ../signup.php');
        exit();
    }

    // Validate passwords
    if ($password_1 != $password_2) {
        $_SESSION['message'] = "Passwords do not match.";
        $_SESSION['message_type'] = 'danger';
        $_SESSION['form_data'] = $_POST;
        header('Location: ../signup.php');
        exit();
    }

    // Check password strength
    $password_regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/";
    if (!preg_match($password_regex, $password_1)) {
        $_SESSION['message'] = "Password must meet security requirements.";
        $_SESSION['message_type'] = 'danger';
        $_SESSION['form_data'] = $_POST;
        header('Location: ../signup.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password_1, PASSWORD_DEFAULT);

    // Insert user details into database
    $query = $funObj->reg_user($member_id, $first_name, $last_name, $mobile_number, $agree_terms, $username, $hashed_password, $email_address, $date_created, null);
    if ($query) {
        // Send welcome email
        $subject = "Welcome to Roselle Santander Dental Clinic!";
        $message = "
        <html>
        <body>
            <p>Hi $first_name,</p>
            <p>Welcome to Roselle Santander Dental Clinic! Your account has been successfully created.</p>
            <p>You can now log in using your registered credentials. Click the link below to log in:</p>
            <a href='https://rs-dentalclinic.com/login.php' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Log In</a>
            <p>If the button above does not work, copy and paste the following link into your browser:</p>
            <p><a href='https://rs-dentalclinic.com/login.php'>https://rs-dentalclinic.com/login.php</a></p>
            <p>Thank you for choosing Roselle Santander Dental Clinic. We look forward to serving you!</p>
            <p>Best regards,<br>RS Dental Clinic Team</p>
        </body>
        </html>
        ";

        $headers = "From: rosellesantander@rs-dentalclinic.com\r\n";
        $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($email_address, $subject, $message, $headers)) {
            $_SESSION['message'] = 'Registration successful! Welcome email sent.';
            $_SESSION['message_type'] = "success";
            header('Location: ../login.php'); // Redirect to login page
            exit();
        } else {
            $_SESSION['message'] = "Registration successful, but welcome email could not be sent.";
            $_SESSION['message_type'] = "warning";
            header('Location: ../login.php'); // Redirect to login page
            exit();
        }
    } else {
        $_SESSION['message'] = 'Registration failed. Please try again.';
        $_SESSION['message_type'] = 'danger';
        $_SESSION['form_data'] = $_POST;
        header('Location: ../signup.php');
        exit();
    }
}
?>
