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
                    <h3>Appointments</h3>
                    <p class="text-subtitle text-muted">List of all Appointment Bookings</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Appointment Bookings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="table-responsive">
                    <div class="card-body">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Member ID</th>
                                    <th>Name</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Services</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Approved By</th>
                                    <th>Date Approved</th>
                                    <th>Re-Scheduled By</th>
                                    <th>Date Re-schedule</th>
                                    <th>Canceled By</th>
                                    <th>Date Canceled</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($get_appointments)) : ?>
                                    <?php foreach ($get_appointments as $appointments) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($appointments['patient_id']) ?></td>
                                            <td><?= htmlspecialchars($appointments['patient_member_id']) ?></td>
                                            <td><?= htmlspecialchars($appointments['patient_first_name'])." ".htmlspecialchars($appointments['patient_last_name']) ?></td>
                                            <td>
                                                <?php
                                                    // Format the appointment date using date() and strtotime()
                                                    echo date('F j, Y', strtotime($appointments['appointment_date'])); // Format as "Month Day, Year"
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    // Format the appointment time using date() and strtotime()
                                                    echo date('h:i A', strtotime($appointments['appointment_time'])); // Format as "Hour:Minute AM/PM"
                                                ?>
                                            </td>
                                            <td>
                                                <?= $appointments['services']?>
                                            </td>
                                            <td>
                                                <?php
                                                    // Display badge based on appointment status
                                                    $status = htmlspecialchars($appointments['status']);
                                                    if ($status === 'Confirmed') {
                                                        echo '<span class="badge bg-success">Confirmed</span>';
                                                    } elseif ($status === 'Pending') {
                                                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                                                    } elseif ($status === 'Canceled') {
                                                        echo '<span class="badge bg-danger">Canceled</span>';
                                                    } elseif ($status === 'Re-schedule') {
                                                        echo '<span class="badge bg-info">Re-Scheduled</span>';
                                                    } else {
                                                        echo '<span class="badge bg-secondary">Unknown</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?= $appointments['notes']?>
                                            </td>
                                            <td><?= htmlspecialchars($appointments['approved_first_name'])." ".htmlspecialchars($appointments['approved_last_name']) ?></td>
                                            <td>
                                                <?php
                                                    // Format the appointment date using date() and strtotime()
                                                    if($appointments['updated_at'] != "") {
                                                        echo date('F j, Y', strtotime($appointments['updated_at'])); // Format as "Month Day, Year"
                                                    } 
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($appointments['rescheduled_first_name'])." ".htmlspecialchars($appointments['rescheduled_last_name']) ?></td>
                                            <td>
                                                <?php
                                                    // Format the appointment date using date() and strtotime()
                                                    if($appointments['updated_at'] != "") {
                                                        echo date('F j, Y', strtotime($appointments['updated_at'])); // Format as "Month Day, Year"
                                                    } 
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($appointments['canceled_first_name'])." ".htmlspecialchars($appointments['canceled_last_name']) ?></td>
                                            <td>
                                                <?php
                                                    // Format the appointment date using date() and strtotime()
                                                    if($appointments['updated_at'] != "") {
                                                        echo date('F j, Y', strtotime($appointments['updated_at'])); // Format as "Month Day, Year"
                                                    } 
                                                ?>
                                            </td>
                                            <td>
                                                 <?php if ($status !== 'Canceled') : ?>
                                                    <button type="button" class="btn btn-success btn-sm approve-button" data-bs-toggle="modal" data-bs-target="#approveModal<?= $appointments['appointment_id'] ?>">Approve</button>
                                                    <button type="button" class="btn btn-info btn-sm reschedule-button" data-bs-toggle="modal" data-bs-target="#rescheduleModal<?= $appointments['appointment_id'] ?>">Reschedule</button>
                                                    <button type="button" class="btn btn-danger btn-sm cancel-button" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $appointments['appointment_id'] ?>">Cancel</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No Patient record found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>

<?php
include_once('inc/actionModal.php');
include_once('inc/footerDashboard.php');
?>