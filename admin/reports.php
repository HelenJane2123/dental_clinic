<?php
include_once('inc/headerDashboard.php');
include_once('inc/sidebarMenu.php');
?>

<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Reports</h3>
                    <p class="text-subtitle text-muted">Generate Reports for Clinic Operations</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <!-- Filter and Export Section -->
            <div class="card">
                <div class="card-header">
                    <h4>Generate Reports</h4>
                </div>
                <div class="card-body">
                    <form action="reports.php" method="GET">
                        <div class="row">
                            <!-- Filter by Date Range -->
                            <div class="col-md-4">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                            <!-- Filter by Report Type -->
                            <div class="col-md-4">
                                <label for="report_type">Report Type</label>
                                <select name="report_type" id="report_type" class="form-control" required>
                                    <option value="appointments">Appointments</option>
                                    <option value="treatments">Monthly Treatment Records</option>
                                    <option value="payments">Payment Records</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                            <button type="button" class="btn btn-secondary" onclick="printReport()">Print</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Report Header with Logo -->
            <div class="card">
                <div class="card-header text-center">
                    <!-- Clinic Title and Logo -->
                    <h3 style="color:#000;">Roselle Santander Dental Clinic</h3>
                    <img src="../img/logo.png" alt="Clinic Logo" class="img-fluid" style="max-width: 150px;">
                    
                    <!-- Clinic Contact Info -->
                    <p style="margin-top: 10px;">
                        <strong>Address:</strong> 2nd flr. EDP Bldg. San Juan I Gen. Trias, Cavite<br>
                        <strong>Email:</strong> rosellesantander@rs-dentalclinic.com<br>
                        <strong>Contact Number:</strong> 09954993703
                    </p>
                </div>
            </div>


            <!-- Report Content Section -->
            <div id="reportContent">
                <!-- Report Results Section -->
                <div class="card">
                    <div class="card-header">
                        <h4>Report Preview</h4>
                    </div>
                    <div class="card-body">
                        <?php
                            // Check if filters are set
                            if (isset($_GET['start_date'], $_GET['end_date'], $_GET['report_type'])) {
                                $start_date = $_GET['start_date'];
                                $end_date = $_GET['end_date'];
                                $report_type = $_GET['report_type'];

                                // Initialize the result variable
                                $result = null;

                                // Get Results Based on Report Type
                                if ($report_type === "appointments") {
                                    $result = $appointment_admin->get_all_appointment_per_date($start_date, $end_date);
                                } elseif ($report_type === "treatments") {
                                    $result = $appointment_admin->get_all_dental_records_per_date($start_date, $end_date);
                                } elseif ($report_type === "payments") {
                                    $result = $appointment_admin->get_all_proof_of_payment_per_date($start_date, $end_date);
                                }

                                // Check if results are available
                                if ($result && $result->num_rows > 0) {
                                    // Custom Table Headers (without Patient ID and Patient Name in rows)
                                    $custom_headers = [];
                                    if ($report_type === 'appointments') {
                                        $custom_headers = ['Appointment ID', 'Appointment Date', 'Appointment Time', 'Doctor Name', 'Appointment Status', 'Service Name'];
                                    } elseif ($report_type === 'treatments') {
                                        $custom_headers = ['Date', 'Tooth No', 'Procedure', 'Dentist', 'Amount Charged', 'Amount Paid', 'Balance', 'Next Appointment'];
                                    } elseif ($report_type === 'payments') {
                                        $custom_headers = ['Service Name', 'Payment Status', 'Remarks', 'Payment Date'];
                                    }

                                    // Group data by Patient ID (or Name)
                                    $grouped_data = [];
                                    while ($row = $result->fetch_assoc()) {
                                        // Using Patient ID as the key for grouping
                                        $patient_id = $row['patient_id'];
                                        if (!isset($grouped_data[$patient_id])) {
                                            $grouped_data[$patient_id] = [
                                                'patient_name' => $row['patient_name'],  // Store patient name once
                                                'appointments' => []  // Array to store the appointments/treatments/payments for this patient
                                            ];
                                        }

                                        // Add current row to the patient's group
                                        $grouped_data[$patient_id]['appointments'][] = $row;
                                    }

                                    // Start Table
                                    echo "<table class='table table-bordered table-striped'>";
                                    echo "<thead><tr>";

                                    // Display Custom Headers (no Patient ID or Name in the rows)
                                    foreach ($custom_headers as $header) {
                                        echo "<th>" . htmlspecialchars($header) . "</th>";
                                    }

                                    echo "</tr></thead><tbody>";

                                    // Loop through grouped data and display
                                    foreach ($grouped_data as $patient_id => $patient_data) {
                                        // Display Patient Name and Patient ID as the Group Header
                                        echo "<tr><td colspan='" . count($custom_headers) . "' class='font-weight-bold'>";
                                        echo "<strong>".htmlspecialchars($patient_data['patient_name']) . " (Patient ID: " . htmlspecialchars($patient_id) . ")</strong></td></tr>";

                                        // Loop through the appointments/treatments/payments for this patient
                                        foreach ($patient_data['appointments'] as $row) {
                                            echo "<tr>";
                                            foreach ($custom_headers as $header) {
                                                // Map header to column name
                                                $key = strtolower(str_replace(' ', '_', $header));
                                                echo "<td>" . htmlspecialchars($row[$key] ?? '') . "</td>";
                                            }
                                            echo "</tr>";
                                        }
                                    }

                                    echo "</tbody></table>";
                                } else {
                                    echo "<p>No records found for the selected filters.</p>";
                                }
                            } else {
                                echo "<p>Please select valid filters to generate a report.</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    // Function to trigger the print dialog for specific content
    function printReport() {
        // Get the content you want to print (only the report part)
        var content = document.getElementById("reportContent").innerHTML;
        
        // Open a new window to display the content
        var printWindow = window.open('', '', 'height=600,width=800');
        
        // Write the content into the new window
        printWindow.document.write('<html><head><title>Print Report</title>');
        
        // Add custom print styles
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
        printWindow.document.write('table, th, td { border: 1px solid black; padding: 8px; text-align: left; }');
        printWindow.document.write('h3 { text-align: center; }');
        printWindow.document.write('img { display: block; margin-left: auto; margin-right: auto; max-width: 150px; }');
        printWindow.document.write('p { text-align: center; font-size: 14px; margin-top: 10px; }');
        printWindow.document.write('</style>');
        
        printWindow.document.write('</head><body>');
        
        // Clinic Header with Logo, Title, and Contact Info for Print
        printWindow.document.write('<div class="card-header text-center">');
        printWindow.document.write('<h3>Roselle Santander Dental Clinic</h3>');
        printWindow.document.write('<img src="../img/logo.png" alt="Clinic Logo">');
        printWindow.document.write('<p><strong>Address:</strong> 2nd flr. EDP Bldg. San Juan I Gen. Trias, Cavite<br>');
        printWindow.document.write('<strong>Email:</strong> rosellesantander@rs-dentalclinic.com<br>');
        printWindow.document.write('<strong>Contact Number:</strong> 09954993703</p>');
        printWindow.document.write('</div><br><br>');
        
        // Write the report content (table, etc.)
        printWindow.document.write(content); // The table content goes here
        
        printWindow.document.write('</body></html>');
        
        // Close the document and trigger printing
        printWindow.document.close();
        printWindow.print();
    }
</script>


<?php
include_once('inc/footerDashboard.php');
?>
