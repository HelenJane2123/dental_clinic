<?php
include_once('inc/header.php');

// Get the token from the URL
$token = $_GET['token'] ?? '';
?>
<section class="ftco-section">
  <div class="container">
    <h2 class="text-center">Reset Your Password</h2>
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
</section>
<?php include_once('inc/footer.php'); ?>
