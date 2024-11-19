<?php
include('../model/userDashboard.php');
session_start();

// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Initialize the UserDashboard class
$user_dashboard = new UserDashboard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    // Sanitize input
    $appointment_id = filter_input(INPUT_POST, 'appointment_id', FILTER_SANITIZE_NUMBER_INT);

    if (!$appointment_id) {
        echo "<p>Invalid appointment ID.</p>";
        exit;
    }

    // Fetch computation details
    $computation = $user_dashboard->getSelectedServicesAndPaymentDetails($appointment_id);

    if ($computation) {
        // Extract data
        $totalPrice = $computation['total_price'];
        $downPayment = $computation['down_payment'];
        $remainingBalance = $computation['remaining_balance'];

        // Display payment breakdown
        echo "<p><strong>Total Amount:</strong> $totalPrice</p>";
        echo "<p><strong>Down Payment (20%):</strong> $downPayment</p>";
        echo "<p><strong>Remaining Balance:</strong> $remainingBalance</p>";

        // Display services in a table
        if (!empty($computation['services'])) {
            echo "<h5>Services:</h5>";
            echo "<table class='table table-bordered' id='computationDetails'>
                    <thead>
                        <tr><th>Service</th><th>Price</th></tr>
                    </thead>
                    <tbody>";

            foreach ($computation['services'] as $service) {
                echo "<tr><td>{$service['sub_category']}</td><td>{$service['price']}</td></tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No services found for this appointment.</p>";
        }
    } else {
        echo "<p>Error: Appointment not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
