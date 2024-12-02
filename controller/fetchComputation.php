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

        // Display payment breakdown with inline styles
        echo "<div class='payment-breakdown' style='border-radius: 5px; margin-bottom: 20px;'>
                <h4 style='color: #495057; margin-bottom: 10px;'>Payment Breakdown</h4>
                <p><strong>Total Amount:</strong> <span style='font-size: 24px; font-weight: bold; color: #28a745;'>$totalPrice</span></p>
                <p><strong>Down Payment:</strong> <span style='font-size: 18px; font-weight: bold; color: #ffc107;'>$downPayment</span></p>
                <p><strong>Remaining Balance:</strong> <span style='font-size: 18px; font-weight: bold; color: #dc3545;'>$remainingBalance</span></p>
            </div>";

        // Display services in a table
        if (!empty($computation['services'])) {
            echo "<div class='services-table' style='margin-top: 20px;'>
                    <h5>Services:</h5>
                    <table style='border-collapse: collapse; width: 100%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);' class='table table-striped table-bordered'>
                        <thead style='background-color: #e9ecef;'>
                            <tr>
                                <th style='padding: 12px; text-align: left;'>Service</th>
                                <th style='padding: 12px; text-align: left;'>Price</th>
                            </tr>
                        </thead>
                        <tbody>";

            foreach ($computation['services'] as $service) {
                // Removed dollar sign from the price display
                echo "<tr style='background-color: #f9f9f9;'>
                        <td style='padding: 12px;'>{$service['sub_category']}</td>
                        <td style='padding: 12px;'>{$service['price']}</td>
                    </tr>";
            }

            echo "</tbody></table>
                </div>";
        } else {
            echo "<p class='text-muted'>No services found for this appointment.</p>";
        }
    } else {
        echo "<p>Error: Appointment not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
