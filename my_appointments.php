<?php
    include_once("inc/user_dashboard_header.php");
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
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appointmentModal">
                            Book an Appointment
                        </button>
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
                                          <th>Member ID</th>
                                          <th>Name</th>
                                          <th>Patient ID</th>
                                          <th>Appointment Date</th>
                                          <th>Appointment Time</th>
                                          <th>Status</th>
                                          <th>Notes</th>
                                          <th>Actions</th>
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
                                                    <label class="badge <?= htmlspecialchars($appointment['status'] == 'Cancelled' ? 'badge-danger' : 
                                                        ($appointment['status'] == 'Confirmed' ? 'badge-success' : 
                                                        ($appointment['status'] == 'Rescheduled' ? 'badge-info' : 'badge-warning'))) ?>">
                                                        <?= htmlspecialchars($appointment['status']) ?>
                                                    </label>
                                                  </td>
                                                  <td><?= htmlspecialchars($appointment['notes']) ?></td>
                                                  <td>
                                                        <button class="btn btn-info btn-sm" onclick="window.location.href='my_appointments.php?id=<?= $appointment['id'] ?>'" title="View">
                                                          <i class="mdi mdi-eye"></i>
                                                        </button>
                                                        <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $appointment['id'] ?>, '<?= htmlspecialchars($appointment['appointment_date']) ?>', '<?= htmlspecialchars($appointment['appointment_time']) ?>', '<?= htmlspecialchars($appointment['notes']) ?>', '<?= htmlspecialchars($appointment['status']) ?>')" title="Edit">
                                                            <i class="mdi mdi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" onclick="setAppointmentId(<?= $appointment['id'] ?>)" title="Delete">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                  </td>
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
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->
<?php
    include_once("inc/my_appointment_modal.php");
    include_once("inc/user_dashboard_footer.php");
?>