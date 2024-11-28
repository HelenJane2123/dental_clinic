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
                    <h3>Doctors</h3>
                    <p class="text-subtitle text-sub-muted">List of all Doctors</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Doctors</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section class="section">
            <?php
                // Display message if set
                if (isset($_SESSION['display_message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type']; ?>" role="alert">
                        <?= $_SESSION['display_message']; ?>
                    </div>
                    <?php 
                    // Clear message after displaying it
                    unset($_SESSION['display_message']);
                    unset($_SESSION['message_type']);
                endif;
            ?>
            <div class="card">
                <div class="card-header">
                    <!-- Add Doctor Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal" onclick="generatePassword()">
                        Add Doctor
                    </button>
                </div>
                <div class="table-responsive">
                    <div class="card-body">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Doctor ID</th>
                                    <th>Member ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>
                                    <th>Specialty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($get_doctors)) : ?>
                                    <?php foreach ($get_doctors as $doctors) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($doctors['doctor_id']) ?></td>
                                            <td><?= htmlspecialchars($doctors['member_id']) ?></td>
                                            <td><?= htmlspecialchars($doctors['first_name']) . " " . htmlspecialchars($doctors['last_name']) ?></td>
                                            <td><?= htmlspecialchars($doctors['gender']) ?></td>
                                            <td><?= htmlspecialchars($doctors['email']) ?></td>
                                            <td><?= htmlspecialchars($doctors['contact_number']) ?></td>
                                            <td><?= htmlspecialchars($doctors['specialty']) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary assign-patient-btn" data-bs-toggle="modal" data-bs-target="#patientListModal" 
                                                        data-doctor-id="<?= htmlspecialchars($doctors['account_id']) ?>">
                                                    Assign Patient
                                                </button>
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#viewDoctorModal" onclick="viewDoctorDetails(<?= htmlspecialchars($doctors['account_id']) ?>)">
                                                    <i class="fas fa-eye"></i> 
                                                </button>                                                
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteDoctorModal" onclick="confirmDoctorDelete(<?= htmlspecialchars($doctors['account_id']) ?>)">
                                                    <i class="fas fa-trash-alt"></i> <!-- Delete icon -->
                                                </button>                                        
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No Doctor/s record found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

           <?php include_once('inc/actionModal.php')?>
        </section>
    </div>
    <script>
        // Function to generate a random password
        function generatePassword() {
            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*";
            let password = "";
            for (let i = 0; i < 10; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById("password").value = password;
        }

        let currentDoctorId = null;  // Global variable to store doctor ID

        // Function to handle when the modal is opened
        function openPatientModal(button) {
            // Get the doctor_id from the data attribute of the button that was clicked
            currentDoctorId = button.getAttribute("data-doctor-id");

            // Set the hidden input field's value to the doctor ID
            document.getElementById("doctorIdInput").value = currentDoctorId;
        }
    </script>

<?php
include_once('inc/footerDashboard.php');
?>
