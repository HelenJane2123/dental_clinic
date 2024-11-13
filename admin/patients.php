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
                    <h3>Patients</h3>
                    <p class="text-subtitle text-sub-muted">List of all Patients</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Patients</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Birthday</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Assigned Doctor</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                              <?php if (!empty($get_patients)) : ?>
                                  <?php foreach ($get_patients as $patients) : ?>
                                      <tr>
                                        <td><?= htmlspecialchars($patients['patient_id']) ?></td>
                                        <td><?= htmlspecialchars($patients['member_id']) ?></td>
                                        <td><?= htmlspecialchars($patients['first_name'])." ".htmlspecialchars($patients['last_name']) ?></td>
                                        <td>
                                            <?php 
                                                $birthdate = new DateTime($patients['birthdate']);
                                                echo $birthdate->format('F j, Y'); // Format as "Month Day, Year"
                                            ?>
                                        </td>
                                        <td>
                                            <?= $patients['sex'] == "F" ? "Female" : "Male"; ?>
                                        </td>
                                        <td>
                                            <?= $patients['age']?>
                                        </td>
                                        <td><?= htmlspecialchars($patients['doctor_first_name'])." ".htmlspecialchars($patients['doctor_last_name']) ?></td>
                                        <td>
                                            <a href="viewRecord.php?patient_id=<?= urlencode($patients['patient_id']) ?>" class="btn btn-primary btn-sm">View Record</a>
                                        </td>
                                      </tr>
                                  <?php endforeach; ?>
                              <?php else : ?>
                                  <tr>
                                      <td colspan="9" class="text-center">No Patient record found.</td>
                                  </tr>
                              <?php endif; ?>
                          </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>

<?php
include_once('inc/footerDashboard.php');
?>
