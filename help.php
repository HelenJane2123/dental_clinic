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
                            <!-- Documentation Title -->
                            <h4>Patient Dashboard Help</h4>
                            <p class="text-muted">Here you will find guidance on how to book appointments, receive notifications, and make payments.</p>
                            
                            <!-- Section: Booking Appointments -->
                            <div class="card">
                                <div class="card-header">
                                    <h5>Booking an Appointment</h5>
                                </div>
                                <div class="card-body">
                                    <p>To book an appointment, follow these steps:</p>
                                    <ol>
                                        <li>Click on the "Book Appointment" button.</li>
                                        <li>Choose the doctor from the available list.</li>
                                        <li>Select your preferred date and time slot (ensure the timeslot is available).</li>
                                        <li>Fill in your personal details and any additional information required.</li>
                                        <li>Click "Confirm" to submit your appointment request.</li>
                                    </ol>
                                    <p>Once your appointment is confirmed, you will receive an email and an on-screen notification.</p>
                                </div>
                            </div>

                            <!-- Section: Notifications -->
                            <div class="card">
                                <div class="card-header">
                                    <h5>Notifications</h5>
                                </div>
                                <div class="card-body">
                                    <p>You will receive notifications for the following:</p>
                                    <ul>
                                        <li>Appointment confirmation.</li>
                                        <li>Appointment rescheduling or cancellations.</li>
                                        <li>Payment reminders and receipts.</li>
                                        <li>Doctor availability updates.</li>
                                    </ul>
                                    <p>Notifications will appear both on your dashboard and via email.</p>
                                </div>
                            </div>

                            <!-- Section: Payment Methods -->
                            <div class="card">
                                <div class="card-header">
                                    <h5>Payment Methods</h5>
                                </div>
                                <div class="card-body">
                                    <p>To make a payment for your appointment:</p>
                                    <ol>
                                        <li>Go to the "Payment" section on your dashboard.</li>
                                        <li>Select the appointment you would like to pay for.</li>
                                        <li>Choose your preferred payment method (Credit/Debit Card, PayPal, etc.).</li>
                                        <li>Enter the payment details and confirm the payment.</li>
                                        <li>Once payment is successful, you will receive a confirmation.</li>
                                    </ol>
                                    <p>After payment, the booking will be confirmed, and you will receive a payment receipt via email and on your dashboard.</p>
                                </div>
                            </div>

                            <!-- Section: Contact Support -->
                            <div class="card">
                                <div class="card-header">
                                    <h5>Need Assistance?</h5>
                                </div>
                                <div class="card-body">
                                    <p>If you encounter any issues, please reach out to support. You can contact us via:</p>
                                    <ul>
                                        <li>Email: support@example.com</li>
                                        <li>Phone: 123-456-7890</li>
                                    </ul>
                                    <p>Our team is available 24/7 to assist you.</p>
                                </div>
                            </div>
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
    include_once("inc/userDashboardFooter.php");
?>