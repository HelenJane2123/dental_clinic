<?php
include_once('inc/headerDashboard.php');
include_once('inc/sidebarMenu.php');
?>
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Change Password</h3>
                    <p class="text-subtitle text-sub-muted">Change your password here</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
              <h4 class="card-title"></h4>
              <p class="card-description">
                <?php
                  if (isset($_SESSION['display_message'])) {
                    $message = $_SESSION['display_message'];
                    $message_type = $_SESSION['message_type'];
                    
                    echo "<div class='alert alert-{$message_type}'>{$message}</div>";
                    
                    // Unset the message so it doesn't persist on page reload
                    unset($_SESSION['display_message']);
                    unset($_SESSION['message_type']);
                  }
                ?>
              </p>
              <form action="controller/changePassword.php" method="POST" name="change_password" class="forms-sample" data-parsley-validate>
                <div class="form-group col-6">
                  <label class="required" for="current_password">Current Password</label>
                  <div class="input-group">
                    <input type="hidden" class="form-control" id="member_id" name="member_id" value="<?= isset($member_id) ? $member_id : '' ?>">
                    <input type="password" class="form-control" id="current_password" name="current_password" required 
                           data-parsley-required-message="Current password is required" 
                           data-parsley-minlength="6" 
                           data-parsley-minlength-message="Password must be at least 6 characters long."
                           data-parsley-errors-container="#current_password_error">
                    <span class="input-group-text" onclick="togglePassword('current_password', 'toggleCurrentPasswordIcon')">
                      <i id="toggleCurrentPasswordIcon" class="fas fa-eye"></i>
                    </span>
                  </div>
                  <div id="current_password_error"></div> <!-- Error container -->
                </div>
                <div class="form-group col-6">
                  <label class="required" for="new_password">New Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="new_password" name="new_password" required
                           data-parsley-required-message="New password is required" 
                           data-parsley-minlength="6" 
                           data-parsley-minlength-message="Password must be at least 6 characters long."
                           data-parsley-errors-container="#new_password_error">
                    <span class="input-group-text" onclick="togglePassword('new_password', 'toggleNewPasswordIcon')">
                      <i id="toggleNewPasswordIcon" class="fas fa-eye"></i>
                    </span>
                  </div>
                  <div id="new_password_error"></div> <!-- Error container -->
                </div>
                <div class="form-group col-6">
                  <label class="required" for="confirm_password">Confirm New Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                           data-parsley-required-message="Please confirm your new password" 
                           data-parsley-equalto="#new_password" 
                           data-parsley-equalto-message="Passwords do not match."
                           data-parsley-errors-container="#confirm_password_error">
                    <span class="input-group-text" onclick="togglePassword('confirm_password', 'toggleConfirmPasswordIcon')">
                      <i id="toggleConfirmPasswordIcon" class="fas fa-eye"></i>
                    </span>
                  </div>
                  <div id="confirm_password_error"></div> <!-- Error container -->
                </div>
                <button type="submit" class="btn btn-primary me-2">Change Password</button>
                <button type="button" class="btn btn-light">Cancel</button>
              </form>
            </div>
            </div>

        </section>
</div>
<script>
// Toggle the password visibility
function togglePassword(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    // Toggle between text and password
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
<?php
include_once('inc/footerDashboard.php');
?>


