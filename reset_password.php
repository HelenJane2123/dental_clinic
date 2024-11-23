<?php
include_once('inc/header.php');

// Get the token from the URL
$token = $_GET['token'] ?? '';
?>
<section class="ftco-section">
  <div class="container">
    <h2 class="text-center">Reset Your Password</h2>
    <form action="controller/resetPassword.php" method="POST" id="resetPasswordForm">

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
        <div class="text-danger" id="passwordError" style="display: none;">Password does not meet the criteria.</div>
      </div>

      <!-- Confirm Password Field -->
      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <div class="input-group">
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePasswordVisibility('confirm_password')">Show</button>
        </div>
        <div class="text-danger" id="confirmPasswordError" style="display: none;">Passwords do not match.</div>
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

  // Password Validation
  document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    const passwordCriteria = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    const isPasswordValid = passwordCriteria.test(password);

    // Validate Password Criteria
    if (!isPasswordValid) {
      document.getElementById('passwordError').style.display = 'block';
      e.preventDefault(); // Prevent form submission
    } else {
      document.getElementById('passwordError').style.display = 'none';
    }

    // Validate Password Match
    if (password !== confirmPassword) {
      document.getElementById('confirmPasswordError').style.display = 'block';
      e.preventDefault(); // Prevent form submission
    } else {
      document.getElementById('confirmPasswordError').style.display = 'none';
    }
  });
</script>

<?php include_once('inc/footer.php'); ?>
