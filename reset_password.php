<?php
include_once('inc/header.php');

// Get the token from the URL
$token = $_GET['token'] ?? '';
?>
<section class="ftco-section">
  <div class="container">
    <h2 class="text-center">Reset Your Password</h2>
    <form action="controller/resetPassword.php" method="POST" id="resetPasswordForm" novalidate>
      <?php 
          if (isset($_SESSION['display_message'])) : 
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
              echo htmlspecialchars($_SESSION['display_message'], ENT_QUOTES, 'UTF-8'); 
              unset($_SESSION['display_message']);
              unset($_SESSION['message_type']);
            ?>
          </span>
        </div>
        <?php endif ?>
      <!-- Hidden field to pass the token -->
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>

      <!-- New Password Field -->
      <div class="form-group">
        <label for="password">New Password</label>
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePasswordVisibility('password')">Show</button>
        </div>
        <small class="form-text text-muted">
          Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a digit, and a special character.
        </small>
        <div class="text-danger" id="passwordError" style="display: none;" aria-live="polite">Password does not meet the criteria.</div>
      </div>

      <!-- Confirm Password Field -->
      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <div class="input-group">
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePasswordVisibility('confirm_password')">Show</button>
        </div>
        <div class="text-danger" id="confirmPasswordError" style="display: none;" aria-live="polite">Passwords do not match.</div>
      </div>

      <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
  </div>
</section>

<script>
  // Toggle Password Visibility
  function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
  }

  // Real-Time Password Validation
  document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const passwordCriteria = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    const passwordError = document.getElementById('passwordError');

    if (!passwordCriteria.test(password)) {
      passwordError.style.display = 'block';
    } else {
      passwordError.style.display = 'none';
    }
  });

  // Password Match Validation
  document.getElementById('confirm_password').addEventListener('input', function () {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    if (password !== confirmPassword) {
      confirmPasswordError.style.display = 'block';
    } else {
      confirmPasswordError.style.display = 'none';
    }
  });

  // Final Validation on Submit
  document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    const passwordCriteria = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    const isPasswordValid = passwordCriteria.test(password);

    if (!isPasswordValid) {
      document.getElementById('passwordError').style.display = 'block';
      e.preventDefault(); // Prevent form submission
    }

    if (password !== confirmPassword) {
      document.getElementById('confirmPasswordError').style.display = 'block';
      e.preventDefault(); // Prevent form submission
    }
  });
</script>

<?php include_once('inc/footer.php'); ?>
