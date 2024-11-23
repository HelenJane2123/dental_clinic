<?php
include_once('inc/header.php');
// Get the token from the URL
$token = $_GET['token'] ?? '';
?>
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <!-- Login Section -->
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="../index.php">
                        <img src="../img/logo.png" alt="Logo" style="height:113px;">
                    </a>
                </div>

                <h1 class="auth-title text-black">Reset Password</h1>

                <form action="controller/resetPassword.php" method="POST">
                    <!-- Hidden field to pass the token -->
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>
                    
                    <!-- Password Input with Show/Hide Icon -->
                    <div class="form-group position-relative mb-3">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <i class="toggle-password bi bi-eye position-absolute"></i>
                    </div>
                    
                    <!-- Confirm Password Input with Show/Hide Icon -->
                    <div class="form-group position-relative mb-3">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <i class="toggle-password bi bi-eye position-absolute"></i>
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

<style>
    .position-relative {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: calc(50% + 5px); /* Adjusted for proper alignment */
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.2rem;
        color: #6c757d; /* Muted gray color for the icon */
    }

    .toggle-password:hover {
        color: #000; /* Change icon color on hover */
    }
</style>

<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(item => {
        item.addEventListener('click', function() {
            const input = this.previousElementSibling; // Get the input field before the icon
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            }
        });
    });

    // Alert auto-hide logic
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
