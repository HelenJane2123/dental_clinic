<?php
    include_once("inc/userDashboardHeader.php");
?>
    <div class="container-fluid page-body-wrapper">
      <?php  include_once("inc/search_header.php"); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">My Profile</h4>
                            <p class="card-description">
                                Update my profile.

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
                            <form action="controller/myProfile.php" method="POST" name="my_profile" class="forms-sample" enctype="multipart/form-data">
                                <div class="form-group col-6">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?= isset($firstname) ? $firstname : '' ?>">
                                    <input type="hidden" class="form-control" id="member_id" name="member_id" value="<?= isset($member_id) ? $member_id : '' ?>">
                                </div>
                                <div class="form-group col-6">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?= isset($lastname) ? $lastname : '' ?>">
                                </div>
                                <div class="form-group col-6">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? $email : '' ?>">
                                </div>
                                <div class="form-group col-6">
                                    <label for="contact_number">Contact Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="country-code">+63</span>
                                        <input 
                                            type="tel" 
                                            class="form-control" 
                                            name="contact_number" 
                                            id="contact_number" 
                                            placeholder=" e.g. 9123456789" 
                                            maxlength="10" 
                                            value="<?= isset($contact_number) ? $contact_number : '' ?>" 
                                            required 
                                            data-parsley-type="digits" 
                                            data-parsley-length="[10, 10]">
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="SelectGender">Gender</label>
                                    <select class="form-control" name="gender" id="SelectGender">
                                        <option value="male" <?= isset($gender) && $gender == 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= isset($gender) && $gender == 'female' ? 'selected' : '' ?>>Female</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Upload your profile picture here</label>
                                    <input type="file" class="form-control" name="profile_picture">
                                </div>
                                <div class="form-group col-6">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" name="address" id="address" value="<?= isset($address) ? $address : '' ?>" placeholder="Location">
                                </div>
                                <div class="form-group col-6">
                                    <label for="remarks">Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks" rows="4"><?= isset($remarks) ? $remarks : '' ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <button class="btn btn-light">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
          </div>
          <!-- row end -->
<?php
    include_once("inc/myAppointmentModal.php");
    include_once("inc/userDashboardFooter.php");
?>