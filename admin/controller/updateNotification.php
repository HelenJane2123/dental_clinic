<?php
include('../model/AdminDashboard.php'); 
session_start();   
// initializing variables
$errors = array(); 
$funObj = new Admin(); 

if (isset($_GET['notification_id'])) {
    $notificationId = intval($_GET['notification_id']); // Use GET instead of POST
    
    // Call the method to update the notification
    if ($funObj->update_notification($notificationId)) {
        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Notification updated successfully.']);
    } else {
        // Return error response
        echo json_encode(['status' => 'error', 'message' => 'Error updating notification.']);
    }
} else {
    // Return an error if notification_id is not set
    echo json_encode(['status' => 'error', 'message' => 'Notification ID is missing.']);
}
?>