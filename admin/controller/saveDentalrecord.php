<?php
    include('../model/AdminDashboard.php'); 
    session_start();   

    $funObj = new Admin(); 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assuming $appointment_admin is already initialized and connected to the database
    
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
    
            // Save the record to the database (assuming a method in appointment_admin for this)
            $result = $funObj->save_dental_record($patient_id, $date, $tooth, $procedure, $dentist, $charged, $paid, $balance, $next_appointment);
        }
    
        if ($result) {
            // Redirect back to the patient record page or show success message
            header('Location: ../view_record.php?patient_id=' . $patient_id);
        } else {
            echo "<p>Failed to save the records. Please try again.</p>";
        }
    }

?>