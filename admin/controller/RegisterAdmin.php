<?php
    include('../model/AdminDashboard.php'); 
    session_start();   
    // initializing variables
    $email_address  = "";
    $errors = array(); 
    $funObj = new Admin(); 

    if (isset($_POST['register'])) {
        // receive all input values from the form
        $first_name       = $_POST['firstname'];
        $last_name        = $_POST['lastname'];
        $email_address    = $_POST['email'];
        $mobile_number    = $_POST['contactnumber'];
        $username         = $_POST['username'];
        $password_1       = $_POST['password'];
        $user_type        = $_POST['user_type'];
        $password_2       = $_POST['confirm_password'];
        $date_created     = date("Y-m-d h:i:sa");
        $fourRandomDigit  = rand(0001,9999);
        $member_id        = "M-".$fourRandomDigit;
        
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
        $query = $funObj->reg_user($member_id, $first_name, $last_name, $mobile_number, $username, $hashed_password, $user_type, $email_address, $date_created);
        if ($query) {
          $_SESSION['success'] = true;
          $_SESSION['message'] = 'Registration successful.';
          header('Location:../index.php');
        }
        else {
          $_SESSION['message'] = 'Registration failed. Email or Username already exists, please try again.';
          $_SESSION['message_type'] = "danger";
          header('Location:../register.php');
          exit();
        }
    }      
?>