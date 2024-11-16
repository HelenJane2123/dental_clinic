<?php
include('../model/userDashboard.php');
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$errors = array();
$user_dashboard = new UserDashboard();

if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Call the method to fetch the selected services and payment details
    $computation = $user_dashboard->getSelectedServicesAndPaymentDetails($appointment_id);

    if ($computation) {
        // Return valid JSON response
        echo json_encode($computation);
    } else {
        // If computation not found, return an error message
        echo json_encode(['error' => 'Computation not found for the given appointment ID.']);
    }
} else {
    // If appointment_id is not provided, return an error
    echo json_encode(['error' => 'Invalid appointment ID']);
}
?>
