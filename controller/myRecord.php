<?php
include('../model/userDashboard.php');
session_start();

// initializing variables
$errors = array();
$user_dashboard = new UserDashboard();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $last_name = $_POST['last_name'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $age = $_POST['age'] ?? 0;
    $sex = $_POST['sex'] ?? '';
    $nickname = $_POST['nickname'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $nationality = $_POST['nationality'] ?? '';
    $cellphone_no = $_POST['cellphone_no'] ?? '';
    $email = $_POST['email'] ?? '';
    $home_address = $_POST['home_address'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $guardian_name = $_POST['guardian_name'] ?? '';
    $guardian_occupation = $_POST['guardian_occupation'] ?? '';
    $referral_source = $_POST['referral_source'] ?? '';
    $reason_for_consultation = $_POST['reason_for_consultation'] ?? '';

    // Other fields (e.g., dental and medical history)
    $previous_dentist = $_POST['previous_dentist'] ?? '';
    $last_dental_visit = $_POST['last_dental_visit'] ?? '';
    $physician_name = $_POST['physician_name'] ?? '';
    $physician_specialty = $_POST['physician_specialty'] ?? '';
    $physician_address = $_POST['physician_address'] ?? '';
    $physician_phone_no = $_POST['physician_phone_no'] ?? '';

    // Health information
    $good_health = $_POST['good_health'] ?? 0;
    $under_medical_treatment = $_POST['under_medical_treatment'] ?? 0;
    $serious_illness = $_POST['serious_illness'] ?? 0;
    $illness_details = $_POST['illness_details'] ?? '';
    $hospitalization = $_POST['hospitalization'] ?? 0;
    $hospitalization_reason = $_POST['hospitalization_reason'] ?? '';
    $taking_medication = $_POST['taking_medication'] ?? 0;
    $medication_details = $_POST['medication_details'] ?? '';
    $use_tobacco = $_POST['use_tobacco'] ?? 0;
    $use_drugs = $_POST['use_drugs'] ?? 0;

    // Allergies and medical conditions (as arrays)
    $allergies = $_POST['allergies'] ?? [];
    $medical_conditions = $_POST['medical_conditions'] ?? [];

    // For women only
    $pregnant = $_POST['pregnant'] ?? 0;
    $nursing = $_POST['nursing'] ?? 0;
    $birth_control = $_POST['birth_control'] ?? 0;

    // Other medical information
    $blood_type = $_POST['blood_type'] ?? '';
    $blood_pressure = $_POST['blood_pressure'] ?? '';

    // Call the update method from userDashboard class
    $sql = $user_dashboard->update_medical_record([
        'last_name' => $last_name,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'birthdate' => $birthdate,
        'age' => $age,
        'sex' => $sex,
        'nickname' => $nickname,
        'religion' => $religion,
        'nationality' => $nationality,
        'cellphone_no' => $cellphone_no,
        'email' => $email,
        'home_address' => $home_address,
        'occupation' => $occupation,
        'guardian_name' => $guardian_name,
        'guardian_occupation' => $guardian_occupation,
        'referral_source' => $referral_source,
        'reason_for_consultation' => $reason_for_consultation,
        'previous_dentist' => $previous_dentist,
        'last_dental_visit' => $last_dental_visit,
        'physician_name' => $physician_name,
        'physician_specialty' => $physician_specialty,
        'physician_address' => $physician_address,
        'physician_phone_no' => $physician_phone_no,
        'good_health' => $good_health,
        'under_medical_treatment' => $under_medical_treatment,
        'serious_illness' => $serious_illness,
        'illness_details' => $illness_details,
        'hospitalization' => $hospitalization,
        'hospitalization_reason' => $hospitalization_reason,
        'taking_medication' => $taking_medication,
        'medication_details' => $medication_details,
        'use_tobacco' => $use_tobacco,
        'use_drugs' => $use_drugs,
        'allergies' => json_encode($allergies),
        'medical_conditions' => json_encode($medical_conditions),
        'pregnant' => $pregnant,
        'nursing' => $nursing,
        'birth_control' => $birth_control,
        'blood_type' => $blood_type,
        'blood_pressure' => $blood_pressure
    ]);

    if ($sql) {
        // Success
        $_SESSION['message_type'] = "success";
        $_SESSION['display_message'] = "Medical Record updated successfully!";
    } else {
        $_SESSION['message_type'] = "error";
        $_SESSION['display_message'] = "Error updating medical record.";
    }

    // Redirect back to profile page
    header("Location: ../my_record.php");
    exit;
}
?>
