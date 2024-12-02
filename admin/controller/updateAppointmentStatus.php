<?php
include('../model/AdminDashboard.php'); 
include('../../lib/email_config.php');
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
    // Fetch patient's email
    $patient = $funObj->get_patient_by_appointment_id($appointment_id);
    $firstName = $patient['first_name'];
    $lastName = $patient['last_name'];
    $serviceName = $patient['service_name'];
    $appointmentTime = date("h:i A", strtotime($patient['appointment_time']));
    $appointmentDate = date("F j, Y", strtotime($patient['appointment_date']));
    $patient_email = $patient['email'];

    
    // Fetch assigned doctor's details
    $doctor = $funObj->get_assigned_doctor_by_appointment_id($patient_id);
    
    if (!empty($doctor)) {
        $doctorName = $doctor[0]['doctor_name'];
        $doctorEmail = $doctor[0]['doctor_email'];
        $doctorPhone = $doctor[0]['contact_number'];
    } else {
        // Handle case where no doctor is assigned
        echo "No doctor assigned for this patient.";
    }

    if (isset($_POST['action']) && $_POST['action'] === 'complete') {
        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

        // Mark the appointment as completed
        if ($funObj->complete_appointment($appointment_id, $notes, $updated_by, $user_id)) {

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
                 <h2>Dear $firstName $lastName,</h2>
                <p>Your appointment for <strong>$serviceName</strong> has been successfully completed.</p>
                <p><strong>Service Description:</strong> $serviceDescription</p>
                <p><strong>Appointment Date:</strong> $appointmentDate</p>
                <p><strong>Time:</strong> $appointmentTime</p>
                

                <p>If you have further questions, feel free to reach out.</p>
                <p>Thank you for choosing our clinic!</p>
                <p>If you have any questions or believe this is an error, please contact us at <a href='mailto:rosellesantander@rs-dentalclinic.com'>rosellesantander@rs-dentalclinic.com</a>.</p>
                <p><a href='https://rs-dentalclinic.com/contact.php' class='btn'>Contact Support</a></p>
                
                <div class='footer'>
                    <p>Best regards,</p>
                    <p>Your Roselle Santander Dental Clinic Team</p>
                </div>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " Roselle Santander Dental Clinic. All rights reserved.</p>
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
                     <h2>Dear $firstName $lastName,</h2>
                    <p>Unfortunately, your appointment for <strong>$serviceName</strong> has been canceled.</p>
                    <p><strong>Reason:</strong> $notes</p>
                    <p>We apologize for the inconvenience. Please contact us to reschedule.</p>
                    <p>We look forward to rescheduling your appointment at a more convenient time.</p>
                    
                    <p>If you have any questions or believe this is an error, please contact us at <a href='mailto:rosellesantander@rs-dentalclinic.com'>rosellesantander@rs-dentalclinic.com</a>.</p>
                    <p><a href='https://rs-dentalclinic.com/contact.php' class='btn'>Contact Support</a></p>
                    
                    <div class='footer'>
                        <p>Best regards,</p>
                        <p>Your Roselle Santander Dental Clinic Team</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " Roselle Santander Dental Clinic. All rights reserved.</p>
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
                   <h2>Dear $firstName $lastName,</h2>
                        <p>Your appointment for <strong>$serviceName</strong> has been rescheduled.</p>
                        <p><strong>New Date:</strong> $new_date</p>
                        <p><strong>New Time:</strong> $new_time</p>
                        <p>If you have further questions, feel free to contact your assigned doctor:</p>
                        <p><strong>Doctor's Email:</strong> <a href='mailto:$doctorEmail'>$doctorEmail</a></p>

                    <p>If you have any questions or believe this is an error, please contact us at <a href='mailto:rosellesantander@rs-dentalclinic.com'>rosellesantander@rs-dentalclinic.com</a>.</p>
                    <p><a href='https://rs-dentalclinic.com/contact.php' class='btn'>Contact Support</a></p>
                    
                    <div class='footer'>
                        <p>Best regards,</p>
                        <p>Your Roselle Santander Dental Clinic Team</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " Roselle Santander Dental Clinic. All rights reserved.</p>
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
                    <h2>Dear $firstName $lastName,</h2>
                    <p>Your appointment for <strong>$serviceName</strong> has been approved.</p>
                    <p><strong>Date:</strong> $appointmentDate</p>
                    <p><strong>Time:</strong> $appointmentTime</p>
                    <p><strong>Notes:</strong> $notes</p>   
                    <p>If you have further questions, feel free to contact your assigned doctor:</p>
                    <p><strong>Doctor's Email:</strong> <a href='mailto:$doctorEmail'>$doctorEmail</a></p>
                    <p>If you have any questions or believe this is an error, please contact us at <a href='mailto:rosellesantander@rs-dentalclinic.com'>rosellesantander@rs-dentalclinic.com</a>.</p>
                    <p><a href='https://rs-dentalclinic.com/contact.php' class='btn'>Contact Support</a></p>
                    
                    <div class='footer'>
                        <p>Best regards,</p>
                        <p>Your Roselle Santander Dental Clinic Team</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " Roselle Santander Dental Clinic. All rights reserved.</p>
                </div>


                </body>
                </html>
                ";

                // Set the email headers for HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
                $headers .= "From: :$doctorEmail" . "\r\n";
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
