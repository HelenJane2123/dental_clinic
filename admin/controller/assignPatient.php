<?php
include('../model/AdminDashboard.php'); 
include('../../lib/email_config.php');

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
            // Check if we retrieved the doctor's email
            $get_doctor_email = $funObj->get_doctor_details($doctor_id);
            if ($get_doctor_email && isset($get_doctor_email['email'])) {
                $get_patients = $funObj->get_patient_details($patient_id);

                $doctor_email = $get_doctor_email['email'];
                $doctor_name = $get_doctor_email['first_name'] . " " . $get_doctor_email['last_name'];

                $patient_name = $get_patients['first_name'] . " " . $get_patients['last_name'];
                $patient_id = $get_patients['member_id'];
                // Compose email content with HTML
                $subject = "New Patient Assigned to You";
                $message = "
                <html>
                <head>
                    <title>New Patient Assigned</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f7f7f7;
                            color: #333;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            width: 100%;
                            padding: 20px;
                            background-color: #fff;
                            border: 1px solid #ddd;
                            margin: 20px auto;
                            max-width: 600px;
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
                            margin-top: 30px;
                            font-size: 14px;
                            text-align: center;
                            color: #888;
                        }
                        .button {
                            background-color: #27ae60;
                            color: white;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            font-size: 16px;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h2>Dear Dr. $doctor_name,</h2>
                        <p>We are pleased to inform you that you have been assigned a new patient. Please reach out to them to schedule an appointment at your earliest convenience.</p>
                        <p><strong>Patient Assignment Details:</strong><br />
                        Patient ID: $patient_id<br />
                        Patient Name: $patient_name<br />
                        <p>If you have any questions, please feel free to contact the administration team.</p>
                        <p>Best regards,<br />Dental Clinic Team</p>
                        <p class='footer'>This is an automated email. Please do not reply.</p>
                    </div>
                </body>
                </html>
                ";

                // Set headers for HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
                $headers .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n";
                $headers .= "Reply-To: rosellesantander@rs-dentalclinic.com" . "\r\n";

                // Send email notification to the doctor
                if (mail($doctor_email, $subject, $message, $headers)) {
                    // Success: Redirect back to the doctors page with success message
                    $_SESSION['display_message'] = "Patient successfully assigned to doctor, and notification sent.";
                    $_SESSION['message_type'] = "success";
                } else {
                    // Email sending failed
                    $_SESSION['display_message'] = "Patient assigned, but failed to send notification email.";
                    $_SESSION['message_type'] = "warning";
                }
            } else {
                // Failed to fetch the doctor's email
                $_SESSION['display_message'] = "Failed to fetch doctor's email address.";
                $_SESSION['message_type'] = "danger";
            }
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
