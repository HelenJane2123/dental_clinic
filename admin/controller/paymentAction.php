<?php
include('../model/AdminDashboard.php'); 
session_start();

$funObj = new Admin();

if (isset($_GET['action']) && isset($_GET['appointment_id'])) {
    $action = $_GET['action'];
    $appointmentId = intval($_GET['appointment_id']); // Sanitize input

    // Fetch patient details
    $patient = $funObj->get_patient_by_appointment_id($appointmentId);

    if ($patient) {
        $email = $patient['email'];
        $firstName = $patient['first_name'];
        $lastName = $patient['last_name'];
        $serviceName = $patient['service_name'];
        $appointmentTime = date("h:i A", strtotime($patient['appointment_time']));
        $appointmentDate = date("F j, Y", strtotime($patient['appointment_date']));

        if ($action === 'approve') {
            // Update payment status to 'Approved'
            $result = $funObj->updatePaymentStatus($appointmentId, 'Approved');
            if ($result) {
                // Prepare approval email
                $subject = "Payment Approved - Appointment ID {$appointmentId}";
                $message = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; }
                        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9; }
                        .header { text-align: center; background-color: #4CAF50; padding: 10px; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px; }
                        .content { padding: 20px; }
                        .footer { text-align: center; font-size: 0.9em; color: #555; }
                        .btn { padding: 10px 20px; color: white; background-color: #4CAF50; text-decoration: none; border-radius: 5px; display: inline-block; }
                        .btn:hover { background-color: #45a049; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Payment Approved</h1>
                        </div>
                        <div class='content'>
                            <p>Dear <strong>{$firstName} {$lastName}</strong>,</p>
                            <p>We are pleased to inform you that your payment for Appointment ID <strong>{$appointmentId}</strong> has been approved successfully.</p>
                            <p><strong>Appointment Details:</strong></p>
                            <ul>
                                <li><strong>Service:</strong> {$serviceName}</li>
                                <li><strong>Date:</strong> {$appointmentDate}</li>
                                <li><strong>Time:</strong> {$appointmentTime}</li>
                            </ul>
                            <p>If you have any questions or believe this is an error, please contact us at <a href='mailto:rosellesantander@rs-dentalclinic.com'>rosellesantander@rs-dentalclinic.com</a>.</p>
                            <p><a href='https://rs-dentalclinic.com/contact.php' class='btn'>Contact Support</a></p>
                        </div>
                        <div class='footer'>
                            <p>&copy; " . date('Y') . " Roselle Santander Dental Clinic. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";

                // Email headers
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: Roselle Santander Dental Clinic <rosellesantander@rs-dentalclinic.com>" . "\r\n";

                if (mail($email, $subject, $message, $headers)) {
                    $_SESSION['modal_message'] = "Payment approved and email sent to the patient.";
                } else {
                    $_SESSION['modal_message'] = "Payment approved, but email failed to send.";
                }
            } else {
                $_SESSION['modal_message'] = "Failed to approve payment.";
            }
        } elseif ($action === 'reject') {
            // Update payment status to 'Rejected'
            $result = $funObj->updatePaymentStatus($appointmentId, 'Rejected');
            if ($result) {
                // Prepare rejection email
                $subject = "Payment Rejected - Appointment ID {$appointmentId}";
                $message = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; }
                        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9; }
                        .header { text-align: center; background-color: #E53935; padding: 10px; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px; }
                        .content { padding: 20px; }
                        .footer { text-align: center; font-size: 0.9em; color: #555; }
                        .btn { padding: 10px 20px; color: white; background-color: #E53935; text-decoration: none; border-radius: 5px; display: inline-block; }
                        .btn:hover { background-color: #D32F2F; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Payment Rejected</h1>
                        </div>
                        <div class='content'>
                            <p>Dear <strong>{$firstName} {$lastName}</strong>,</p>
                            <p>We regret to inform you that your payment for Appointment ID <strong>{$appointmentId}</strong> has been rejected.</p>
                            <p><strong>Appointment Details:</strong></p>
                            <ul>
                                <li><strong>Service:</strong> {$serviceName}</li>
                                <li><strong>Date:</strong> {$appointmentDate}</li>
                                <li><strong>Time:</strong> {$appointmentTime}</li>
                            </ul>
                            <p>If you have any questions or believe this is an error, please contact us at <a href='mailto:rosellesantander@rs-dentalclinic.com'>rosellesantander@rs-dentalclinic.com</a>.</p>
                            <p><a href='https://rs-dentalclinic.com/contact.php' class='btn'>Contact Support</a></p>
                        </div>
                        <div class='footer'>
                            <p>&copy; " . date('Y') . " Roselle Santander Dental Clinic. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";

                // Email headers
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: Roselle Santander Dental Clinic <rosellesantander@rs-dentalclinic.com>" . "\r\n";

                if (mail($email, $subject, $message, $headers)) {
                    $_SESSION['modal_message'] = "Payment rejected and email sent to the patient.";
                } else {
                    $_SESSION['modal_message'] = "Payment rejected, but email failed to send.";
                }
            } else {
                $_SESSION['modal_message'] = "Failed to reject payment.";
            }
        } else {
            $_SESSION['modal_message'] = "Invalid action.";
        }
    } else {
        $_SESSION['modal_message'] = "Patient not found for the given appointment ID.";
    }

    // Redirect to payment list
    header("Location: ../payment.php");
    exit();
} else {
    $_SESSION['modal_message'] = "Invalid request.";
    header("Location: ../payment.php");
    exit();
}
?>
