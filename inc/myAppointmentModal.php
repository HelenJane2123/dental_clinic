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
                <form action="controller/setAppointment.php" method="POST" name="appointmentForm" novalidate enctype="multipart/form-data" id="appointmentForm">
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
            <form action="controller/editAppointment.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="edit_appointment_id">
                    
                    <div class="form-group">
                        <label for="edit_appointment_date">New Appointment Date (Leave empty to keep the same)</label>
                        <input type="date" class="form-control" id="edit_appointment_date" name="appointment_date">
                    </div>
                    <div class="form-group">
                        <label for="edit_appointment_time">New Appointment Time (Leave empty to keep the same)</label>
                        <input type="time" class="form-control" id="edit_appointment_time" name="appointment_time">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Rescheduled">Rescheduled</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_notes">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
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
</div