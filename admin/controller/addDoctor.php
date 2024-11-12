<?php
include('../model/AdminDashboard.php'); 
session_start();   

$errors = array();
$funObj = new Admin(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect input values
    $first_name       = $_POST['first_name'];
    $last_name        = $_POST['last_name'];
    $email_address    = $_POST['email'];
    $mobile_number    = $_POST['contact_number'];
    $username         = $_POST['username'];
    $password         = $_POST['password'];
    $user_type        = $_POST['user_type'];
    $specialty        = $_POST['specialty'];
    $date_created     = date("Y-m-d H:i:s");

    // Generate member ID
    $fourRandomDigit  = rand(1000, 9999);
    $member_id        = "M-" . $fourRandomDigit;

    // Validate email
    if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "The email address '$email_address' is not valid.";
        $_SESSION['message_type'] = "danger";
        header('Location: ../doctors.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Call `reg_doctor`
    $query = $funObj->reg_doctor(
        $member_id,
        $first_name,
        $last_name,
        $mobile_number,
        $username,
        $hashed_password,
        $user_type,
        $email_address,
        $date_created,
        $specialty
    );

    if ($query) {
        $_SESSION['message_type'] = "success";
        $_SESSION['display_message'] = 'Doctor added successfully.';
        header('Location: ../doctors.php');
        exit();
    } else {
        $_SESSION['display_message'] = 'Registration failed. Email or Username already exists, please try again.';
        $_SESSION['message_type'] = "danger";
        header('Location: ../doctors.php');
        exit();
    }
}
