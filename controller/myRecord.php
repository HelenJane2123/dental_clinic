<?php
include('../model/userDashboard.php');
session_start();

// Initializing variables
$errors = array();
$user_dashboard = new UserDashboard();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collecting form data
    $formData = [
        'patient_id' => $_POST['patient_id'] ?? '',
        'member_id' => $_POST['member_id'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'first_name' => $_POST['first_name'] ?? '',
        'middle_name' => $_POST['middle_name'] ?? '',
        'birthdate' => $_POST['birthdate'] ?? '',
        'age' => $_POST['age'] ?? 0,
        'sex' => $_POST['sex'] ?? '',
        'nickname' => $_POST['nickname'] ?? '',
        'marital_status' => $_POST['marital_status'] ?? '',
        'religion' => $_POST['religion'] ?? '',
        'nationality' => $_POST['nationality'] ?? '',
        'cellphone_no' => $_POST['cellphone_no'] ?? '',
        'email' => $_POST['email'] ?? '',
        'home_address' => $_POST['home_address'] ?? '',
        'occupation' => $_POST['occupation'] ?? '',
        'guardian_name' => $_POST['guardian_name'] ?? '',
        'guardian_occupation' => $_POST['guardian_occupation'] ?? '',
        'referral_source' => $_POST['referral_source'] ?? '',
        'reason_for_consultation' => $_POST['reason_for_consultation'] ?? '',
        'previous_dentist' => $_POST['previous_dentist'] ?? '',
        'last_dental_visit' => $_POST['last_dental_visit'] ?? '',
        'physician_name' => $_POST['physician_name'] ?? '',
        'physician_specialty' => $_POST['physician_specialty'] ?? '',
        'physician_address' => $_POST['physician_address'] ?? '',
        'physician_phone_no' => $_POST['physician_phone_no'] ?? '',
        'good_health' => $_POST['good_health'] ?? 0,
        'under_medical_treatment' => $_POST['under_medical_treatment'] ?? 0,
        'medical_condition_treated' => $_POST['illness_details'] ?? 0,
        'serious_illness' => $_POST['serious_illness'] ?? 0,
        'illness_details' => $_POST['illness_details'] ?? '',
        'hospitalization' => $_POST['hospitalization'] ?? 0,
        'hospitalization_reason' => $_POST['hospitalization_reason'] ?? '',
        'taking_medication' => $_POST['taking_medication'] ?? 0,
        'medication_details' => $_POST['medication_details'] ?? '',
        'use_tobacco' => $_POST['use_tobacco'] ?? 0,
        'use_drugs' => $_POST['use_drugs'] ?? 0,
        'allergies' => json_encode($_POST['allergies'] ?? []),
        'pregnant' => $_POST['pregnant'] ?? 0,
        'nursing' => $_POST['nursing'] ?? 0,
        'birth_control' => $_POST['birth_control'] ?? 0,
        'blood_type' => $_POST['blood_type'] ?? '',
        'blood_pressure' => $_POST['blood_pressure'] ?? '',
        'medical_conditions' => json_encode($_POST['medical_conditions'] ?? [])
    ];

    // Call the update method from userDashboard class
    $updateSuccess = $user_dashboard->update_medical_record($formData);

    if ($updateSuccess) {
        // Success message
        $_SESSION['message_type'] = "success";
        $_SESSION['display_message'] = "Medical Record updated successfully!";
    } else {
        // Error message
        $_SESSION['message_type'] = "danger";
        $_SESSION['display_message'] = "Error updating medical record.";
    }

    // Redirect back to profile page
    header("Location: ../my_record.php");
    exit;
}
?>
