<?php
    include_once('inc/headerDashboard.php');
    include_once('inc/sidebarMenu.php');
?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Notifications</h3>
                            <p class="text-subtitle text-muted">List of all Notifications</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
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
                                                    <span class="badge bg-success">Marked as Read</span>
                                                    <?php
                                                        }
                                                        else {
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
                                              <td colspan="8" class="text-center">No notifications   found.</td>
                                          </tr>
                                      <?php endif; ?>
                                  </tbody>
                            </table>
                        </div>
                    </div>

                </section>
            </div>

<?php
    include_once('inc/footerDashboard.php');
?>