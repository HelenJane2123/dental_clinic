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
                    <h3>Dental Services</h3>
                    <p class="text-subtitle text-sub-muted">Dental Services Maintenance</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dental Services</li>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServicesModal" onclick="generatePassword()">
                        Add Dental Services
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Price Range</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($get_dental_services)) : ?>
                                <?php foreach ($get_dental_services as $dental_services) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($dental_services['id']) ?></td>
                                        <td><?= htmlspecialchars($dental_services['category']) ?></td>
                                        <td><?= htmlspecialchars($dental_services['sub_category']) ?></td>
                                        <td><?= htmlspecialchars($dental_services['price_range']) ?></td>
                                        <td><?= htmlspecialchars($dental_services['price']) ?></td>
                                        <td>
                                            <!-- Edit Button -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDentalServiceModal" 
                                                    onclick="populateEditModal(<?= htmlspecialchars(json_encode($dental_services)) ?>)">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>

                                            <!-- Delete Button -->
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteDentalServiceModal" 
                                                    onclick="confirmDelete(<?= htmlspecialchars($dental_services['id']) ?>)">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">No record found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

           <?php include_once('inc/actionModal.php')?>
        </section>
    </div>

<?php
include_once('inc/footerDashboard.php');
?>
