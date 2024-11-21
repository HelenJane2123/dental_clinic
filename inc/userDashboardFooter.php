
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
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!-- Include Bootstrap Bundle with Popper.js -->
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

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

  <script src='vendors/fullcalendar/packages/core/main.js'></script>
  <script src='vendors/fullcalendar/packages/interaction/main.js'></script>
  <script src='vendors/fullcalendar/packages/daygrid/main.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>

  <!-- DataTables JS -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  

  <script>
    // Helper function for currency formatting
    function formatCurrency(amount) {
        return parseFloat(amount).toFixed(2); // Format to 2 decimal places
    }
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
            title: '<?php echo htmlspecialchars($appointment['service_name']); ?>',
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
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        initialView: 'dayGridMonth',    
        defaultDate: '2024-09-24',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: events,

        validRange: {
            start: new Date() // Disable dates before today
        },

        eventDidMount: function(info) {
            const status = info.event.extendedProps.status;
            const color = statusColors[status] || '#000';
            info.el.style.backgroundColor = color;
            info.el.style.color = '#fff';
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


    function toggleNameFields(radio) {
      const nameFields = document.getElementById('nameFields');
      nameFields.style.display = (radio.value === 'myself') ? 'none' : 'block';
    }

    // Hide name fields by default if "Myself" is selected
    $('#appointmentModal').on('show.bs.modal', function () {
        const myselfRadio = document.querySelector('input[name="appointmentType"][value="myself"]');
        toggleNameFields(myselfRadio);
    });


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

    function openEditModal(
        appointmentId,
        serviceId,
        appointmentDate,
        appointmentTime,
        notes,
        status,
        firstName,
        lastName,
        memberId) {
            // Populate modal fields with the passed data
            document.getElementById('edit_appointment_id').value = appointmentId;
            document.getElementById('edit_services').value = serviceId; // Pre-select the service
            document.getElementById('edit_appointment_date').value = appointmentDate;
            document.getElementById('edit_appointment_time').value = appointmentTime;
            document.getElementById('edit_notes').value = notes;
            document.getElementById('status').value = status;
            document.getElementById('first_name').value = firstName;
            document.getElementById('last_name').value = lastName;
            document.getElementById('member_id').value = memberId;


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

    // Toggle the offcanvas menu
    $('#menuToggle').on('click', function() {
        $('#offcanvasMenu').collapse('toggle');
    });

    // Close the offcanvas menu manually
    $('#closeMenu').on('click', function() {
        $('#offcanvasMenu').collapse('hide');
    });
    
    function printFormRecord() {
        // Create a new window
        var printWindow = window.open('', '', 'width=800,height=600');
        
        // Get the HTML content of the form container
        var formContent = document.getElementById('my_record_form').innerHTML;

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
        printWindow.document.write('<img src="img/logo.png" alt="Clinic Logo" style="max-width: 150px; margin-bottom: 5px;">'); // Reduced bottom margin for logo
        printWindow.document.write('<h1 style="margin-bottom: 5px;">Roselle Santander Dental Clinic</h1>'); // Reduced bottom margin for header
        printWindow.document.write('<p style="margin-bottom: 5px;">Address: 2nd flr. EDP Bldg. San Juan I Gen. Trias, Cavite</p>'); // Reduced bottom margin for address
        printWindow.document.write('<p style="margin-bottom: 5px;">Telephone: 09954993703</p>'); // Reduced bottom margin for telephone
        printWindow.document.write('<p>Patient ID: ' + (document.querySelector('#member_id') ? document.querySelector('#member_id').value : 'N/A') + '</p>');
        printWindow.document.write('</div>');
        
        // Wrap the form content in a div with a print container class
        printWindow.document.write('<div class="print-container">');
        
        // // Divide content into categories (for example, Personal Info, Contact, etc.)
        // printWindow.document.write('<div class="category">');
        // printWindow.document.write('<h2>Patient Information</h2>');
        // printWindow.document.write('<div class="row"><div class="col"><strong>Patient/Member ID:</strong> ' + patientId + '</div></div>');
        // printWindow.document.write('</div>');

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
        
        // Close the content container and body
        printWindow.document.write('</div>');
        printWindow.document.write('</body></html>');
        
        // Close the document and trigger the print dialog
        printWindow.document.close();
        printWindow.print();
    }




  </script>
</body>

</html>