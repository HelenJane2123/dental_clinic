<?php
    include_once("inc/user_dashboard_header.php");

    // Fetch appointments for the logged-in member
    $member_id = $_SESSION['member_id']; // Get the member ID from session
    $appointments = $appointment->get_all_appointments_by_member_id($member_id); // Fetch appointments

    if (isset($_GET['id'])) {
      $appointmentId = $_GET['id'];
      // Fetch appointment details
      $appointmentDetails = $appointment->view_appointment_by_id($appointmentId); // Ensure this method exists
    }
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
                              <table class="table table-hover">
                                  <thead>
                                      <tr>
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
                                                  <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                                                  <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                                                  <td>
                                                      <label class="badge <?= htmlspecialchars($appointment['status'] == 'Cancelled' ? 'badge-danger' : ($appointment['status'] == 'Confirmed' ? 'badge-success' : 'badge-warning')) ?>">
                                                          <?= htmlspecialchars($appointment['status']) ?>
                                                      </label>
                                                  </td>
                                                  <td><?= htmlspecialchars($appointment['notes']) ?></td>
                                                  <td>
                                                      <button class="btn btn-info btn-sm" onclick="window.location.href='my_appointments.php?id=<?= $appointment['id'] ?>'" title="View">
                                                          <i class="mdi mdi-eye"></i>
                                                      </button>
                                                      <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $appointment['id'] ?>)" title="Edit">
                                                          <i class="mdi mdi-pencil"></i>
                                                      </button>
                                                      <button class="btn btn-danger btn-sm" onclick="deleteAppointment(<?= $appointment['id'] ?>)" title="Delete">
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
      <!-- Add Appointment Modal -->
      <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="appointmentModalLabel">Book an Appointment</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form action="controller/set_appointment.php" method="POST" name="appointmentForm" novalidate enctype="multipart/form-data" id="appointmentForm">
                          <div class="form-group">
                              <label for="appointmentType">Appointment For:</label>
                              <div>
                                  <label>
                                      <input type="radio" name="appointmentType" value="myself" onclick="toggleNameFields(this)" checked> For Myself
                                  </label>
                                  <label>
                                      <input type="radio" name="appointmentType" value="newPatient" onclick="toggleNameFields(this)"> New Patient
                                  </label>
                              </div>
                          </div>
                          <input type="hidden" name="old_firstname" class="form-control" value="<?=$_SESSION['firstname']?>" id="old_firstname">
                          <input type="hidden" name="old_lastname" class="form-control" value="<?=$_SESSION['lastname']?>" id="old_lastname">
                          <input type="hidden" name="member_id" class="form-control" value="<?=$_SESSION['member_id']?>"  id="member_id">
                          <div id="nameFields">
                              <div class="form-group">
                                  <label for="userName">First Name</label>
                                  <input type="text" name="firstname" class="form-control" id="userName" required>
                              </div>
                              <div class="form-group">
                                  <label for="lastName">Last Name</label>
                                  <input type="text" name="lastname" class="form-control" id="lastName" required>
                              </div>
                          </div>
                          <div class="form-group">
                                  <label for="lastName">Contact Number</label>
                                  <input type="text" name="contactnumber" class="form-control" id="lastName" required>
                          </div>
                          <div class="form-group">
                              <label for="lastName">Email Address</label>
                              <input type="text" name="emailaddress" class="form-control" id="lastName" required>
                          </div>
                          <div class="form-group">
                              <label for="appointmentDate">Appointment Date</label>
                              <input type="date" class="form-control" name="appointmentDate" id="appointmentDate" required>
                          </div>
                          <div class="form-group">
                              <label for="appointmentTime">Appointment Time</label>
                              <input type="time" class="form-control" name="appointmentTime" id="appointmentTime" required>
                          </div>
                          <div class="form-group">
                              <label for="services">Dental Services</label>
                              <select class="form-control" id="services" name="services" required>
                                  <option value="" disabled selected>Select a service</option>
                                  <option value="cleaning">Teeth Cleaning</option>
                                  <option value="extraction">Tooth Extraction</option>
                                  <option value="filling">Dental Filling</option>
                                  <option value="checkup">Dental Checkup</option>
                                  <option value="whitening">Teeth Whitening</option>
                                  <option value="brace_adjustment">Brace Adjustment</option>
                                  <option value="brace_consultation">Braces Consultation</option>
                                  <option value="brace_installation">Dental Braces Installation</option>
                              </select>
                          </div>
                          <div class="form-group">
                              <label for="notes">Additional Notes</label>
                              <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" onclick="submitAppointment()">Submit</button>
                  </div>
              </div>
          </div>
      </div>
      <!-- Modal for Viewing Appointment Details -->
      <!-- Modal for Viewing Appointment Details -->
      <div class="modal fade" id="viewAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="viewAppointmentModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="viewAppointmentModalLabel">Appointment Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <?php if (isset($appointmentDetails)): ?>
                          <p><strong>Appointment Date:</strong> <span><?= htmlspecialchars($appointmentDetails['appointment_date']) ?></span></p>
                          <p><strong>Appointment Time:</strong> <span><?= htmlspecialchars($appointmentDetails['appointment_time']) ?></span></p>
                          <p><strong>Status:</strong> <span><?= htmlspecialchars($appointmentDetails['status']) ?></span></p>
                          <p><strong>Notes:</strong> <span><?= htmlspecialchars($appointmentDetails['notes']) ?></span></p>
                          <p><strong>Services:</strong> <span><?= htmlspecialchars($appointmentDetails['services']) ?></span></p>
                      <?php else: ?>
                          <p>No appointment details found.</p>
                      <?php endif; ?>
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