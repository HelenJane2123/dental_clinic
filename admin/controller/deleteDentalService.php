<?php
include('../model/AdminDashboard.php');
session_start();

$errors = array();
$funObj = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Delete the dental service
    $sql = $funObj->delete_dental_services($id);

    if ($sql) {
        // Success message
        $_SESSION['display_message'] = "Dental service deleted successfully!";
        $_SESSION['message_type'] = 'success';
        header("Location: ../dental_services.php");
        exit();
    } else {
        // Error message
        $_SESSION['display_message'] = "Failed to delete dental service.";
        $_SESSION['message_type'] = 'danger';
        header("Location: ../dental_services.php");
        exit();
    }
}
?>
