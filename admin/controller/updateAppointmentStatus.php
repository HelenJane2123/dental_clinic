<?php
include('../model/AdminDashboard.php'); 
session_start();   

// Initializing variables
$errors = array(); 
$funObj = new Admin(); 

// Ensure that the user is logged in and username is available
if (!isset($_SESSION['username'])) {
    // Redirect to login or display an error message
    echo json_encode(['status' => 'error', 'message' => 'User is not authenticated.']);
    exit;
}

$updated_by = $_SESSION['username']; // Get the username from the session
$user_id = $_POST['user_id_admin'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']); // Use POST data

    // Check if new date and time are provided for rescheduling
    if (isset($_POST['new_date']) && isset($_POST['new_time'])) {
        // Rescheduling logic
        $new_date = $_POST['new_date'];
        $new_time = $_POST['new_time'];
        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
        
        if ($funObj->reschedule_appointment($appointment_id, $new_date, $new_time, $notes, $updated_by, $user_id)) {
            // Redirect to a success page
            header("Location: ../appointment_bookings.php");
            exit;
        } else {
            // Return an error message
            echo json_encode(['status' => 'error', 'message' => 'Error rescheduling appointment.']);
        }
    } 
    // Check if notes are provided for approval or cancellation
    else if (isset($_POST['notes'])) {
        $notes = $_POST['notes'];
        
        // Determine if it's an approval or cancellation
        if (isset($_POST['action']) && $_POST['action'] === 'cancel') {
            // Cancellation logic
            if ($funObj->cancel_appointment($appointment_id, $notes, $updated_by, $user_id)) {
                // Redirect to a success page
                header("Location: ../appointment_bookings.php");
                exit;
            } else {
                // Return an error message
                echo json_encode(['status' => 'error', 'message' => 'Error canceling appointment.']);
            }
        } else {
            // Approval logic
            if ($funObj->approve_appointment($appointment_id, $notes, $updated_by, $user_id)) { // Ensure user_id is included
                // Redirect to a success page
                header("Location: ../appointment_bookings.php");
                exit;
            } else {
                // Return an error message
                echo json_encode(['status' => 'error', 'message' => 'Error approving appointment.']);
            }
        }
    } else {
        // Return an error if notes are not set
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    }
} else {
    // Return an error if appointment_id is not set
    echo json_encode(['status' => 'error', 'message' => 'Appointment ID is missing.']);
}
?>
