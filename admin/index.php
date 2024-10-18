<?php
    include_once('inc/header.php');
?>
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.php"><h5 class="auth-subtitle">Roselle Santander's Dental Clinic - Admin</h5></a>
            </div>
            <h1 class="auth-title">Log in.</h1>
            <p class="auth-subtitle mb-5">Log in to Admin Panel.</p>

            <form action="controller/loginAccount.php" id="login_form" class="needs-validation" method="POST" novalidate enctype="multipart/form-data">
            <?php 
                if (isset($_SESSION['message'])) : 
                    $message_type = $_SESSION['message_type'] ?? 'info';
                    $alert_class = 'alert-info';
                    switch ($message_type) {
                        case 'error':
                            $alert_class = 'alert-danger';
                            break;
                        case 'success':
                            $alert_class = 'alert-success';
                            break;
                        case 'warning':
                            $alert_class = 'alert-warning';
                            break;
                    }
            ?>
            <div class="alert <?php echo htmlspecialchars($alert_class, ENT_QUOTES, 'UTF-8'); ?> fade show" role="alert">
                <span>
                <?php 
                    echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); 
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                ?>
                </span>
            </div>
          <?php endif ?>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" placeholder="Username" name="username">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" placeholder="Password" name="password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit" name="login_user">Log in</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Don't have an account? <a href="register.php"
                        class="font-bold">Sign
                        up</a>.</p>
                <p><a class="font-bold" href="forgot-password.php">Forgot password?</a>.</p>
            </div>
        </div>
    </div>
<?php
    include_once('inc/footer.php');
?>        