<?php
include('../model/AdminDashboard.php');
session_start();

$errors = array();
$funObj = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $subCategory = $_POST['sub_category'];
    $priceRange = $_POST['price_range'];
    $price = $_POST['price'];

    // Update the dental service
    $sql = $funObj->update_dental_services($id, $category, $subCategory, $priceRange, $price);

    if ($sql) {
        // Success message
        $_SESSION['display_message'] = "Dental service updated successfully!";
        $_SESSION['message_type'] = 'success';
        header("Location: ../dental_services.php");
        exit();
    } else {
        // Error message
        $_SESSION['display_message'] = "Failed to update dental service.";
        $_SESSION['message_type'] = 'danger';
        header("Location: ../dental_services.php");
        exit();
    }
}
?>
