<?php
include_once('inc/headerDashboard.php');
include_once('inc/sidebarMenu.php');

// Fetch the patient ID from the URL
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    // Fetch the patient details
    $get_patient_by_id = $appointment_admin->get_patient_by_id($patient_id);
    if (!$get_patient_by_id) {
        echo "<p>Patient record not found.</p>";
        exit();
    }

    // Fetch additional details from the tables
    $medical_history = $appointment_admin->get_medical_history($patient_id);
    $guardians = $appointment_admin->get_guardians($patient_id);
    $consultations = $appointment_admin->get_consultations($patient_id);

    // Fetch existing dental treatment records
    $dental_records = $appointment_admin->get_dental_records($patient_id);
} else {
    echo "<p>No patient ID provided.</p>";
    exit();
}
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
                    <h3>Patient Record</h3>
                    <p class="text-subtitle text-muted">View and manage the detailed record of the patient</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="patients.php">Patients</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Patient Record</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button onclick="window.print();" class="btn btn-primary">Print Record</button>
                </div>
                <div id="printable-content">
                    <div class="card-body">
                        <!-- Printable content -->
                        <div class="mb-4">
                            <div class="p-3 border rounded bg-light text-center">
                                <p class="mb-0 fs-4"><strong>Patient Information Form</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h5 style="color:#000;">Basic Information</h5><br/>
                                <p><strong>Patient ID:</strong> <?= htmlspecialchars($get_patient_by_id['member_id']) ?></p>
                                <p><strong>Name:</strong> <?= htmlspecialchars($get_patient_by_id['first_name']) . ' ' . htmlspecialchars($get_patient_by_id['last_name']) ?></p>
                                <p><strong>Gender:</strong> <?= $get_patient_by_id['sex'] == 'F' ? 'Female' : 'Male' ?></p>
                                <p><strong>Birthday:</strong> <?= date('F j, Y', strtotime($get_patient_by_id['birthdate'])) ?></p>
                                <p><strong>Age:</strong> <?= $get_patient_by_id['age'] ?></p>
                            </div>
                            <div class="col-md-4">
                                <h5 style="color:#000;">Medical Information</h5><br/>
                                <p><strong>Assigned Doctor:</strong> <?= htmlspecialchars($get_patient_by_id['doctor_first_name']) . ' ' . htmlspecialchars($get_patient_by_id['doctor_last_name']) ?></p>
                                <p><strong>Doctor Email:</strong> <?= htmlspecialchars($get_patient_by_id['doctor_email']) ?></p>
                                <p><strong>Specialty:</strong> <?= htmlspecialchars($get_patient_by_id['doctor_specialty']) ?></p>
                            </div>
                            <div class="col-md-4">
                                <h5 style="color:#000;">Contact Details</h5><br/>
                                <p><strong>Email:</strong> <?= htmlspecialchars($get_patient_by_id['email']) ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($get_patient_by_id['cellphone_no']) ?></p>
                            </div>
                        </div>
                        <hr>
                        <h5 style="color:#000;">Medical History</h5><br/>
                        <p><strong>Physician Name:</strong> <?= htmlspecialchars($medical_history['physician_name']) ?></p>
                        <h5>Guardians</h5>
                        <?php if ($guardians) : ?>
                            <ul>
                                <?php foreach ($guardians as $guardian) : ?>
                                    <li>
                                        <strong>Name:</strong> <?= htmlspecialchars($guardian['guardian_name']) ?><br>
                                        <strong>Occupation:</strong> <?= htmlspecialchars($guardian['guardian_occupation']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>No guardians listed.</p>
                        <?php endif; ?>
                        <h5 style="color:#000;">Consultations</h5><br/>
                        <?php if ($consultations) : ?>
                            <ul>
                                <?php foreach ($consultations as $consultation) : ?>
                                    <li>
                                        <strong>Referral Source:</strong> <?= htmlspecialchars($consultation['referral_source']) ?><br>
                                        <strong>Reason:</strong> <?= nl2br(htmlspecialchars($consultation['reason_for_consultation'])) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>No consultations recorded.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Dental Treatment Records</h4>
            <button type="button" class="btn btn-success" onclick="addRow()">Add Row</button>
        </div>
        <div class="table-responsive">
            <div class="card-body">
                <form id="dentalRecordForm" action="controller/saveDentalrecord.php" method="POST">
                    <table class="table table-bordered" id="dentalTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Tooth No./s</th>
                                <th>Procedure</th>
                                <th>Dentist/s</th>
                                <th>Amount Charged</th>
                                <th>Amount Paid</th>
                                <th>Balance</th>
                                <th>Next Appointment Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <input type="hidden" value=<?=$patient_id?> name="patient_id"/>
                            <?php if ($dental_records) : ?>
                                <?php foreach ($dental_records as $record) : ?>
                                    <tr>
                                        <td><input type="date" name="date[]" value="<?= $record['date'] ?>" class="form-control" required></td>
                                        <td><input type="text" name="tooth_no[]" value="<?= $record['tooth_no'] ?>" class="form-control" required></td>
                                        <td><input type="text" name="procedure[]" value="<?= $record['procedure'] ?>" class="form-control" required></td>
                                        <td><input type="text" name="dentist[]" value="<?= $record['dentist'] ?>" class="form-control" required></td>
                                        <td><input type="number" name="amount_charged[]" value="<?= $record['amount_charged'] ?>" class="form-control" step="0.01" required></td>
                                        <td><input type="number" name="amount_paid[]" value="<?= $record['amount_paid'] ?>" class="form-control" step="0.01" required></td>
                                        <td><input type="number" name="balance[]" value="<?= $record['balance'] ?>" class="form-control" step="0.01" required></td>
                                        <td><input type="date" name="next_appointment[]" value="<?= $record['next_appointment'] ?>" class="form-control" required></td>
                                        <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td><input type="date" name="date[]" class="form-control" required></td>
                                    <td><input type="text" name="tooth_no[]" class="form-control" required></td>
                                    <td><input type="text" name="procedure[]" class="form-control" required></td>
                                    <td><input type="text" name="dentist[]" class="form-control" required></td>
                                    <td><input type="number" name="amount_charged[]" class="form-control" step="0.01" required></td>
                                    <td><input type="number" name="amount_paid[]" class="form-control" step="0.01" required></td>
                                    <td><input type="number" name="balance[]" class="form-control" step="0.01" required></td>
                                    <td><input type="date" name="next_appointment[]" class="form-control" required></td>
                                    <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Save All Records</button>
                </form>
            </div>
        </div>
    </div>
</div>




<?php
include_once('inc/footerDashboard.php');
?>
