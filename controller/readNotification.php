<?php
include('../model/userDashboard.php');
session_start();

// Initialize variables
$user_dashboard = new UserDashboard();

// Get notification ID from URL
if (isset($_GET['id'])) {
    $notificationId = intval($_GET['id']);

    // Update the notification status to 'read'
    $stmt = $user_dashboard->update_notification_read($notificationId);

    // Fetch the updated notification for confirmation or display
    $notification = $user_dashboard->get_notification_by_id($notificationId);

    // Store the notification message in session to pass it to the modal
    $_SESSION['notification_message'] = htmlspecialchars($notification['message']);
    $_SESSION['notification_id'] = $notificationId;

    // No need to redirect, just update status and return the content
    echo json_encode([
        'status' => 'success',
        'message' => $notification['message'],
        'id' => $notificationId
    ]);
} else {
    // If no ID is provided, return an error
    echo json_encode([
        'status' => 'error',
        'message' => 'Notification ID not provided.'
    ]);
}
?>
