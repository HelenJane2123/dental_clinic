<?php
    include_once("inc/user_dashboard_header.php");

    // Fetch appointments for the logged-in member
    $member_id = $_SESSION['member_id']; // Get the member ID from session
    $appointments = $appointment->get_all_appointments_by_member_id($member_id); // Fetch appointments
?>
    <div class="container-fluid page-body-wrapper">
      <!-- partial:./partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="navbar-brand-wrapper">
            <!-- <a class="navbar-brand brand-logo" href="index.html"><img src="img/images/logo.svg" alt="logo"/></a>
            <a class="navbar-brand brand-logo-mini" href="index.html"><img src="img/images/logo-mini.svg" alt="logo"/></a> -->
          </div>
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?></h4>
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
            <li class="nav-item dropdown me-2">
              <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                <i class="mdi mdi-email-open mx-0"></i>
                <span class="count bg-danger">1</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-success">
                      <i class="mdi mdi-information mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      Just now
                    </p>
                  </div>
                </a>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-warning">
                      <i class="mdi mdi-settings mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      Private message
                    </p>
                  </div>
                </a>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-info">
                      <i class="mdi mdi-account-box mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      2 days ago
                    </p>
                  </div>
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
          <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Here..." aria-label="search" aria-describedby="search">
              </div>
            </li>
          </ul>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                <img src="img/images/faces/face5.jpg" alt="profile"/>
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
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Set Appointments</h4>
                    <div class="status-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #FFC107;"></div>
                            <span>Pending</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #28A745;"></div>
                            <span>Confirmed</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #DC3545;"></div>
                            <span>Canceled</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #007BFF;"></div>
                            <span>Completed</span>
                        </div>
                    </div>
                    <div class="content">
                        <div id='calendar'></div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">My Appointments</h4>
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>
                            Appointment Description
                          </th>
                          <th>
                            Date
                          </th>
                          <th>
                            Remarks
                          </th>
                          <th>
                            Status
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="py-1">
                            Brace Adjustment
                          </td>
                          <td>
                            September 9, 2024
                          </td>
                          <td>
                            None
                          </td>
                          <td>
                            Upcoming
                          </td>
                        </tr>
                        <tr>
                          <td class="py-1">
                            Tooth Extraction
                          </td>
                          <td>
                            September 9, 2024
                          </td>
                          <td>
                            for brace adjustment
                          </td>
                          <td>
                            Upcoming
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->
          <!-- Appointment Details Modal -->
          <div class="modal fade" id="appointmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="appointmentDetailsModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="appointmentDetailsModalLabel">Appointment Details</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <div id="appointmentDetailsContent">
                              <!-- Details will be populated here -->
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
          </div>
        

<?php
    include_once("inc/user_dashboard_footer.php");
?>