<?php
session_start();
include('../model/register_login.php');

$funObj = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user credentials
    $is_authenticated = $funObj->check_login($username, $password);

    if (!$is_authenticated) {
        $_SESSION['message'] = 'Invalid username or password.';
        $_SESSION['message_type'] = "error";
        header('Location: ../login.php');
        exit();
    } else {
        // Set session variables for logged-in user
        $_SESSION['username'] = $username;
        $_SESSION['success'] = true;
        $_SESSION['message'] = 'Login successful.';
        header('Location: ../appointment.php');
        exit();
    }
} else {
    // Redirect if the request is not a POST request
    header('Location: ../login.php');
    exit();
}
?>
