<?php
    include('../model/userDashboard.php');
    session_start();
    
    // initializing variables
    $errors = array();
    $user_dashboard = new UserDashboard();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
        // Get the posted data
        $user_id = $_POST['doctor_id'];
        $appointmentId = intval($_POST['appointment_id']);
        $appointmentDate = $_POST['appointment_date'];
        $appointmentTime = $_POST['appointment_time'];
        $status = $_POST['status'];
        $notes = $_POST['notes'];
        $firstname = $_POST['first_name'];
        $lastname = $_POST['last_name'];
        $member_id = $_POST['member_id'];
    
        // Call the update appointment method
        if ($user_dashboard->update_appointment($appointmentId, $appointmentDate, $appointmentTime, $status, $notes, $firstname, $lastname, $member_id, $user_id)) {
            // Set success message
            $_SESSION['display_message'] = "Appointment successfully updated.";
            $_SESSION['message_type'] = "success";

            // Fetch doctor's email from the database
            $doctorEmail = $user_dashboard->get_doctor_details($user_id);

            // Fetch patient's email from the database
            $patientEmail = $user_dashboard->get_patient_email($member_id);

            // Prepare the email content for the doctor
            $subjectDoctor = "Appointment Updated - " . $appointmentId;
            $messageDoctor = "Dear Dr. " . $doctorEmail['first_name'] . " " . $doctorEmail['last_name'] . ",\n\n";
            $messageDoctor .= "The appointment details have been updated:\n\n";
            $messageDoctor .= "Appointment ID: " . $appointmentId . "\n";
            $messageDoctor .= "Patient Name: " . $firstname . " " . $lastname . "\n";
            $messageDoctor .= "Member ID: " . $member_id . "\n";
            $messageDoctor .= "New Appointment Date: " . $appointmentDate . "\n";
            $messageDoctor .= "New Appointment Time: " . $appointmentTime . "\n";
            $messageDoctor .= "Status: " . $status . "\n";
            $messageDoctor .= "Notes: " . $notes . "\n\n";
            $messageDoctor .= "Best regards,\nYour Clinic Team";

            // Prepare the email content for the patient
            $subjectPatient = "Your Appointment Has Been Updated - " . $appointmentId;
            $messagePatient = "Dear " . $firstname . " " . $lastname . ",\n\n";
            $messagePatient .= "Your appointment details have been updated:\n\n";
            $messagePatient .= "Appointment ID: " . $appointmentId . "\n";
            $messagePatient .= "New Appointment Date: " . $appointmentDate . "\n";
            $messagePatient .= "New Appointment Time: " . $appointmentTime . "\n";
            $messagePatient .= "Status: " . $status . "\n";
            $messagePatient .= "Notes: " . $notes . "\n\n";
            $messagePatient .= "Best regards,\nYour Clinic Team";

            // Send the email notification to the doctor
            $headers = "From: no-reply@yourclinic.com" . "\r\n" . 
                "Reply-To: no-reply@yourclinic.com" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            if (mail($doctorEmail['email'], $subjectDoctor, $messageDoctor, $headers)) {
                $_SESSION['email_message'] = "Email notification sent to the doctor.";
                $_SESSION['email_type'] = "success";
            } else {
                $_SESSION['email_message'] = "Failed to send email notification to the doctor.";
                $_SESSION['email_type'] = "danger";
            }

            // Send the email notification to the patient
            if (mail($patientEmail, $subjectPatient, $messagePatient, $headers)) {
                $_SESSION['patient_email_message'] = "Email notification sent to the patient.";
                $_SESSION['patient_email_type'] = "success";
            } else {
                $_SESSION['patient_email_message'] = "Failed to send email notification to the patient.";
                $_SESSION['patient_email_type'] = "danger";
            }

            header("Location: ../my_appointments.php");
            exit();

        } else {
            $_SESSION['display_message'] = "Error updating appointment.";
            $_SESSION['message_type'] = "danger";

            header("Location: ../my_appointments.php");
            exit();
        }
    }
?>
