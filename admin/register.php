<?php
    include_once('inc/header.php');
?>
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.php"><h5 class="auth-subtitle">Roselle Santander's Dental Clinic - Admin</h5></a>
            </div>
            <h1 class="auth-title">Sign Up</h1>
            <p class="auth-subtitle mb-5">Input your data to register to our website.</p>
             <!-- Error Message Display -->
             <?php if (isset($_SESSION['message'])) : ?>
                <?php 
                    $message_type = $_SESSION['message_type'] ?? 'info'; 
                    $alert_class = ($message_type === 'error') ? 'alert-danger' : 
                                   (($message_type === 'success') ? 'alert-success' : 
                                   (($message_type === 'warning') ? 'alert-warning' : 'alert-info'));
                ?>
                <div class="alert <?php echo htmlspecialchars($alert_class, ENT_QUOTES, 'UTF-8'); ?> fade show" role="alert">
                    <span><?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                </div>
            <?php endif ?>

            <form action="controller/RegisterAdmin.php" name="register" method="post" class="needs-validation-registration" novalidate enctype="multipart/form-data">
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" id="firstname" name="firstname" placeholder="First Name" required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>   
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" id="lastname" name="lastname" placeholder="Last Name" required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>  
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" id="email" name="email" placeholder="Email">
                    <div class="form-control-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="number" class="form-control form-control-xl" id="contactnumber" name="contactnumber" placeholder="Contact Number">
                    <div class="form-control-icon">
                        <i class="bi bi-phone"></i>
                    </div>
                </div>
                <!-- Dropdown for Admin or Super Admin -->
                <div class="form-group position-relative has-icon-left mb-4">
                    <select class="form-control form-control-xl" id="user_type" name="user_type" required>
                        <option value="" disabled selected>Select User Type</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                    <div class="form-control-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" id="username" name="username" placeholder="Username">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" id="password" name="password" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" name="register" type="submit">Sign Up</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class='text-gray-600'>Already have an account? <a href="index.php"
                        class="font-bold">Log
                        in</a>.</p>
            </div>
        </div>
    </div>
<?php
    include_once('inc/footer.php');
?>