<?php
include('../model/AdminDashboard.php');
session_start();

// Create an instance of Admin class
$funObj = new Admin();

if (isset($_GET['prescription_id']) && isset($_GET['patient_id'])) {
    $prescription_id = (int)$_GET['prescription_id'];
    $patient_id = (int)$_GET['patient_id'];

    // Call the method to delete the prescription
    if ($funObj->delete_prescription($prescription_id)) {
        $_SESSION['message'] = 'Prescription deleted successfully.';
    } else {
        $_SESSION['message'] = 'Failed to delete prescription.';
    }

    // Redirect back to the patient record page
    header('Location: ../view_record.php?patient_id=' . $patient_id);
    exit();
} else {
    $_SESSION['message'] = 'Invalid request.';
    header('Location: ../dashboard.php');
    exit();
}
?>
