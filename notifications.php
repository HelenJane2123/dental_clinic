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
                  <h4 class="card-title">Notifications</h4>
                    <div class="content">
                      <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">My Notifcation Lists</h4>
                            <div class="table-responsive">
                              <table id="appointmentTable" class="table table-hover">
                                  <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Patient ID</th>
                                        <th>Member ID</th>
                                        <th>Message</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                      <?php if (!empty($notification_lists)) : ?>
                                          <?php foreach ($notification_lists as $notification) : ?>
                                              <tr>
                                                <td><?= htmlspecialchars($notification['first_name'])." ".htmlspecialchars($notification['last_name']) ?></td>
                                                <td><?= htmlspecialchars($notification['patient_id']) ?></td>
                                                <td><?= htmlspecialchars($notification['member_id']) ?></td>
                                                <td><?= htmlspecialchars($notification['message']) ?></td>
                                                <td>
                                                    <label class="badge bg-info">
                                                        <?php 
                                                            if ($notification['type'] === 'appointment') {
                                                                echo htmlspecialchars('Schedule     Appointment');  
                                                            } elseif ($notification['type'] === 'appointment_update') {
                                                                echo htmlspecialchars('Update in Appointment'); 
                                                            } else {
                                                                echo htmlspecialchars('Other Status'); 
                                                            }
                                                        ?>
                                                    </label>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($notification['is_read'] == 1) {
                                                    ?>
                                                    <span class="badge bg-success">Read</span>
                                                    <?php
                                                        } else {
                                                    ?>
                                                    <button class="btn btn-warning btn-sm" 
                                                        onclick="toggleStatus(this, <?= $notification['id'] ?>)">
                                                        Mark as Read
                                                    </button>
                                                    <?php
                                                        }
                                                    ?>
                                                </td>
                                              </tr>
                                          <?php endforeach; ?>
                                      <?php else : ?>
                                          <tr>
                                              <td colspan="8" class="text-center">No notification/s found.</td>
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