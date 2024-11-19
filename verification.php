<?php
include_once('inc/header.php');
?>
<section class="home-slider owl-carousel">
  <div class="slider-item bread-item" style="background-image: url('img/bg_1.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
  </div>
</section>

<section class="ftco-section contact-section ftco-degree-bg">
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-12 col-md-8 col-lg-6">
      <!-- Card Container -->
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <h3 class="card-title">Verify Your Account</h3>
            <p class="text-secondary">Enter the verification code sent to your email.</p>
          </div>

          <!-- Display Alert Messages -->
          <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
              <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <!-- Form -->
          <form action="controller/verifyAccount.php" method="post" class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="verification_code" class="form-label">Verification Code</label>
              <input type="text" id="verification_code" name="verification_code" class="form-control" placeholder="Enter Verification Code" required>
              <div class="invalid-feedback">
                Please enter the verification code.
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" name="verify" class="btn btn-primary btn-lg">Verify</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once('inc/footer.php'); ?>
<script>
  // Bootstrap Validation
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach((form) => {
      form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();
</script>
