<?php
include('../model/AdminDashboard.php'); 
include('../../lib/email_configuration.php');
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
$patient_id = $_POST['patient_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']); // Use POST data
    if (isset($_POST['action']) && $_POST['action'] === 'complete') {
        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

        // Mark the appointment as completed
        if ($funObj->complete_appointment($appointment_id, $notes, $updated_by, $user_id)) {

            // Fetch patient's email
            $patient_email = $funObj->get_patient_email_by_appointment($patient_id);

            // Prepare email content for completion
            $subject = "Your Appointment is Completed";
            $message = "Dear Patient,\n\n";
            $message .= "We are pleased to inform you that your appointment has been successfully completed.\n\n";
            $message .= "Thank you for trusting Roselle Santander Dental Clinic for your dental care. If you have any further concerns or need assistance, please don't hesitate to reach out.\n\n";
            $message .= "Best regards,\n";
            $message .= "Your Roselle Santander Dental Clinic Team";

            // Set the email headers
            $headers = "From: rosellesantander@rs-dentalclinic.com" . "\r\n" .
                "Reply-To: rosellesantander@rs-dentalclinic.com" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            mail($patient_email, $subject, $message, $headers);

            // Redirect to the appointments page
            header("Location: ../appointment_bookings.php");
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error completing appointment.']);
        }
    } 
    // Check if new date and time are provided for rescheduling
    else if (isset($_POST['new_date']) && isset($_POST['new_time'])) {
        // Rescheduling logic
        $new_date = $_POST['new_date'];
        $new_time = $_POST['new_time'];
        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
        
        if ($funObj->reschedule_appointment($appointment_id, $new_date, $new_time, $notes, $updated_by, $user_id)) {
           // Fetch patient's email after rescheduling
           $patient_email = $funObj->get_patient_email_by_appointment($patient_id);

           // Prepare email content
           $subject = "Your Appointment has been Rescheduled";
           $message = "Dear Patient,\n\n";
           $message .= "Your appointment has been rescheduled to:\n";
           $message .= "New Date: " . $new_date . "\n";
           $message .= "New Time: " . $new_time . "\n";
           $message .= "Notes: " . $notes . "\n\n";
           $message .= "If you have any questions, please feel free to contact us.\n\n";
           $message .= "Best regards,\nYour Roselle Santander Dental Clinic Team";

           // Send email to patient
           $headers = "From: rosellesantander@rs-dentalclinic.com\r\n" .
                      "Reply-To: rosellesantander@rs-dentalclinic.com\r\n" .
                      "X-Mailer: PHP/" . phpversion();

           mail($patient_email, $subject, $message, $headers);
           
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

                // Fetch patient's email after cancelation
                $patient_email = $funObj->get_patient_email_by_appointment($patient_id);

                // Prepare email content
                $subject = "Your Appointment has been Canceled";
                $message = "Dear Patient,\n\n";
                $message .= "Your appointment has been canceld due to the following reason:\n";
                $message .= " " . $notes . "\n\n";
                $message .= "If you have any questions, please feel free to contact us.\n\n";
                $message .= "Best regards,\nYour Roselle Santander Dental Clinic Team";

                // Send email to patient
                $headers = "From: rosellesantander@rs-dentalclinic.com\r\n" .
                            "Reply-To: rosellesantander@rs-dentalclinic.com\r\n" .
                            "X-Mailer: PHP/" . phpversion();

                mail($patient_email, $subject, $message, $headers);
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
                
                // Fetch patient's email after rescheduling
                $patient_email = $funObj->get_patient_email_by_appointment($patient_id);

                // Prepare email content for the patient
                $subject = "Your Appointment has been Approved";
                $message = "Dear Patient,\n\n";
                $message .= "We are pleased to inform you that your appointment has been approved.\n\n";
                $message .= "Appointment Date: " . $appointment_date . "\n";
                $message .= "Appointment Time: " . $appointment_time . "\n";
                $message .= "Notes: " . $notes . "\n\n";
                $message .= "If you have any questions or concerns, please feel free to contact us.\n\n";
                $message .= "Best regards,\n";
                $message .= "Your Roselle Santander Dental Clinic Team";

                // Set the email headers
                $headers = "From: rosellesantander@rs-dentalclinic.com" . "\r\n" .
                    "Reply-To: rosellesantander@rs-dentalclinic.com" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

                 mail($patient_email, $subject, $message, $headers);
                
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
