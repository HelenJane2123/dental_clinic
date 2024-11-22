<?php
include_once('inc/header.php');
?>
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <!-- Login Section -->
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="../index.php">
                        <img src="images/logo/logo.png" alt="Logo" style="height:113px;">
                    </a>
                </div>

                <h1 class="auth-title text-black">Forgot Password</h1>
                <form action="controller/forgetPass.php" method="POST" id="login_form" class="needs-validation" novalidate>
                    <?php 
                    if (isset($_SESSION['message'])):
                        $message_type = $_SESSION['message_type'] ?? 'info';
                        $alert_class = "alert-{$message_type}";
                    ?>
                    <div class="alert <?php echo htmlspecialchars($alert_class); ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_SESSION['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php 
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                    endif; 
                    ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary text-white"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" class="form-control" placeholder="Enter your email address" name="email" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-success" name="reset_btn" type="submit">Send Reset Link</button>
                    </div>

                    <p class="mt-3 text-center">Back to <a href="index.php">Login</a></p>
                </form>
            </div>
        </div>

        <!-- Image Section -->
        <div class="col-lg-7 col-12 d-none d-lg-block">
            <img src="images/bg/bg_1.JPG" alt="Background Image" class="img-fluid" style="height: 100vh; object-fit: cover;">
        </div>
    </div>
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