<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: login.php'); // Redirect to login page if not logged in
        exit();
    }
    
    // Generate CSRF token if not already set
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    require_once ('model/AdminDashboard.php');
    $appointment_admin = new Admin();

    // Assuming session variables are set somewhere before this
    $member_id_admin = $_SESSION['member_id'];
    $user_id_admin = $_SESSION['user_id'];

    $get_user_acct_details = $appointment_admin->get_user_details_from_account($member_id_admin);

    $getConfirmedAppointments = $appointment_admin->get_confirmed_appointment_count();
    $getCanceledAppointments = $appointment_admin->get_canceled_appointment_count();
    $getAllBookings = $appointment_admin->get_bookings_count();
    $getAllPatient = $appointment_admin->get_patient_count();

    $notifications = $appointment_admin->get_notifications($user_id_admin);
    $notification_lists = $appointment_admin->get_all_notifications($user_id_admin);

    $get_patients = $appointment_admin->get_all_patients();

    $get_appointments = $appointment_admin->get_all_appointment_bookings();
    $get_recent_appointments = $appointment_admin->get_today_appointments();
    $get_doctors = $appointment_admin->get_doctor_details_with_account();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Roselle Santander Dental Clinic Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css">

    <link rel="stylesheet" href="vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="shortcut icon" href="images/favicon.svg" type="image/x-icon">
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="dashboard.php">Roselle Santander Dental Clinic</a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>