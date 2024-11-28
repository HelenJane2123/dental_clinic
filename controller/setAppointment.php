<?php
include('../model/userDashboard.php');
include('../lib/email_config.php');

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
    $doctor_id = $_POST['doctor_id'];
    $contactNumber = isset($_POST['contactNumber']) ? $_POST['contactNumber'] : null;
    $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : null;
  
    // Check if the appointment is for myself
    //if ($appointmentType === 'myself') {
        // Use session data for first and last name
        $firstname = $_POST['old_firstname'];
        $lastname = $_POST['old_lastname'];
        $emailAddress = $_POST['old_emailaddress'];
    // } elseif ($appointmentType === 'newPatient') {
    //     // Get data from the form
    //     $firstname = $_POST['firstname'];
    //     $lastname = $_POST['lastname'];
    //     $contactNumber = $_POST['contactnumber'];
    //     $emailAddress = $_POST['emailaddress'];
    // }

    // Insert appointment details
    $query = $user_dashboard->register_appointment($member_id, $firstname, $lastname, $contactNumber, $emailAddress, $appointmentType, $appointmentDate, $appointmentTime, $services, $notes, $patient_id,$user_admin_id, $doctor_id);
    
    if ($query) {
        $_SESSION['success'] = true;
        $_SESSION['display_message'] = 'New appointment booked successfully.';
        $_SESSION['message_type'] = "success";

        // Get doctor's email
        $get_doctor_email = $user_dashboard->get_doctor_details($doctor_id);
        // Send email notification to the doctor (or any relevant recipient)
        $to = $get_doctor_email['email']; // Replace with the doctor's email
        $get_services = $user_dashboard->get_dental_service_by_id($services); // Assuming this returns an array
        $service_sub_category = isset($get_services['sub_category']) ? $get_services['sub_category'] : 'N/A'; // Default value in case of missing data
        $service_sub_category = htmlspecialchars($service_sub_category);
        // Email to Doctor
        $doctorMessage = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    background-color: #f9f9f9;
                    color: #333;
                }
                .email-container {
                    max-width: 600px;
                    margin: 20px auto;
                    padding: 20px;
                    background: #ffffff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                }
                .header {
                    text-align: center;
                    background-color: #007bff;
                    color: white;
                    padding: 10px;
                    border-radius: 8px 8px 0 0;
                }
                .header h1 {
                    margin: 0;
                    font-size: 20px;
                }
                .content {
                    padding: 20px;
                }
                .content p {
                    margin: 10px 0;
                }
                .content ul {
                    padding-left: 20px;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #777;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <h1>New Appointment Booked</h1>
                </div>
                <div class='content'>
                    <p>A new appointment has been booked for you. Here are the details:</p>
                    <ul>
                        <li><strong>Patient Name:</strong> $firstname $lastname</li>
                        <li><strong>Date:</strong> $appointmentDate</li>
                        <li><strong>Time:</strong> $appointmentTime</li>
                       <li><strong>Services:</strong> $service_sub_category</li>
                        <li><strong>Contact Number:</strong> $contactNumber</li>
                        <li><strong>Email Address:</strong> $emailAddress</li>
                        <li><strong>Notes:</strong> $notes</li>
                    </ul>
                </div>
                <p>You may log in to the <a href='https://rs-dentalclinic.com/admin'>Roselle Santander Website</a> for more information and to manage your appointments.</p>
                <div class='footer'>
                    <p>&copy; 2024 Roselle Santander Dental Clinic. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Email to User
        $userMessage = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    background-color: #f9f9f9;
                    color: #333;
                }
                .email-container {
                    max-width: 600px;
                    margin: 20px auto;
                    padding: 20px;
                    background: #ffffff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                }
                .header {
                    text-align: center;
                    background-color: #28a745;
                    color: white;
                    padding: 10px;
                    border-radius: 8px 8px 0 0;
                }
                .header h1 {
                    margin: 0;
                    font-size: 20px;
                }
                .content {
                    padding: 20px;
                }
                .content p {
                    margin: 10px 0;
                }
                .content ul {
                    padding-left: 20px;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #777;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <h1>Appointment Confirmation</h1>
                </div>
                <div class='content'>
                    <p>Dear $firstname $lastname,</p>
                    <p>Your appointment has been successfully booked. Here are the details:</p>
                    <ul>
                        <li><strong>Date:</strong> $appointmentDate</li>
                        <li><strong>Time:</strong> $appointmentTime</li>
                        <li><strong>Services:</strong> $service_sub_category</li>
                        <li><strong>Contact Number:</strong> $contactNumber</li>
                        <li><strong>Email Address:</strong> $emailAddress</li>
                        <li><strong>Notes:</strong> $notes</li>
                        <li><strong>Assigned Doctor's Contact Email:</strong> $to</li>
                    </ul>
                    <p>If you need to reschedule or cancel, please contact us promptly or reach out to your assigned doctor at the email provided above.</p>
                    <p>Thank you for choosing Roselle Santander Dental Clinic!</p>
                    <p>You may log in to the <a href='https://rs-dentalclinic.com/login.php'>Roselle Santander Website</a> for more information and to manage your appointments.</p>
                </div>
               <div class='footer'>
                    <p>&copy; 2024 Roselle Santander Dental Clinic. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Headers for Doctor
        $doctorHeaders = "MIME-Version: 1.0" . "\r\n";
        $doctorHeaders .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $doctorHeaders .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n";

        // Headers for User
        $userHeaders = "MIME-Version: 1.0" . "\r\n";
        $userHeaders .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $userHeaders .= "From: rosellesantander@rs-dentalclinic.com" . "\r\n";

        // Send emails
        mail($to, "New Appointment Booking", $doctorMessage, $doctorHeaders); // Email to Doctor
        mail($emailAddress, "Appointment Confirmation", $userMessage, $userHeaders); // Email to User

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