<?php
include('../model/AdminDashboard.php');
session_start();

// Initializing variables
$errors = array();
$funObj = new Admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doctor_id'])) { // Check for 'doctor_id' instead
    $doctor_id = $_POST['doctor_id'];

    // Assuming $user_dashboard is an instance of the class that contains delete_doctor()
    if ($funObj->delete_doctor($doctor_id)) {
        // Success
        $_SESSION['message'] = "Doctor successfully deleted.";
        $_SESSION['message_type'] = "success";
    } else {
        // Failure
        $_SESSION['message'] = "Error deleting doctor.";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect back to the doctors page
    header("Location: ../doctors.php");
    exit();
}
?>
