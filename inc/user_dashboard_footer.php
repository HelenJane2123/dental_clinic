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
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid' ],
        defaultDate: '2024-09-24',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: [
          {
            title: 'Brace Adjustment',
            start: '2024-09-24'
          },
          {
            title: 'Tooth Extraction',
            start: '2024-09-24'
          },
          {
            title: 'Not Available',
            start: '2024-09-07'
          },
          {
            title: 'Not Available',
            start: '2024-09-14'
          },
          {
            title: 'Not Available',
            start: '2024-09-21'
          },
          {
            title: 'Not Available',
            start: '2024-09-28'
          }
        ]
      });

    calendar.render();
    });

  </script>
</body>

</html>