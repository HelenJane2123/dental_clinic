<?php
    include_once('inc/header.php');
    // If the user is already logged in, kill the session and redirect them
    if (isset($_SESSION['success']) && $_SESSION['success'] === true) {
        // Destroy the session to kill the login state
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session

        // Prevent the login page from being cached by the browser
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");  // HTTP/1.1
        header("Pragma: no-cache");  // HTTP/1.0
        header("Expires: 0");  // Proxies

        // Redirect to the index page (or any other page)
        header('Location: index.php');  // Redirect to index.php or home page
        exit();  // Stop further execution of code
    }
?>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="height: 100vh; overflow: hidden;">
        <!-- Login Section -->
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="../index.php"><h5 class="auth-subtitle"><img src="images/logo/logo.png" alt="Logo" style="height:113px;"></h5></a>
                </div>
                <h1 class="auth-title text-black">Log in.</h1>
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
                    <p><a class="font-bold" href="forgot-password.php">Forgot password?</a></p>
                </div>
            </div>
        </div>

        <!-- Image Section -->
        <div class="col-lg-7 col-12 d-none d-lg-block">
            <img src="images/bg/bg_1.JPG" alt="Login Image" class="img-fluid" style="height: 100vh; object-fit: cover;">
        </div>
    </div>
</div>

<?php
    include_once('inc/footer.php');
?>

