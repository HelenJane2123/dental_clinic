<?php
include('../model/AdminDashboard.php'); 
session_start(); 

$funObj = new Admin(); 

if (isset($_GET['action']) && isset($_GET['appointment_id'])) {
    $action = $_GET['action'];
    $appointmentId = $_GET['appointment_id'];

    if ($action === 'approve') {
        $result = $funObj->updatePaymentStatus($appointmentId, 'Approved');
        if ($result) {
            $_SESSION['modal_message'] = "Payment approved successfully!";
        } else {
            $_SESSION['modal_message'] = "Failed to approve payment.";
        }
    } elseif ($action === 'reject') {
        $result = $funObj->updatePaymentStatus($appointmentId, 'Rejected');
        if ($result) {
            $_SESSION['modal_message'] = "Payment rejected successfully!";
        } else {
            $_SESSION['modal_message'] = "Failed to reject payment.";
        }
    } else {
        $_SESSION['modal_message'] = "Invalid action.";
    }

    // Redirect to payment.php
    header("Location: ../payment.php");
    exit();
} else {
    $_SESSION['modal_message'] = "Invalid request.";
    header("Location: ../payment.php");
    exit();
}
?>
