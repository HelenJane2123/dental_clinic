<?php
session_start();
include('../model/registerLogin.php');

$funObj = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user credentials
    $is_authenticated = $funObj->check_login($username, $password);

    if (!$is_authenticated) {
        $_SESSION['message'] = 'Invalid username or password.';
        $_SESSION['message_type'] = "danger";
        header('Location: ../login.php');
        exit();
    } else {
       // Set session variables for logged-in user
       $_SESSION['username'] = $username;
       $_SESSION['success'] = true;
       $_SESSION['message'] = 'Login successful.';

       // Get user information
       $user_info = $funObj->getUserByUsername($username);
       
       if ($user_info) {
           $_SESSION['firstname'] = $user_info['firstname'];
           $_SESSION['lastname'] = $user_info['lastname'];
           $_SESSION['email'] = $user_info['email'];
           $_SESSION['member_id'] = $user_info['member_id'];
           $_SESSION['user_id'] = $user_info['id'];
           $_SESSION['user_type'] = $user_info['user_type'];

       }

       header('Location: ../appointment.php');
       exit();
    }
} else {
    // Redirect if the request is not a POST request
    header('Location: ../login.php');
    exit();
}
?>
