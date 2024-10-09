<?php
    include('../model/userDashboard.php');
    session_start();
    
    // initializing variables
    $errors = array();
    $user_dashboard = new UserDashboard();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
        $appointmentId = intval($_POST['appointment_id']);
    
        // Assuming $user_dashboard is an instance of the class that contains delete_appointment()
        if ($user_dashboard->delete_appointment($appointmentId)) {
            // Success
            $_SESSION['display_message'] = "Appointment successfully deleted.";
            $_SESSION['message_type'] = "success";
        } else {
            // Failure
            $_SESSION['display_message'] = "Error deleting appointment.";
            $_SESSION['message_type'] = "danger";
        }
    
        // Redirect back to the appointments page
        header("Location: ../my_appointments.php");
        exit();
    }
?>