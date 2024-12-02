<?php
include_once('inc/header.php');
if (isset($_SESSION['form_data'])) {
  foreach ($_SESSION['form_data'] as $key => $value) {
      $_POST[$key] = $value;
  }
  unset($_SESSION['form_data']); // Clear after use
}
?>
<section class="home-slider owl-carousel">
  <div class="slider-item bread-item" style="background-image: url('img/bg_1.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
  </div>
</section>

<section class="ftco-section contact-section ftco-degree-bg">
  <div class="container h-100">
    <div class="row justify-content-center align-items-center h-100">
      <!-- Left Side - Dental Image -->
      <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <div class="dental-image-wrapper">
          <img src="img/dental-banner.jpg" alt="Dental Care" class="img-fluid rounded">
        </div>
      </div>

      <!-- Right Side - Signup Form -->
      <div class="col-12 col-lg-6">
        <div class="card border border-light-subtle rounded-4">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row">
              <div class="col-12">
                <div class="mb-5 text-center">
                  <h2 class="h4">Sign Up</h2>
                  <h5 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h5>
                </div>
              </div>
            </div>

            <!-- Error Message Display -->
            <?php if (isset($_SESSION['message'])): ?>
              <div class="alert alert-<?php echo $_SESSION['message_type']; ?> fade show" role="alert">
                <span><?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?></span>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
              </div>
            <?php endif; ?>

            <!-- Signup Form -->
            <form action="controller/registerAccount.php" method="post" id="register" data-parsley-validate>
              <div class="row gy-3">
                <div class="col-12">
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name'], ENT_QUOTES) : ''; ?>" required>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name'], ENT_QUOTES) : ''; ?>" required>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ''; ?>" required>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <div class="input-group">
                        <span class="input-group-text" id="country-code">+63</span>
                        <input 
                          type="tel" 
                          class="form-control" 
                          name="contact_number" 
                          id="contact_number" 
                          placeholder=" e.g. 9123456789" 
                          maxlength="10" 
                          value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : ''; ?>" 
                          required 
                          data-parsley-type="digits" 
                          data-parsley-length="[10, 10]">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : ''; ?>" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group row position-relative">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="password" class="form-control" name="password" id="password" placeholder="Password" required data-parsley-minlength="8">
                      <i class="fa fa-eye position-absolute" id="showhidepassword" style="cursor: pointer; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required data-parsley-equalto="#password">
                      <i class="fa fa-eye position-absolute" id="confirm_showhidepassword" style="cursor: pointer; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="iAgree" id="iAgree" required>
                    <label class="form-check-label text-secondary" for="iAgree">
                      I agree to the <a href="javascript:void(0);" class="link-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                    </label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid text-center">
                    <button class="btn btn-success" name="register" type="submit">SIGN UP</button>
                  </div>
                </div>
              </div>
            </form>

            <div class="row">
              <div class="col-12">
                <hr class="mt-5 mb-4 border-secondary-subtle">
                <p class="text-secondary text-center">Already have an account? <a href="login.php" class="link-primary text-decoration-none">Log in</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Modal for Terms and Conditions -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
      </div>
      <div class="modal-body">
        <div class="container">
          <h4 class="text-secondary mb-3">User Agreement for <strong>Roselle Santander Dental Clinic</strong></h4>
          <p class="text-muted mb-4">By accessing and using our system, you agree to comply with and be bound by the following terms and conditions. Please read them carefully.</p>

          <!-- Terms and Conditions Content -->
          <div class="terms-content" style="max-height: 400px; overflow-y: auto;">
            <h5 class="mb-3">1. Introduction</h5>
            <p>Welcome to <strong>Your Roselle Santander Dental Clinic</strong>. By accessing and using our system, you agree to comply with and be bound by the following terms and conditions.</p>

            <h5 class="mb-3">2. User Responsibilities</h5>
            <ul class="list-unstyled">
              <li>You must provide accurate and complete information when using the system.</li>
              <li>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</li>
              <li>You agree to use the system only for lawful purposes and in accordance with applicable laws and regulations.</li>
            </ul>

            <h5 class="mb-3">3. Access and Use</h5>
            <p>We grant you a non-exclusive, non-transferable license to access and use the system for its intended purpose. We reserve the right to modify, suspend, or discontinue any part of the system at our discretion.</p>

            <h5 class="mb-3">4. Data Privacy</h5>
            <p>Your personal and medical information will be collected and stored in accordance with our Privacy Policy, which outlines how we use, protect, and share your data. You are responsible for ensuring that your data is accurate and up-to-date.</p>

            <h5 class="mb-3">5. Intellectual Property</h5>
            <p>All content and materials provided through the system are the property of <strong>Roselle Santander Dental Clinic</strong> or its licensors and are protected by intellectual property laws.</p>

            <h5 class="mb-3">6. Limitation of Liability</h5>
            <p><strong>Roselle Santander Dental Clinic</strong> is not liable for any direct, indirect, incidental, or consequential damages arising from the use or inability to use the system.</p>

            <h5 class="mb-3">7. Termination</h5>
            <p>We may terminate or suspend your access to the system at any time, with or without cause, if you violate these terms or for any other reason.</p>

            <h5 class="mb-3">8. Changes to Terms</h5>
            <p>We may update these terms from time to time. Any changes will be posted on our website, and your continued use of the system constitutes acceptance of the updated terms.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include_once('inc/footer.php'); ?>

<!-- Parsley and Custom JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.3/parsley.min.js"></script>
<script>
  // Password show/hide functionality
  document.getElementById('showhidepassword').addEventListener('click', function() {
    const passField = document.getElementById('password');
    const type = passField.getAttribute('type') === 'password' ? 'text' : 'password';
    passField.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
  });

  document.getElementById('confirm_showhidepassword').addEventListener('click', function() {
    const confirmPassField = document.getElementById('confirm_password');
    const type = confirmPassField.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPassField.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
  });

  const modalTrigger = document.querySelector('[data-bs-toggle="modal"]');
  const termsModal = new bootstrap.Modal(document.getElementById('termsModal'));
  modalTrigger.addEventListener('click', function() {
    termsModal.show();
  });
</script>
