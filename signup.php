<?php
include_once('inc/header.php');
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
                  <h5 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h3>
                </div>
              </div>
            </div>

            <!-- Error Message Display -->
            <?php if (isset($_SESSION['message'])) : ?>
                <?php 
                    $message_type = $_SESSION['message_type'] ?? 'info'; 
                    $alert_class = ($message_type === 'error') ? 'alert-danger' : 
                                   (($message_type === 'success') ? 'alert-success' : 
                                   (($message_type === 'warning') ? 'alert-warning' : 'alert-info'));
                ?>
                <div class="alert <?php echo htmlspecialchars($alert_class, ENT_QUOTES, 'UTF-8'); ?> fade show" role="alert">
                    <span><?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                </div>
            <?php endif ?>

            <!-- Signup Form -->
            <form action="controller/registerAccount.php" method="post" id="register" class="needs-validation-registration" novalidate>
              <div class="row gy-3">
                <div class="col-12">
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" required>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" required>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="text" class="form-control" name="email" id="email" placeholder="Email" required>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="tel" class="form-control" name="contact_number" id="contact_number" placeholder="Contact Number" maxlength="10" required>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group row position-relative">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                      <i class="fa fa-eye position-absolute" aria-hidden="true" id="showhidepassword" style="cursor: pointer; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                      <i class="fa fa-eye position-absolute" aria-hidden="true" id="confirm_showhidepassword" style="cursor: pointer; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="iAgree" id="iAgree" required>
                    <label class="form-check-label text-secondary" for="iAgree">
                      I agree to the <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>
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

<?php include_once('inc/footer.php'); ?>

<script>
  // Password show/hide functionality
  const showHidePass = document.getElementById('showhidepassword');
  const userPassword = document.getElementById('password');
  showHidePass.addEventListener('click', function() {
    const type = userPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    userPassword.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
  });

  const confirmShowHidePass = document.getElementById('confirm_showhidepassword');
  const confirmUserPassword = document.getElementById('confirm_password');
  confirmShowHidePass.addEventListener('click', function() {
    const type = confirmUserPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmUserPassword.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
  });

  // Alert hide after 5 seconds
  document.addEventListener('DOMContentLoaded', function() {
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
      setTimeout(() => {
        alertElement.classList.add('fade');
        setTimeout(() => alertElement.style.display = 'none', 1500);
      }, 5000);
    }
  });
</script>
