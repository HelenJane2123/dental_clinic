<?php
    include('../model/registerLogin.php'); 
    session_start();   
    // initializing variables
    $errors = array(); 
    $funObj = new User(); 


    if (isset($_POST['verify'])) {
        $verification_code = $_POST['verification_code'];
    
        // Verify the code
        if ($funObj->verify_code($verification_code)) {
            $_SESSION['message'] = "Account verified successfully!";
            $_SESSION['message_type'] = "success";
            header('Location: ../login.php');
            exit();
        } else {
            $_SESSION['message'] = "Invalid or expired verification code.";
            $_SESSION['message_type'] = "danger";
        }
    }
   
?>