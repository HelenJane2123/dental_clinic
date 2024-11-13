<?php
    include('../model/AdminDashboard.php'); 
    session_start();   
    // initializing variables
    $email_address  = "";
    $errors = array(); 
    $funObj = new Admin(); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $member_id = $_POST['member_id'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
    
         // Password condition: at least 8 characters, one uppercase, one lowercase, one digit, and one special character
         $password_regex = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{8,}$/";
         if (!preg_match($password_regex, $new_password)) {
             $_SESSION['display_message'] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
             $_SESSION['message_type'] = "danger";
             header('Location: ../change_password.php');
             exit();
         }
         else if ($new_password !== $confirm_password) {
             $_SESSION['display_message'] = "New password and confirm password do not match!";
             $_SESSION['message_type'] = "danger";
             header("Location: ../change_password.php");
             exit();
         }

        // Hash the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stored_password = $funObj->get_current_password($member_id);

       
        if ($stored_password !== false) {
            // Verify the current password with the stored hash
            if (password_verify($current_password, $stored_password)) {
                // Hash the new password
                $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
    
                // Update the password in the database
                $update_query = $funObj->update_password($member_id, $hashed_new_password);
    
                if ($update_query) {
                    $_SESSION['display_message'] = "Password changed successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['display_message'] = "Failed to update password. Please try again.";
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                $_SESSION['display_message'] = "Current password is incorrect!";
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['display_message'] = "User not found!";
            $_SESSION['message_type'] = "danger";
        }
    
        // Redirect back to the password change page
        header("Location: ../change_password.php");
        exit();
    }      
?>