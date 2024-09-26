
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
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

  <script src='vendors/fullcalendar/packages/core/main.js'></script>
  <script src='vendors/fullcalendar/packages/interaction/main.js'></script>
  <script src='vendors/fullcalendar/packages/daygrid/main.js'></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const statusColors = {
          'Pending': '#FFC107',   // Yellow
          'Confirmed': '#28A745', // Green
          'Canceled': '#DC3545',  // Red
          'Completed': '#007BFF'  // Blue
      };

      var calendarEl = document.getElementById('calendar');

      // Prepare events from PHP appointments
      var events = [
        <?php foreach ($appointments as $appointment): ?>
          {
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
          if (info.date.getDay() === 6) { // 6 is Saturday
            info.el.style.backgroundColor = '#f0f0f0'; // Change background color to indicate closure
            info.el.style.pointerEvents = 'none'; // Disable interaction
            info.el.innerHTML = '<div style="text-align: center; padding-top: 10px; color: red;">Closed</div>'; // Add closed text
          }
        },
        
        // eventMouseEnter: function(info) {
        //     var tooltip = document.createElement('div');
        //     tooltip.className = 'tooltip';
        //     tooltip.innerHTML = `
        //         <strong>Description:</strong> ${info.event.title}<br>
        //         <strong>Status:</strong> ${info.event.extendedProps.status || 'No status available'}
        //     `;

        //     document.body.appendChild(tooltip);

        //     // Position the tooltip near the cursor
        //     tooltip.style.left = (info.jsEvent.pageX + 10) + 'px';
        //     tooltip.style.top = (info.jsEvent.pageY + 10) + 'px';

        //     // Store the tooltip in the event's extendedProps for later removal
        //     info.event.extendedProps.tooltip = tooltip;
        // },
        // eventMouseLeave: function(info) {
        //     // Remove the tooltip
        //     if (info.event.extendedProps.tooltip) {
        //         document.body.removeChild(info.event.extendedProps.tooltip);
        //         delete info.event.extendedProps.tooltip;
        //     }
        // },
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
    });

    function submitAppointment() {
        // Add logic to handle form submission
        //alert("Appointment booked successfully!");
        $('#appointmentModal').modal('hide');
        document.getElementById('appointmentForm').reset();
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

    function submitAppointment() {
        document.getElementById('appointmentForm').submit();
    }

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
  </script>
</body>

</html>