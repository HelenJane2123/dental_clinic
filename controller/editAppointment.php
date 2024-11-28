<?php
    include('../model/userDashboard.php');
    include('../lib/email_config.php');

    session_start();
    
    // initializing variables
    $errors = array();
    $user_dashboard = new UserDashboard();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
        // Get the posted data
        $user_id = $_POST['doctor_id'];
        $appointmentId = intval($_POST['appointment_id']);
        $appointmentDate = $_POST['appointment_date'];
        $appointmentTime = $_POST['appointment_time'];
        $status = $_POST['status'];
        $notes = $_POST['notes'];
        $firstname = $_POST['first_name'];
        $lastname = $_POST['last_name'];
        $member_id = $_POST['member_id'];
    
        // Call the update appointment method
        if ($user_dashboard->update_appointment($appointmentId, $appointmentDate, $appointmentTime, $status, $notes, $firstname, $lastname, $member_id, $user_id)) {
            // Set success message
            $_SESSION['display_message'] = "Appointment successfully updated.";
            $_SESSION['message_type'] = "success";

            // Fetch doctor's email from the database
            $doctorEmail = $user_dashboard->get_doctor_details($user_id);

            // Fetch patient's email from the database
            $patientEmail = $user_dashboard->get_patient_email($member_id);

            $subjectDoctor = "Appointment Updated - " . $appointmentId;

            $messageDoctor = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f9f9f9;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        width: 100%;
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        overflow: hidden;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }
                    .email-header {
                        background-color: #007bff;
                        color: #ffffff;
                        text-align: center;
                        padding: 20px;
                        font-size: 24px;
                    }
                    .email-body {
                        padding: 20px;
                    }
                    .email-body p {
                        margin: 10px 0;
                    }
                    .email-footer {
                        text-align: center;
                        font-size: 12px;
                        color: #888;
                        margin: 20px 0;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='email-header'>
                        Appointment Update Notification
                    </div>
                    <div class='email-body'>
                        <p>Dear Dr. {$doctorEmail['first_name']} {$doctorEmail['last_name']},</p>
                        <p>The appointment details have been updated. Here are the new details:</p>
                        <ul>
                            <li><strong>Appointment ID:</strong> {$appointmentId}</li>
                            <li><strong>Patient Name:</strong> {$firstname} {$lastname}</li>
                            <li><strong>Member ID:</strong> {$member_id}</li>
                            <li><strong>New Appointment Date:</strong> {$appointmentDate}</li>
                            <li><strong>New Appointment Time:</strong> {$appointmentTime}</li>
                            <li><strong>Status:</strong> {$status}</li>
                            <li><strong>Notes:</strong> {$notes}</li>
                        </ul>
                        <p>Thank you,<br>Your Clinic Team</p>
                    </div>
                    <div class='email-footer'>
                        &copy; " . date("Y") . " Roselle Santander Dental Clinic. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ";
            
            // Prepare the email content for the patient
            $subjectPatient = "Your Appointment Has Been Updated - " . $appointmentId;
            
            $messagePatient = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    /* Reuse the same styles as above */
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='email-header'>
                        Appointment Update Notification
                    </div>
                    <div class='email-body'>
                        <p>Dear {$firstname} {$lastname},</p>
                        <p>Your appointment details have been updated. Here are the new details:</p>
                        <ul>
                            <li><strong>Appointment ID:</strong> {$appointmentId}</li>
                            <li><strong>New Appointment Date:</strong> {$appointmentDate}</li>
                            <li><strong>New Appointment Time:</strong> {$appointmentTime}</li>
                            <li><strong>Status:</strong> {$status}</li>
                            <li><strong>Notes:</strong> {$notes}</li>
                            <li><strong>Assigned Doctor:</strong> {$doctorEmail['email']}</li>
                        </ul>
                        <p>Thank you,<br>Your Clinic Team</p>
                    </div>
                    <div class='email-footer'>
                        &copy; " . date("Y") . " Roselle Santander Dental Clinic. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ";
            
            // Headers for HTML email
            $headers = "From: rosellesantander@rs-dentalclinic.com\r\n";
            $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            // Send the email notification to the doctor
            if (mail($doctorEmail['email'], $subjectDoctor, $messageDoctor, $headers)) {
                $_SESSION['email_message'] = "Email notification sent to the doctor.";
                $_SESSION['email_type'] = "success";
            } else {
                $_SESSION['email_message'] = "Failed to send email notification to the doctor.";
                $_SESSION['email_type'] = "danger";
            }
            
            // Send the email notification to the patient
            if (mail($patientEmail, $subjectPatient, $messagePatient, $headers)) {
                $_SESSION['patient_email_message'] = "Email notification sent to the patient.";
                $_SESSION['patient_email_type'] = "success";
            } else {
                $_SESSION['patient_email_message'] = "Failed to send email notification to the patient.";
                $_SESSION['patient_email_type'] = "danger";
            }

            header("Location: ../my_appointments.php");
            exit();

        } else {
            $_SESSION['display_message'] = "Error updating appointment.";
            $_SESSION['message_type'] = "danger";

            header("Location: ../my_appointments.php");
            exit();
        }
    }
?>
