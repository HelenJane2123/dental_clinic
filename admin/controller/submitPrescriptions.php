<?php
include('../model/AdminDashboard.php');
session_start();

// Create an instance of Admin class
$funObj = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Form data
    $dental_record_id = $_POST['dental_record_id'] ?? '';
    $medication = trim($_POST['medication'] ?? '');
    $dosage = trim($_POST['dosage'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $patient_id = $_POST['patient_id'] ?? '';

    // Initialize variables and error array
    $errors = array();
    $publicDir = "../public/prescriptions/{$dental_record_id}/"; // Directory for the dental record ID
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
    // Validate form inputs
    if (empty($medication)) $errors[] = "Medication is required.";
    if (empty($dosage)) $errors[] = "Dosage is required.";
    if (empty($instructions)) $errors[] = "Instructions are required.";

    // Handle file upload
    if (!empty($_FILES['prescription_image']['name'])) {
        $fileName = $_FILES['prescription_image']['name'];
        $fileTmpName = $_FILES['prescription_image']['tmp_name'];
        $fileSize = $_FILES['prescription_image']['size'];
        $fileType = $_FILES['prescription_image']['type'];

        // Validate file type
        if (!in_array($fileType, $allowedFileTypes)) {
            $errors[] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }

        // Validate file size (e.g., max 2MB)
        if ($fileSize > 2 * 1024 * 1024) {
            $errors[] = "File size must not exceed 2MB.";
        }

        // Create directory if it doesn't exist
        if (empty($errors)) {
            if (!is_dir($publicDir)) {
                if (!mkdir($publicDir, 0777, true)) {
                    $errors[] = "Failed to create directory: {$publicDir}";
                }
            }

            // Define the full path for the uploaded file
            $uploadPath = $publicDir . basename($fileName);

            // Move the uploaded file to the target directory
            if (!move_uploaded_file($fileTmpName, $uploadPath)) {
                $errors[] = "Failed to upload the image.";
            }
        }
    } else {
        $fileName = null; // No file uploaded
    }

    // If no errors, proceed to save the prescription
    if (empty($errors)) {
        $filePath = "public/prescriptions/{$dental_record_id}/" . $fileName; // Relative path to store in DB
        $result = $funObj->save_prescription($patient_id, $dental_record_id, $medication, $dosage, $instructions, $filePath);

        if ($result) {
            $_SESSION['message'] = 'Prescription added successfully.';
            header('Location: ../view_record.php?patient_id=' . $patient_id);
            exit();
        } else {
            $_SESSION['message'] = 'Failed to add prescription.';
            header('Location: ../view_record.php?patient_id=' . $patient_id);
            exit();
        }
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: ../view_record.php?patient_id=' . $patient_id);
        exit();
    }
}


?>
