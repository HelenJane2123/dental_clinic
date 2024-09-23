<?php
session_start();
include_once('inc/header.php');
?>
    <div class="wrapper-forgotpassword">
        <form action="forgotpassword/process_request.php" id="forgot_password_form" class="needs-validation" method="POST" novalidate>
            <h1>Forgot Your Password?</h1>
            <p>Enter your email address below, and we'll send you instructions to reset your password.</p>
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
                    <span class="m-3"><i class="fas fa-envelope mt-3"></i></span>
                </div>
                <input type="email" class="form-control" placeholder="Email Address" name="email" required>
            </div>
            <div class="form-group mt-3">
                <div class="d-grid">
                    <button class="btn bsb-btn-xs btn-primary" name="send_reset_link" type="submit">Send Reset Link</button>
                </div>
            </div>
            <div class="text-center mt-4">
                <p>Remembered your password? <a href="login.php">Log in here</a></p>
            </div>
            <div class="text-center mt-4">
                <p>Back to Homepage <a href="index.php">Homepage</a></p>
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
