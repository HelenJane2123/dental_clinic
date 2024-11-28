<?php
include_once('inc/headerDashboard.php');
include_once('inc/sidebarMenu.php');

// Retrieve modal message from session (if any)
$modalMessage = isset($_SESSION['modal_message']) ? $_SESSION['modal_message'] : null;
unset($_SESSION['modal_message']); // Clear message after displaying it
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
                    <h3>Payment</h3>
                    <p class="text-subtitle text-sub-muted">List of Patient Payments</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payments</li>
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
                                <th>Appointment ID</th>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Assigned Doctor</th>
                                <th>Payment Status</th>
                                <th>Appointment Status</th> <!-- New column -->
                                <th>Uploaded Receipt</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $paymentStatus = $appointment_admin->getPaymentStatus(); // Example function
                            if ($paymentStatus && count($paymentStatus) > 0) {
                                foreach ($paymentStatus as $payment) {
                                    echo "<tr>";
                                    echo "<td>{$payment['appointment_id']}</td>";
                                    echo "<td>{$payment['patient_member_id']}</td>";
                                    echo "<td>" . $payment['patient_first_name'] . " " . $payment['patient_last_name'] . "</td>";
                                    echo "<td>" . $payment['doctor_first_name'] . " " . $payment['doctor_last_name'] . "</td>";
                                    echo "<td>{$payment['status']}</td>";
                                    
                                    // Appointment Status
                                    echo "<td>";
                                    $statusClass = htmlspecialchars(
                                        $payment['appointment_status'] == 'Canceled' ? 'badge-danger' : 
                                        ($payment['appointment_status'] == 'Confirmed' ? 'badge-success' : 
                                        ($payment['appointment_status'] == 'Completed' ? 'badge-primary' : 
                                        ($payment['appointment_status'] == 'Re-schedule' ? 'badge-info' : 'badge-warning')))
                                    );
                                    echo "<label class='badge {$statusClass}'>{$payment['appointment_status']}</label>";
                                    echo "</td>";

                                    // Receipt
                                    echo "<td>";
                                    if ($payment['file_name']) {
                                        echo "<a href='../public/payment/{$payment['patient_member_id']}/{$payment['appointment_id']}/{$payment['file_name']}' target='_blank'>View Receipt</a>";
                                    } else {
                                        echo "No receipt uploaded";
                                    }
                                    echo "</td>";

                                    // Remarks and Action Buttons
                                    echo "<td>{$payment['remarks']}</td>";
                                    echo "<td>";
                                    if ($payment['status'] == 'Approved') {
                                        echo "<button class='btn btn-success btn-sm' disabled>Secured 20% Down Payment</button>";
                                    } elseif ($payment['status'] == 'Rejected') {
                                        echo "Payment Rejected";
                                    } else {
                                        if ($payment['file_name']) {
                                            echo "<button class='btn btn-success btn-sm approve-btn'  data-appointment-id='{$payment['appointment_id']}'>Approve</button>";
                                            echo "<button class='btn btn-danger btn-sm reject-btn'  data-appointment-id='{$payment['appointment_id']}'>Reject</button>";
                                        } else {
                                            echo "No receipt uploaded";
                                        }
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No payment found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

        </section>
    </div>
     <!-- Bootstrap Modal -->
     <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo htmlspecialchars($modalMessage); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to approve this payment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmApproval">Yes</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal for Reject -->
    <div class="modal fade" id="rejectconfirmationModal" tabindex="-1" role="dialog" aria-labelledby="rejectconfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectconfirmationModalLabel">Confirm Rejection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reject this payment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="confirmReject">Yes</button>
                </div>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalMessage = "<?php echo $modalMessage; ?>";
        if (modalMessage) {
            $('#feedbackModal').modal('show');
        }
    });
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('approve-btn')) {
            const appointmentId = event.target.getAttribute('data-appointment-id');
            $('#confirmationModal').modal('show');
            document.getElementById('confirmApproval').onclick = function () {
                window.location.href = `controller/paymentAction.php?action=approve&appointment_id=${appointmentId}`;
            };
        } else if (event.target.classList.contains('reject-btn')) {
            const appointmentId = event.target.getAttribute('data-appointment-id');
            $('#rejectconfirmationModal').modal('show');
            document.getElementById('confirmReject').onclick = function () {
                window.location.href = `controller/paymentAction.php?action=reject&appointment_id=${appointmentId}`;
            };
        }
    });
</script>
<?php
include_once('inc/footerDashboard.php');
?>
