<?php
    include('../model/registerLogin.php'); 
    include('../lib/email_config.php');
    session_start();   
    // initializing variables
    $email_address  = "";
    $errors = array(); 
    $funObj = new User(); 

    if (isset($_POST['register'])) {
        // receive all input values from the form
        $first_name       = $_POST['first_name'];
        $last_name        = $_POST['last_name'];
        $email_address    = $_POST['email'];
        $mobile_number    = $_POST['contact_number'];
        $username         = $_POST['username'];
        $password_1       = $_POST['password'];
        $password_2       = $_POST['confirm_password'];
        $agree_terms      = isset($_POST['iAgree']) ? 1 : 0;
        $date_created     = date("Y-m-d h:i:sa");
        $fourRandomDigit  = rand(0001,9999);
        $member_id        = "M-".$fourRandomDigit;
        $verification_code = rand(1000, 9999); // 4-digit code
        
        //check if email exists
        $result_email = $funObj->isUserExist($email_address);
        if ($result_email->num_rows > 0) {
            $_SESSION['message'] = "Email already exists.";
            $_SESSION['message_type'] = 'danger';
            $_SESSION['form_data'] = $_POST; // Save form data
            header('Location: ../signup.php');
            exit();
        }

        //check if username exists
        $result_username = $funObj->isUserName($username);
        if ($result_username->num_rows > 0) {
            $_SESSION['message'] = "Username already exists.";
            $_SESSION['message_type'] = 'danger';
            $_SESSION['form_data'] = $_POST; // Save form data
            header('Location: ../signup.php');
            exit();
        }

        //validate password
        if ($password_1 != $password_2) {
            $_SESSION['message'] =  "The two passwords do not match";
            $_SESSION['message_type'] = "danger";
            $_SESSION['form_data'] = $_POST; // Save form data
            header('Location: ../signup.php');
            exit();
        }  //validate email address
        else if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = "The email address '$email_address' is not valid.";
            $_SESSION['message_type'] = "danger";
            $_SESSION['form_data'] = $_POST; // Save form data
            header('Location: ../signup.php');
            exit();
        }

         // Password condition: at least 8 characters, one uppercase, one lowercase, one digit, and one special character
         $password_regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/";
         if (!preg_match($password_regex, $password_1)) {
             $_SESSION['message'] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
             $_SESSION['message_type'] = "danger";
             $_SESSION['form_data'] = $_POST; // Save form data
             header('Location: ../signup.php');
             exit();
         }

        // Hash the password
        $hashed_password = password_hash($password_1, PASSWORD_DEFAULT);

        //insert registration details
        $query = $funObj->reg_user($member_id, $first_name, $last_name, $mobile_number, $agree_terms, $username, $hashed_password, $email_address, $date_created, $verification_code);
        if ($query) {
            // Send verification email
            $subject = "Verify Your Account";
            $message = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                    }
                    .email-container {
                        background: #ffffff;
                        max-width: 600px;
                        margin: 20px auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    }
                    .header {
                        text-align: center;
                        padding-bottom: 20px;
                    }
                    .header img {
                        max-width: 150px;
                    }
                    .content {
                        text-align: center;
                    }
                    .content h1 {
                        color: #007bff;
                    }
                    .content p {
                        margin: 10px 0;
                    }
                    .verification-code {
                        font-size: 24px;
                        font-weight: bold;
                        color: #007bff;
                        margin: 20px 0;
                    }
                    .button {
                        display: inline-block;
                        margin: 20px 0;
                        padding: 10px 20px;
                        background-color: #007bff;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                    }
                    .footer {
                        margin-top: 20px;
                        text-align: center;
                        font-size: 12px;
                        color: #888;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='header'>
                        <img src='https://rs-dentalclinic.com/img/logo.png'>
                    </div>
                    <div class='content'>
                        <h1>Welcome, $first_name!</h1>
                        <p>Thank you for registering with RS Dental Clinic! To complete your account setup, please verify your email using the code below:</p>
                        <div class='verification-code'>$verification_code</div>
                        <p>Click the button below to verify your account:</p>
                        <a href='https://rs-dentalclinic.com/verification.php?code=$verification_code' class='button'>Verify Account</a>
                        <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
                        <p><a href='https://rs-dentalclinic.com/verification.php?code=$verification_code'>https://rs-dentalclinic.com/verification.php?code=$verification_code</a></p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2024 Roselle Santander Dental Clinic. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            $headers = "From: rosellesantander@rs-dentalclinic.com\r\n";
            $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            if (mail($email_address, $subject, $message, $headers)) {
                $_SESSION['message'] = 'Registration successful. Please check your email for the verification code.';
                $_SESSION['message_type'] = "success";
                header('Location: ../verification.php');
            } else {
                $_SESSION['message'] = "Registration failed. Unable to send verification email.";
                $_SESSION['message_type'] = "danger";
                header('Location: ../signup.php');
                exit();
            }
        }
        else {
          $_SESSION['message'] = 'Registration failed. Email or Username already exists, please try again.';
          $_SESSION['message_type'] = "danger";
          $_SESSION['form_data'] = $_POST; // Save form data
          header('Location:../signup.php');
          exit();
        }
    }      
?>