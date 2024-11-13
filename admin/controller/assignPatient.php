<?php
include('../model/AdminDashboard.php'); 
session_start();   
$funObj = new Admin(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get doctor ID and patient ID from the form
    $doctor_id = $_POST['doctor_id'];
    $patient_id = $_POST['patient_id'];

    // Check if both doctor_id and patient_id are provided
    if (!empty($doctor_id) && !empty($patient_id)) {
        // Update query to assign the doctor to the patient
        $assign_patient = $funObj->assign_patient($doctor_id, $patient_id);

        if ($assign_patient) {
            // Success: Redirect back to the doctors page with success message
            $_SESSION['display_message'] = "Patient successfully assigned to doctor.";
            $_SESSION['message_type'] = "success";
        } else {
            // Error: Redirect with error message
            $_SESSION['display_message'] = "Failed to assign patient to doctor.";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: ../doctors.php");
    } else {
        // Redirect with error if required data is missing
        $_SESSION['display_message'] = "Invalid doctor or patient ID.";
        $_SESSION['message_type'] = "danger";
        header("Location: ../doctors.php");
    }
}
?>
