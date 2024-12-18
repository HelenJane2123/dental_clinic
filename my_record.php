<?php
    include_once("inc/userDashboardHeader.php");


    $patient_record = $appointment->get_patientid_by_member_id($member_id);
    $patient_allergies = [];
    $patient_medical_conditions = [];

    if (!empty($patient_record)) {

        if ($patient_record['patient_id']) {

            $patientRecords = $appointment->get_all_patient_record($patient_record['patient_id']) ?: [];
            // Example: Initialize $patient_allergies
            $patient_allergies = $appointment->get_patient_allergies($patient_record['patient_id']);

            $patient_medical_conditions = $appointment->get_patient_medical_conditions($patient_record['patient_id']); // Default to empty array if null


            // Assuming that you are fetching only one patient's record
            $patient = !empty($patientRecords) ? $patientRecords[0] : null;
        }
    }
   
?>
<div class="container-fluid page-body-wrapper">
      <?php  include_once("inc/search_header.php"); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">Patient Record</h4>
                    <div class="content">
                      <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Patient Information Record</h4>
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
                                 <!-- Container for Print button and Member ID -->
                                <div class="top-right-container">
                                    <!-- Print Button -->
                                    <button type="button" class="print-button" onclick="printFormRecord()">Print</button>
                                </div>
                                <br/><br/>
                                <form action="controller/myRecord.php" method="POST" class="forms-sample" name="my_record" id="my_record_form" enctype="multipart/form-data">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" class="form-control" id="member_id" name="member_id" value="<?= isset($member_id) ? $member_id : '' ?>">
                                    <input type="hidden" class="form-control" id="patient_id" name="patient_id" value="<?= isset($patient_record['patient_id']) ? $patient_record['patient_id'] : '' ?>">
                                    <!-- Member ID Display -->
                                    <div id="patient-info">
                                        <div class="member-id">
                                            Patient ID: <span id="member_id_display"><?=$member_id?></span>
                                        </div>
                                    </div>
                                    <div id="personal-info">
                                        <div class="row">
                                        <!-- Personal Information -->
                                            <div class="form-group col-sm-4">
                                                <label class="required" for="last_name">Last Name</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= isset($lastname) ? $lastname : '' ?>"  required data-parsley-required-message="This field is required.">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required" for="first_name">First Name</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= isset($firstname) ? $firstname : '' ?>" required data-parsley-required-message="This field is required.">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="middle_name">Middle Name</label>
                                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= isset($patient['middle_name']) ? $patient['middle_name'] : '' ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <label class="required" for="birthdate">Birthdate</label>
                                                <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= isset($patient['birthdate']) ? $patient['birthdate'] : '' ?>" required data-parsley-required-message="This field is required.">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="age">Age</label>
                                                <input type="number" class="form-control" id="age" name="age" value="<?= isset($patient['age']) ? $patient['age'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required">Gender</label>
                                                <select 
                                                    class="form-control" 
                                                    id="sex" 
                                                    name="sex" 
                                                    required 
                                                    data-parsley-required-message="Please select gender" 
                                                    onchange="toggleForWomenSection()">
                                                    <option value="">--Please Select--</option>
                                                    <option value="M" <?= isset($patient['sex']) && $patient['sex'] == 'M' ? 'selected' : '' ?>>Male</option>
                                                    <option value="F" <?= isset($patient['sex']) && $patient['sex'] == 'F' ? 'selected' : '' ?>>Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required">Marital Status</label>
                                                <select class="form-control" id="marital_status" name="marital_status" required data-parsley-required-message="Please select gender">
                                                    <option>--Please Select--</option>
                                                    <option value="Single" <?= isset($patient['marital_status']) && $patient['marital_status'] == 'Single' ? 'selected' : '' ?>>Single</option>
                                                    <option value="Married" <?= isset($patient['marital_status']) && $patient['marital_status'] == 'Married' ? 'selected' : '' ?>>Married</option>
                                                    <option value="Separated" <?= isset($patient['marital_status']) && $patient['marital_status'] == 'Separated' ? 'selected' : '' ?>>Separated</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="nickname">Nickname</label>
                                                <input type="text" class="form-control" id="nickname" name="nickname" value="<?= isset($patient['nickname']) ? $patient['nickname'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="religion">Religion</label>
                                                <input type="text" class="form-control" id="religion" name="religion" value="<?= isset($patient['religion']) ? $patient['religion'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required" for="nationality">Nationality</label>
                                                <input type="text" class="form-control" id="nationality" name="nationality" 
                                                    value="<?= isset($patient['nationality']) && !empty($patient['nationality']) ? $patient['nationality'] : 'Filipino'; ?>" 
                                                    required data-parsley-required-message="This field is required.">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required" for="cellphone_no">Cellphone No.</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">+63</span> <!-- Philippines Country Code -->
                                                    </div>
                                                    <input type="text" class="form-control" id="cellphone_no" name="cellphone_no" 
                                                        value="<?= isset($contact_number) ? $contact_number : '' ?>" 
                                                        required data-parsley-required-message="This field is required.">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required" for="email">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? $email : '' ?>" required data-parsley-required-message="This field is required.">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required" for="home_address">Home Address</label>
                                                <input type="text" class="form-control" id="home_address" name="home_address" value="<?= isset($patient['home_address']) ? $patient['home_address'] : '' ?>" required data-parsley-required-message="This field is required.">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required" for="occupation">Occupation</label>
                                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?= isset($patient['occupation']) ? $patient['occupation'] : '' ?>" required data-parsley-required-message="This field is required.">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="guardian_name">Parent/Guardian's Name (if minor)</label>
                                                <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="<?= isset($patient['guardian_name']) ? $patient['guardian_name'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="guardian_occupation">Parent/Guardian's Occupation</label>
                                                <input type="text" class="form-control" id="guardian_occupation" name="guardian_occupation" value="<?= isset($patient['guardian_occupation']) ? $patient['guardian_occupation'] : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="referral-info">
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label for="referral_source">Whom may we thank for referring you?</label>
                                                <input type="text" class="form-control" id="referral_source" name="referral_source" placeholder="Please enter full name" value="<?= isset($patient['referral_source']) ? $patient['referral_source'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="required" for="reason_for_consultation">Reason for consultation</label>
                                                <input type="text" class="form-control" id="reason_for_consultation" name="reason_for_consultation" value="<?= isset($patient['reason_for_consultation']) ? $patient['reason_for_consultation'] : '' ?>" required data-parsley-required-message="This field is required.">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Dental History -->
                                    <div id="dental-history">
                                        <h4>Dental History</h4>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="required" for="previous_dentist">Previous Dentist</label>
                                                <input type="text" class="form-control" id="previous_dentist" name="previous_dentist" value="<?= isset($patient['previous_dentist']) ? $patient['previous_dentist'] : '' ?>" required data-parsley-required-message="This field is required.">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="last_dental_visit">Last Dental Visit</label>
                                                <input type="date" class="form-control" id="last_dental_visit" name="last_dental_visit" value="<?= isset($patient['last_dental_visit']) ? $patient['last_dental_visit'] : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Medical History -->
                                    <div id="medical-history">
                                        <h4>Medical History</h4>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label for="physician_name">Physician Name</label>
                                                <input type="text" class="form-control" id="physician_name" name="physician_name" value="<?= isset($patient['physician_name']) ? $patient['physician_name'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="physician_specialty">Specialty</label>
                                                <input type="text" class="form-control" id="physician_specialty" name="physician_specialty" value="<?= isset($patient['physician_specialty']) ? $patient['physician_specialty'] : '' ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label for="physician_address">Office Address</label>
                                                <input type="text" class="form-control" id="physician_address" name="physician_address" value="<?= isset($patient['physician_address']) ? $patient['physician_address'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="physician_phone_no">Office Number</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">+63</span> <!-- Philippines Country Code -->
                                                    </div>
                                                    <input type="text" class="form-control" id="physician_phone_no" name="physician_phone_no" 
                                                        value="<?= isset($patient['physician_phone_no']) ? $patient['physician_phone_no'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="health-questions">
                                        <!-- Health Questions -->
                                        <h4>Health Information</h4>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <label class="required">Are you in good health?</label>
                                                <select class="form-control" name="good_health" id="good_health" required data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['good_health']) && $patient['good_health'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['good_health']) && $patient['good_health'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required">Are you under medical treatment now?</label>
                                                <select class="form-control" id="under_medical_treatment" name="under_medical_treatment" required data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['under_medical_treatment']) && $patient['under_medical_treatment'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['under_medical_treatment']) && $patient['under_medical_treatment'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required">Have you had a serious illness or operation?</label>
                                                <select class="form-control" id="serious_illness" name="serious_illness" required data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['serious_illness']) && $patient['serious_illness'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['serious_illness']) && $patient['serious_illness'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                                <div id="serious_illness_group" style="display: none;">
                                                    <label class="required">If yes, what illness/operation?</label>
                                                    <input type="text" class="form-control" id="illness_details" name="illness_details" value="<?= isset($patient['illness_details']) ? $patient['illness_details'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <label class="required">Have you been hospitalized?</label>
                                                <select class="form-control" id="hospitalization" name="hospitalization" required data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['hospitalization']) && $patient['hospitalization'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['hospitalization']) && $patient['hospitalization'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                                <div id="hospitalization_details_group" style="display: none;">
                                                    <label class="required">If yes, why?</label>
                                                    <input type="text" id="hospitalization_reason" class="form-control" name="hospitalization_reason" value="<?= isset($patient['hospitalization_reason']) ? $patient['hospitalization_reason'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required">Are you taking any prescription/non-prescription medication?</label>
                                                <select class="form-control" id="taking_medication" name="taking_medication" required data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['taking_medication']) && $patient['taking_medication'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['taking_medication']) && $patient['taking_medication'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                                <div id="medication_details_group" style="display: none;">
                                                    <label class="required">If yes, what medication?</label>
                                                    <input type="text" class="form-control" id="medication_details" name="medication_details" value="<?= isset($patient['medication_details']) ? $patient['medication_details'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label class="required">Do you use tobacco products?</label>
                                                <select class="form-control" name="use_tobacco" id="use_tobacco" required data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['use_tobacco']) && $patient['use_tobacco'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['use_tobacco']) && $patient['use_tobacco'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <label class="required">Do you use alcohol or other dangerous drugs?</label>
                                                <select class="form-control" name="use_drugs" id="use_drugs" required data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['use_drugs']) && $patient['use_drugs'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['use_drugs']) && $patient['use_drugs'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="required">Are you allergic to any of the following?</label>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input allergy" name="allergies[]" value="Local Anesthetic" 
                                                        <?= in_array("Local Anesthetic", $patient_allergies) ? 'checked' : '' ?> data-parsley-mincheck="1" 
                                                        data-parsley-required-message="Please choose at least one allergy.">
                                                    <label class="form-check-label">Local Anesthetic (e.g. Lidocaine)</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input allergy" name="allergies[]" value="Penicillin" 
                                                        <?= in_array("Penicillin", $patient_allergies) ? 'checked' : '' ?> data-parsley-mincheck="1" 
                                                        data-parsley-required-message="Please choose at least one allergy.">
                                                    <label class="form-check-label">Penicillin</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input allergy" name="allergies[]" value="Antibiotics" 
                                                        <?= in_array("Antibiotics", $patient_allergies) ? 'checked' : '' ?> data-parsley-mincheck="1" 
                                                        data-parsley-required-message="Please choose at least one allergy.">
                                                    <label class="form-check-label">Antibiotics</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input allergy" name="allergies[]" value="Sulfa Drugs" 
                                                        <?= in_array("Sulfa Drugs", $patient_allergies) ? 'checked' : '' ?> data-parsley-mincheck="1" 
                                                        data-parsley-required-message="Please choose at least one allergy.">
                                                    <label class="form-check-label">Sulfa Drugs</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input allergy" name="allergies[]" value="Aspirin" 
                                                        <?= in_array("Aspirin", $patient_allergies) ? 'checked' : '' ?> data-parsley-mincheck="1" 
                                                        data-parsley-required-message="Please choose at least one allergy.">
                                                    <label class="form-check-label">Aspirin</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input allergy" name="allergies[]" value="Latex" 
                                                        <?= in_array("Latex", $patient_allergies) ? 'checked' : '' ?> data-parsley-mincheck="1" 
                                                        data-parsley-required-message="Please choose at least one allergy.">
                                                    <label class="form-check-label">Latex</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input allergy" name="allergies[]" value="None of the above" 
                                                        <?= in_array("None of the above", $patient_allergies) ? 'checked' : '' ?> data-parsley-mincheck="1" 
                                                        data-parsley-required-message="Please choose at least one allergy.">
                                                    <label class="form-check-label">None of the above</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input allergy" name="allergies[]" value="Others" 
                                                        <?= in_array("Others", $patient_allergies) ? 'checked' : '' ?> data-parsley-mincheck="1" 
                                                        data-parsley-required-message="Please choose at least one allergy.">
                                                    <label class="form-check-label">Others</label>
                                                    <input type="text" class="form-control mt-2" name="other_allergies" value="<?= (in_array("Others", $patient_allergies) ? htmlspecialchars($other_allergies_value) : '') ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Additional Information for Women -->
                                    <div id="for-women" style="display: none;">
                                        <h4>For Women Only</h4>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="required">Are you pregnant?</label>
                                                <select class="form-control" id="pregnant" name="pregnant" data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['pregnant']) && $patient['pregnant'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['pregnant']) && $patient['pregnant'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="required">Are you nursing?</label>
                                                <select class="form-control" id="nursing" name="nursing" data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['nursing']) && $patient['nursing'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['nursing']) && $patient['nursing'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="required">Are you taking birth control pills?</label>
                                                <select class="form-control" id="birth_control" name="birth_control" data-parsley-required-message="This field is required.">
                                                    <option value="">--Please Select--</option>
                                                    <option value="1" <?= isset($patient['taking_birth_control']) && $patient['taking_birth_control'] == 1 ? 'selected' : '' ?>>Yes</option>
                                                    <option value="0" <?= isset($patient['taking_birth_control']) && $patient['taking_birth_control'] == 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Other Medical Information -->
                                    <div id="other-medical-info">
                                        <h4>Other Medical Information</h4>
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <label for="blood_type">Blood Type</label>
                                                <input type="text" class="form-control" id="blood_type" name="blood_type" value="<?= isset($patient['blood_type']) ? $patient['blood_type'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label for="blood_pressure">Blood Pressure</label>
                                                <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" value="<?= isset($patient['blood_pressure']) ? $patient['blood_pressure'] : '' ?>">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label>Check if you have or had any of the following:</label>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input medical_conditions" name="medical_conditions[]" value="High Blood Pressure" <?= in_array("High Blood Pressure", $patient_medical_conditions) ? 'checked' : '' ?>>
                                                    <label class="form-check-label">High Blood Pressure</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input medical_conditions" name="medical_conditions[]" value="Heart Diseases" <?= in_array("Heart Diseases", $patient_medical_conditions) ? 'checked' : '' ?>>
                                                    <label class="form-check-label">Heart Diseases</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input medical_conditions" name="medical_conditions[]" value="Cancer" <?= in_array("Cancer", $patient_medical_conditions) ? 'checked' : '' ?>>
                                                    <label class="form-check-label">Cancer/Tumors</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input medical_conditions" name="medical_conditions[]" value="Diabetes" <?= in_array("Diabetes", $patient_medical_conditions) ? 'checked' : '' ?>>
                                                    <label class="form-check-label">Diabetes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input medical_conditions" name="medical_conditions[]" value="None of the above" <?= in_array("None of the above", $patient_medical_conditions) ? 'checked' : '' ?>>
                                                    <label class="form-check-label">None of the above</label>
                                                </div>
                                                <!-- Add additional medical condition checkboxes as needed -->
                                            </div>
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
                <div class="card">
                    <div class="card">
                        <div class="card-header">
                            <h4>Dental Treatment Records</h4>
                        </div>
                        <div class="table-responsive">
                            <div class="card-body">
                                <?php
                                    // Check if $dental_records is an array and not false
                                    if ($dental_records && is_array($dental_records)) :
                                ?>
                                    <table class="table table-bordered" id="dentalTable">
                                        <thead>
                                            <tr>
                                                <th colspan="8" class="text-center bg-light ">Dental Treatment Record</th>
                                                <th colspan="4" class="text-center bg-light">Prescription Details</th>
                                            </tr>
                                            <tr>
                                                <th class="dental-treatment">Date</th>
                                                <th class="dental-treatment">Tooth No./s</th>
                                                <th class="dental-treatment">Procedure</th>
                                                <th class="dental-treatment">Dentist/s</th>
                                                <th class="dental-treatment">Amount Charged</th>
                                                <th class="dental-treatment">Amount Paid</th>
                                                <th class="dental-treatment">Balance</th>
                                                <th class="dental-treatment">Next Appointment Date</th>
                                                <th class="prescription">Prescription</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dental_records as $record) : ?>
                                                <tr>
                                                    <td><?= $record['date'] ?></td>
                                                    <td><?= $record['tooth_no'] ?></td>
                                                    <td><?= $record['procedure'] ?></td>
                                                    <td><?= $record['dentist'] ?></td>
                                                    <td><?= $record['amount_charged'] ?></td>
                                                    <td><?= $record['amount_paid'] ?></td>
                                                    <td><?= $record['balance'] ?></td>
                                                    <td><?= $record['next_appointment'] ?></td>
                                                    <td>
                                                        <?php
                                                            if ($record['image']) {
                                                                echo "<a href='admin/{$record['image']}' target='_blank'>View Prescription</a>";
                                                            } else {
                                                                echo "No prescription uploaded";
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p>No dental records found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->

<?php
    include_once("inc/userDashboardFooter.php");
?>
