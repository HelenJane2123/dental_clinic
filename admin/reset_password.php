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

                <h1 class="auth-title text-black">Reset Password</h1>

                <form action="controller/resetPassword.php" method="POST">
                    <!-- Hidden field to pass the token -->
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>
                    
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Reset Password</button>
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