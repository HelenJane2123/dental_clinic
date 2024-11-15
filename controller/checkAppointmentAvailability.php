<?php
// Include necessary files
include('../model/userDashboard.php');
session_start();

header('Content-Type: application/json');

// Initialize variables
$errors = array();
$user_dashboard = new UserDashboard();

// Log the incoming POST data (this is important for debugging)
error_log('Received POST Data: ' . file_get_contents('php://input'));  // Log raw POST data

// Get the JSON payload from the request body
$input = json_decode(file_get_contents('php://input'), true);

// Check if the necessary data is available in the decoded JSON
if (isset($input['appointmentDate']) && isset($input['appointmentTime']) && isset($input['doctor_id'])) {
    $appointmentDate = $input['appointmentDate'];
    $appointmentTime = $input['appointmentTime'];
    $doctorId = $input['doctor_id'];

    // Get the booked appointments
    $result = $user_dashboard->getBookedAppointments($appointmentDate, $appointmentTime, $doctorId);

    if ($result) {
        // Check if any rows are returned (i.e., the time slot is already booked)
        if ($result->num_rows > 0) {
            echo json_encode(["available" => false]);  // Time slot is taken
        } else {
            echo json_encode(["available" => true]);   // Time slot is available
        }
    } else {
        // Return error if the query failed
        echo json_encode(["error" => "Error retrieving data. Please try again later."]);
    }
} else {
    // Return error if necessary data is not present
    echo json_encode(["error" => "Missing required data.", "data" => $input]); 
}
?>
