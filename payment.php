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
                      To proceed with your appointment, a down payment is required. 
                      Please use one of the QR codes below to complete your payment. Once payment is confirmed, your appointment will be finalized.
                    </p>
                    <div class="mt-4">
                      <h4>Steps to Complete Payment:</h4>
                      <ol>
                        <li>Scan the QR code corresponding to your preferred payment method using a mobile banking app or e-wallet.</li>
                        <li>Enter the required amount. <strong>Note:</strong> You can view the computation of the amount you need to pay by clicking the <b>"View Computation"</b> button in the table. This will show you the breakdown of the total amount, the 20% down payment, and the remaining balance.</li>
                        <li>Complete the payment and take a screenshot or save the receipt.</li>
                        <li>Upload the proof of payment in the <b>Upload Payment Proof</b> section below.</li>
                      </ol>
                    </div>
                    <div class="mt-5">
                      <h4 class="card-title">Payment Status</h4>
                      <div class="table-responsive">
                          <table class="table table-bordered" id="appointmentTable">
                              <thead>
                                  <tr>
                                      <th>Appointment ID</th>
                                      <th>Appointment Status</th>
                                      <th>Dental Service</th>
                                      <th>Patient ID</th>
                                      <th>Status</th>
                                      <th>Uploaded Receipt</th>
                                      <th>Remarks</th>
                                      <th>Actions</th> <!-- New column for actions -->
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
                                            // Appointment Status with Badge
                                            $statusClass = htmlspecialchars(
                                                $payment['appointment_status'] == 'Canceled' ? 'badge-danger' : 
                                                ($payment['appointment_status'] == 'Confirmed' ? 'badge-success' : 
                                                ($payment['appointment_status'] == 'Completed' ? 'badge-primary' : 
                                                ($payment['appointment_status'] == 'Re-schedule' ? 'badge-info' : 'badge-warning')))
                                            );
                                            echo "<td><label class='badge {$statusClass}'>{$payment['appointment_status']}</label></td>";
                                            echo "<td>{$payment['sub_category']}</td>";
                                            echo "<td>{$payment['patient_member_id']}</td>";
                                            echo "<td>{$payment['status']}</td>";
                                            // Payment Proof
                                            echo "<td>";
                                            // Disable the "Upload Payment Proof" button if the status is "Canceled"
                                            if ($payment['appointment_status'] != 'Canceled') {
                                                if ($payment['file_name']) {
                                                    echo "<a href='public/payment/{$member_id}/{$payment['appointment_id']}/{$payment['file_name']}' target='_blank'>View Receipt</a>";
                                                } else {
                                                    echo "<button 
                                                        class='btn btn-warning btn-sm btn-upload-proof' 
                                                        data-appointment-id='{$payment['appointment_id']}'>
                                                        Upload Payment Proof
                                                        </button>";
                                                }
                                            } else {
                                                echo "<p class='text-muted'>Appointment Canceled. Upload disabled.</p>";
                                            }
                                            echo "</td>";
                                    
                                            // Remarks
                                            echo "<td>{$payment['remarks']}</td>";
                                    
                                            // View Computation Button
                                            echo "<td>";
                                            echo "<button 
                                                    class='btn btn-primary btn-sm' 
                                                    onclick='viewComputation(this)'
                                                    data-appointment-id='{$payment['appointment_id']}'>
                                                    View Computation
                                                </button>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No payment found.</td></tr>";
                                    }
                                ?>
                              </tbody>
                          </table>
                      </div>
                  </div>
                  <br/>
                  <div class="container">
                    <h3 class="text-center mb-4">QR Codes for Payment Methods</h3>
                    <div class="row">
                      <!-- GCash QR Code -->
                      <div class="col-12 col-md-4 text-center mb-3">
                        <img src="img/payment/gcash.jpg" alt="GCash QR Code" class="img-fluid" style="max-width: 100%; height: auto;">
                        <h5>GCash</h5>
                      </div>
                      <!-- PayMaya QR Code -->
                      <div class="col-12 col-md-4 text-center mb-3">
                        <img src="img/payment/paymaya.jpg" alt="PayMaya QR Code" class="img-fluid" style="max-width: 100%; height: auto;">
                        <h5>PayMaya</h5>
                      </div>
                      <!-- Bank Transfer QR Code -->
                      <div class="col-12 col-md-4 text-center mb-3">
                        <img src="img/payment/bpi.jpg" alt="Bank Transfer QR Code" class="img-fluid" style="max-width: 100%; height: auto;">
                        <h5>Bank Transfer</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->
            
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
                        <input type="hidden" name="doctor_id" class="form-control" value="<?=$get_doctor_id['account_id']?>" id="doctor_id">
                        <input type="file" class="form-control" id="paymentReceipt" name="paymentReceipt"  accept=".jpg,.jpeg,.png,.pdf" required>
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
                    <button type="button" class="btn btn-secondary">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        </div>
    </div>


  <!-- Hidden Form -->
  <form id="computationForm" method="POST" action="controller/fetchComputation.php" target="computationFrame" style="display: none;">
    <input type="hidden" id="appointmentIdInput" name="appointment_id">
  </form>

  <div class="modal fade" id="viewComputationModal" tabindex="-1" role="dialog" aria-labelledby="viewComputationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewComputationModalLabel">Computation Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- The iframe will show the fetched computation details -->
                <iframe name="computationFrame" id="computationFrame" style="width: 100%; height: 300px; border: none;"></iframe>      
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        function checkFrameContent() {
            console.log('Iframe content loaded');
        }

        document.getElementById('computationFrame').onload = checkFrameContent;
    });
    function viewComputation(button) {
        var appointmentId = button.getAttribute('data-appointment-id'); // Get the appointment ID
        if (!appointmentId) {
            alert('Invalid Appointment ID');
            return;
        }
        document.getElementById('appointmentIdInput').value = appointmentId; // Set it in the hidden form
        document.getElementById('computationForm').submit(); // Submit the form
        $('#viewComputationModal').modal('show'); // Show the modal
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Select all "Upload Payment Proof" buttons
        var uploadButtons = document.querySelectorAll('.btn-upload-proof');
        
        // Add event listener to each button
        uploadButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                // Get the appointment ID from the button's data-attribute
                var appointmentId = button.getAttribute('data-appointment-id');
                
                // Log the appointment ID to verify it's correct
                console.log('Appointment ID:', appointmentId);

                // Set the appointment ID in the modal input field
                var appointmentInput = document.getElementById('appointmentID');
                if (appointmentInput) {
                    appointmentInput.value = appointmentId;
                } else {
                    console.error('Appointment ID input field not found!');
                }

                // Show the modal (Bootstrap's native method)
                var modal = new bootstrap.Modal(document.getElementById('uploadProofModal'));
                modal.show();
            });
        });
    });
</script>
<?php
    include_once("inc/userDashboardFooter.php");
?>