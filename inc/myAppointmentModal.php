<!-- Add Appointment Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Book an Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="controller/setAppointment.php" method="POST" id="addappointmentForm" name="appointmentForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="required" for="appointmentType">Appointment For:</label>
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
                    <input type="hidden" name="member_id" class="form-control" value="<?=$_SESSION['member_id']?>" id="member_id">
                    <input type="hidden" name="old_emailaddress" class="form-control" value="<?=$_SESSION['email']?>" id="email">
                    <input type="hidden" name="user_admin_id" class="form-control" value="<?=$user_id_admin?>" id="user_id">
                    <input type="hidden" name="doctor_id" class="form-control" value="<?=$get_doctor_id['account_id']?>" id="doctor_id">
                    <input type="hidden" class="form-control" id="patient_id" name="patient_id" value="<?= isset($patient_id) ? $patient_id : '' ?>">
                    
                    <div id="nameFields" style="display: none;">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="required" for="userName">First Name</label>
                                <input type="text" name="firstname" class="form-control" id="userName" data-parsley-required-message="This field is required if booking for a new patient.">
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="required" for="lastName">Last Name</label>
                                <input type="text" name="lastname" class="form-control" id="lastName" data-parsley-required-message="This field is required if booking for a new patient.">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="required" for="contactNumber">Contact Number</label>
                                <input type="number" name="contactnumber" class="form-control" id="contactNumber" data-parsley-required-message="This field is required if booking for a new patient.">
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="required" for="emailAddress">Email Address</label>
                                <input type="email" name="emailaddress" class="form-control" id="emailAddress" data-parsley-required-message="This field is required if booking for a new patient.">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required" for="services">Dental Services</label>
                        <select class="form-control" id="services" name="services" required data-parsley-required-message="Please select a service.">
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

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="required" for="appointmentDate">Appointment Date</label>
                            <input type="date" class="form-control" name="appointmentDate" id="appointmentDate" required data-parsley-required-message="Please select an appointment date.">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="required" for="appointmentTime">Appointment Time</label>
                            <input type="time" class="form-control" name="appointmentTime" id="appointmentTime" required data-parsley-required-message="Please select an appointment time.">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="required" for="notes">Additional Notes</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" required data-parsley-required-message="Please add any notes."></textarea>
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
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Are you sure you want to delete this appointment?
        </div>
        <div class="modal-footer">
            <form method="POST" action="controller/deleteAppointment.php">
                <input type="hidden" name="user_id" class="form-control" value="<?=$_SESSION['user_id']?>" id="user_id">
                <input type="hidden" class="form-control" id="patient_id" name="patient_id" value="<?= isset($patient_id) ? $patient_id : '' ?>">
                <input type="hidden" name="appointment_id" id="appointment_id" value="">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes, delete</button>
            </form>
        </div>
        </div>
    </div>
</div>
<!-- Edit Appointment Modal -->
<div class="modal fade" id="editAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAppointmentModalLabel">Edit Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="controller/editAppointment.php" method="POST" id="editAppointmentForm" data-parsley-validate>
                <div class="modal-body">
                    <!-- Hidden Fields -->
                    <input type="hidden" name="appointment_id" id="edit_appointment_id">
                    <input type="hidden" id="first_name" name="first_name">
                    <input type="hidden" id="last_name" name="last_name">
                    <input type="hidden" id="member_id" name="member_id" value="<?=$member_id?>">
                    <input type="hidden" name="doctor_id" class="form-control" value="<?=$get_doctor_id['account_id']?>" id="doctor_id">
                    <input type="hidden" class="form-control" id="patient_id" name="patient_id" value="<?= isset($patient_id) ? $patient_id : '' ?>">


                    <!-- Appointment Date -->
                    <div class="form-group">
                        <label class="required" for="edit_appointment_date">New Appointment Date (Leave empty to keep the same)</label>
                        <input type="date" class="form-control" id="edit_appointment_date" name="appointment_date" required data-parsley-required-message="This field is required.">
                    </div>

                    <!-- Appointment Time -->
                    <div class="form-group">
                        <label class="required" for="edit_appointment_time">New Appointment Time (Leave empty to keep the same)</label>
                        <input type="time" class="form-control" id="edit_appointment_time" name="appointment_time" required data-parsley-required-message="This field is required.">
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="required" for="status">Status</label>
                        <select class="form-control" id="status" name="status" required data-parsley-required-message="This field is required.">
                            <option value="Canceled">Canceled</option>
                            <option value="Re-Schedule">Rescheduled</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label class="required" for="edit_notes">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3" required data-parsley-required-message="This field is required."></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Appointment Details Modal -->
