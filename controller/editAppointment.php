<?php
    include('../model/userDashboard.php');
    session_start();
    
    // initializing variables
    $errors = array();
    $user_dashboard = new UserDashboard();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
        // Get the posted data
        $appointmentId = intval($_POST['appointment_id']);
        $appointmentDate = $_POST['appointment_date']; // Ensure these values are sanitized before use
        $appointmentTime = $_POST['appointment_time'];
        $status = $_POST['status'];
        $notes = $_POST['notes'];
    
        // Call the update appointment method
        if ($user_dashboard->update_appointment($appointmentId, $appointmentDate, $appointmentTime, $status, $notes)) {
            // Set success message
            $_SESSION['display_message'] = "Appointment successfully updated.";
            $_SESSION['message_type'] = "success";
        } else {
            // Set error message
            $_SESSION['display_message'] = "Error updating appointment.";
            $_SESSION['message_type'] = "danger";
        }
    
        // Redirect back to the appointments page
        header("Location: ../my_appointments.php");
        exit();
    }
?>