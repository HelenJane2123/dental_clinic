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
                    <h3>Documentation</h3>
                    <p class="text-subtitle text-muted">Help and Guidelines for Admin Dashboard</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Documentation</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <!-- Getting Started Section -->
            <div class="card">
                <div class="card-header">
                    <h4>Getting Started</h4>
                </div>
                <div class="card-body">
                    <p>Welcome to the Admin Dashboard! Here are some key functionalities:</p>
                    <ul>
                        <li><strong>Dashboard:</strong> View overall statistics, notifications and appointments for today.</li>
                        <li><strong>Dental Services:</strong> Add, edit, or remove dental services provided in the clinic.</li>
                        <li><strong>Appointments:</strong> View and manage appointments made by patients. (Can approve, reschedule, cancel and confirmed the appointment)</li>
                        <li><strong>Patients:</strong> Manage patient information and assign doctors to patients.</li>
                    </ul>
                    <p>If you need help with any of the features, feel free to contact support or check out the specific sections in this documentation.</p>
                </div>
            </div>

            <!-- Managing Dental Services Section -->
            <div class="card">
                <div class="card-header">
                    <h4>Managing Dental Services</h4>
                </div>
                <div class="card-body">
                    <p>To add, edit, or delete dental services, use the respective buttons in the Dental Services section. Make sure to enter the correct details, such as category, subcategory, price range, price, and down payment.</p>
                    <p>For any changes to pricing or categories, ensure that all related data is updated accordingly across the system.</p>
                </div>
            </div>

            <!-- Appointments and Scheduling Section -->
            <div class="card">
                <div class="card-header">
                    <h4>Appointments and Scheduling</h4>
                </div>
                <div class="card-body">
                    <p>Appointments can be managed directly from the Appointment section. You can assign a doctor, view available timeslots, and confirm patient bookings.</p>
                    <p>Ensure to regularly check for new appointment requests and update the status as needed.</p>
                </div>
            </div>

            <!-- Managing Patients Section -->
            <div class="card">
                <div class="card-header">
                    <h4>Managing Patients</h4>
                </div>
                <div class="card-body">
                    <p>In the Patients section, you can view patient information, assign doctors to specific patients, and track their appointments. Ensure that each patient's record is updated after each visit.</p>
                </div>
            </div>

            <!-- Managing Payments Section -->
            <!-- <div class="card">
                <div class="card-header">
                    <h4>Managing Payments</h4>
                </div>
                <div class="card-body">
                    <p>In the Payment section, you can view the list of payments made for appointments. Make sure to confirm the booking once the payment is successful. You can also check for any pending payments here.</p>
                </div>
            </div> -->
        </section>
    </div>

<?php
include_once('inc/footerDashboard.php');
?>
