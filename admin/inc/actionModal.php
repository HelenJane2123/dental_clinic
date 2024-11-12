<?php foreach ($get_appointments as $appointments): ?>
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal<?= $appointments['appointment_id'] ?>" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve the appointment for <?= htmlspecialchars($appointments['patient_first_name']) ?> <?= htmlspecialchars($appointments['patient_first_name']) ?>?</p>
                    <form action="controller/updateAppointmentStatus.php" method="POST">
                        <input type="hidden" name="appointment_id" value="<?= $appointments['appointment_id'] ?>">
                        <input type="hidden" name="patient_id" value="<?= $appointments['patient_id'] ?>">
                        <input type="hidden" name="user_id_admin" value="<?= $user_id_admin ?>"> <!-- Add this line -->
                        <input type="hidden" name="action" value="approve">
                        <textarea class="form-control" name="notes" placeholder="Add notes..." rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div class="modal fade" id="rescheduleModal<?= $appointments['appointment_id'] ?>" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rescheduleModalLabel">Reschedule Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Suggest a new date and time for the appointment:</p>
                    <form action="controller/updateAppointmentStatus.php" method="POST">
                        <input type="hidden" name="appointment_id" value="<?= $appointments['appointment_id'] ?>">
                        <input type="hidden" name="user_id_admin" value="<?= $user_id_admin ?>"> <!-- Add this line -->
                        <input type="hidden" name="patient_id" value="<?= $appointments['patient_id'] ?>">
                        <input type="hidden" name="action" value="reschedule">
                        <input type="date" class="form-control appointmentDate" id="appointmentDate<?= $appointments['appointment_id'] ?>" name="new_date" required>
                        <input type="time" class="form-control appointment_time" id="appointment_time<?= $appointments['appointment_id'] ?>" name="new_time" required>
                        <textarea class="form-control" name="notes" placeholder="Add notes..." rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Reschedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal<?= $appointments['appointment_id'] ?>" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancel Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel the appointment for <?= htmlspecialchars($appointments['patient_first_name']) ?> <?= htmlspecialchars($appointments['patient_last_name']) ?>?</p>
                    <form action="controller/updateAppointmentStatus.php" method="POST">
                        <input type="hidden" name="appointment_id" value="<?= $appointments['appointment_id'] ?>">
                        <input type="hidden" name="user_id_admin" value="<?= $user_id_admin ?>"> <!-- Add this line -->
                        <input type="hidden" name="patient_id" value="<?= $appointments['patient_id'] ?>">
                        <input type="hidden" name="action" value="cancel">
                        <textarea class="form-control" name="notes" placeholder="Reason for cancelation." rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

 <!-- Add Doctor Modal -->
 <div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDoctorModalLabel">Add Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDoctorForm" action="controller/addDoctor.php" name="register" method="POST">
                    <!-- Hidden input to set user_type as 'admin' by default -->
                    <input type="hidden" name="user_type" value="admin">
                    <input type="hidden" id="submissionStatus" name="submission_status" value="">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Default Password</label>
                        <input type="text" class="form-control" id="password" name="password" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="specialty" class="form-label">Specialty</label>
                        <input type="text" class="form-control" id="specialty" name="specialty" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button"  onclick="doctorsubmitForm()" class="btn btn-primary">Add Doctor</button>
            </div>
                </form>
        </div>
    </div>
</div>
<!-- Edit Doctor Modal -->
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDoctorModalLabel">Edit Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDoctorForm" action="controller/editDoctor.php" method="POST">
                    <input type="hidden" id="edit_doctor_id" name="doctor_id">
                    <input type="hidden" name="user_type" value="admin">
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="edit_contact_number" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_specialty" class="form-label">Specialty</label>
                        <input type="text" class="form-control" id="edit_specialty" name="specialty" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Delete Doctor Modal -->
<div class="modal fade" id="deleteDoctorModal" tabindex="-1" aria-labelledby="deleteDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDoctorModalLabel">Delete Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this doctor?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteDoctorForm" action="controller/deleteDoctor.php" method="POST">
                    <input type="hidden" id="delete_doctor_id" name="doctor_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Validate time for each appointment time input
    document.querySelectorAll('.appointment_time').forEach(function(timeInput) {
        timeInput.addEventListener('change', function() {
            const time = this.value;

            if (time) {
                const [hours, minutes] = time.split(':').map(num => parseInt(num, 10));
                const totalMinutes = hours * 60 + minutes;

                const minTime = 9 * 60;   // 9:00 AM in minutes
                const maxTime = 16 * 60;  // 4:00 PM in minutes

                if (totalMinutes < minTime || totalMinutes > maxTime) {
                    alert('Please select a time between 9:00 AM and 4:00 PM.');
                    this.value = '';  // Clear the input field if time is invalid
                }
            }
        });
    });

    // Validate date for each appointment date input
    document.querySelectorAll('.appointmentDate').forEach(function(dateInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                alert("You cannot set an appointment for a past date. Please choose a valid date.");
                this.value = ''; // Clear the input field if date is invalid
            }
        });
    });

</script>