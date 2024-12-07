<?php
include('../model/AdminDashboard.php'); 
session_start();   

$funObj = new Admin(); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the arrays from the form submission
    $patient_id = $_POST['patient_id'];
    $dates = $_POST['date'];
    $tooth_no = $_POST['tooth_no'];
    $procedures = $_POST['procedure'];
    $dentists = $_POST['dentist'];
    $amount_charged = $_POST['amount_charged'];
    $amount_paid = $_POST['amount_paid'];
    $balances = $_POST['balance'];
    $next_appointments = $_POST['next_appointment'];
    $medications = $_POST['medication'];  // Pluralize to avoid overwriting the loop variable
    $dosages = $_POST['dosage'];          // Pluralize to avoid overwriting the loop variable
    $instructions = $_POST['instructions']; // Pluralize to avoid overwriting the loop variable
    $doctor_id = $_POST['doctor_id'];

    // Iterate over the arrays and save each record
    for ($i = 0; $i < count($dates); $i++) {
        // Sanitize input to avoid SQL injection
        $date = $dates[$i];
        $tooth = $tooth_no[$i];
        $procedure = $procedures[$i];
        $dentist = $dentists[$i];
        $charged = $amount_charged[$i];
        $paid = $amount_paid[$i];
        $balance = $balances[$i];
        $next_appointment = $next_appointments[$i];
        $medication = $medications[$i];  // Now using the pluralized variable
        $dosage = $dosages[$i];          // Now using the pluralized variable
        $instruction = $instructions[$i]; // Now using the pluralized variable

        // Save the record to the database (assuming a method in appointment_admin for this)
        $result = $funObj->save_dental_record(
                        $patient_id, 
                        $date, 
                        $tooth, 
                        $procedure, 
                        $dentist, 
                        $charged, 
                        $paid, 
                        $balance, 
                        $next_appointment,
                        $medication,
                        $dosage,
                        $instruction,
                        $doctor_id);
    }

    if ($result) {
        // Redirect back to the patient record page or show success message
        header('Location: ../view_record.php?patient_id=' . $patient_id);
    } else {
        echo "<p>Failed to save the records. Please try again.</p>";
    }
}
?>
