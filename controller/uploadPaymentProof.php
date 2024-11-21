<?php
include('../model/userDashboard.php');
include('../lib/email_configuration.php');

session_start();

// Initialize variables
$errors = array();
$user_dashboard = new UserDashboard();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['paymentReceipt'])) {
    // Retrieve data from the form
    $appointmentId = isset($_POST['appointmentID']) ? $_POST['appointmentID'] : null;
    $memberId = $_POST['patientId']; // Ensure member ID is passed from the form
    $file = $_FILES['paymentReceipt'];
    $doctor_id = $_POST['doctor_id'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    $status = "Pending"; // Default status for newly uploaded proof of payment

    // Log the patient ID
    error_log("Patient ID: " . $memberId);

    // Validate file upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading the file.";
    } else {
        // Validate file type and size (e.g., max 2MB, only images/pdf)
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "Invalid file type. Only JPG, PNG, and PDF files are allowed.";
        }
        if ($file['size'] > $maxSize) {
            $errors[] = "File size exceeds 2MB limit.";
        }
    }

    if (empty($errors)) {
        // Define the base upload directory
        $baseUploadDir = "../public/payment/";

        // Log the base directory
        error_log("Base directory: " . $baseUploadDir);

        if (!is_dir($baseUploadDir)) {
            mkdir($baseUploadDir, 0777, true); // Create the base directory if it doesn't exist
            error_log("Base directory created: " . $baseUploadDir);
        }

        // Create a subdirectory for the member ID
        $memberDir = $baseUploadDir . $memberId . '/' .$appointmentId .'/';
        if (!is_dir($memberDir)) {
            error_log("Creating member directory: " . $memberDir);
            mkdir($memberDir, 0777, true); // Create the member directory if it doesn't exist
        } else {
            error_log("Directory already exists: " . $memberDir);
        }

        // Generate a unique file name
        $fileName = uniqid() . "_" . basename($file['name']);
        $filePath = $memberDir . $fileName;

        // Log the file path
        error_log("File path: " . $filePath);

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Log the upload success
            error_log("File uploaded to: " . $filePath);

            $get_doctor_email = $user_dashboard->get_doctor_details($doctor_id);
            $doctorEmail = $get_doctor_email['email']; // Replace with the doctor's email
            $doctorName = $get_doctor_email['first_name'].' '.$get_doctor_email['last_name'];
            // Save payment proof details to the database
            $result = $user_dashboard->savePaymentProof($appointmentId, $memberId, $fileName, $remarks, $status);

            if ($result) {
                $subject = "Payment Proof Uploaded for Appointment #$appointmentId";
                $message = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .content { padding: 20px; }
                        .content h2 { color: #007bff; }
                        .content p { font-size: 14px; }
                        .footer { margin-top: 20px; font-size: 12px; color: #555; }
                    </style>
                </head>
                <body>
                    <div class='content'>
                        <h2>Payment Proof Uploaded</h2>
                        <p>Dear Dr. $doctorName,</p>
                        <p>A patient has uploaded proof of payment for their appointment. Here are the details:</p>
                        <ul>
                            <li><strong>Appointment ID:</strong> $appointmentId</li>
                            <li><strong>Patient ID:</strong> $memberId</li>
                            <li><strong>Remarks:</strong> $remarks</li>
                        </ul>
                        <p>You may log in to your dashboard to review the payment proof and confirm the status.</p>
                        <p>Thank you.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2024 Roselle Santander Dental Clinic. All rights reserved.</p>
                    </div>
                </body>
                </html>";

                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                $headers .= "From: noreply@rs-dentalclinic.com\r\n"; // Use a no-reply email address

                mail($doctorEmail, $subject, $message, $headers);

                $_SESSION['display_message'] = "Payment proof uploaded successfully. Awaiting confirmation.";
                $_SESSION['message_type'] = "success";

                header("Location: ../payment.php");
                exit;

            } else {
                $errors[] = "Failed to save payment proof to the database.";
                header("Location: ../payment.php");
                exit;
            }
        } else {
            $errors[] = "Failed to move uploaded file.";
        }
    }

    // Handle errors
    if (!empty($errors)) {
        $_SESSION['display_message'] = implode("<br>", $errors);
        $_SESSION['message_type'] = "danger";
    }

}

?>
