<?php
include('../model/AdminDashboard.php'); 
session_start();   
$funObj = new Admin(); 

// Check if doctor_id is provided
if (isset($_GET['doctor_id'])) {
    $doctorId = $_GET['doctor_id'];

    // Fetch doctor details
    $doctor = $funObj->get_doctor_details($doctorId);  // This will return doctor data or false if not found

    // Fetch patients assigned to this doctor
    $patients = $funObj->get_patient_assigned_doctor($doctorId);  // Assuming this returns an array of patients

    // Check if doctor data is valid
    if ($doctor) {
        // If patients are found, return doctor and patients data as JSON
        echo json_encode([
            'doctor' => $doctor,
            'patients' => $patients
        ]);
    } else {
        // Return an error if no doctor is found
        echo json_encode([
            'error' => 'Doctor not found.'
        ]);
    }
} else {
    // Return an error message if doctor_id is not provided
    echo json_encode([
        'error' => 'No doctor_id provided.'
    ]);
}
?>
