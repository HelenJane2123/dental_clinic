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
                <h3>Dashboard</h3>
                <h6>
                    <?php
                        date_default_timezone_set('Asia/Manila'); // Set timezone

                        // Display today's date in a custom format
                        $today = date('l, F j, Y'); // e.g., "Monday, September 24, 2024"
                        echo "Today's date is: " . $today;
                    ?>
                </h6>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="row">
                             <!-- Patients Count -->
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon purple">
                                                    <i class="iconly-boldProfile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Total <br/>Patients</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $getAllPatient; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Bookings Count -->
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon blue">
                                                    <i class="iconly-boldCalendar"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Total Bookings</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $getAllBookings; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Confirmed Bookings Count -->
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon green">
                                                    <i class="iconly-boldCheck"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Confirmed Bookings</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $getConfirmedAppointments; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Canceled Bookings Count -->
                            <div class="col-12 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon red">
                                                    <i class="iconly-boldCloseSquare"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Canceled Bookings</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $getCanceledAppointments; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Patient Statistics</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="chart-profile-visit"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                         <div class="card">
                            <div class="card-body py-4 px-5">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="assets/images/faces/1.jpg" alt="Face 1">
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="font-bold">Welcome back,</h6>
                                        <h6 class="text-muted mb-0"><?php echo htmlspecialchars($_SESSION['username']); ?></h6>
                                    </div>
                                </div>
                                <!-- Move logout button to the right using ms-auto -->
                                <div class="float-end ms-auto">
                                    <form action="logout.php" method="post">
                                        <button type="submit" class="btn btn-danger">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Notifications</h4>
                            </div>
                            <div class="card-content pb-4">
                                <div class="notification-list">
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="notification-item d-flex justify-content-between align-items-center px-4 py-2">
                                            <div class="notification-message">
                                                <!-- Use a standard anchor link and JavaScript to trigger the modal -->
                                                <h6 class="mb-0">
                                                    <a href="javascript:void(0);" class="<?php echo $notification['is_read'] ? 'notification-read' : 'notification-unread'; ?>" data-id="<?php echo $notification['id']; ?>" data-message="<?php echo htmlspecialchars($notification['message']); ?>">
                                                        <?php echo htmlspecialchars($notification['message']); ?>
                                                    </a>
                                                </h6>
                                                <small class="text-muted"><?php echo date('F j, Y, g:i A', strtotime($notification['created_at'])); ?></small>
                                            </div>
                                        </div>
                                        <hr class="my-1">
                                    <?php endforeach; ?>
                                </div>
                                <div class="px-4">
                                    <a href="notifications.php" class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>View All Notifications</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-12">
                        <div class="row">
                            <div class="col-12 col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Appointments</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-lg">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Comment</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="col-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar avatar-md">
                                                                    <img src="assets/images/faces/5.jpg">
                                                                </div>
                                                                <p class="font-bold ms-3 mb-0">Si Cantik</p>
                                                            </div>
                                                        </td>
                                                        <td class="col-auto">
                                                            <p class=" mb-0">Congratulations on your graduation!</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar avatar-md">
                                                                    <img src="assets/images/faces/2.jpg">
                                                                </div>
                                                                <p class="font-bold ms-3 mb-0">Si Ganteng</p>
                                                            </div>
                                                        </td>
                                                        <td class="col-auto">
                                                            <p class=" mb-0">Wow amazing design! Can you make another
                                                                tutorial for
                                                                this design?</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    
    
    <?php
        include_once('inc/footerDashboard.php');
    ?>