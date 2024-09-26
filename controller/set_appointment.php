<?php
include('../model/user_dashboard.php');
session_start();

// initializing variables
$errors = array();
$user_dashboard = new UserDashboard();

if (isset($_POST['appointmentType'])) {
    // Get the form values
    $appointmentType = $_POST['appointmentType'];
    $appointmentDate = isset($_POST['appointmentDate']) ? $_POST['appointmentDate'] : null;
    $appointmentTime = isset($_POST['appointmentTime']) ? $_POST['appointmentTime'] : null;
    $services = $_POST['services'];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : null;
    $member_id = $_POST['member_id'];

    // Check if the appointment is for myself
    if ($appointmentType === 'myself') {
        // Use session data for first and last name
        $firstname = $_POST['old_firstname'];
        $lastname = $_POST['old_lastname'];
        // Use session data for contact number and email address
        $contactNumber = $_POST['contactnumber'];
        $emailAddress = $_POST['emailaddress'];
    } elseif ($appointmentType === 'newPatient') {
        // Get data from the form
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $contactNumber = $_POST['contactnumber'];
        $emailAddress = $_POST['emailaddress'];
    }

    // Insert appointment details
    $query = $user_dashboard->register_appointment($member_id, $firstname, $lastname, $contactNumber, $emailAddress, $appointmentType, $appointmentDate, $appointmentTime, $services, $notes);
    
    if ($query) {
        $_SESSION['success'] = true;
        $_SESSION['display_message'] = 'New appointment booked successfully.';
        $_SESSION['message_type'] = "success";
        header('Location: ../my_appointments.php');
        exit();
    } else {
        $_SESSION['display_message'] = "Error booking appointment. Please try again.";
        $_SESSION['message_type'] = "error";
        header('Location: ../my_appointments.php');
        exit();
    }
}
?>