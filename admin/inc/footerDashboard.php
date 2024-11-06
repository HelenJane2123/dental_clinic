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
    <script src="vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="vendors/apexcharts/apexcharts.js"></script>
    <script src="js/pages/dashboard.js"></script>

    <script>
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
                    location.reload();
                } else {
                    alert('Failed to approve appointment.');
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
                    location.reload();
                } else {
                    alert('Failed to reschedule appointment.');
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
                    location.reload();
                } else {
                    alert('Failed to cancel appointment.');
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
    </script>
      
      <script src="js/main.js"></script>
</body>

</html>