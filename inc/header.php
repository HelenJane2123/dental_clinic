<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Roselle Santander Dental Clinic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .parsley-errors-list {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
  </head>
  <?php
    session_start();
  ?>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">Roselle Santander <span>Dental Clinic</span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="oi oi-menu"></span> Menu
    </button>

    <div class="collapse navbar-collapse" id="ftco-nav">
      <ul class="navbar-nav ml-auto align-items-center"> <!-- align-items-center ensures the items are aligned properly -->
        <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
        <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
        <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
        <li class="nav-item cta">
          <a href="signup.php" class="nav-link">
            <span>Make an Appointment</span>
          </a>
        </li>
      </ul>

      <!-- User Dropdown -->
      <div class="ml-lg-3 d-flex align-items-center"> <!-- Add margin-left and center align -->
        <?php
          if (isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) {
              // User is logged in, display their name and dropdown
              echo '<div class="dropdown">';
              echo '<a class="nav-link dropdown-toggle text-primary" href="#" id="profileDropdown" data-toggle="dropdown">';
              echo 'Welcome, ' . htmlspecialchars($_SESSION['firstname']) . ' ' . htmlspecialchars($_SESSION['lastname']) . '';
              echo '</a>';
              echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">';
              echo '<a class="dropdown-item" href="appointment.php">Go to Dashboard</a>';
              echo '<a class="dropdown-item" href="logout.php">Logout</a>';
              echo '</div>';
              echo '</div>';
          } else {
              // User is not logged in, display the login button
              echo '<button class="btn btn-primary ml-3" id="login_button">';
              echo '<span>Login </span><i class="fa fa-user"></i>';
              echo '</button>';
          }
        ?>
      </div>
    </div>
  </div>
</nav>