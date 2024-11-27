<!-- partial:./partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-menu"></span>
      </button>
      <div class="navbar-brand-wrapper">
        <!-- <a class="navbar-brand brand-logo" href="index.html"><img src="img/images/logo.svg" alt="logo"/></a> -->
        <!-- <a class="navbar-brand brand-logo-mini" href="index.html"><img src="img/images/logo-mini.svg" alt="logo"/></a> -->
      </div>
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item">
          <h4 class="mb-0 font-weight-bold d-none d-xl-block">
          <?php
                date_default_timezone_set('Asia/Manila'); // Set timezone

                // Display today's date in a custom format
                $today = date('l, F j, Y'); // e.g., "Monday, September 24, 2024"
                echo "Today's date is: " . $today;
            ?>
          </h4>
        </li>
        
      </ul>
     <!-- Button to toggle the menu -->
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="collapse" data-target="#offcanvasMenu" aria-controls="offcanvasMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="mdi mdi-menu"></span>
    </button>

    <!-- Offcanvas Menu -->
    <div class="collapse navbar-collapse" id="offcanvasMenu">
      <div class="offcanvas-header">
        <h5>Navigation</h5>
        <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?></h4>
      </div>
      <div class="offcanvas-body">
        <ul class="nav">
          <a class="navbar-brand brand-logo" href="index.php"><img src="img/logo.png" alt="logo" style="height: 105px;margin-left: 8px;"/></a>
          <li class="nav-item sidebar-category">
            <p>Navigation</p>
            <span></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="appointment.php">
              <i class="mdi mdi-view-quilt menu-icon"></i>
              <span class="menu-title text-white">Dashboard</span>
            </a>
          </li>
          <li class="nav-item sidebar-category">
            <p>Components</p>
            <span></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="my_appointments.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title text-white">My Appointments</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="notifications.php">
              <i class="mdi mdi-bell menu-icon"></i>
              <span class="menu-title text-white">Notifications</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="my_record.php">
              <i class="mdi mdi-notebook menu-icon"></i>
              <span class="menu-title text-white">My Record</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="my_profile.php">
              <i class="mdi mdi-emoticon menu-icon"></i>
              <span class="menu-title text-white">My Profile</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="change_password.php">
              <i class="mdi mdi-lock menu-icon"></i>
              <span class="menu-title text-white">Change Password</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="payment.php">
              <i class="mdi mdi-note menu-icon"></i>
              <span class="menu-title text-white">Payment</span>
            </a>
          </li>
          <li class="nav-item sidebar-category">
            <p>Menu</p>
            <span></span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="help.php">
              <i class="mdi mdi-book menu-icon"></i>
              <span class="menu-title text-white">Help</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title text-white">Back to Home Page</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-logout text-white">Logout</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
      
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item nav-profile dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
          <?php
                // Display the user's profile picture if available, otherwise use a default image
                $profilePic = isset($profile_picture) && !empty($profile_picture)
                    ? htmlspecialchars($profile_picture)
                    : 'img/images/faces/face5.jpg'; // Path to your default profile image

                echo '<img src="' . $profilePic . '" alt="profile" />';
            ?>
            <span class="nav-profile-name">
                <?php 
                    if (isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) {
                        echo "Welcome, " . htmlspecialchars($_SESSION['firstname']) . " " . htmlspecialchars($_SESSION['lastname']);
                    } else {
                        echo "User information is not available.";
                    }
                ?>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
            <a class="dropdown-item" onclick="window.location.href ='logout.php'">
              <i class="mdi mdi-logout text-primary"></i>
                Logout
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
