<?php
include('../model/AdminDashboard.php');
session_start();

// Initializing variables
$errors = array();
$funObj = new Admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doctor_id'])) {
    $doctor_id = $_POST['doctor_id'];

    // Check if the doctor has assigned patients
    $assigned_doctor = $funObj->check_assigned_doctor($doctor_id);
    if ($assigned_doctor) {
        // Doctor has assigned patients, cannot delete
        $_SESSION['display_message'] = "Cannot delete doctor because there is an existing patient assigned to this doctor.";
        $_SESSION['message_type'] = "danger"; // Set the message type to 'danger' for errors
    } else {
        // No assigned patients, delete the doctor
        if ($funObj->delete_doctor($doctor_id)) {
            // Success: Doctor successfully deleted
            $_SESSION['display_message'] = "Doctor successfully deleted.";
            $_SESSION['message_type'] = "success";
        } else {
            // Failure: Error deleting doctor
            $_SESSION['display_message'] = "Error deleting doctor.";
            $_SESSION['message_type'] = "danger";
        }
    }

    // Redirect back to the doctors page with the session message
    header("Location: ../doctors.php");
    exit();
}
?>
