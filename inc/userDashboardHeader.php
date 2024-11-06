<!DOCTYPE html>
<html lang="en">

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
  require_once ('model/userDashboard.php');
  $appointment = new UserDashboard();

    // Fetch appointments for the logged-in member
    $member_id = $_SESSION['member_id']; // Get the member ID from session
    $appointments = $appointment->get_all_appointments_by_member_id($member_id); // Fetch appointments

    if (isset($_GET['id'])) {
      $appointmentId = $_GET['id'];
      // Fetch appointment details
      $appointmentDetails = $appointment->view_appointment_by_id($appointmentId); // Ensure this method exists
    }

    $upcomingAppointments = $appointment->get_upcoming_appointments($member_id);
    $confirmedAppointmentsCount = $appointment->get_confirmed_appointments_count($member_id);
    $canceledAppointmentsCount = $appointment->get_canceled_appointments_count($member_id);
    $todaysAppointments = $appointment->get_todays_appointments($member_id);

    if (isset($_GET['cancel'])) {
      $appointmentId = (int) $_GET['cancel']; // Get the appointment ID from the URL
      $member_id = // Get the member ID based on your session or authentication method
  
      // Call the cancellation function
      $result = $appointment->automatic_cancel_appointment($member_id, $appointmentId);
      echo $result; // You can echo or log the result for debugging
  }

  // Fetch user details
  $userDetails = $appointment->get_user_profile($member_id);

  if ($userDetails) {
      $firstname = $userDetails['firstname'];
      $lastname = $userDetails['lastname'];
      $email = $userDetails['email'];
      $contact_number = $userDetails['contactnumber'];
      $gender = $userDetails['gender'];
      $remarks = $userDetails['remarks'];
      $profile_picture = $userDetails['profile_picture'];
  }

    // Get recent notifications (up to 3)
    $recentNotifications = $appointment->get_recent_notifications_by_member($member_id, 3);
    $notification_lists = $appointment->get_all_notifications($member_id);
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Roselle Santander's Dental Clinic - User Dashboard</title>
  <!-- base:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/dashboard/style.css">
  <link href='vendors/fullcalendar/packages/core/main.css' rel='stylesheet' />
  <link href='vendors/fullcalendar/packages/daygrid/main.css' rel='stylesheet' />
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

  <!-- endinject -->
  <link rel="shortcut icon" href="img/images/favicon.png" />
</head>
<body>
  <div class="container-scroller d-flex">
    <!-- partial:./partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>Navigation</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="appointment.php">
            <i class="mdi mdi-view-quilt menu-icon"></i>
            <span class="menu-title">Dashboard</span>
          </a>
        </li>
        <li class="nav-item sidebar-category">
          <p>Components</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="my_appointments.php">
            <i class="mdi mdi-grid-large menu-icon"></i>
            <span class="menu-title">My Appointments</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="notifications.php">
            <i class="mdi mdi-bell menu-icon"></i>
            <span class="menu-title">Notificatons</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="my_record.php">
            <i class="mdi mdi-notebook menu-icon"></i>
            <span class="menu-title">My Record</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="my_profile.php">
            <i class="mdi mdi-emoticon menu-icon"></i>
            <span class="menu-title">My Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="change_password.php">
            <i class="mdi mdi-lock menu-icon"></i>
            <span class="menu-title">Change Password</span>
          </a>
        </li>
        <li class="nav-item sidebar-category">
          <p>Menu</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="mdi mdi-home menu-icon"></i>
            <span class="menu-title">Back to Home Page</span>
          </a>
        </li>
      </ul>
    </nav>
    <!-- partial -->