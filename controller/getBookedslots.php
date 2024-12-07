<?php
include('../model/userDashboard.php');
include('../lib/email_config.php');

session_start();

// Initialize variables
$errors = [];
$user_dashboard = new UserDashboard();

header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the appointment date, either from add or edit
    $appointmentDate = $_POST['appointmentDate'] ?? $_POST['edit_appointment_date'];
    $doctorId = $_POST['doctor_id'] ?? null;

    // Validate parameters
    if (!$appointmentDate || !$doctorId) {
        echo json_encode(['success' => false, 'error' => 'Invalid parameters provided.']);
        exit;
    }

    // Fetch booked slots for the doctor on the selected date
    try {
        $bookedSlots = $user_dashboard->get_booked_appointments($doctorId, $appointmentDate);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to fetch booked slots.']);
        exit;
    }

    // Define all possible time slots
    $allTimeSlots = [
        "09:00:00" => "9:00 AM",
        "10:00:00" => "10:00 AM",
        "11:00:00" => "11:00 AM",
        "12:00:00" => "12:00 PM",
        "13:00:00" => "1:00 PM",
        "14:00:00" => "2:00 PM",
        "15:00:00" => "3:00 PM",
        "16:00:00" => "4:00 PM",
    ];

    // Send response with available slots and booked slots
    echo json_encode([
        'success' => true,
        'allTimeSlots' => $allTimeSlots,
        'bookedSlots' => $bookedSlots,  // List of booked slots
    ]);
    exit;
}

// Invalid request method
echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
exit;
?>
