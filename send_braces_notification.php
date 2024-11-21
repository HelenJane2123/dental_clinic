<?php
include_once('lib/db_connect.php'); // Database connection
include_once('lib/email_config.php'); 

function sendBracesNotifications() {
    global $db;

    // Query for completed braces appointments
    $query = "
        SELECT 
            a.patient_id, 
            p.first_name as patient_first_name,
            p.last_name as patient_last_name,
            p.email AS patient_email, 
            d.email AS doctor_email, 
            d.first_name AS doctor_first_name, 
            d.last_name AS doctor_last_name, 
            a.completed_at 
        FROM appointments a
        JOIN patients p ON a.patient_id = p.patient_id
        JOIN doctors d ON p.assigned_doctor = d.account_id
        JOIN dental_services ds ON a.services = ds.id
        WHERE ds.sub_category LIKE '%braces%' 
        AND a.status = 'Completed' 
        AND (DATE(a.completed_at) = CURDATE() - INTERVAL 1 MONTH - INTERVAL 1 DAY
         OR DATE(a.completed_at) = CURDATE() - INTERVAL 1 MONTH - INTERVAL 2 DAY)
    ";

    $result = $db->query($query);
    if ($result === false) {
        error_log("Error in query: " . $db->error);
    }

    // Process each appointment and send notifications
    while ($row = $result->fetch_assoc()) {
        $patientEmail = $row['patient_email'];
        $doctorEmail = $row['doctor_email'];
        $patientName = htmlspecialchars($row['patient_first_name']).' '.htmlspecialchars($row['patient_last_name']);
        $doctorName = htmlspecialchars($row['doctor_first_name']).' '.htmlspecialchars($row['doctor_last_name']);

        // Links for login
        $patientLink = "https://rs-dentalclinic.com/login.php";
        $doctorLink = "https://rs-dentalclinic.com/admin";

        // Message for the patient
        $patientMessage = buildPatientEmail($patientName, $patientLink);
        sendNotification($patientEmail, "Braces Adjustment Reminder", $patientMessage);

        // Message for the doctor
        $doctorMessage = buildDoctorEmail($doctorName, $patientName, $doctorLink);
        sendNotification($doctorEmail, "Braces Adjustment Notification", $doctorMessage);
    }
}

function buildPatientEmail($name, $link) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; }
            .email-container { max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; }
            .header { text-align: center; background: #007bff; color: white; padding: 10px 0; }
            .content { padding: 20px; }
            .cta-button { text-align: center; margin: 20px 0; }
            .cta-button a { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #555; }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h2>Braces Adjustment Reminder</h2>
            </div>
            <div class='content'>
                <p>Dear <strong>{$name}</strong>,</p>
                <p>Your braces adjustment is coming up soon! Please log in to your account to book your next appointment for a hassle-free adjustment experience.</p>
                <div class='cta-button'>
                    <a href='{$link}' target='_blank'>Book an Appointment</a>
                </div>
                <p>We look forward to seeing you soon!</p>
                <p>Warm regards,<br>Roselle Santander Dental Clinic Team</p>
            </div>
            <div class='footer'>
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>";
}

function buildDoctorEmail($name, $patient_name, $link) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; }
            .email-container { max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; }
            .header { text-align: center; background: #17a2b8; color: white; padding: 10px 0; }
            .content { padding: 20px; }
            .cta-button { text-align: center; margin: 20px 0; }
            .cta-button a { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #555; }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h2>Braces Adjustment Notification</h2>
            </div>
            <div class='content'>
                <p>Dear Dr. <strong>{$name}</strong>,</p>
                <p>A patient's braces adjustment is coming up soon. Please log in to the system to check their status and provide any necessary follow-up.</p>
                <p>Patient Name: {$patient_name}</p>
                <div class='cta-button'>
                    <a href='{$link}' target='_blank'>View Patient Details</a>
                </div>
                <p>Thank you for your continued care!</p>
                <p>Warm regards,<br>Roselle Santander Dental Clinic Team</p>
            </div>
            <div class='footer'>
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>";
}

function sendNotification($email, $subject, $message) {
    // Example email logic with error logging
    $headers = "From: no-reply@rs-dentalclinic.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n"; // Ensure HTML emails are supported
    
    // Try sending email and log errors
    $mailSuccess = mail($email, $subject, $message, $headers);
    
    if (!$mailSuccess) {
        error_log("Failed to send email to: " . $email . " with subject: " . $subject);
    }
}

// Execute the function
sendBracesNotifications();
?>
