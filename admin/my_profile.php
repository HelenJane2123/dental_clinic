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
                    <h3>My Profile</h3>
                    <p class="text-subtitle text-sub-muted"></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">My Profile</li>
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
                    <form action="controller/myProfile.php" method="POST" name="my_profile" class="forms-sample" enctype="multipart/form-data">
                        <div class="form-group col-6">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?= isset($firstname) ? $firstname : '' ?>">
                            <input type="hidden" class="form-control" id="member_id" name="member_id" value="<?= $member_id_admin ?>">
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
                            <input type="number" class="form-control" id="contact_number" name="contact_number" value="<?= isset($contact_number) ? $contact_number : '' ?>">
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

        </section>
</div>

<?php
include_once('inc/footerDashboard.php');
?>
