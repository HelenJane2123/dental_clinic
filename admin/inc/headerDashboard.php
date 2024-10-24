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

    $getConfirmedAppointments = $appointment_admin->get_confirmed_appointment_count();
    $getCanceledAppointments = $appointment_admin->get_canceled_appointment_count();
    $getAllBookings = $appointment_admin->get_bookings_count();
    $getAllPatient = $appointment_admin->get_patient_count();

     // Sample notifications array - replace this with a real database query
     $notifications = [
        [
            'message' => 'Your appointment is confirmed.',
            'created_at' => '2024-10-20 10:00:00'
        ],
        [
            'message' => 'A new patient has registered.',
            'created_at' => '2024-10-19 15:30:00'
        ],
        [
            'message' => 'Your profile has been updated successfully.',
            'created_at' => '2024-10-18 08:20:00'
        ]
    ];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mazer Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">

    <link rel="stylesheet" href="vendors/iconly/bold.css">

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
                            <a href="index.html">Roselle Santander's Dental Clinic</a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>