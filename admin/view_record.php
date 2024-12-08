<?php
include_once('inc/headerDashboard.php');
include_once('inc/sidebarMenu.php');

// Fetch the patient ID from the URL
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    // Fetch the patient details
    $get_patient_by_id = $appointment_admin->get_patient_by_id($patient_id);
    if (!$get_patient_by_id) {
        echo "<p>Patient record not found.</p>";
        exit();
    }

    // Fetch additional details from the tables
    $medical_history = $appointment_admin->get_medical_history($patient_id);
    $guardians = $appointment_admin->get_guardians($patient_id);
    $consultations = $appointment_admin->get_consultations($patient_id);

    $patient_allergies = [];
    $patient_medical_conditions = [];

    // Example: Initialize $patient_allergies
    $patient_allergies = $appointment_admin->get_patient_allergies($patient_id);
    $patient_medical_conditions = $appointment_admin->get_patient_medical_conditions($patient_id); // Default to empty array if null
    // Assuming that you are fetching only one patient's record
    $patient = !empty($patientRecords) ? $patientRecords[0] : null;

    // Fetch existing dental treatment records
    $dental_records = $appointment_admin->get_dental_records($patient_id);
       
} else {
    echo "<p>No patient ID provided.</p>";
    exit();
}
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
                    <h3>Patient Record</h3>
                    <p class="text-subtitle text-muted">View and manage the detailed record of the patient</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="patients.php">Patients</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Patient Record</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button onclick="printFormRecord();" class="btn btn-primary">Print Record</button>
                </div>
                <div id="printable-content">
                    <div class="card-body">
                        <form class="forms-sample" id="patient_record_form" >

                            <!-- Printable content -->
                            <div class="mb-4">
                                <div class="p-3 border rounded bg-light text-center">
                                    <p class="mb-0 fs-4"><strong>Patient Information Form</strong></p>
                                </div>
                            </div>
                            <div id="personal-info">
                                <div class="row">
                                    <div id="patient-info">
                                        <div class="member-id">
                                            <strong>Patient ID:</strong> <span id="member_id_display"><?=$get_patient_by_id['member_id']?></span>
                                        </div>
                                        <div class="assigned-doctor">
                                            <strong>Assigned Doctor:</strong> <span id="assigned_doctor_display"><?=$get_patient_by_id['doctor_first_name'] .' '.$get_patient_by_id['doctor_last_name']?></span>
                                        </div>
                                    </div>
                                    <br/>
                                <!-- Personal Information -->
                                    <div class="form-group col-sm-4">
                                    <input type="hidden" class="form-control" id="doctor" name="doctor" value="<?=$get_patient_by_id['doctor_first_name'] .' '.$get_patient_by_id['doctor_last_name']?>"  readonly>
                                    <input type="hidden" class="form-control" id="member_id" name="member_id" value="<?= isset($get_patient_by_id['member_id']) ? $get_patient_by_id['member_id'] : '' ?>"  readonly>
                                        <label class="required" for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= isset($get_patient_by_id['last_name']) ? $get_patient_by_id['last_name'] : '' ?>"  readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required" for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= isset($get_patient_by_id['first_name']) ? $get_patient_by_id['first_name'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= isset($get_patient_by_id['middle_name']) ? $get_patient_by_id['middle_name'] : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="required" for="birthdate">Birthdate</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= isset($get_patient_by_id['birthdate']) ? $get_patient_by_id['birthdate'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="age">Age</label>
                                        <input type="number" class="form-control" id="age" name="age" value="<?= isset($get_patient_by_id['age']) ? $get_patient_by_id['age'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required">Gender</label>
                                        <input type="number" class="form-control" id="sex" name="sex" value="<?= isset($get_patient_by_id['sex']) ? $get_patient_by_id['sex'] : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="nickname">Nickname</label>
                                        <input type="text" class="form-control" id="nickname" name="nickname" value="<?= isset($get_patient_by_id['nickname']) ? $get_patient_by_id['nickname'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="religion">Religion</label>
                                        <input type="text" class="form-control" id="religion" name="religion" value="<?= isset($get_patient_by_id['religion']) ? $get_patient_by_id['religion'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required" for="nationality">Nationality</label>
                                        <input type="text" class="form-control" id="nationality" name="nationality" value="<?= isset($get_patient_by_id['nationality']) ? $get_patient_by_id['nationality'] : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="required" for="cellphone_no">Cellphone No.</label>
                                        <input type="text" class="form-control" id="cellphone_no" name="cellphone_no" value="<?= isset($get_patient_by_id['cellphone_no']) ? $get_patient_by_id['cellphone_no'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required" for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($get_patient_by_id['email']) ? $get_patient_by_id['email'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required" for="home_address">Home Address</label>
                                        <input type="text" class="form-control" id="home_address" name="home_address" value="<?= isset($get_patient_by_id['home_address']) ? $get_patient_by_id['home_address'] : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="required" for="occupation">Occupation</label>
                                        <input type="text" class="form-control" id="occupation" name="occupation" value="<?= isset($get_patient_by_id['occupation']) ? $get_patient_by_id['occupation'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="guardian_name">Parent/Guardian's Name (if minor)</label>
                                        <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="<?= isset($get_patient_by_id['guardian_name']) ? $v['guardian_name'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="guardian_occupation">Parent/Guardian's Occupation</label>
                                        <input type="text" class="form-control" id="guardian_occupation" name="guardian_occupation" value="<?= isset($get_patient_by_id['guardian_occupation']) ? $get_patient_by_id['guardian_occupation'] : '' ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div id="referral-info">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="referral_source">Whom may we thank for referring you?</label>
                                        <input type="text" class="form-control" id="referral_source" name="referral_source" value="<?= isset($consultations['referral_source']) ? $consultations['referral_source'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="required" for="reason_for_consultation">Reason for consultation</label>
                                        <input type="text" class="form-control" id="reason_for_consultation" name="reason_for_consultation" value="<?= isset($consultations['reason_for_consultation']) ? $consultations['reason_for_consultation'] : '' ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Dental History -->
                            <div id="dental-history">
                                <h4>Dental History</h4>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="required" for="previous_dentist">Previous Dentist</label>
                                        <input type="text" class="form-control" id="previous_dentist" name="previous_dentist" value="<?= isset($consultations['previous_dentist']) ? $consultations['previous_dentist'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="required" for="last_dental_visit">Last Dental Visit</label>
                                        <input type="text" class="form-control" id="last_dental_visit" name="last_dental_visit" value="<?= isset($consultations['last_dental_visit']) ? $consultations['last_dental_visit'] : '' ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <!-- Medical History -->
                            <div id="medical-history">
                                <h4>Medical History</h4>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="physician_name">Physician Name</label>
                                        <input type="text" class="form-control" id="physician_name" name="physician_name" value="<?= isset($medical_history['physician_name']) ? $medical_history['physician_name'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="physician_specialty">Specialty</label>
                                        <input type="text" class="form-control" id="physician_specialty" name="physician_specialty" value="<?= isset($medical_history['physician_specialty']) ? $medical_history['physician_specialty'] : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="physician_address">Office Address</label>
                                        <input type="text" class="form-control" id="physician_address" name="physician_address" value="<?= isset($medical_history['physician_address']) ? $medical_history['physician_address'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="physician_phone_no">Office Number</label>
                                        <input type="text" class="form-control" id="physician_phone_no" name="physician_phone_no" value="<?= isset($medical_history['physician_phone_no']) ? $medical_history['physician_phone_no'] : '' ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div id="health-questions">
                                <!-- Health Questions -->
                                <h4>Health Information</h4>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="required">Are you in good health?</label>
                                        <select class="form-control" name="good_health" id="good_health" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['good_health']) && $medical_history['good_health'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['good_health']) && $medical_history['good_health'] == 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required">Are you under medical treatment now?</label>
                                        <select class="form-control" id="under_medical_treatment" name="under_medical_treatment" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['under_medical_treatment']) && $medical_history['under_medical_treatment'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['under_medical_treatment']) && $medical_history['under_medical_treatment'] == 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required">Have you had a serious illness or operation?</label>
                                        <select class="form-control" id="serious_illness" name="serious_illness" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['serious_illness']) && $medical_history['serious_illness'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['serious_illness']) && $medical_history['serious_illness'] == 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                        <div id="serious_illness_group" style="display: none;">
                                            <label class="required">If yes, what illness/operation?</label>
                                            <input type="text" class="form-control" id="illness_details" name="illness_details" value="<?= isset($medical_history['illness_details']) ? $medical_history['illness_details'] : '' ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="required">Have you been hospitalized?</label>
                                        <select class="form-control" id="hospitalization" name="hospitalization" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['hospitalization']) && $medical_history['hospitalization'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['hospitalization']) && $medical_history['hospitalization'] == 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                        <div id="hospitalization_details_group" style="display: none;">
                                            <label class="required">If yes, why?</label>
                                            <input type="text" id="hospitalization_reason" class="form-control" name="hospitalization_reason" value="<?= isset($medical_history['hospitalization_reason']) ? $medical_history['hospitalization_reason'] : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required">Are you taking any prescription/non-prescription medication?</label>
                                        <select class="form-control" id="taking_medication" name="taking_medication" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['under_medical_treatment']) && $medical_history['under_medical_treatment'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['under_medical_treatment']) && $medical_history['under_medical_treatment'] == 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                        <div id="medication_details_group" style="display: none;">
                                            <label class="required">If yes, what medication?</label>
                                            <input type="text" class="form-control" id="medication_details" name="medication_details" value="<?= isset($medical_history['medication_details']) ? $medical_history['medication_details'] : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="required">Do you use tobacco products?</label>
                                        <select class="form-control" name="use_tobacco" id="use_tobacco" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['use_tobacco']) && $medical_history['use_tobacco'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['use_tobacco']) && $medical_history['use_tobacco'] == 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="required">Do you use alcohol or other dangerous drugs?</label>
                                        <select class="form-control" name="use_drugs" id="use_drugs" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['use_drugs']) && $medical_history['use_drugs'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['use_drugs']) && $medical_history['use_drugs'] == 0 ? 'selected' : '' ?>>No</option>
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
                            <div id="for-women">
                                <h4>For Women Only</h4>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="required">Are you pregnant?</label>
                                        <select class="form-control" id="pregnant" name="pregnant" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['pregnant']) && $medical_history['pregnant'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['pregnant']) && $medical_history['pregnant'] == 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="required">Are you nursing?</label>
                                        <select class="form-control" id="nursing" name="nursing" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['nursing']) && $medical_history['nursing'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['nursing']) && $medical_history['nursing'] == 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="required">Are you taking birth control pills?</label>
                                        <select class="form-control" id="birth_control" name="birth_control" readonly>
                                            <option value="">--Please Select--</option>
                                            <option value="1" <?= isset($medical_history['taking_birth_control']) && $medical_history['taking_birth_control'] == 1 ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= isset($medical_history['taking_birth_control']) && $medical_history['taking_birth_control'] == 0 ? 'selected' : '' ?>>No</option>
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
                                        <input type="text" class="form-control" id="blood_type" name="blood_type" value="<?= isset($medical_history['blood_type']) ? $medical_history['blood_type'] : '' ?>" readonly>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="blood_pressure">Blood Pressure</label>
                                        <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" value="<?= isset($medical_history['blood_pressure']) ? $medical_history['blood_pressure'] : '' ?>" readonly>
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
                                        <!-- Add additional medical condition checkboxes as needed -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Dental Treatment Records</h4>
                            <button type="button" class="btn btn-success" onclick="addRow()">Add Row</button>
                        </div>
                        <div class="table-responsive">
                            <div class="card-body">
                                <form id="dentalRecordForm" action="controller/saveDentalrecord.php" method="POST">
                                    <table class="table table-bordered" id="dentalTable">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Tooth No./s</th>
                                                <th>Procedure</th>
                                                <th>Dentist/s</th>
                                                <th>Amount Charged</th>
                                                <th>Amount Paid</th>
                                                <th>Balance</th>
                                                <th>Next Appointment Date</th>
                                                <th>Medication</th>
                                                <th>Dosage</th>
                                                <th>Instructions</th>
                                                <th>Prescription Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <input type="hidden" value=<?=$patient_id?> name="patient_id"/>
                                            <?php if ($dental_records) : ?>
                                                <?php foreach ($dental_records as $record) : ?>
                                                    <tr>
                                                        <td>
                                                            <input type="date" name="date[]" value="<?= $record['date'] ?>" class="form-control" required>
                                                        </td>
                                                        <td><input type="text" name="tooth_no[]" value="<?= $record['tooth_no'] ?>" class="form-control"></td>
                                                        <td><input type="text" name="procedure[]" value="<?= $record['procedure'] ?>" class="form-control" required></td>
                                                        <td><input type="text" name="dentist[]" value="<?= $record['dentist'] ?>" class="form-control" required></td>
                                                        <td>
                                                            <input type="number" name="amount_charged[]" 
                                                                value="<?= $record['amount_charged'] ?>" 
                                                                class="form-control amount-charged" 
                                                                step="0.01" 
                                                                oninput="updateBalance(this)" 
                                                                required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="amount_paid[]" 
                                                                value="<?= $record['amount_paid'] ?>" 
                                                                class="form-control amount-paid" 
                                                                step="0.01" 
                                                                oninput="updateBalance(this)" 
                                                                required>
                                                        </td>
                                                        <td><input type="number" name="balance[]" value="<?= $record['balance'] ?>" class="form-control balance" step="0.01" readonly></td>
                                                        <td><input type="date" name="next_appointment[]" value="<?= $record['next_appointment'] ?>" class="form-control"></td>
                                                        <td><input type="text" name="medication[]" disabled value="<?= $record['medication'] ?>" class="form-control"></td>
                                                        <td><textarea name="dosage[]"  style="width: 250px; height: 200px; font-size: 16px; padding: 10px;" value="<?= $record['dosage'] ?>" disabled class="form-control"><?= $record['dosage'] ?></textarea></td>
                                                        <td><textarea name="instructions[]"  style="width: 250px; height: 200px; font-size: 16px; padding: 10px;" disabled value="<?= $record['instructions'] ?>" class="form-control"><?= $record['instructions'] ?></textarea></td>
                                                        <td>
                                                            <?php
                                                                if ($record['image']) {
                                                                    echo "<a href='{$record['image']}' target='_blank'>View Prescription</a>";
                                                                } else {
                                                                    echo "No prescription uploaded";
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button>
                                                            <?php if (!empty($record['dental_record_id'])): ?>
                                                                <button type="button" class="btn btn-primary" onclick="openPrescriptionModal('<?= $record['dental_record_id'] ?>')">Add Prescription</button>
                                                            <?php endif; ?>                                                        
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td><input type="date" name="date[]" class="form-control" required></td>
                                                    <td><input type="text" name="tooth_no[]" class="form-control" ></td>
                                                    <td><input type="text" name="procedure[]" class="form-control" required></td>
                                                    <td><input type="text" name="dentist[]" class="form-control" required></td>
                                                    <td>
                                                        <input type="number" name="amount_charged[]" 
                                                            class="form-control amount-charged" 
                                                            step="0.01" 
                                                            oninput="updateBalance(this)" 
                                                            required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="amount_paid[]" 
                                                            class="form-control amount-paid" 
                                                            step="0.01" 
                                                            oninput="updateBalance(this)" 
                                                            required>
                                                    </td>
                                                    <td><input type="number" name="balance[]" class="form-control balance" step="0.01" readonly></td>
                                                    <td><input type="date" name="next_appointment[]" class="form-control"></td>
                                                    <td><input type="text" name="medication[]" class="form-control" disabled></td>
                                                    <td><textarea style="width: 250px; height: 200px; font-size: 16px; padding: 10px;" disabled name="dosage[]" class="form-control"></textarea></td>
                                                    <td><textarea style="width: 250px; height: 200px; font-size: 16px; padding: 10px;" disabled name="instructions[]" class="form-control"></textarea></td>
                                                    <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button>
                                                        <?php if (!empty($record['dental_record_id'])): ?>
                                                            <button type="button" class="btn btn-primary" onclick="openPrescriptionModal('<?= $record['dental_record_id'] ?>')">Add Prescription</button>
                                                        <?php endif; ?>                                                    
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Save All Records</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1" aria-labelledby="prescriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prescriptionModalLabel">Add Prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="prescriptionForm" action="controller/submitPrescriptions.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="<?=$patient_id?>" name="patient_id"/>
                    <input type="hidden" name="dental_record_id" id="modal_dental_record_id" class="form-control">
                    <div class="mb-3">
                        <label for="medication" class="form-label">Medication</label>
                        <input type="text" name="medication" id="modal_medication" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosage" class="form-label">Dosage</label>
                        <textarea name="dosage" id="modal_dosage" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="instructions" class="form-label">Instructions</label>
                        <textarea name="instructions" id="modal_instructions" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="prescription_image" class="form-label">Upload Prescription Image</label>
                        <input type="file" name="prescription_image" id="prescription_image" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitPrescription()">Save Prescription</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to open the prescription modal and set the dental_record_id
    function openPrescriptionModal(dental_record_id) {
        document.getElementById('modal_dental_record_id').value = dental_record_id;
        $('#prescriptionModal').modal('show');
    }

    function submitPrescription() {
        // Ensure all fields are valid before submitting
        var form = document.getElementById("prescriptionForm");

        // Trigger form submission programmatically
        if (form.checkValidity()) {
            form.submit(); // This will submit the form to the action defined in the form
            $('#prescriptionModal').modal('hide'); // Close the modal after form submission
        } else {
            // If form is invalid, alert the user or handle validation errors
            alert('Please fill in all required fields.');
        }
    }


    function updateBalance(element) {
        // Get the current row of the clicked input
        var row = element.closest('tr');
        
        // Get the Amount Charged and Amount Paid inputs in this row
        var chargedInput = row.querySelector('input[name="amount_charged[]"]');
        var paidInput = row.querySelector('input[name="amount_paid[]"]');
        
        // Get the Balance input field
        var balanceInput = row.querySelector('input[name="balance[]"]');

        // Check if the inputs exist (just in case)
        if (!chargedInput || !paidInput || !balanceInput) {
            console.error('Missing one of the required inputs');
            return;
        }

        // Parse the values (default to 0 if the input is empty)
        var charged = parseFloat(chargedInput.value) || 0;
        var paid = parseFloat(paidInput.value) || 0;

        // Calculate the balance (charged - paid)
        var balance = charged - paid;

        // Update the Balance input field with the computed balance
        balanceInput.value = balance.toFixed(2);  // Format to 2 decimal places
    }

    // Add event listeners to each Amount Charged and Amount Paid field when the page loads
    document.querySelectorAll('input[name="amount_charged[]"], input[name="amount_paid[]"]').forEach(function(input) {
        input.addEventListener('input', function() {
            updateBalance(this);
        });
    });
</script>
<?php
include_once('inc/footerDashboard.php');
?>
