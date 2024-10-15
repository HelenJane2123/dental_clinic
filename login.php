<?php
include_once('inc/header.php');
?>
<section class="home-slider owl-carousel">
  <div class="slider-item bread-item" style="background-image: url('img/bg_1.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
  </div>
</section>

<section class="ftco-section contact-section ftco-degree-bg">
  <div class="container">
    <div class="row d-flex align-items-center justify-content-center"> <!-- Center the content horizontally -->

      <!-- Left Column for Image -->
      <div class="col-md-6 mb-5 d-none d-md-block">
        <img src="img/dental-banner.jpg" alt="Dental Clinic" class="img-fluid"> <!-- Image -->
      </div>

      <!-- Right Column for Login Form -->
      <div class="col-md-6">
        <h2 class="h4 text-center mb-4">Log in</h2> <!-- Centered heading -->

        <form action="controller/loginAccount.php" id="login_form" class="needs-validation" method="POST" novalidate enctype="multipart/form-data">
          <?php 
            if (isset($_SESSION['message'])) : 
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
                echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); 
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
              ?>
            </span>
          </div>
          <?php endif ?>

          <!-- Input field for Username with Icon -->
          <div class="input-group form-group mt-3 input-box">
            <div class="input-group-prepend">
              <span class="input-group-text bg-secondary text-white px-3"> <!-- Padding added here -->
                <i class="fas fa-user"></i>
              </span>
            </div>
            <input type="text" class="form-control" placeholder="Username" name="username" required style="padding-left: 10px;"> <!-- Padding added to input -->
          </div>

          <!-- Input field for Password with Icon -->
          <div class="input-group form-group mt-3 input-box">
            <div class="input-group-prepend">
              <span class="input-group-text bg-secondary text-white px-3"> <!-- Padding added here -->
                <i class="fas fa-lock"></i>
              </span>
            </div>
            <input type="password" class="form-control" placeholder="Password" name="password" required style="padding-left: 10px;"> <!-- Padding added to input -->
          </div>

          <!-- Login Button -->
          <div class="form-group mt-3">
            <div class="d-grid">
              <button class="btn btn-success"  name="login_user" type="submit">
                Log In
              </button>
            </div>
          </div>

          <div class="row mb-4 mt-3">
            <div class="col">
              <p><a href="forgetpass.php">Forgot password?</a></p>
            </div>
            <div class="col">
              <p>Not a member? <a href="signup.php">Register</a></p>
            </div>
          </div>
        </form>
      </div> <!-- End of Right Column -->
      
    </div> <!-- End of Row -->
  </div> <!-- End of Container -->
</section>


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
        }, 1500);
      }, 5000);
    }
  });
</script>