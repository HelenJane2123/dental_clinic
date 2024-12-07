<?php
include('../model/AdminDashboard.php');
session_start();

// Initialize an error array to store any errors
$errors = array();

// Create an instance of Admin class
$funObj = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Log the POST data (only for development purposes, remove in production)
    error_log(print_r($_POST, true)); // Log all incoming data for debugging

    // Get form data
    $dental_record_id = isset($_POST['dental_record_id']) ? $_POST['dental_record_id'] : '';
    $medication = isset($_POST['medication']) ? trim($_POST['medication']) : '';
    $dosage = isset($_POST['dosage']) ? trim($_POST['dosage']) : '';
    $instructions = isset($_POST['instructions']) ? trim($_POST['instructions']) : '';
    $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : '';

    // Validate inputs
    if (empty($medication)) {
        $errors[] = "Medication is required.";
    }
    if (empty($dosage)) {
        $errors[] = "Dosage is required.";
    }
    if (empty($instructions)) {
        $errors[] = "Instructions are required.";
    }

    // If there are no validation errors, proceed to save the prescription
    if (empty($errors)) {
        // Call the save_prescription method from the Admin class
        $result = $funObj->save_prescription($patient_id, $dental_record_id, $medication, $dosage, $instructions);

        // Check the result and return an appropriate response
        if ($result) {
            // Store success message in session and redirect
            $_SESSION['message'] = 'Prescription added successfully.';
            header('Location: ../view_record.php?patient_id=' . $patient_id);
            exit(); // Ensure script ends after redirect
        } else {
            // Store error message in session and redirect
            $_SESSION['message'] = 'Failed to add prescription.';
            header('Location: ../view_record.php?patient_id=' . $patient_id);
            exit();
        }
    } else {
        // If there are validation errors, redirect with the error messages
        $_SESSION['errors'] = $errors;
        header('Location: ../view_record.php?patient_id=' . $patient_id);
        exit();
    }
} else {
    echo 'Invalid request method.';
}
?>
