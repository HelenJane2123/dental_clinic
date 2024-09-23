<?php
    session_start();
    include_once('inc/header.php');
?>
<!-- Registration 7 - Bootstrap Brain Component -->
<section class="p-3 p-md-4 p-xl-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
        <div class="card border border-light-subtle rounded-4">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row">
              <div class="col-12">
                <div class="mb-5">
                  <h2 class="h4 text-center">Sign Up</h2>
                  <h3 class="fs-6 fw-normal text-secondary text-center m-0">Enter your details to register</h3>
                </div>
              </div>
            </div>
            <?php 
                if (isset($_SESSION['message'])) : 
                    $message_type = $_SESSION['message_type'] ?? 'info';
                    // Define Bootstrap alert classes for different message types
                    $alert_class = 'alert-info'; // Default class for 'info' type
                    if ($message_type === 'error') {
                        $alert_class = 'alert-danger'; // Red alert for errors
                    } elseif ($message_type === 'success') {
                        $alert_class = 'alert-success'; // Green alert for success
                    } elseif ($message_type === 'warning') {
                        $alert_class = 'alert-warning'; // Yellow alert for warnings
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
            <form action="register/register_account.php"  method="post"  name="register" id="register" class="needs-validation-registration" novalidate enctype="multipart/form-data">
              <div class="row gy-3 overflow-hidden">
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="text" class="form-control form-control-user" name="first_name" id="first_name"
                                placeholder="First Name" required>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="text" class="form-control form-control-user" name="last_name" id="last_name"
                                placeholder="Last Name" required>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="text" class="form-control form-control-user" name="email" id="email"
                                placeholder="Email" required>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="number" class="form-control form-control-user" name="contact_number" id="contact_number"
                                placeholder="Contact Number" required>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input type="text" class="form-control form-control-user" name="username" id="username"
                                placeholder="Username" required>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input required="" name="password" type="password" id="password"
                                class="form-control" placeholder="Password">
                            <i class="fa fa-eye" aria-hidden="true" id="showhidepassword"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12 mb-3 mb-sm-0">
                            <input required="" name="confirm_password" type="password" id="confirm_password"
                                class="form-control" placeholder="Confirm Password">
                            <i class="fa fa-eye" aria-hidden="true" id="confirm_showhidepassword"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="iAgree" id="iAgree" required>
                    <label class="form-check-label text-secondary" for="iAgree">
                      I agree to the <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>
                    </label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn bsb-btn-xs btn-success"  name="register" type="submit">SIGN UP</button>
                  </div>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-12">
                <hr class="mt-5 mb-4 border-secondary-subtle">
                <p class="m-0 text-secondary text-center">Already have an account? <a href="login.php" class="link-primary text-decoration-none">Log in</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
     <!-- Footer -->
     <?php
        include_once('inc/footer.php');
    ?>
    <script>
        const showHidePass = document.getElementById('showhidepassword');
        const userPassword = document.getElementById('password');

        showHidePass.addEventListener('click', function(e) {
            let showHideAttr = userPassword.getAttribute('type');

            if (showHideAttr === 'password') {
                showHideAttr = 'text';
            } else {
                showHideAttr = 'password';
            }
            userPassword.setAttribute('type', showHideAttr);
            this.classList.toggle('fa-eye-slash');
        });

        const confirmshowHidePass = document.getElementById('confirm_showhidepassword');
        const confirmuserPassword = document.getElementById('confirm_password');

        confirmshowHidePass.addEventListener('click', function(e) {
            let showHideAttr = confirmuserPassword.getAttribute('type');

            if (showHideAttr === 'password') {
                showHideAttr = 'text';
            } else {
                showHideAttr = 'password';
            }
            confirmuserPassword.setAttribute('type', showHideAttr);
            this.classList.toggle('fa-eye-slash');
        });

        // Automatically hide the alert after 10 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alertElement = document.querySelector('.alert');
            if (alertElement) {
                // Show the alert and then fade it out after 5 seconds
                setTimeout(function() {
                    alertElement.classList.add('fade');
                    setTimeout(function() {
                        alertElement.style.opacity = '0'; // Fade out effect
                        setTimeout(function() {
                            alertElement.style.display = 'none'; // Hide the alert after fade
                        }, 1500); // Match this duration with the CSS transition duration
                    }, 5000); // Time before starting to fade out (5 seconds)
                }, 0); // Delay of 0 ensures immediate effect
            }
        });
    </script>
</body>
</html>