<?php
    session_start();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");

    // If not logged in, redirect to login page
    if (!isset($_SESSION['success']) || $_SESSION['success'] !== true || ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'super_admin')) {
        header('Location: ../index.php');
        exit();
    }

    // Update last activity for timeout
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        session_unset();
        session_destroy();
        header('Location: ../index.php');
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time();
    
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

    if ($_SESSION['user_type'] == 'super_admin') {
        $get_appointments = $appointment_admin->get_all_appointment_bookings();
        $get_patients = $appointment_admin->get_all_patients();
        $get_doctors = $appointment_admin->get_doctor_details_with_account();
        $get_dental_services = $appointment_admin->get_dental_services();

    }
    else {
        $get_appointments = $appointment_admin->get_all_appointment_bookings_per_doctor($user_id_admin);
        $get_patients = $appointment_admin->get_all_patients_per_doctor($user_id_admin);
    }
    $get_recent_appointments = $appointment_admin->get_today_appointments();

     // Fetch user details
    $userDetails = $appointment_admin->get_user_profile($member_id_admin);

    if ($userDetails) {
        $firstname = $userDetails['firstname'];
        $lastname = $userDetails['lastname'];
        $email = $userDetails['email'];
        $contact_number = $userDetails['contactnumber'];
        $gender = $userDetails['gender'];
        $address = $userDetails['address'];
        $remarks = $userDetails['remarks'];
        $profile_picture = $userDetails['profile_picture'];
    }

    //get patients monthly count
    $monthlyPatientCounts =  $appointment_admin->getMonthlyPatientCounts();
    $get_all_patients = $appointment_admin->get_all_patients_without_doctor();

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
    <!-- Add Font Awesome (or use Bootstrap Icons if preferred) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <link rel="stylesheet" href="vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="shortcut icon" href="images/favicon.svg" type="image/x-icon">

    <style>
        /* Print-specific styles */
        @media print {
            /* Hide all elements except #printable-content */
            body * {
                visibility: hidden;
            }
            #printable-content, #printable-content * {
                visibility: visible;
            }
            #printable-content {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            /* Remove unnecessary elements */
            .sidebar, #sidebarMenu, .header, .breadcrumb, .btn {
                display: none !important;
            }

            /* Ensure proper page size and margins */
            @page {
                size: letter; /* 8.5 x 11 inches */
                margin: 1in; /* Normal 1-inch margin */
            }

            /* Clean print layout */
            body {
                background: #fff;
                margin: 0;
            }

            /* Optional: Adjust font size and styles for printing */
            #printable-content {
                font-size: 12pt;
                line-height: 1.5;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="dashboard.php"><img src="images/logo/logo.png" alt="Logo" style="height:113px;"></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>