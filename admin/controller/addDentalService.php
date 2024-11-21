<?php
include('../model/AdminDashboard.php');
session_start();

$funObj = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $category = $_POST['category'];
    $subCategory = $_POST['sub_category'];
    $priceRange = $_POST['price_range'];
    $price = $_POST['price'];
    $down_payment = $_POST['down_payment'];

    // Call the method to insert the new dental service
    $sql = $funObj->add_dental_service($category, $subCategory, $priceRange, $price, $down_payment);

    if ($sql) {
        // Success message
        $_SESSION['display_message'] = "Dental service added successfully!";
        $_SESSION['message_type'] = 'success';
        header("Location: ../dental_services.php");
        exit();
    } else {
        // Error message
        $_SESSION['display_message'] = "Failed to add dental service.";
        $_SESSION['message_type'] = 'danger';
        header("Location: ../dental_services.php");
        exit();
    }
}
?>
