<?php
    include('../model/userDashboard.php');
    session_start();
    
    // initializing variables
    $errors = array();
    $user_dashboard = new UserDashboard();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Variables from form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $remarks = $_POST['remarks'];
    $contactnumber = $_POST['contact_number'];
    $member_id = $_POST['member_id'];
    $profilePicPath = null;  // Initialize as null in case no picture is uploaded

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Define valid file extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            
            // Define the folder path (create if not exists)
            $uploadDir = "../public/profile_picture/{$member_id}/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Move the file to the new directory
            $newFileName = "profile_pic." . $fileExtension; // Rename the file
            $dest_path = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // File successfully uploaded, save the path
                $profilePicPath = "public/profile_picture/{$member_id}/" . $newFileName;
            } else {
                $_SESSION['message_type'] = "danger";
                $_SESSION['display_message'] = "There was an error moving the uploaded file.";
            }
        } else {
            $_SESSION['message_type'] = "danger";
            $_SESSION['display_message'] = "Invalid file extension. Only JPG, PNG, GIF files are allowed.";
        }
    }

    // Call update method from userDashboard class
    $sql = $user_dashboard->update_profile($firstname, $lastname, $contactnumber, $email, $gender, $address, $profilePicPath, $remarks, $member_id);

    if ($sql) {
        // Success
        $_SESSION['message_type'] = "success";
        $_SESSION['display_message'] = "Profile updated successfully!";
    } else {
        $_SESSION['message_type'] = "danger";
        $_SESSION['display_message'] = "Error updating profile.";
    }

    // Redirect back to profile page
    header("Location: ../my_profile.php");
    exit;
}
?>
