<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dental Clinic</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-7/assets/css/registration-7.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Custom style for the dropdown menu */
        .dropdown-menu {
            background-color: #003366; /* Dark blue color */
            border: none; /* Optional: removes border */
        }
        .dropdown-item {
            color: white; /* White text for dropdown items */
        }
        .dropdown-item:hover {
            background-color: #00509E; /* Lighter blue on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="navigation">
                <nav class="menu">
                    <a href="#" class="logo">
                        <img src="images/logo.png" alt="Dental Clinic Logo">
                    </a>
                    <div class="menu-items">
                        <a href="index.php">HOME</a>
                        <a href="about-us.php">ABOUT</a>
                        <a href="services.php">SERVICES</a>
                        <a href="contact-us.php">CONTACT</a>
                    </div>
                    
                    <?php
                        session_start();
                        if (isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) {
                            // User is logged in, display their name and dropdown
                            echo '<div class="nav-profile dropdown">';
                            echo '<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">';
                            echo '<span class="navbar-text">Welcome, ' . htmlspecialchars($_SESSION['firstname']) . ' ' . htmlspecialchars($_SESSION['lastname']) . '!</span>';
                            echo '</a>';
                            echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">';
                            echo '<a class="dropdown-item" href="appointment.php">Go to Dashboard</a>'; // Link to user dashboard
                            echo '<a class="dropdown-item" href="logout.php">Logout</a>'; // Logout option
                            echo '</div>';
                            echo '</div>';
                        } else {
                            // User is not logged in, display the login button
                            echo '<button class="btn_Login" id="login_button">LOGIN <i class="fa-solid fa-circle-user fa-lg"></i></button>';
                        }
                    ?>
                </nav>
            </div>
        </header>