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

            let dataTableInstance = null;

            document.getElementById('patientListModal').addEventListener('shown.bs.modal', function() {
                if (!dataTableInstance) {
                    const table = document.querySelector('#table_assignee');
                    dataTableInstance = new simpleDatatables.DataTable(table);
                }
                if (currentDoctorId !== null) {
                    // Loop through each form and find the doctor_id hidden input field
                    document.querySelectorAll('.doctorIdInput').forEach(function(input) {
                        input.value = currentDoctorId;
                    });
                }
            });

            document.getElementById('viewDoctorModal').addEventListener('shown.bs.modal', function() {
                if (!dataTableInstance) {
                    const table = document.querySelector('#patientsTable');
                    dataTableInstance = new simpleDatatables.DataTable(table);
                }
            });

            // Handle the Assign Patient button click, which passes the correct doctor ID
            document.querySelectorAll('.assign-patient-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Get doctor_id from the button's data attribute
                    currentDoctorId = button.getAttribute("data-doctor-id");

                    // Update all hidden doctor_id inputs when the button is clicked
                    document.querySelectorAll('.doctorIdInput').forEach(function(input) {
                        input.value = currentDoctorId;
                    });
                });
            });
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

        function addRow() {
            var table = document.getElementById('dentalTable').getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);

            newRow.innerHTML = `
                <td><input type="date" name="date[]" class="form-control" required></td>
                <td><input type="text" name="tooth_no[]" class="form-control"></td>
                <td><input type="text" name="procedure[]" class="form-control" required></td>
                <td><input type="text" name="dentist[]" class="form-control" required></td>
                <td><input type="number" name="amount_charged[]" class="form-control" step="0.01" required></td>
                <td><input type="number" name="amount_paid[]" class="form-control" step="0.01" required></td>
                <td><input type="number" name="balance[]" class="form-control" step="0.01" required readonly></td>
                <td><input type="date" name="next_appointment[]" class="form-control" required></td>
                <td><input type="text" name="medication[]" class="form-control" disabled></td>
                <td><textarea name="dosage[]"  style="width: 250px; height: 200px; font-size: 16px; padding: 10px;" disabled class="form-control"></textarea></td>
                <td><textarea name="instructions[]"  style="width: 250px; height: 200px; font-size: 16px; padding: 10px;" disabled class="form-control"></textarea></td>
                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
            `;

            // Attach event listeners to the new Amount Charged and Amount Paid inputs
            var newChargedInput = newRow.querySelector('input[name="amount_charged[]"]');
            var newPaidInput = newRow.querySelector('input[name="amount_paid[]"]');

            newChargedInput.addEventListener('input', function() {
                updateBalance(this);
            });

            newPaidInput.addEventListener('input', function() {
                updateBalance(this);
            });
        }

        // Remove a row from the table
        function removeRow(button) {
            var row = button.closest('tr');
            row.parentNode.removeChild(row);
        }

        function printFormRecord() {
            // Create a new window
            var printWindow = window.open('', '', 'width=800,height=600');
            
            // Get the HTML content of the form container
            var formContent = document.getElementById('patient_record_form').innerHTML;
            var dentalTable = document.getElementById('dentalTable');


            // Set the content of the new window
            printWindow.document.write('<html><head><title>Patient Information form</title>');
            
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; line-height: 1.5; padding: 20px; margin: 0; text-align: left; }'); // Align text to left
            printWindow.document.write('.print-container { width: 100%; max-width: 900px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; background-color: #f9f9f9; }');
            printWindow.document.write('.category { margin-bottom: 30px; }'); // Margin between categories
            printWindow.document.write('.category h2 { background-color: #007bff; color: white; padding: 10px; margin: 0; font-size: 18px; text-align: left; }'); // Align h2 to left

            // Update row to ensure labels and values are aligned in separate columns
            printWindow.document.write('.category .row { display: flex; flex-wrap: wrap; margin-bottom: 10px; }'); // Flexbox for rows
            printWindow.document.write('.label-value { flex: 1 1 30%; margin-bottom: 10px; padding: 0 10px; box-sizing: border-box; display: flex; justify-content: space-between; align-items: center; }'); // Align label and value in column

            // Styling for labels and values
            printWindow.document.write('label { font-weight: bold; display: inline-block; }'); // Set label width and bold
            printWindow.document.write('span { display: inline-block; width: 65%; }'); // Set span width to align with label

            // Input fields alignment
            printWindow.document.write('input, select, textarea { border: none; background-color: transparent; font-size: 14px; padding: 5px; width: 100%; text-align: left; box-sizing: border-box; }'); // Align input fields

            // Hide buttons and extra elements
            printWindow.document.write('.btn { display: none; }'); // Hide buttons
            printWindow.document.write('.no-print { display: none; }'); // Hide elements marked as no-print
            printWindow.document.write('</style>');
            
            printWindow.document.write('</head><body>');

            // Add clinic logo and header before the form content
            printWindow.document.write('<div style="text-align:center; margin-bottom: 10px;">'); // Reduced bottom margin
            printWindow.document.write('<img src="../img/logo.png" alt="Clinic Logo" style="max-width: 150px; margin-bottom: 5px;">'); // Reduced bottom margin for logo
            printWindow.document.write('<h1 style="margin-bottom: 5px;">Roselle Santander Dental Clinic</h1>'); // Reduced bottom margin for header
            printWindow.document.write('<p style="margin-bottom: 5px;">Address: 2nd flr. EDP Bldg. San Juan I Gen. Trias, Cavite</p>'); // Reduced bottom margin for address
            printWindow.document.write('<p style="margin-bottom: 5px;">Telephone: 09954993703</p>'); // Reduced bottom margin for telephone
            printWindow.document.write('<p>Patient ID: ' + (document.querySelector('#member_id') ? document.querySelector('#member_id').value : 'N/A') + '</p>');
            printWindow.document.write('<p>Assigned Doctor: ' + (document.querySelector('#doctor') ? document.querySelector('#doctor').value : 'N/A') + '</p>');
            printWindow.document.write('</div>');
            
            // Wrap the form content in a div with a print container class
            printWindow.document.write('<div class="print-container">');
            
            printWindow.document.write('<div class="category">');
            printWindow.document.write('<h2>Personal Information</h2>');
            // Start of the first row with first name, last name, and middle name
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>First Name:</label><span>' + (document.querySelector('#first_name') ? document.querySelector('#first_name').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Last Name:</label><span>' + (document.querySelector('#last_name') ? document.querySelector('#last_name').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Middle Name:</label><span>' + (document.querySelector('#middle_name') ? document.querySelector('#middle_name').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of first row

            // New row after the middle name field
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Birth Day:</label><span>' + (document.querySelector('#birth_date') ? document.querySelector('#birth_date').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Age:</label><span>' + (document.querySelector('#age') ? document.querySelector('#age').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Gender:</label><span>' + (document.querySelector('#sex') ? document.querySelector('#sex').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of second row

            // New row after the middle name field
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Nickname:</label><span>' + (document.querySelector('#nickanme') ? document.querySelector('#nickname').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Religion:</label><span>' + (document.querySelector('#religion') ? document.querySelector('#religion').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Nationality:</label><span>' + (document.querySelector('#nationality') ? document.querySelector('#nationality').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of second row

            // New row after the middle name field
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Cellphone No:</label><span>' + (document.querySelector('#cellphone_no') ? document.querySelector('#cellphone_no').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Email Address:</label><span>' + (document.querySelector('#email') ? document.querySelector('#email').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Home Address:</label><span>' + (document.querySelector('#home_address') ? document.querySelector('#home_address').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of second row

            // New row after the middle name field
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Occupation:</label><span>' + (document.querySelector('#occupation') ? document.querySelector('#occupation').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Guardian Name:</label><span>' + (document.querySelector('#guardian_name') ? document.querySelector('#guardian_name').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Guardian Occupation:</label><span>' + (document.querySelector('#guardian_occupation') ? document.querySelector('#guardian_occupation').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of second row
            printWindow.document.write('</div>'); // End of category div
            
            printWindow.document.write('<div class="category">');
            printWindow.document.write('<h2>Referral Information</h2>');
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Whom may we thank for referring you?:</label><span>' + (document.querySelector('#referral_source') ? document.querySelector('#referral_source').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Reason for consultation:</label><span>' + (document.querySelector('#reason_for_consultation') ? document.querySelector('#reason_for_consultation').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of second row
            printWindow.document.write('</div>');
            
            printWindow.document.write('<div class="category">');
            printWindow.document.write('<h2>Dental History</h2>');
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Previous Dentist:</label><span>' + (document.querySelector('#previous_dentist') ? document.querySelector('#previous_dentist').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Last Dental Visit:</label><span>' + (document.querySelector('#last_dental_visit') ? document.querySelector('#last_dental_visit').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of second row
            printWindow.document.write('</div>');

            printWindow.document.write('<div class="category">');
            printWindow.document.write('<h2>Medical History</h2>');
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Physician Name:</label><span>' + (document.querySelector('#physician_name') ? document.querySelector('#physician_name').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Specialty:</label><span>' + (document.querySelector('#physician_specialty') ? document.querySelector('#physician_specialty').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of second row
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Office Address:</label><span>' + (document.querySelector('#physician_address') ? document.querySelector('#physician_address').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Office Number:</label><span>' + (document.querySelector('#physician_phone_no') ? document.querySelector('#physician_phone_no').value : 'N/A') + '</span></div>');
            printWindow.document.write('</div>'); // End of second row
            printWindow.document.write('</div>');

            printWindow.document.write('<div class="category">');
            printWindow.document.write('<h2>Health Information</h2>');
            printWindow.document.write('<div class="row">');

            // Get the selected value from the dropdown (assuming the dropdown ID is #good_health)
            var goodHealthValue = document.querySelector('#good_health') ? document.querySelector('#good_health').value : 'N/A';
            var medicalTreatment = document.querySelector('#under_medical_treatment') ? document.querySelector('#under_medical_treatment').value : 'N/A';
            var seriousillness = document.querySelector('#serious_illness') ? document.querySelector('#serious_illness').value : 'N/A';
            var hospitalized = document.querySelector('#hospitalization') ? document.querySelector('#hospitalization').value : 'N/A';
            var takingMedication = document.querySelector('#taking_medication') ? document.querySelector('#taking_medication').value : 'N/A';
            var useTobacco = document.querySelector('#use_tobacco') ? document.querySelector('#use_tobacco').value : 'N/A';
            var useDrugs = document.querySelector('#use_drugs') ? document.querySelector('#use_drugs').value : 'N/A';


            // Check if the selected value is '1' for Yes, else display No
            var healthStatus = (goodHealthValue === '1') ? 'Yes' : (goodHealthValue === '0' ? 'No' : 'N/A');
            var medicalTreatmentStatus = (medicalTreatment === '1') ? 'Yes' : (medicalTreatment === '0' ? 'No' : 'N/A');
            var seriousillnessStatus = (seriousillness === '1') ? 'Yes' : (seriousillness === '0' ? 'No' : 'N/A');
            var hospitalizedStatus = (hospitalized === '1') ? 'Yes' : (hospitalized === '0' ? 'No' : 'N/A');
            var takingMedicationStatus = (takingMedication === '1') ? 'Yes' : (takingMedication === '0' ? 'No' : 'N/A');
            var useTobaccoStatus = (useTobacco === '1') ? 'Yes' : (useTobacco === '0' ? 'No' : 'N/A');
            var useDrugsStatus = (useDrugs === '1') ? 'Yes' : (useDrugs === '0' ? 'No' : 'N/A');

            printWindow.document.write('<div class="label-value"><label>Are you in good health? :</label><span>' + healthStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Are you under medical treatment now? :</label><span>' + medicalTreatmentStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Are you under medical treatment now? :</label><span>' + seriousillnessStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Have you had a serious illness or operation? :</label><span>' + seriousillnessStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>If yes, what illness/operation? :</label><span>' + (document.querySelector('#illness_details') ? document.querySelector('#illness_details').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Have you been hospitalized? :</label><span>' + hospitalizedStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>If yes, why? :</label><span>' + (document.querySelector('#hospitalization_reason') ? document.querySelector('#hospitalization_reason').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Are you taking any prescription/non-prescription medication? :</label><span>' + hospitalizedStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>If yes, what medication? :</label><span>' + (document.querySelector('#medication_details') ? document.querySelector('#medication_details').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Do you use tobacco products? :</label><span>' + useTobaccoStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Do you use alcohol or other dangerous drugs? :</label><span>' + useDrugsStatus + '</span></div>');
            // Get all the checkboxes with the class 'allergy'
            var selectedAllergies = [];

            // Loop through each checkbox to check if it's selected
            document.querySelectorAll('.allergy').forEach(function(checkbox) {
                if (checkbox.checked) {
                    selectedAllergies.push(checkbox.value); // Add selected value to the array
                }
            });

            // Check if "Others" is selected and retrieve its value
            var otherAllergiesValue = '';
            var othersCheckbox = document.querySelector('input[name="allergies[]"][value="Others"]');
            if (othersCheckbox && othersCheckbox.checked) {
                otherAllergiesValue = document.querySelector('input[name="other_allergies"]').value; // Get the value from the text input field
            }

            // Add "Others" value to the selected allergies list if it was checked
            if (othersCheckbox && othersCheckbox.checked && otherAllergiesValue) {
                selectedAllergies.push('Others: ' + otherAllergiesValue);
            }

            // Join the selected allergies into a readable string
            var allergiesText = selectedAllergies.length > 0 ? selectedAllergies.join(', ') : 'None';

            // Print the selected allergies to the print window
            printWindow.document.write('<div class="label-value"><label>Allergies:</label><span>' + allergiesText + '</span></div>');
            
            printWindow.document.write('</div>'); // End of row
            printWindow.document.write('</div>'); // End of category

            printWindow.document.write('<div class="category">');
            printWindow.document.write('<h2>Information for Women</h2>');
            printWindow.document.write('<div class="row">');
            
            var pregnantValue = document.querySelector('#pregnant') ? document.querySelector('#pregnant').value : 'N/A';
            var nursingValue = document.querySelector('#nursing') ? document.querySelector('#nursing').value : 'N/A';
            var birthControlValue = document.querySelector('#birth_control') ? document.querySelector('#birth_control').value : 'N/A';

            var pregnantStatus = (pregnantValue === '1') ? 'Yes' : (pregnantValue === '0' ? 'No' : 'N/A');
            var nursingStatus = (nursingValue === '1') ? 'Yes' : (nursingValue === '0' ? 'No' : 'N/A');
            var birthControlStatus = (birthControlValue === '1') ? 'Yes' : (birthControlValue === '0' ? 'No' : 'N/A');
            
            printWindow.document.write('<div class="label-value"><label>Are you pregnant? :</label><span>' + pregnantStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Are you nursing? :</label><span>' + nursingStatus + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Are you taking birth control pills? :</label><span>' + birthControlStatus + '</span></div>');
            printWindow.document.write('</div></div>');

            printWindow.document.write('<div class="category">');
            printWindow.document.write('<h2>Other Medical Information</h2>');
            printWindow.document.write('<div class="row">');
            printWindow.document.write('<div class="label-value"><label>Blood Type:</label><span>' + (document.querySelector('#blood_type') ? document.querySelector('#blood_type').value : 'N/A') + '</span></div>');
            printWindow.document.write('<div class="label-value"><label>Blood Pressure:</label><span>' + (document.querySelector('#blood_pressure') ? document.querySelector('#blood_type').value : 'N/A') + '</span></div>');
            // Get all the checkboxes with the class 'allergy'
            var selectedMedicalCondition = [];

            // Loop through each checkbox to check if it's selected
            document.querySelectorAll('.medical_conditions').forEach(function(checkbox) {
                if (checkbox.checked) {
                    selectedMedicalCondition.push(checkbox.value); // Add selected value to the array
                }
            });

            // Join the selected allergies into a readable string
            var medicalConditionText = selectedMedicalCondition.length > 0 ? selectedMedicalCondition.join(', ') : 'None';

            // Print the selected allergies to the print window
            printWindow.document.write('<div class="label-value"><label>Medical Condition:</label><span>' + medicalConditionText + '</span></div>');
            printWindow.document.write('</div>'); // End of row
            printWindow.document.write('</div>'); // End of category
            

            printWindow.document.write('<div class="category">');
            printWindow.document.write('<h2>Dental Records</h2>');

            if (dentalTable) {
                var tableClone = dentalTable.cloneNode(true);
                // Remove actions column (if it exists)
                var actionsIndex = Array.from(tableClone.querySelectorAll('th')).findIndex(th => th.textContent === 'Actions');
                if (actionsIndex > -1) {
                    tableClone.querySelectorAll('tr').forEach(row => {
                        row.removeChild(row.children[actionsIndex]);
                    });
                }
                printWindow.document.write(tableClone.outerHTML);
            } else {
                printWindow.document.write('<p>No dental records available.</p>');
            }

            printWindow.document.write('</div>'); // End of dental records section
            printWindow.document.write('</div>'); // End of container

            
            // Close the content container and body
            printWindow.document.write('</div>');
            printWindow.document.write('</body></html>');
            
            
            // Close the document and trigger the print dialog
            printWindow.document.close();
            printWindow.print();
        }  
    </script>
      
      <script src="js/main.js"></script>
</body>

</html>