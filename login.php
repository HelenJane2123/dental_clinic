<?php
session_start();
include_once('inc/header.php');
?>
    <div class="wrapper-login">
        <form action="controller/login_account.php" id="login_form" class="needs-validation" method="POST" novalidate enctype="multipart/form-data">
            <h1>Log in to Dental Clinic</h1>
            <?php 
                if (isset($_SESSION['message'])) : 
                    $message_type = $_SESSION['message_type'] ?? 'info';
                    $alert_class = 'alert-info'; // Default class for 'info' type
                    switch ($message_type) {
                        case 'error':
                            $alert_class = 'alert-danger'; // Red alert for errors
                            break;
                        case 'success':
                            $alert_class = 'alert-success'; // Green alert for success
                            break;
                        case 'warning':
                            $alert_class = 'alert-warning'; // Yellow alert for warnings
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
            <div class="input-group form-group mt-3 input-box">
                <div class="bg-secondary rounded-start">
                    <span class="m-3"><i class="fas fa-user mt-3"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Username" name="username" required>
            </div>
            <div class="input-group form-group mt-3 input-box">
                <div class="bg-secondary rounded-start">
                    <span class="m-3"><i class="fas fa-lock mt-3"></i></span>
                </div>
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <div class="form-group mt-3">
                <div class="d-grid">
                    <button class="btn bsb-btn-xs btn-success" name="login_user" type="submit">LOG IN</button>
                </div>
            </div>
            <div class="row mb-4 mt-3">
                <div class="col">
                    <a href="forgetpass.php" class="text-black">Forgot password?</a>
                </div>
                <div class="col">
                    <a href="signup.php" class="text-black">Not yet a member?</a>
                </div>
            </div>
            <div class="text-center">
                <p>Not a member? <a href="#!">Register</a></p>
            </div>
        </form>
    </div>
    <!-- Footer -->
    <?php include_once('inc/footer.php'); ?>
    <script>
        window.addEventListener('load', function() {
            const alertElement = document.querySelector('.alert');
            if (alertElement) {
                setTimeout(() => {
                    alertElement.classList.remove('show');
                    alertElement.classList.add('fade');
                    setTimeout(() => {
                        alertElement.style.display = 'none';
                    }, 1500); // Match this duration with the CSS transition duration (for fade effect)
                }, 5000); // Time before the alert starts fading out (5 seconds)
            }
        });
    </script>
</body>
</html>