<div class="modal fade" id="appointmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="appointmentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentDetailsModalLabel">Appointment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="appointmentDetailsContent">
                    <!-- Details will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal for viewing the notification -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalMessageContent">
                <!-- Notification message will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to check if the selected date is a Saturday
    document.getElementById("appointmentDate").addEventListener("change", function() {
        var selectedDate = new Date(this.value);

        console.log(selectedDate.getDay());
        
        // Check if the selected date is a Saturday (getDay() returns 6 for Saturday)
        if (selectedDate.getDay() === 6) {
            alert("Appointments cannot be scheduled on Saturdays. Please choose another date.");
            this.value = ''; // Clear the selected date
        }
    });

    document.getElementById("edit_appointment_date").addEventListener("change", function() {
        var selectedDate = new Date(this.value);

        console.log(selectedDate.getDay());
        
        // Check if the selected date is a Saturday (getDay() returns 6 for Saturday)
        if (selectedDate.getDay() === 6) {
            alert("Appointments cannot be scheduled on Saturdays. Please choose another date.");
            this.value = ''; // Clear the selected date
        }
    });

    document.getElementById('appointmentTime').addEventListener('change', function() {
        const time = this.value;  // Get the value from the time input field
        
        if (time) {
            // Split the time value into hours and minutes
            const [hours, minutes] = time.split(':').map(num => parseInt(num, 10));
            const totalMinutes = hours * 60 + minutes; // Convert to minutes since midnight
            
            // Valid time range in minutes (9:00 AM is 540 minutes, 4:00 PM is 960 minutes)
            const minTime = 9 * 60;   // 9:00 AM in minutes
            const maxTime = 16 * 60;  // 4:00 PM in minutes
            
            if (totalMinutes < minTime || totalMinutes > maxTime) {
                alert('Please select a time between 9:00 AM and 4:00 PM.');
                this.value = '';  // Clear the input field if time is invalid
            }
        }
    });

    document.getElementById('edit_appointment_time').addEventListener('change', function() {
        const time = this.value;  // Get the value from the time input field
        
        if (time) {
            // Split the time value into hours and minutes
            const [hours, minutes] = time.split(':').map(num => parseInt(num, 10));
            const totalMinutes = hours * 60 + minutes; // Convert to minutes since midnight
            
            // Valid time range in minutes (9:00 AM is 540 minutes, 4:00 PM is 960 minutes)
            const minTime = 9 * 60;   // 9:00 AM in minutes
            const maxTime = 16 * 60;  // 4:00 PM in minutes
            
            if (totalMinutes < minTime || totalMinutes > maxTime) {
                alert('Please select a time between 9:00 AM and 4:00 PM.');
                this.value = '';  // Clear the input field if time is invalid
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Listen for clicks on the notification link
        const notificationLinks = document.querySelectorAll('.notification-link');
        notificationLinks.forEach(link => {
            link.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                const notificationMessage = this.getAttribute('data-message');

                // Update the modal content with the notification message
                document.getElementById('modalMessageContent').innerText = notificationMessage;

                // Make AJAX request to update the notification as read
                fetch('controller/readNotification.php?id=' + notificationId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Update the link color to gray to indicate that the notification is read
                            link.style.color = 'gray';
                        } else {
                            console.error('Failed to update notification');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating notification:', error);
                    });
            });
        });


        // Get today's date
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;

        // Set the min attribute for appointment date to today's date
        document.getElementById("appointmentDate").setAttribute("min", today);
        document.getElementById("edit_appointment_date").setAttribute("min", today);

        document.getElementById("appointmentDate").addEventListener("change", function() {
            var selectedDate = new Date(this.value);
            if (selectedDate < today) {
                alert("You cannot set an appointment for a past date. Please choose a valid date.");
                this.value = ''; // Clear the input field
            }
        });

        document.getElementById("edit_appointment_date").addEventListener("change", function() {
            var selectedDate = new Date(this.value);
            if (selectedDate < today) {
                alert("You cannot set an appointment for a past date. Please choose a valid date.");
                this.value = ''; // Clear the input field
            }
        });
    });
    // Initialize Parsley validation
    $(document).ready(function() {
        $('#addappointmentForm').parsley();

        // Check the selected radio button on page load and set field visibility
        const appointmentType = document.querySelector('input[name="appointmentType"]:checked');
        if (appointmentType) {
            toggleNameFields(appointmentType);
        }
    });

    // Function to toggle visibility and requirement of name fields
    function toggleNameFields(element) {
        const nameFields = document.getElementById("nameFields");
        const firstName = document.getElementById("userName");
        const lastName = document.getElementById("lastName");
        const contactNumber = document.getElementById("contactNumber");
        const emailAddress = document.getElementById("emailAddress");

        if (element.value === "myself") {
            // Hide name fields for "For Myself" and remove Parsley validation
            nameFields.style.display = "none";
            firstName.removeAttribute("required");
            lastName.removeAttribute("required");
            contactNumber.removeAttribute("required");
            emailAddress.removeAttribute("required");
            $(firstName).parsley().reset();
            $(lastName).parsley().reset();
            $(contactNumber).parsley().reset();
            $(emailAddress).parsley().reset();
        } else {
            // Show name fields for "New Patient" and add Parsley validation
            nameFields.style.display = "block";
            firstName.setAttribute("required", "true");
            lastName.setAttribute("required", "true");
            contactNumber.setAttribute("required", "true");
            emailAddress.setAttribute("required", "true");
        }
    }

</script>