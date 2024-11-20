    <div class="sidebar-menu">
        <ul class="menu">
            <li class="sidebar-title">Menu</li>

            <li class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?> ">
                <a href="dashboard.php" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'notifications.php' ? 'active' : '' ?> ">
                <a href="notifications.php" class='sidebar-link'>
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>
            <li class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'patients.php' ? 'active' : '' ?>">
                <a href="patients.php" class='sidebar-link'>
                    <i class="bi bi-collection-fill"></i>
                    <span>Patients</span>
                </a>
            </li>
            <li class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'appointment_bookings.php' ? 'active' : '' ?>">
                <a href="appointment_bookings.php" class='sidebar-link'>
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Appointment Bookings</span>
                </a>
            </li>

            <li class="sidebar-title">Profiles</li>
            <?php if ($_SESSION['user_type'] == 'super_admin'): ?>
                <li class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'doctor_profile.php' ? 'active' : '' ?>">
                    <a href="doctors.php" class='sidebar-link'>
                        <i class="bi bi-file-earmark-medical-fill"></i>
                        <span>Doctor's Profile</span>
                    </a>
                </li>
            <?php endif; ?>
            <li class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'my_profile.php' ? 'active' : '' ?>">
                <a href="my_profile.php" class='sidebar-link'>
                    <i class="bi bi-person"></i>
                    <span>My Profile</span>
                </a>
            </li>

            <li class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'change_password.php' ? 'active' : '' ?>">
                <a href="change_password.php" class='sidebar-link'>
                    <i class="bi bi-pen-fill"></i>
                    <span>Change Password</span>
                </a>
            </li>
            <li class="sidebar-title">Maintenance</li>
            <?php if ($_SESSION['user_type'] == 'super_admin'): ?>
                <li class="sidebar-item  <?= basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : '' ?>">
                    <a href="dental_services.php" class='sidebar-link'>
                        <i class="bi bi-gear-fill"></i>
                        <span>Dental Services</span>
                    </a>
                </li>
                <li class="sidebar-item  <?= basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : '' ?>">
                    <a href="reports.php" class='sidebar-link'>
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Reports</span>
                    </a>
                </li>
            <?php endif; ?>
                <li class="sidebar-title">Others</li>
                <?php if ($_SESSION['user_type'] == 'super_admin'): ?>
                <li class="sidebar-item  <?= basename($_SERVER['PHP_SELF']) == 'payment.php' ? 'active' : '' ?>">
                    <a href="payment.php" class='sidebar-link'>
                        <i class="bi bi-cash"></i>
                        <span>Payment</span>
                    </a>
                </li>
                <?php endif; ?>
            
            <li class="sidebar-item  <?= basename($_SERVER['PHP_SELF']) == 'help.php' ? 'active' : '' ?>">
                <a href="help.php" class='sidebar-link'>
                    <i class="bi bi-life-preserver"></i>
                    <span>Documentation</span>
                </a>
            </li>
            <li class="sidebar-item  <?= basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : '' ?>">
                <a href="logout.php" class='sidebar-link'>
                    <i class="bi bi-unlock"></i>
                    <span>Logout</span>
                </a>
            </li>

        </ul>
    </div>
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>