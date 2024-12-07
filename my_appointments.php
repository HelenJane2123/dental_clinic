<?php
    include_once("inc/userDashboardHeader.php");
   
?>
    <div class="container-fluid page-body-wrapper">
      <?php  include_once("inc/search_header.php"); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Book an Appointment</h4>
                    <div style="text-align: right; margin-bottom: 20px;">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appointmentModal" id="bookAppointmentBtn" <?php echo ($patientCount == 0) ? 'disabled' : ''; ?>>
                            Book an Appointment
                        </button>

                        <?php if ($patientCount == 0): ?>
                            <!-- Message for the user if patient record is incomplete -->
                            <div class="alert alert-warning mt-3">
                                You must complete your patient record before booking an appointment. 
                                <a href="my_record.php" class="btn btn-link">Complete your record here.</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="content">
                      <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">My Appointment Lists</h4>
                            <p class="card-description">
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
                            </p>
                            <div class="table-responsive">
                              <table id="appointmentTable" class="table table-hover">
                                  <thead>
                                      <tr>
                                          <th>Appointment ID</th>
                                          <th>Patient ID</th>
                                          <th>Name</th>
                                          <th>Appointment Date</th>
                                          <th>Appointment Time</th>
                                          <th>Assigned Doctor</th>
                                          <th>Status</th>
                                          <th>Notes</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php if (!empty($appointments)) : ?>
                                          <?php foreach ($appointments as $appointment) : ?>
                                              <tr>
                                                  <td><?= htmlspecialchars($appointment['id']) ?></td>
                                                  <td><?= htmlspecialchars($appointment['member_id']) ?></td>
                                                  <td><?= htmlspecialchars($appointment['patient_first_name'])." ".htmlspecialchars($appointment['patient_last_name']) ?></td>
                                                  <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                                                  <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                                                  <td><?= htmlspecialchars($appointment['doctor_first_name'])." ".htmlspecialchars($appointment['doctor_last_name']) ?></td>
                                                  <td>
                                                    <label class="badge <?= htmlspecialchars($appointment['status'] == 'Canceled' ? 'badge-danger' : 
                                                        ($appointment['status'] == 'Confirmed' ? 'badge-success' : 
                                                        ($appointment['status'] == 'Completed' ? 'badge-primary' : 
                                                        ($appointment['status'] == 'Re-schedule' ? 'badge-info' : 'badge-warning')))) ?>">
                                                        <?= htmlspecialchars($appointment['status']) ?>
                                                    </label>
                                                  </td>
                                                  <td><?= htmlspecialchars($appointment['notes']) ?></td>
                                                  <td>
                                                    <!-- View Button -->
                                                    <button class="btn btn-info btn-sm" onclick="window.location.href='my_appointments.php?id=<?= $appointment['id'] ?>'" title="View">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>

                                                    <?php if ($appointment['status'] !== 'Canceled') : ?>
                                                    
                                                        <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $appointment['id'] ?>, 
                                                            '<?= htmlspecialchars($appointment['services']) ?>', 
                                                            '<?= htmlspecialchars($appointment['appointment_date']) ?>', 
                                                            '<?= htmlspecialchars($appointment['appointment_time']) ?>', 
                                                            '<?= htmlspecialchars($appointment['notes']) ?>', 
                                                            '<?= htmlspecialchars($appointment['status']) ?>', 
                                                            '<?= htmlspecialchars($appointment['patient_first_name']) ?>', 
                                                            '<?= htmlspecialchars($appointment['patient_last_name']) ?>', 
                                                            '<?= htmlspecialchars($appointment['doctor_account_id']) ?>', 
                                                            '<?= htmlspecialchars($appointment['member_id']) ?>')" title="Edit">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </button>
                                                    

                                                        <!-- Delete Button -->
                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" onclick="setAppointmentId(<?= $appointment['id'] ?>)" title="Delete">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                              </tr>
                                          <?php endforeach; ?>
                                      <?php else : ?>
                                          <tr>
                                              <td colspan="8" class="text-center">No appointments found.</td>
                                          </tr>
                                      <?php endif; ?>
                                  </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
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