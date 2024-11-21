<?php
    include_once("inc/userDashboardHeader.php");
?>
    <div class="container-fluid page-body-wrapper">
    <?php  include_once("inc/search_header.php"); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-8 grid-margin stretch-card"> <!-- Adjusted width for main content -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Set Appointments</h4>
                        <div class="status-legend">
                            <div class="status-item">
                                <div class="color-box" style="background-color: #FFC107;"></div>
                                <span>Pending</span>
                            </div>
                            <div class="status-item">
                                <div class="color-box" style="background-color: #28A745;"></div>
                                <span>Confirmed</span>
                            </div>
                            <div class="status-item">
                                <div class="color-box" style="background-color: #DC3545;"></div>
                                <span>Canceled</span>
                            </div>
                            <div class="status-item">
                                <div class="color-box" style="background-color: #007BFF;"></div>
                                <span>Completed</span>
                            </div>
                            <div class="status-item">
                                <div class="color-box" style="background-color: #A020F0;"></div>
                                <span>Re-schedule</span>
                            </div>
                        </div>

                        <div class="content">
                            <?php
                                if (isset($_SESSION['display_message'])) {
                                    $message = $_SESSION['display_message'];
                                    $message_type = $_SESSION['message_type'];
                                    
                                    echo "<div class='alert alert-{$message_type}'>{$message}</div>";
                                    
                                    // Unset the message so it doesn't persist on page reload
                                    unset($_SESSION['display_message']);
                                    unset($_SESSION['message_type']);
                                }
                              ?>
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
                        <?php if (!empty($recentNotifications)) : ?>
                            <?php foreach ($recentNotifications as $notification) : ?>
                                <li class="list-group-item">
                                    <!-- Link to trigger modal with notification ID -->
                                    <a href="javascript:void(0);" 
                                        class="notification-link" 
                                        data-id="<?= $notification['id'] ?>"
                                        style="color: <?= $notification['is_read'] ? 'gray' : 'blue' ?>;"
                                        data-message="<?= htmlspecialchars($notification['message']) ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#notificationModal">
                                        <?= htmlspecialchars(substr($notification['message'], 0, 50)) ?>...
                                    </a>
                                    <small class="text-muted"><?= date('F j, Y, g:i A', strtotime($notification['created_at'])) ?></small>
                                </li>
                            <?php endforeach; ?>
                            <?php else : ?>
                                <li class="list-group-item">No notifications available.</li>
                            <?php endif; ?>
                        </ul>
                        <!-- "View All Notifications" link -->
                        <div class="mt-3 text-center">
                            <a href="notifications.php" class="btn btn-primary">View All Notifications</a>
                        </div>
                        <br/>
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
                                                      echo "Service: " . htmlspecialchars($appointment['services']);
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
                                                      echo "Service: " . htmlspecialchars($appointment['services']);
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
                                          <td><?= htmlspecialchars($appointment['patient_first_name'])." ".htmlspecialchars($appointment['patient_last_name']) ?></td>
                                          <td><?= htmlspecialchars($appointment['patient_id']) ?></td>
                                          <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                                          <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                                          <td>
                                            <label class="badge <?= htmlspecialchars($appointment['status'] == 'Canceled' ? 'badge-danger' : 
                                                ($appointment['status'] == 'Confirmed' ? 'badge-success' : 
                                                ($appointment['status'] == 'Re-schedule' ? 'badge-info' : 'badge-warning'))) ?>">
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