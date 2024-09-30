<?php
    include_once("inc/userDashboardHeader.php");
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
            <div class="col-lg-8 grid-margin stretch-card"> <!-- Adjusted width for main content -->
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
            <!-- Appointment Notifications Column -->
            <div class="col-lg-4 grid-margin stretch-card"> <!-- New column for notifications -->
              <div class="card">
                  <div class="card-body">
                      <h4 class="card-title">Appointment Notifications</h4>
                      <ul class="list-group">
                          <?php 
                          $serviceNames = [
                              'cleaning' => 'Teeth Cleaning',
                              'extraction' => 'Tooth Extraction',
                              'filling' => 'Dental Filling',
                              'checkup' => 'Dental Checkup',
                              'whitening' => 'Teeth Whitening',
                              'brace_adjustment' => 'Brace Adjustment',
                              'brace_consultation' => 'Braces Consultation',
                              'brace_installation' => 'Dental Braces Installation'
                          ];                                

                          // Upcoming Appointments
                          if (!empty($upcomingAppointments)): ?>
                              <li class="list-group-item">
                                  <strong>Upcoming Appointments:</strong>
                                  <ul class="list-unstyled">
                                      <?php foreach ($upcomingAppointments as $appointment): ?>
                                          <li class="mt-2">
                                              <strong><?= htmlspecialchars($appointment['appointment_date']) ?> at <?= htmlspecialchars($appointment['appointment_time']) ?></strong>
                                              <p>
                                                  <?php
                                                      // Get the service name based on the appointment's services
                                                      $serviceName = isset($serviceNames[$appointment['services']]) ? $serviceNames[$appointment['services']] : 'Unknown Service';
                                                      echo "Service: " . htmlspecialchars($serviceName);
                                                  ?>
                                              </p>
                                          </li>
                                      <?php endforeach; ?>
                                  </ul>
                              </li>
                          <?php else: ?>
                              <li class="list-group-item">No upcoming appointments.</li>
                          <?php endif; ?>

                          <!-- Today's Appointments -->
                          <?php if (!empty($todaysAppointments)): ?>
                              <li class="list-group-item">
                                  <strong>Appointments for Today:</strong>
                                  <ul class="list-unstyled">
                                      <?php foreach ($todaysAppointments as $appointment): ?>
                                          <li class="mt-2">
                                              <strong><?= htmlspecialchars($appointment['appointment_date']) ?> at <?= htmlspecialchars($appointment['appointment_time']) ?></strong>
                                              <p>
                                                  <?php
                                                      // Get the service name based on the appointment's services
                                                      $serviceName = isset($serviceNames[$appointment['services']]) ? $serviceNames[$appointment['services']] : 'Unknown Service';
                                                      echo "Service: " . htmlspecialchars($serviceName);
                                                  ?>
                                              </p>
                                          </li>
                                      <?php endforeach; ?>
                                  </ul>
                              </li>
                          <?php else: ?>
                              <li class="list-group-item">No appointments for today.</li>
                          <?php endif; ?>

                          <!-- Confirmed Appointments Count -->
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                              Confirmed
                              <span class="badge bg-success rounded-pill"><?= $confirmedAppointmentsCount ?></span>
                          </li>

                          <!-- Canceled Appointments Count -->
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                              Canceled
                              <span class="badge bg-danger rounded-pill"><?= $canceledAppointmentsCount ?></span>
                          </li>
                      </ul>
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
                      <table id="appointmentTable" class="table table-hover">
                          <thead>
                              <tr>
                                  <th>Member ID</th>
                                  <th>Name</th>
                                  <th>Patient ID</th>
                                  <th>Appointment Date</th>
                                  <th>Appointment Time</th>
                                  <th>Status</th>
                                  <th>Notes</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php if (!empty($appointments)) : ?>
                                  <?php foreach ($appointments as $appointment) : ?>
                                      <tr>
                                          <td><?= htmlspecialchars($appointment['member_id']) ?></td>
                                          <td><?= htmlspecialchars($appointment['first_name'])." ".htmlspecialchars($appointment['last_name']) ?></td>
                                          <td><?= htmlspecialchars($appointment['patient_id']) ?></td>
                                          <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                                          <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                                          <td>
                                              <label class="badge <?= htmlspecialchars($appointment['status'] == 'Cancelled' ? 'badge-danger' : ($appointment['status'] == 'Confirmed' ? 'badge-success' : 'badge-warning')) ?>">
                                                  <?= htmlspecialchars($appointment['status']) ?>
                                              </label>
                                          </td>
                                          <td><?= htmlspecialchars($appointment['notes']) ?></td>
                                      </tr>
                                  <?php endforeach; ?>
                              <?php else : ?>
                                  <tr>
                                      <td colspan="5" class="text-center">No appointments found.</td>
                                  </tr>
                              <?php endif; ?>
                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <!-- row end -->
          
<?php
    include_once("inc/myAppointmentModal.php");
    include_once("inc/userDashboardFooter.php");
?>