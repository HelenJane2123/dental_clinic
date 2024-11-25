<?php
include_once('lib/db_connect.php'); // Database connection
include_once('lib/email_config.php'); 

function handleUnpaidAppointments() {
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
        WHERE pp.id IS NULL AND DATE(a.appointment_date) = CURDATE() - INTERVAL 2 DAYS
    ";

    $result = $db->query($query);
    if ($result === false) {
        error_log("Error in query: " . $db->error);
        return;
    }

    // Process each appointment without proof of payment
    while ($row = $result->fetch_assoc()) {
        $appointmentId = $row['appointment_id'];
        $patientEmail = $row['patient_email'];
        $patientName = htmlspecialchars($row['patient_first_name']) . ' ' . htmlspecialchars($row['patient_last_name']);
        $appointmentDate = htmlspecialchars($row['appointment_date']);

        // Send payment reminder notification
        $message = buildPaymentReminderEmail($patientName, $appointmentDate);
        sendNotification($patientEmail, "Proof of Payment Required", $message);

        // Cancel the appointment in the database
        cancelAppointment($appointmentId);
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
                <p>We noticed that no proof of payment has been uploaded for your appointment scheduled on <strong>{$appointmentDate}</strong>. As a result, your appointment has been automatically cancelled.</p>
                <p>If you wish to rebook, please upload your proof of payment during the booking process.</p>
                <p>For assistance, contact our support team.</p>
                <p>Thank you for your understanding.</p>
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

function cancelAppointment($appointmentId) {
    global $db;

    // Update the appointment status to 'cancelled'
    $updateQuery = "
        UPDATE appointments
        SET status = 'Canceled'
        WHERE id = ?
    ";

    $stmt = $db->prepare($updateQuery);
    if ($stmt) {
        $stmt->bind_param("i", $appointmentId);
        if (!$stmt->execute()) {
            error_log("Failed to cancel appointment ID: " . $appointmentId . " Error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("Failed to prepare statement for cancelling appointment ID: " . $appointmentId);
    }
}

// Execute the function
handleUnpaidAppointments();
?>
