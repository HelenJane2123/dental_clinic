<?php
include_once('lib/db_connect.php'); // Database connection
include_once('lib/email_config.php'); 

function sendPaymentReminderNotifications() {
    global $db;

    // Query appointments booked yesterday without proof of payment
    $query = "
        SELECT 
            a.id AS appointment_id,
            a.patient_id, 
            p.first_name AS patient_first_name,
            p.last_name AS patient_last_name,
            p.email AS patient_email,
            a.appointment_date 
        FROM appointments a
        LEFT JOIN proof_of_payment pp ON a.id = pp.appointment_id
        JOIN patients p ON a.patient_id = p.patient_id
        WHERE pp.id IS NULL AND DATE(a.appointment_date) = CURDATE() - INTERVAL 1 DAY
    ";

    $result = $db->query($query);
    if ($result === false) {
        error_log("Error in query: " . $db->error);
        return;
    }

    // Process each appointment without proof of payment
    while ($row = $result->fetch_assoc()) {
        $patientEmail = $row['patient_email'];
        $patientName = htmlspecialchars($row['patient_first_name']) . ' ' . htmlspecialchars($row['patient_last_name']);
        $appointmentDate = htmlspecialchars($row['appointment_date']);

        // Build the notification message
        $message = buildPaymentReminderEmail($patientName, $appointmentDate);
        sendNotification($patientEmail, "Proof of Payment Required", $message);
    }
}

function buildPaymentReminderEmail($name, $appointmentDate) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; }
            .email-container { max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; }
            .header { text-align: center; background: #f44336; color: white; padding: 10px 0; }
            .content { padding: 20px; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #555; }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h2>Payment Reminder</h2>
            </div>
            <div class='content'>
                <p>Dear <strong>{$name}</strong>,</p>
                <p>We noticed that no proof of payment has been uploaded for your appointment scheduled on <strong>{$appointmentDate}</strong>. Please upload your proof of payment by today to confirm your booking.</p>
                <p>Failure to upload proof of payment may result in the cancellation of your appointment.</p>
                <p>For assistance, contact our support team.</p>
                <p>Thank you for your cooperation.</p>
                <p>Best regards,<br>Roselle Santander Dental Clinic Team</p>
            </div>
            <div class='footer'>
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>";
}

function sendNotification($email, $subject, $message) {
    $headers = "From: no-reply@rs-dentalclinic.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (!mail($email, $subject, $message, $headers)) {
        error_log("Failed to send email to: " . $email . " with subject: " . $subject);
    }
}

// Execute the function
sendPaymentReminderNotifications();
?>
