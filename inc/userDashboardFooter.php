
<!-- content-wrapper ends -->
        <!-- partial:./partials/_footer.html -->
        <footer class="footer">
          <div class="card">
            <div class="card-body">
              <div class="d-sm-flex justify-content-center justify-content-sm-between py-2">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © <a href="https://www.bootstrapdash.com/" target="_blank"> rosellesantanderdentalclinic </a>2024</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">For Capstone purposes only</span>
              </div>
            </div>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <div class="modal fade" id="genericModal" tabindex="-1" aria-labelledby="genericModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="genericModalLabel"></h5>
                <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="genericModalMessage"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
</div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <script src="js/dashboard/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/dashboard/off-canvas.js"></script>
  <script src="js/dashboard/hoverable-collapse.js"></script>
  <script src="js/dashboard/template.js"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
    <script src="js/dashboard/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="js/dashboard/dashboard.js"></script>
  <!-- End custom js for this page-->
 <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

  <script src='vendors/fullcalendar/packages/core/main.js'></script>
  <script src='vendors/fullcalendar/packages/interaction/main.js'></script>
  <script src='vendors/fullcalendar/packages/daygrid/main.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>

  <!-- DataTables JS -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  

  <script>
    function showModal(modal_title, message, callback) {
        // Set modal title and message
        $('#genericModalLabel').text(modal_title);
        $('#genericModalMessage').text(message);

        // Apply color classes based on the title type
        const modalTitle = $('#genericModalLabel')[0];
        modalTitle.classList.remove('text-danger', 'text-warning', 'text-success', 'text-dark');

        if (modal_title.toLowerCase() === 'error') {
            modalTitle.classList.add('text-danger');
        } else if (modal_title.toLowerCase() === 'warning') {
            modalTitle.classList.add('text-warning');
        } else if (modal_title.toLowerCase() === 'success') {
            modalTitle.classList.add('text-success');
        } else if (modal_title.toLowerCase() === 'notification') {
            modalTitle.classList.add('text-dark');
        }

        // Show modal without affecting table
        $('#genericModal').modal({ backdrop: 'static', keyboard: false }).modal('show');

        // Run the callback function when modal closes
        $('#genericModal').on('hidden.bs.modal', function() {
            if (typeof callback === 'function') {
                callback();
            }
        });
    }
    function closeModal() {
        // For Bootstrap 4, using jQuery to hide the modal
        $('#genericModal').modal('hide');
    }

    var patientCount = <?php echo $patientCount; ?>;
    document.addEventListener('DOMContentLoaded', function() {
      const statusColors = {
          'Pending': '#FFC107',   // Yellow
          'Confirmed': '#28A745', // Green
          'Canceled': '#DC3545',  // Red
          'Completed': '#007BFF',  // Blue
          'Re-schedule': '#A020F0'  // Purple
      };
      let hoveredDate = null;
      var calendarEl = document.getElementById('calendar');

      // Prepare events from PHP appointments
      var events = [
        <?php foreach ($appointments as $appointment): ?>
          {
            id: '<?php echo htmlspecialchars($appointment['id']); ?>',
            title: '<?php echo htmlspecialchars(
                $appointment['services'] === 'cleaning' ? 'Teeth Cleaning' :
                ($appointment['services'] === 'extraction' ? 'Tooth Extraction' : 
                ($appointment['services'] === 'filling' ? 'Dental Filling' : 
                ($appointment['services'] === 'checkup' ? 'Dental Checkup' : 
                ($appointment['services'] === 'whitening' ? 'Teeth Whitening' : 
                ($appointment['services'] === 'brace_adjustment' ? 'Brace Adjustment' : 
                ($appointment['services'] === 'brace_consultation' ? 'Braces Consultation' : 
                ($appointment['services'] === 'brace_installation' ? 'Dental Braces Installation' : 
                $appointment['services'])))))))); ?>',
            start: '<?php echo htmlspecialchars($appointment['appointment_date']); ?>',
            time: '<?php 
                $time = new DateTime($appointment['appointment_time']);
                echo htmlspecialchars($time->format('g:i A')); // Convert to 12-hour format with AM/PM
            ?>',
            <?php if (!empty($appointment['notes'])): ?>
              description: '<?php echo htmlspecialchars($appointment['notes']); ?>',
            <?php endif; ?>
            <?php if (!empty($appointment['status'])): ?>
              status: '<?php echo htmlspecialchars($appointment['status']); ?>',
            <?php endif; ?>
          },
        <?php endforeach; ?>
      ];

      var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['interaction', 'dayGrid'],
        defaultDate: '2024-09-24',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: events,

        validRange: {
            start: new Date() // Disable dates before today
        },

        eventRender: function(info) {
            const status = info.event.extendedProps.status; // Get the status from event props
            const color = statusColors[status] || '#000'; // Default color if status not found

            // Set the color for the event title
            info.el.style.backgroundColor = color; // Set the background color
            info.el.style.color = '#fff'; // Set the text color to white for contrast
            info.el.querySelector('.fc-title').style.color = '#fff'; // Ensure title is white
        },
        
        // Block all Saturdays
        dayRender: function(info) {
            // Block all Saturdays
            if (info.date.getDay() === 6) {
                info.el.style.backgroundColor = '#f0f0f0';
                info.el.style.pointerEvents = 'none';
                info.el.innerHTML = '<div style="text-align: center; padding-top: 10px; color: red;">Closed</div>';
            } else {
                // For past dates
                if (info.date < new Date()) {
                    info.el.style.backgroundColor = '#f0f0f0'; // Light gray for past dates
                    info.el.style.color = '#aaa'; // Gray text for past dates
                    info.el.style.pointerEvents = 'none'; // Disable interaction
                    //info.el.innerHTML = `<div style="text-align: center; padding-top: 10px;">${info.date.getDate()}</div>`;
                } else {
                    info.el.classList.add('hoverable');
                }
            }
        },
        dateClick: function(info) {
            if (patientCount === 0) {
                showModal('Warning', 'You must complete your patient record before booking an appointment. Please complete your record first.', function() {
                    window.location.href = 'my_record.php'; // Redirect after modal closes
                });
                return; // Prevent further actions
            }

            if (info.date.getDay() === 6) {
                showModal('Warning', 'Appointments cannot be booked on Saturdays. Please select another date.');
                return; // Prevent the modal from opening
            }
            
            if (info.date >= new Date()) {
                // Convert the selected date to the local timezone date string
                const localDate = new Date(info.date);  // Get a copy of the date
                const year = localDate.getFullYear();
                const month = String(localDate.getMonth() + 1).padStart(2, '0');  // Get month and pad with leading zero if needed
                const day = String(localDate.getDate()).padStart(2, '0');  // Get day and pad with leading zero if needed
                
                // Set the input's value in the format yyyy-mm-dd
                const formattedDate = `${year}-${month}-${day}`;
                document.getElementById('appointmentDate').value = formattedDate;

                // Show the modal
                $('#appointmentModal').modal('show');

                // Attach event listeners to close modal
                $('.close, .btn-secondary').on('click', function() {
                    $('#appointmentModal').modal('hide');
                });
            } else {
                showModal('Warning', 'You cannot select past dates.')
            }
        },
        // Add event click handling
        eventClick: function(info) {
            // Populate modal with event details
            var detailsContent = `
                <strong>Title:</strong> ${info.event.title}<br>
                <strong>Date:</strong> ${info.event.start.toISOString().split('T')[0]}<br>
                <strong>Time:</strong> ${info.event.extendedProps.time || 'No time available'}<br>
                ${info.event.extendedProps.description ? `<strong>Notes:</strong> ${info.event.extendedProps.description}<br>` : ''}
                <strong>Status:</strong> ${info.event.extendedProps.status || 'No status available'}
            `;
            document.getElementById('appointmentDetailsContent').innerHTML = detailsContent;
            // Show the modal
            $('#appointmentDetailsModal').modal('show');

             // Attach an event listener to the close button
             $('.close, .btn-secondary').on('click', function() {
                $('#appointmentDetailsModal').modal('hide'); // Hide the modal
            });
        },
      });

      calendar.render();

      //automatically cancel appointment
      const pendingAppointments = events.filter(event => event.status === 'Pending');
      const today = new Date().toISOString().split('T')[0];

      // Get the current time in 24-hour format (HH:mm)
      const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

      pendingAppointments.forEach(appointment => {
          if (appointment.start === today) {
              // Compare current time with appointment time
              if (currentTime > convertTo24Hour(appointment.time)) {  // Convert appointment time to 24-hour format for comparison
                 // Set the message dynamically using string interpolation
                const message = `Appointment for ${appointment.title} scheduled at ${appointment.time} is still pending. It will be automatically canceled.`;

                // Store the appointment ID globally for redirection
                window.appointmentToCancel = appointment.id;

                // Show the modal with the appointment message
                showModal('Notification', message, function() {
                    window.location.href = `appointment.php?cancel=${appointment.id}`; // Redirect after modal closes
                });
              }
          }
      });
    });

    function convertTo24Hour(timeStr) {
        const [time, modifier] = timeStr.split(' ');
        let [hours, minutes] = time.split(':');
        
        // Convert hours to string before using padStart
        hours = String(hours);

        if (hours === '12') {
            hours = '00';
        }
        if (modifier === 'PM') {
            hours = (parseInt(hours, 10) + 12).toString();
        }

        return `${hours.padStart(2, '0')}:${minutes}`;
    }

  
    async function submitAppointment() {
        const appointmentDate = document.getElementById('appointmentDate').value;
        const appointmentTime = document.getElementById('appointmentTime').value;
        const doctorId = document.getElementById('doctor_id').value;

        // Log the data to check it's correct
        console.log('Sending Data:', { 
            appointmentDate: appointmentDate, 
            appointmentTime: appointmentTime, 
            doctor_id: doctorId 
        });

        // Check if date and time are selected
        if (!appointmentDate || !appointmentTime) {
            showModal('Error', 'Please select both date and time.')
            return;
        }

        try {
            // Fetch existing appointments from the server (checkAppointmentAvailability.php)
            const response = await fetch('controller/checkAppointmentAvailability.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    appointmentDate: appointmentDate,
                    appointmentTime: appointmentTime,
                    doctor_id: doctorId
                })
            });

            // Check if the response is valid
            const result = await response.json();

            console.log("check", result);

            // Check if the appointment is available
            if (result.available) {
                showModal('Success', 'Appointment successfully booked! Redirecting to the payment page...', function() {
                    window.location.href = 'payment.php'; // Redirect to the payment page
                });                
               
                // Submit the form if the slot is available
                document.getElementById('addappointmentForm').submit();
                $('#appointmentModal').modal('hide'); // Close modal
            } else {
                showModal('Warning', 'This time slot is already booked. Please choose another time.');
            }

        } catch (error) {
            console.error('Error checking availability:', error);
            showModal('Error', 'An error occurred while checking availability. Please try again later.');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        $('#uploadProofModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var appointmentId = button.data('appointment-id'); // Extract appointment ID from data-* attribute
            
            if (appointmentId) {
                var modal = $(this);
                modal.find('#appointmentID').val(appointmentId); // Set the appointmentId in the modal input field
            } else {
                console.error("Appointment ID is not available.");
            }
        });
    });

    function toggleNameFields(radio) {
      const nameFields = document.getElementById('nameFields');
      nameFields.style.display = (radio.value === 'myself') ? 'none' : 'block';
    }

    // Hide name fields by default if "Myself" is selected
    $('#appointmentModal').on('show.bs.modal', function () {
        const myselfRadio = document.querySelector('input[name="appointmentType"][value="myself"]');
        toggleNameFields(myselfRadio);
    });

    // function submitAppointment() {
    //     const form = $('#addappointmentForm');
    //     form.parsley().whenValidate().done(function() {
    //         form[0].submit();
    //     });
    // }

    // Check if the appointment details exist
    <?php if (isset($appointmentDetails)): ?>
        $(document).ready(function() {
            $('#viewAppointmentModal').modal('show'); // Show the modal

            // Attach an event listener to the close button
            $('.close, .btn-secondary').on('click', function() {
                $('#viewAppointmentModal').modal('hide'); // Hide the modal
            });
        });
    <?php endif; ?>

    $(document).ready( function () {
      $('#appointmentTable').DataTable({
          "paging": true,       // Enable pagination
          "searching": true,    // Enable search filter
          "ordering": true,     // Enable sorting
          "info": true,         // Display table information
          "autoWidth": false    // Disable automatic column width calculation
      });
    });

    function setAppointmentId(appointmentId) {
      document.getElementById('appointment_id').value = appointmentId;
    }

    function openEditModal(id, date, time, notes, status, first_name, last_name, member_id) {
        // Populate the form fields with existing appointment data
        document.getElementById('edit_appointment_id').value = id;
        document.getElementById('edit_appointment_date').value = date || ''; // Default to empty if no date
        document.getElementById('edit_appointment_time').value = time || ''; // Default to empty if no time
        document.getElementById('edit_notes').value = notes || ''; // Default to empty if no notes
        document.getElementById('status').value = status; // Set the status
        document.getElementById('first_name').value = first_name;
        document.getElementById('last_name').value = last_name;
        document.getElementById('member_id').value = member_id;

        // Show the modal
        $('#editAppointmentModal').modal('show');

        // Initialize Parsley validation when the modal is shown
        $('#editAppointmentForm').parsley();

        // Attach event listener for closing the modal
        closeModalOnButtonClick();

        // Attach event listeners to date and time fields for validation
        document.getElementById('edit_appointment_date').addEventListener('change', checkAppointmentAvailabilityEdit);
        document.getElementById('edit_appointment_time').addEventListener('change', checkAppointmentAvailabilityEdit);
    }

    // Function to check appointment availability
    async function checkAppointmentAvailabilityEdit() {
        const appointmentDate = document.getElementById('edit_appointment_date').value;
        const appointmentTime = document.getElementById('edit_appointment_time').value;
        const doctorId = document.getElementById('doctor_id').value;

        // Log the data to check it's correct
        console.log('Sending Data:', { 
            appointmentDate: appointmentDate, 
            appointmentTime: appointmentTime, 
            doctor_id: doctorId 
        });

        if (!appointmentDate || !appointmentTime) {
            return; // If either date or time is empty, skip the validation
        }

        try {
            // Perform the check via an API or server-side request
            const response = await fetch('controller/checkAppointmentAvailability.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    appointmentDate: appointmentDate,
                    appointmentTime: appointmentTime,
                    doctor_id: doctorId
                })
            });

            const result = await response.json();

            console.log("Result:", result);

            if (result.available === false) {
                showModal('Warning', 'The selected date and time is already booked. Please choose another time.');
                document.getElementById('edit_appointment_time').value = ''; // Clear the time field
            }

        } catch (error) {
            console.error('Error checking availability:', error);
            showModal('Error', 'An error occurred while checking availability. Please try again later.');
        }
    }


    // Separate function for closing the modal when close button is clicked
    function closeModalOnButtonClick() {
        $('.close, .btn-secondary').off('click').on('click', function() {
            $('#editAppointmentModal').modal('hide'); // Hide the modal
        });
    }

    document.getElementById('birthdate').addEventListener('change', function() {
        const birthdate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();
        const m = today.getMonth() - birthdate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }
        document.getElementById('age').value = age;
    });

    function toggleStatus(button, notificationId) {
        // Send an AJAX request to update the notification status
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "controller/readNotification.php?id=" + notificationId, true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                // On success, change the button text and style
                button.innerHTML = "Read";
                button.disabled = true; // Disable the button after it's clicked
                button.classList.remove("btn-warning");
                button.classList.add("btn-success");
            } else {
                showModal('Error', 'Error updating notification.');
            }
        };
        xhr.send();
    }

    $(document).ready(function() {
        // Function to show or hide the details group based on selection
        function toggleVisibility(selectId, groupId) {
            var value = $('#' + selectId).val();  // Get the selected value
            if (value == "1") {
                $('#' + groupId).show();  // Show the details input if "Yes"
            } else {
                $('#' + groupId).hide();  // Hide it if "No"
            }
        }

        // Initial check when the page loads
        toggleVisibility('serious_illness', 'serious_illness_group');
        toggleVisibility('hospitalization', 'hospitalization_details_group');
        toggleVisibility('taking_medication', 'medication_details_group');

        // Event listeners for each select element to toggle visibility based on selection
        $('#serious_illness').change(function() {
            toggleVisibility('serious_illness', 'serious_illness_group');
        });

        $('#hospitalization').change(function() {
            toggleVisibility('hospitalization', 'hospitalization_details_group');
        });

        $('#taking_medication').change(function() {
            toggleVisibility('taking_medication', 'medication_details_group');
        });
    });

    function printFormRecord() {
        // Create a new window
        var printWindow = window.open('', '', 'width=800,height=600');
        
        // Get the HTML content of the form container
        var formContent = document.getElementById('my_record_form').innerHTML;
        
        // Set the content of the new window
        printWindow.document.write('<html><head><title>Print Form</title></head><body>');
        printWindow.document.write(formContent);  // Add form content
        printWindow.document.write('</body></html>');
        
        // Close the document and trigger the print dialog
        printWindow.document.close();
        printWindow.print();
    }

    //initialize validation
    $(document).ready(function() {
        $('#my_record_form').parsley(); // Initialize Parsley

       // Handle form submission
        $('#my_record_form').on('submit', function(e) {
            // Check if the form is valid
            var parsleyInstance = $(this).parsley();

            // Only submit the form if it is valid
            if (!parsleyInstance.isValid()) {
                // If the form is invalid, prevent submission
                e.preventDefault();
                // Optionally, you can add custom behavior if the form is invalid
                showModal('Warning', 'Please correct the errors before submitting the form.');
            }
            // If Parsley validation passes, the form will submit normally
        });
    });
    
    function handleFormSubmit() {
        // Custom validation or logic
        document.getElementById('proofPayment').submit();  // Manually submit form
        return false;  // Prevent default form submission
    }
  </script>
</body>

</html>