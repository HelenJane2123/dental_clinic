<!DOCTYPE html>
<html lang="en">

<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: login.php'); // Redirect to login page if not logged in
        exit();
    }
  require_once ('model/user_dashboard.php');
  $appointment = new UserDashboard();
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
          <a class="nav-link" href="my_profile.php">
            <i class="mdi mdi-emoticon menu-icon"></i>
            <span class="menu-title">My Profile</span>
          </a>
        </li>
        <li class="nav-item sidebar-category">
          <p>Menu</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="mdi mdi-home menu-icon"></i>
            <span class="menu-title">Home Page</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="docs/documentation.html">
            <i class="mdi mdi-file-document-box-outline menu-icon"></i>
            <span class="menu-title">Services</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="docs/documentation.html">
            <i class="mdi mdi-phone menu-icon"></i>
            <span class="menu-title">Contact us</span>
          </a>
        </li>
      </ul>
    </nav>
    <!-- partial -->