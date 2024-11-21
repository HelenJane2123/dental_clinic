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
            $message = "
            <html>
            <head>
                <title>Your Appointment is Completed</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f9;
                        color: #333;
                        margin: 0;
                        padding: 20px;
                    }
                    .container {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    h2 {
                        color: #2c3e50;
                    }
                    p {
                        font-size: 16px;
                        line-height: 1.5;
                    }
                    .footer {
                        margin-top: 20px;
                        font-size: 14px;
                        color: #7f8c8d;
                    }
                    .button {
                        background-color: #27ae60;
                        color: #fff;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                        display: inline-block;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>

            <div class='container'>
                <h2>Dear Patient,</h2>
                <p>We are pleased to inform you that your appointment has been successfully completed. We appreciate your trust in Roselle Santander Dental Clinic for your dental care.</p>
                <p>If you have any further concerns or need assistance, please don't hesitate to reach out.</p>
                
                <p>Thank you for choosing us, and we look forward to seeing you again!</p>
                
                <a href='https://rs-dentalclinic.com/contact.php' class='button'>Contact Us</a>
                
                <div class='footer'>
                    <p>Best regards,</p>
                    <p>Your Roselle Santander Dental Clinic Team</p>
                </div>
            </div>

            </body>
            </html>
            ";

            // Set the email headers for HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
            $headers .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n";
            $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com" . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            mail($patient_email, $subject, $message, $headers);


            // Redirect to the appointments page
            header("Location: ../appointment_bookings.php");
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error completing appointment.']);
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

                // Prepare email content with HTML
                $subject = "Your Appointment has been Canceled";
                $message = "
                <html>
                <head>
                    <title>Your Appointment has been Canceled</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f9;
                            color: #333;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            background-color: #fff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        h2 {
                            color: #2c3e50;
                        }
                        p {
                            font-size: 16px;
                            line-height: 1.5;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 14px;
                            color: #7f8c8d;
                        }
                        .button {
                            background-color: #e74c3c;
                            color: #fff;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            display: inline-block;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>

                <div class='container'>
                    <h2>Dear Patient,</h2>
                    <p>We regret to inform you that your appointment has been canceled due to the following reason:</p>
                    <p><strong>$notes</strong></p>
                    <p>If you have any questions or need assistance, please don't hesitate to reach out to us. We apologize for any inconvenience caused.</p>

                    <p>We look forward to rescheduling your appointment at a more convenient time.</p>
                    
                    <a href='#' class='button'>Contact Us</a>
                    
                    <div class='footer'>
                        <p>Best regards,</p>
                        <p>Your Roselle Santander Dental Clinic Team</p>
                    </div>
                </div>

                </body>
                </html>
                ";

                // Set the email headers for HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
                $headers .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n";
                $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com" . "\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();

                mail($patient_email, $subject, $message, $headers);
                // Redirect to a success page
                header("Location: ../appointment_bookings.php");
                exit;
            } else {
                // Return an error message
                echo json_encode(['status' => 'error', 'message' => 'Error canceling appointment.']);
            }
        } 
        else if (isset($_POST['action']) && $_POST['action'] === 'reschedule') {
            $new_date = $_POST['new_date'];
            $new_time = $_POST['appointment_time'];
            $notes = isset($_POST['notes']) ? $_POST['notes'] : ''; 
            
            if ($funObj->reschedule_appointment($appointment_id, $new_date, $new_time, $notes, $updated_by, $user_id)) {
               // Fetch patient's email after rescheduling
               $patient_email = $funObj->get_patient_email_by_appointment($patient_id);
    
                // Prepare email content with HTML
                $subject = "Your Appointment has been Rescheduled";
                $message = "
                <html>
                <head>
                    <title>Your Appointment has been Rescheduled</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f9;
                            color: #333;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            background-color: #fff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        h2 {
                            color: #2c3e50;
                        }
                        p {
                            font-size: 16px;
                            line-height: 1.5;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 14px;
                            color: #7f8c8d;
                        }
                        .button {
                            background-color: #3498db;
                            color: #fff;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            display: inline-block;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>

                <div class='container'>
                    <h2>Dear Patient,</h2>
                    <p>We are writing to inform you that your appointment has been successfully rescheduled. The details of your new appointment are as follows:</p>

                    <p><strong>New Date:</strong> $new_date</p>
                    <p><strong>New Time:</strong> $new_time</p>
                    <p><strong>Notes:</strong> $notes</p>

                    <p>If you have any questions or need to further adjust your appointment, please don't hesitate to reach out to us. We're here to assist you.</p>
                    
                    <a href='#' class='button'>Contact Us</a>
                    
                    <div class='footer'>
                        <p>Best regards,</p>
                        <p>Your Roselle Santander Dental Clinic Team</p>
                    </div>
                </div>

                </body>
                </html>
                ";

                // Set the email headers for HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
                $headers .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n";
                $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com" . "\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();

                mail($patient_email, $subject, $message, $headers);
               
                // Redirect to a success page
                header("Location: ../appointment_bookings.php");
                exit;
            } else {
                // Return an error message
                echo json_encode(['status' => 'error', 'message' => 'Error rescheduling appointment.']);
            }
        }
        else {
            // Approval logic
            if ($funObj->approve_appointment($appointment_id, $notes, $updated_by, $user_id)) { // Ensure user_id is included
                
                // Fetch patient's email after rescheduling
                $patient_email = $funObj->get_patient_email_by_appointment($patient_id);

                // Prepare email content with HTML
                $subject = "Your Appointment has been Approved";
                $message = "
                <html>
                <head>
                    <title>Your Appointment has been Approved</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f9;
                            color: #333;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            background-color: #fff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
                        h2 {
                            color: #2c3e50;
                        }
                        p {
                            font-size: 16px;
                            line-height: 1.5;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 14px;
                            color: #7f8c8d;
                        }
                        .button {
                            background-color: #3498db;
                            color: #fff;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            display: inline-block;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>

                <div class='container'>
                    <h2>Dear Patient,</h2>
                    <p>We are pleased to inform you that your appointment has been approved. The details of your appointment are as follows:</p>

                    <p><strong>Notes:</strong> $notes</p>

                    <p>If you have any questions or concerns, please feel free to reach out to us. We're here to assist you.</p>
                    
                    <a href='#' class='button'>Contact Us</a>
                    
                    <div class='footer'>
                        <p>Best regards,</p>
                        <p>Your Roselle Santander Dental Clinic Team</p>
                    </div>
                </div>

                </body>
                </html>
                ";

                // Set the email headers for HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
                $headers .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n";
                $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com" . "\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();

                // Send the email
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
