<?php
include('../model/userDashboard.php');
include('../lib/email_configuration.php');

session_start();

// initializing variables
$errors = array();
$user_dashboard = new UserDashboard();

if (isset($_POST['appointmentType'])) {
    // Get the form values
    $user_admin_id = $_POST['user_admin_id'];
    $appointmentType = $_POST['appointmentType'];
    $appointmentDate = isset($_POST['appointmentDate']) ? $_POST['appointmentDate'] : null;
    $appointmentTime = isset($_POST['appointmentTime']) ? $_POST['appointmentTime'] : null;
    $services = $_POST['services'];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : null;
    $member_id = $_POST['member_id'];
    $contactNumber = isset($_POST['contactNumber']) ? $_POST['contactNumber'] : null;
    $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : null;
  
    // Check if the appointment is for myself
    if ($appointmentType === 'myself') {
        // Use session data for first and last name
        $firstname = $_POST['old_firstname'];
        $lastname = $_POST['old_lastname'];
        $emailAddress = $_POST['old_emailaddress'];
    } elseif ($appointmentType === 'newPatient') {
        // Get data from the form
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $contactNumber = $_POST['contactnumber'];
        $emailAddress = $_POST['emailaddress'];
    }

    // Insert appointment details
    $query = $user_dashboard->register_appointment($member_id, $firstname, $lastname, $contactNumber, $emailAddress, $appointmentType, $appointmentDate, $appointmentTime, $services, $notes, $patient_id,$user_admin_id);
    
    if ($query) {
        $_SESSION['success'] = true;
        $_SESSION['display_message'] = 'New appointment booked successfully.';
        $_SESSION['message_type'] = "success";

        // Get doctor's email
        $get_doctor_email = $user_dashboard->get_doctor_details($user_admin_id);
        // Send email notification to the doctor (or any relevant recipient)
        $to = $get_doctor_email['email']; // Replace with the doctor's email
        $subject = "New Appointment Booking";
        $message = "
        <html>
        <head>
            <title>New Appointment</title>
        </head>
        <body>
            <p>A new appointment has been booked:</p>
            <ul>
                <li><strong>Patient:</strong> $firstname $lastname</li>
                <li><strong>Appointment Date:</strong> $appointmentDate</li>
                <li><strong>Appointment Time:</strong> $appointmentTime</li>
                <li><strong>Services:</strong> $services</li>
                <li><strong>Contact Number:</strong> $contactNumber</li>
                <li><strong>Email Address:</strong> $emailAddress</li>
                <li><strong>Notes:</strong> $notes</li>
            </ul>
        </body>
        </html>";

        // Headers for email format (HTML)
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n"; // Replace with your domain's email


        // Send email confirmation to the user
        $toUser = $emailAddress; // User's email address
        $subjectUser = "Appointment Sent for Confirmation";
        $messageUser = "
        <html>
        <head>
            <title>Appointment Sent for Confirmation</title>
        </head>
        <body>
            <p>Dear $firstname $lastname,</p>
            <p>Your appointment has been successfully booked. Below are the details:</p>
            <ul>
                <li><strong>Appointment Date:</strong> $appointmentDate</li>
                <li><strong>Appointment Time:</strong> $appointmentTime</li>
                <li><strong>Services:</strong> $services</li>
                <li><strong>Contact Number:</strong> $contactNumber</li>
                <li><strong>Email Address:</strong> $emailAddress</li>
                <li><strong>Notes:</strong> $notes</li>
            </ul>
            <p>If you need to make any changes to your appointment, please contact us as soon as possible.</p>
            <p>Thank you for choosing our services!</p>
        </body>
        </html>";

        $headersUser = "MIME-Version: 1.0" . "\r\n";
        $headersUser .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $headersUser .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n"; // Replace with your domain's email

        // Send email to user
        mail($toUser, $subjectUser, $messageUser, $headersUser);

        // Send email
        mail($to, $subject, $message, $headers);

        // Redirect to my_appointments.php after booking
        header('Location: ../payment.php');
        exit();
    } else {
        $_SESSION['display_message'] = "Error booking appointment. Please try again.";
        $_SESSION['message_type'] = "danger";
        header('Location: ../my_appointments.php');
        exit();
    }
}
?>