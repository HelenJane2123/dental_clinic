<footer>
                <div class="footer clearfix mb-0 text-sub-muted">
                    <div class="float-start">
                        <p>2024 &copy; Roselle Santander's Dental Clinic</p>
                    </div>
                    <div class="float-end">
                        <p>For Capstone purposes only <span class="text-danger"><i class="bi bi-heart"></i></span> by BSIT</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notification Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content will be populated dynamically with JavaScript -->
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Hidden Form for submitting notification update -->
    <form id="notificationForm" method="POST" action="controller/updateNotification.php" style="display: none;">
        <input type="hidden" name="notification_id" id="notificationId">
    </form>

    <div class="modal fade" id="genericModal" tabindex="-1" aria-labelledby="genericModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="genericModalLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="genericModalMessage">
                    <!-- Message will be dynamically populated -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    <script src="vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/pages/dashboard.js"></script>

    <script>
        function showModal(title, message) {
            // Set the title and message dynamically
            document.getElementById('genericModalLabel').textContent = title;
            document.getElementById('genericModalMessage').textContent = message;

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('genericModal'));
            modal.show();
        }
        // Simple Datatable
        document.addEventListener("DOMContentLoaded", function() {
            const dataTableElement = document.getElementById("table1");
            const dataTable = new simpleDatatables.DataTable(dataTableElement);
        });

        function toggleStatus(button, notificationId) {
            // Confirm the action if needed
            if (confirm("Are you sure you want to mark this notification as read?")) {
                fetch(`controller/updateNotification.php?notification_id=${notificationId}`, {
                            method: 'GET',
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // Expect JSON response
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Update your UI as needed, e.g., marking the notification as read
                        button.innerText = "Marked as Read";
                        button.classList.remove("btn-success");
                        button.classList.add("btn-secondary");
                        button.disabled = true; // Disable the button to prevent re-clicks

                        location.reload();
                    } else {
                        console.error(data.message); // Log the error message
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
            }
        }

        const username = '<?= $_SESSION['username'] ?>';
        const memberId = <?= json_encode($member_id_admin) ?>;
        const userId = <?= json_encode($user_id_admin) ?>;

        function approveAppointment(appointmentId) {
            const notes = document.getElementById(`approveNotes${appointmentId}`).value;

            // Use memberId and userId in your request body
            fetch('controller/updateAppointmentStatus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    appointment_id: appointmentId, 
                    notes: notes,
                    updated_by: userId, // Send the user ID
                    member_id: memberId  // Send the member ID
                })
            })
            .then(response => {
                if (response.ok) {
                    showModal('Appointment Approved', 'The appointment has been successfully approved.');
                    location.reload();
                } else {
                    showModal('Error', 'Failed to approve appointment.');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function rescheduleAppointment(appointmentId) {
            const newDate = document.getElementById(`newDate${appointmentId}`).value;
            const newTime = document.getElementById(`newTime${appointmentId}`).value;
            const notes = document.getElementById(`rescheduleNotes${appointmentId}`).value;

            // Use memberId and userId in your request body
            fetch('controller/updateAppointmentStatus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    appointment_id: appointmentId, 
                    new_date: newDate, 
                    new_time: newTime, 
                    notes: notes,
                    updated_by: userId, // Send the user ID
                    member_id: memberId  // Send the member ID
                })
            })
            .then(response => {
                if (response.ok) {
                    showModal('Appointment Rescheduled', 'The appointment has been successfully rescheduled.');
                    location.reload();
                } else {
                    showModal('Appointment Reschedule Failed', 'Failed to reschedule appointment.');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function cancelAppointment(appointmentId) {
            const notes = document.getElementById(`cancelNotes${appointmentId}`).value;

            // Use memberId and userId in your request body
            fetch('controller/updateAppointmentStatus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    appointment_id: appointmentId, 
                    notes: notes,
                    updated_by: userId, // Send the user ID
                    member_id: memberId  // Send the member ID
                })
            })
            .then(response => {
                if (response.ok) {
                    showModal('Appointment Canceled', 'Appointment successfully canceled.');
                    location.reload();
                } else {
                    showModal('Appointment Cancelation Failed', 'Failed to cancel appointment.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
        document.addEventListener('DOMContentLoaded', function () {
            // Event delegation to handle dynamic content
            document.querySelector('.notification-list').addEventListener('click', function (event) {
                const notificationLink = event.target.closest('a');
                if (notificationLink) {
                    const notificationId = notificationLink.getAttribute('data-id'); // Get the notification ID
                    const notificationMessage = notificationLink.getAttribute('data-message'); // Get the message
                    
                    // Set the message in the modal
                    document.getElementById('modalMessage').textContent = notificationMessage;

                    // Show the modal
                    $('#notificationModal').modal('show');

                    // Update the notification status to read
                    updateNotificationStatus(notificationId);
                }
            });

            // Optional: Close modal when clicking outside of it (Bootstrap handles this by default)
            $('#notificationModal').on('hidden.bs.modal', function () {
                // Clear the message when modal is closed (if desired)
                document.getElementById('modalMessage').textContent = '';
            });
        });

        // Function to update notification status
        function updateNotificationStatus(notificationId) {
            fetch(`controller/updateNotification.php?notification_id=${notificationId}`, {
                method: 'GET',
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Expect JSON response
            })
            .then(data => {
                if (data.status === 'success') {
                    // Update your UI as needed, e.g., marking the notification as read
                    const notificationLink = document.querySelector(`a[data-id="${notificationId}"]`);
                    if (notificationLink) {
                        notificationLink.classList.remove('notification-unread');
                        notificationLink.classList.add('notification-read');
                    }
                } else {
                    console.error(data.message); // Log the error message
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        }
        // Close the modal when the close button is clicked
        document.querySelector('.modal .close').addEventListener('click', function () {
            $('#notificationModal').modal('hide'); // Hide the modal
        });

        document.querySelector('.modal .close-btn').addEventListener('click', function () {
            $('#notificationModal').modal('hide'); // Hide the modal
        });

        function doctorsubmitForm() {

            // Now submit the form
            document.getElementById('addDoctorForm').submit();
        }

        // Prepare data for the chart
        const patientCounts = <?php echo json_encode(array_values($monthlyPatientCounts)); ?>;
        const monthLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const currentYear = new Date().getFullYear(); // Get the current year

        // Configure and display the chart
        const ctx = document.getElementById('patientChart').getContext('2d');
        const patientChart = new Chart(ctx, {
            type: 'bar', // Choose 'bar' or 'line'
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Patients per Month (' + currentYear + ')',
                    data: patientCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)', // Light teal color
                    borderColor: 'rgba(75, 192, 192, 1)', // Teal color
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        function openPatientModal(doctorId) {
            // Set the doctor ID in a hidden input within the modal
            document.getElementById('doctorIdInput').value = doctorId;
        }

    </script>
      
      <script src="js/main.js"></script>
</body>

</html>