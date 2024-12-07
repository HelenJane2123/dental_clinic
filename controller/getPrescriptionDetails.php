<?php
include('../model/userDashboard.php');
include('../lib/email_config.php');

session_start();

// Initialize variables
$errors = [];
$user_dashboard = new UserDashboard();
// Validate the patient ID
if (!$patientId) {
    echo json_encode(['success' => false, 'error' => 'Invalid patient ID.']);
    exit;
}

// Assuming $user_dashboard is a class instance containing the get_prescription method
$user_dashboard = new UserDashboard(); // Initialize the UserDashboard object

// Call the method to get the prescription
$prescription = $user_dashboard->get_prescription($patientId);

// Check if prescription data was returned
if ($prescription) {
    echo json_encode([
        'success' => true,
        'data' => $prescription
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'No prescription found for this patient.']);
}

?>
