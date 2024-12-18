<!DOCTYPE html>
<html lang="en">

<?php
    session_start();
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");

    if (!isset($_SESSION['success']) || $_SESSION['success'] !== true || $_SESSION['user_type'] !== 'patient') {
        session_unset();
        session_destroy();
        header('Location: ../login.php');
        exit();
    }

    // Update last activity for timeout
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        session_unset();
        session_destroy();
        header('Location: ../login.php');
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time();

    // Generate CSRF token if not already set
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  require_once ('model/userDashboard.php');
  $appointment = new UserDashboard();

    // Fetch appointments for the logged-in member
    $member_id = $_SESSION['member_id']; // Get the member ID from session
    $appointments = $appointment->get_all_appointments_by_member_id($member_id); // Fetch appointments

    $user_id_admin = $appointment->get_doctor_admin();
    $get_doctor_id = $appointment->get_doctor_details_assigned_patient($member_id);
    
  

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

    //check if member id is already in patient record
    $patientCount = $appointment->patient_record_existence($member_id);
    $appointment_id = $appointment->getAppointmentId($member_id);

    $dental_services = $appointment->get_dental_services();
     
    // // Get the selected appointment date from the POST or other source
    // $selectedDate = $_POST['appointmentDate'] ?? date('Y-m-d'); // Get current date in 'YYYY-MM-DD' format

    // // Fetch the booked appointments for the doctor on the selected appointment date
    // $get_booked_appointment = $appointment->get_booked_appointments($get_doctor_id['account_id'], $selectedDate);
    // // Define all possible time slots
    // $allTimeSlots = [
    //   "09:00:00" => "9:00 AM",
    //   "10:00:00" => "10:00 AM",
    //   "11:00:00" => "11:00 AM",
    //   "12:00:00" => "12:00 PM",
    //   "13:00:00" => "1:00 PM",
    //   "14:00:00" => "2:00 PM",
    //   "15:00:00" => "3:00 PM",
    //   "16:00:00" => "4:00 PM",
    // ];
    // // Extract the booked times into an array for easier comparison
    // $bookedSlots = $get_booked_appointment; // Directly use the returned array
    $dental_records = $appointment->get_dental_records($member_id);

    
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.css">
  <!-- Include Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" rel="stylesheet">

  <!-- endinject -->
  <link rel="shortcut icon" href="img/logo.png" />
  <script>
     window.onload = function () {
        // Push the current state to the history stack
        history.pushState(null, null, location.href);

        // Detect back/forward navigation
        window.onpopstate = function () {
            // Send AJAX request to destroy session
            fetch('destroy_session.php', {
                method: 'POST'
            }).then(response => {
                // Redirect to login page after session destruction
                window.location.href = '../login.php';
            });
        };
    };
  </script>
</head>
<body>
  <div class="container-scroller d-flex">
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <a class="navbar-brand brand-logo" href="index.php"><img src="img/logo.png" alt="logo" style="height: 105px;margin-left: 8px;"/></a>
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
        <!-- <li class="nav-item">
          <a class="nav-link" href="payment.php">
            <i class="mdi mdi-note menu-icon"></i>
            <span class="menu-title">Payment</span>
          </a>
        </li> -->
        <li class="nav-item sidebar-category">
          <p>Menu</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="help.php">
            <i class="mdi mdi-book menu-icon"></i>
            <span class="menu-title">Help</span>
          </a>
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