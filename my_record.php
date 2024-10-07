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
                            <h4 class="card-title">Patient Information Record</h4>
                            <form action="controller/myRecord.php" method="POST" class="forms-sample"  enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                <input type="hidden" class="form-control" id="member_id" name="member_id" value="<?= isset($member_id) ? $member_id : '' ?>">
                                <div class="row">
                                <!-- Personal Information -->
                                    <div class="form-group col-sm-4">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= isset($lastname) ? $lastname : '' ?>" disabled required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= isset($firstname) ? $firstname : '' ?>" disabled required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= isset($middle_name) ? $middle_name : '' ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="birthdate">Birthdate</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="age">Age</label>
                                        <input type="number" class="form-control" id="age" name="age">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Sex</label>
                                        <select class="form-control" id="sex" name="sex">
                                            <option value="male" <?= isset($gender) && $gender == 'male' ? 'selected' : '' ?>>Male</option>
                                            <option value="female" <?= isset($gender) && $gender == 'female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="nickname">Nickname</label>
                                        <input type="text" class="form-control" id="nickname" name="nickname">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="religion">Religion</label>
                                        <input type="text" class="form-control" id="religion" name="religion">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="nationality">Nationality</label>
                                        <input type="text" class="form-control" id="nationality" name="nationality">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="cellphone_no">Cellphone No.</label>
                                        <input type="text" class="form-control" id="cellphone_no" name="cellphone_no" value="<?= isset($contact_number) ? $contact_number : '' ?>" disabled>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? $email : '' ?>" disabled>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="home_address">Home Address</label>
                                        <input type="text" class="form-control" id="home_address" name="home_address" value="<?= isset($address) ? $address : '' ?>" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="occupation">Occupation</label>
                                        <input type="text" class="form-control" id="occupation" name="occupation">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="guardian_name">Parent/Guardian's Name (if minor)</label>
                                        <input type="text" class="form-control" id="guardian_name" name="guardian_name">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="guardian_occupation">Parent/Guardian's Occupation</label>
                                        <input type="text" class="form-control" id="guardian_occupation" name="guardian_occupation">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="referral_source">Whom may we thank for referring you?</label>
                                        <input type="text" class="form-control" id="referral_source" name="referral_source">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="reason_for_consultation">Reason for consultation</label>
                                        <input type="text" class="form-control" id="reason_for_consultation" name="reason_for_consultation">
                                    </div>
                                </div>
                                
                                <!-- Dental History -->
                                <h4>Dental History</h4>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="previous_dentist">Previous Dentist</label>
                                        <input type="text" class="form-control" id="previous_dentist" name="previous_dentist">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="last_dental_visit">Last Dental Visit</label>
                                        <input type="date" class="form-control" id="last_dental_visit" name="last_dental_visit">
                                    </div>
                                </div>
                                
                                <!-- Medical History -->
                                <h4>Medical History</h4>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="physician_name">Physician Name</label>
                                        <input type="text" class="form-control" id="physician_name" name="physician_name">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="physician_specialty">Specialty</label>
                                        <input type="text" class="form-control" id="physician_specialty" name="physician_specialty">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="physician_address">Office Address</label>
                                        <input type="text" class="form-control" id="physician_address" name="physician_address">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="physician_phone_no">Office Number</label>
                                        <input type="text" class="form-control" id="physician_phone_no" name="physician_phone_no">
                                    </div>
                                </div>
                                
                                <!-- Health Questions -->
                                <h4>Health Information</h4>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label>Are you in good health?</label>
                                        <select class="form-control" name="good_health">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Are you under medical treatment now?</label>
                                        <select class="form-control" name="under_medical_treatment">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Have you had a serious illness or operation?</label>
                                        <select class="form-control" name="serious_illness">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <label>If yes, what illness/operation?</label>
                                        <input type="text" class="form-control" name="illness_details">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label>Have you been hospitalized?</label>
                                        <select class="form-control" name="hospitalization">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <label>If yes, why?</label>
                                        <input type="text" class="form-control" name="hospitalization_reason">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Are you taking any prescription/non-prescription medication?</label>
                                        <select class="form-control" name="taking_medication">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <label>If yes, what medication?</label>
                                        <input type="text" class="form-control" name="medication_details">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Do you use tobacco products?</label>
                                        <select class="form-control" name="use_tobacco">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Do you use alcohol or other dangerous drugs?</label>
                                        <select class="form-control" name="use_drugs">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Are you allergic to any of the following?</label>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="allergies[]" value="Local Anesthetic">
                                            <label class="form-check-label">Local Anesthetic (e.g. Lidocaine)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="allergies[]" value="Penicillin">
                                            <label class="form-check-label">Penicillin</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="allergies[]" value="Antibiotics">
                                            <label class="form-check-label">Antibiotics</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="allergies[]" value="Sulfa Drugs">
                                            <label class="form-check-label">Sulfa Drugs</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="allergies[]" value="Aspirin">
                                            <label class="form-check-label">Aspirin</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="allergies[]" value="Latex">
                                            <label class="form-check-label">Latex</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="allergies[]" value="Others">
                                            <label class="form-check-label">Others</label>
                                            <input type="text" class="form-control mt-2" name="other_allergies">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional Information for Women -->
                                <h4>For Women Only</h4>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label>Are you pregnant?</label>
                                        <select class="form-control" name="pregnant">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Are you nursing?</label>
                                        <select class="form-control" name="nursing">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Are you taking birth control pills?</label>
                                        <select class="form-control" name="birth_control">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Other Medical Information -->
                                <h4>Other Medical Information</h4>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="blood_type">Blood Type</label>
                                        <input type="text" class="form-control" id="blood_type" name="blood_type">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="blood_pressure">Blood Pressure</label>
                                        <input type="text" class="form-control" id="blood_pressure" name="blood_pressure">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Check if you have or had any of the following:</label>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="medical_conditions[]" value="High Blood Pressure">
                                            <label class="form-check-label">High Blood Pressure</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="medical_conditions[]" value="Heart Diseases">
                                            <label class="form-check-label">Heart Diseases</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="medical_conditions[]" value="Cancer">
                                            <label class="form-check-label">Cancer/Tumors</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="medical_conditions[]" value="Diabetes">
                                            <label class="form-check-label">Diabetes</label>
                                        </div>
                                        <!-- Add additional medical condition checkboxes as needed -->
                                    </div>
                                </div>
                                
                                <!-- Submit -->
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <button type="reset" class="btn btn-light">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    include_once("inc/myAppointmentModal.php");
    include_once("inc/userDashboardFooter.php");
?>
