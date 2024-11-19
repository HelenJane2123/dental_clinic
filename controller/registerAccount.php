<?php
    include('../model/registerLogin.php'); 
    include('../lib/email_configuration.php');
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
        $verification_code = bin2hex(random_bytes(16)); // Generate a unique code
        
        //check if email exists
        $result_email = $funObj->isUserExist($email_address);
        if ($result_email->num_rows > 0) {
            $_SESSION['message'] = "Email already exists.";
            $_SESSION['message_type'] = 'error';
            header('Location: ../signup.php');
            exit();
        }

        //check if username exists
        $result_username = $funObj->isUserName($username);
        if ($result_username->num_rows > 0) {
            $_SESSION['message'] = "Username already exists.";
            $_SESSION['message_type'] = 'error';
            header('Location: ../signup.php');
            exit();
        }

        //validate password
        if ($password_1 != $password_2) {
            $_SESSION['message'] =  "The two passwords do not match";
            $_SESSION['message_type'] = "danger";
            header('Location: ../signup.php');
            exit();
        }  //validate email address
        else if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = "The email address '$email_address' is not valid.";
            $_SESSION['message_type'] = "error";
            header('Location: ../signup.php');
            exit();
        }

         // Password condition: at least 8 characters, one uppercase, one lowercase, one digit, and one special character
         $password_regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/";
         if (!preg_match($password_regex, $password_1)) {
             $_SESSION['message'] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
             $_SESSION['message_type'] = "danger";
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
                Hello $first_name,

                Thank you for registering! Please verify your account using the code below:

                Verification Code: $verification_code

                Visit the following link to verify: 
                https://rs-dentalclinic.com/verification.php?code=$verification_code

                Thank you!
            ";
            $headers = "From: rosellesantander@rs-dentalclinic.com\r\n";
            $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

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
          header('Location:../signup.php');
          exit();
        }
    }      
?>