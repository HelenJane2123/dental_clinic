<?php
    include_once("inc/userDashboardHeader.php");
?>
<div class="container-fluid page-body-wrapper">
  <?php include_once("inc/search_header.php"); ?>
  <!-- partial -->
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Change Password</h4>
              <p class="card-description">
                Change password here
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
                      <i id="toggleCurrentPasswordIcon" class="mdi mdi-eye menu-icon"></i>
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
                      <i id="toggleNewPasswordIcon" class="mdi mdi-eye menu-icon"></i>
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
                      <i id="toggleConfirmPasswordIcon" class="mdi mdi-eye menu-icon"></i>
                    </span>
                  </div>
                  <div id="confirm_password_error"></div> <!-- Error container -->
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <button type="button" class="btn btn-light">Cancel</button>
              </form>
            </div>
          </div>
        </div>
        <!-- row end -->

<script>
    $(document).ready(function() {
        // Initialize Parsley validation on the form
        $('form[name="change_password"]').parsley();
    });

    function togglePassword(inputId, iconId) {
        var passwordField = document.getElementById(inputId);
        var icon = document.getElementById(iconId);
        
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.replace("mdi-eye", "mdi-eye-off");
        } else {
            passwordField.type = "password";
            icon.classList.replace("mdi-eye-off", "mdi-eye");
        }
    }

</script>
<?php
    include_once("inc/myAppointmentModal.php");
    include_once("inc/userDashboardFooter.php");
?>
