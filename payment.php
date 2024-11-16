<?php
include_once("inc/userDashboardHeader.php");
?>
<div class="container-fluid page-body-wrapper">
  <?php include_once("inc/search_header.php"); ?>
  <!-- partial -->
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Payment Method</h4>
              <?php
              if (isset($_SESSION['display_message'])) {
                  $message = $_SESSION['display_message'];
                  $message_type = $_SESSION['message_type'];
                  
                  echo "<div class='alert alert-{$message_type}'>{$message}</div>";
                  
                  unset($_SESSION['display_message']);
                  unset($_SESSION['message_type']);
              }
              ?>
              
              <p class="mb-4">
                To proceed with your appointment, a **20% down payment** of the total amount is required. 
                Please use one of the QR codes below to complete your payment. Once payment is confirmed, your appointment will be finalized.
              </p>
              
              <p>
                **Steps to Complete Payment:**
                <ol>
                  <li>Scan the QR code corresponding to your preferred payment method using a mobile banking app or e-wallet.</li>
                  <li>Enter the required amount (20% of the total).</li>
                  <li>Complete the payment and take a screenshot or save the receipt.</li>
                  <li>Upload the proof of payment in the <b>Upload Payment Proof</b> section below.</li>
                </ol>
              </p>

              <div class="row">
                <!-- QR Codes Section -->
                <div class="col-md-4 text-center">
                  <img src="img/payment/gcash.jpg" alt="GCash QR Code" class="img-fluid mb-3">
                  <h5>GCash</h5>
                </div>
                <div class="col-md-4 text-center">
                  <img src="img/payment/paymaya.jpg" alt="PayMaya QR Code" class="img-fluid mb-3">
                  <h5>PayMaya</h5>
                </div>
                <div class="col-md-4 text-center">
                  <img src="img/payment/bpi.jpg" alt="Bank Transfer QR Code" class="img-fluid mb-3">
                  <h5>Bank Transfer</h5>
                </div>
              </div>

              <!-- Payment Status Table -->
              <div class="mt-5">
                <h4 class="card-title">Payment Status</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="appointmentTable">
                        <thead>
                            <tr>
                                <th>Appointment ID</th>
                                <th>Patient ID</th>
                                <th>Status</th>
                                <th>Uploaded Receipt</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch payment details from the database
                            $paymentStatus = $appointment->getPaymentStatus($member_id); // Example function to fetch data
                            
                            if ($paymentStatus && count($paymentStatus) > 0) {
                                foreach ($paymentStatus as $payment) {
                                    echo "<tr>";
                                    echo "<td>{$payment['appointment_id']}</td>";
                                    echo "<td>{$payment['patient_member_id']}</td>";
                                    echo "<td>{$payment['status']}</td>";
                                    echo "<td>";
                                    if ($payment['file_name']) {
                                        echo "<a href='public/payment/{$member_id}/{$payment['appointment_id']}/{$payment['file_name']}' target='_blank'>View Receipt</a>";
                                    } else {
                                        echo "<button 
                                            class='btn btn-warning btn-sm' 
                                            data-toggle='modal' 
                                            data-target='#uploadProofModal' 
                                            data-appointment-id='{$payment['appointment_id']}'>
                                        Upload Payment Proof
                                        </button>";
                                    }
                                    echo "</td>";
                                    echo "<td>{$payment['remarks']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No payment found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
              </div>

              <!-- Modal for Payment Proof -->
                <div class="modal fade" id="uploadProofModal" tabindex="-1" role="dialog" aria-labelledby="uploadProofModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadProofModalLabel">Upload Payment Proof</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="controller/uploadPaymentProof.php" method="POST" name="proofPayment" id="proofPayment" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="paymentReceipt">Upload Receipt</label>
                                        <input type="file" class="form-control" id="paymentReceipt" name="paymentReceipt" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="appointmentID">Patient ID</label>
                                        <input type="text" class="form-control" id="patientId" name="patientId" value="<?=$member_id?>">  <!-- This will be set by JS -->
                                    </div>
                                    <div class="form-group">
                                        <label for="appointmentID">Appointment ID</label>
                                        <input type="text" class="form-control" id="appointmentID" name="appointmentID" readonly>  <!-- This will be set by JS -->
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
          </div>
        </div>
      </div>
    </div>
<?php
include_once("inc/myAppointmentModal.php");
include_once("inc/userDashboardFooter.php");
?>
