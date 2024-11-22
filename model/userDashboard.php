<?php
    require_once(__DIR__ . '/../lib/config.php'); 
	require_once(BASE_DIR . 'lib/authenticate.php');
	class UserDashboard{
		public $db;
		public function __construct(){
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			if(mysqli_connect_errno()) {
				echo "Error: Could not connect to database.";
			        exit;
			}
		}

        public function register_appointment($member_id, $first_name, $last_name, $contactNumber, $emailAddress, $appointmentType, $appointmentDate, $appointmentTime, $services, $notes, $patient_id, $user_admin_id) {
            // Check if the patient already exists in the patients table
            $stmt = $this->db->prepare("SELECT patient_id FROM patients WHERE member_id = ?");
            $stmt->bind_param("s", $member_id);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                // Existing patient found
                $stmt->bind_result($existing_patient_id);
                $stmt->fetch();
                $stmt->close();
                
                // Use the existing patient ID for the appointment
                $patient_id = $existing_patient_id;
        
                if ($appointmentType === 'newPatient') {
                    // Insert new patient details under the same member ID (using alt_patient_id)
                    $stmt = $this->db->prepare("INSERT INTO patients (member_id, last_name, first_name, cellphone_no, email, assigned_doctor) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssi", $member_id, $first_name, $last_name, $contactNumber, $emailAddress, $user_admin_id);
            
                    if (!$stmt->execute()) {
                        echo "Error inserting new alternate patient: " . $stmt->error;
                        $stmt->close();
                        return false;
                    }
            
                    // Get the new alternate patient_id for the appointment
                    $patient_id = $this->db->insert_id;
                }
                else {
                     // Update existing patient record with assigned doctor only
                    $stmt = $this->db->prepare("UPDATE patients SET assigned_doctor = ? WHERE patient_id = ?");
                    $stmt->bind_param("ii", $user_admin_id, $patient_id);
                    
                    if (!$stmt->execute()) {
                        echo "Error updating existing patient: " . $stmt->error;
                        $stmt->close();
                        return false;
                    }
                    $stmt->close();

                } 
            } else {
                // No existing patient found, insert a new patient
                $stmt->close();
                $stmt = $this->db->prepare("INSERT INTO patients (member_id, last_name, first_name, cellphone_no, email, assigned_doctor) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $member_id, $first_name, $last_name, $contactNumber, $emailAddress, $user_admin_id);
            
                if (!$stmt->execute()) {
                    echo "Error inserting new patient: " . $stmt->error;
                    $stmt->close();
                    return false;
                }
            
                // Get the newly inserted patient ID
                $patient_id = $this->db->insert_id;
                $stmt->close();
            }
        
            // Insert appointment record using the new or existing patient ID
            $stmt = $this->db->prepare("INSERT INTO appointments (patient_id, appointment_date, appointment_time, services, status, notes) VALUES (?, ?, ?, ?, 'Pending', ?)");
            $stmt->bind_param("sssss", $patient_id, $appointmentDate, $appointmentTime, $services, $notes);
            
            if (!$stmt->execute()) {
                echo "Error inserting appointment: " . $stmt->error;
                return false;
            }
        
            // If appointment is successfully created, register notification
            $appointment_id = $this->db->insert_id;
            $message = "Appointment created for " . $first_name . " " . $last_name . " on " . $appointmentDate . " at " . $appointmentTime ." and is now pending for approval.";
            
            // Include notification_to in the notification insertion
            $stmt = $this->db->prepare("INSERT INTO notifications (user_id, notif_to, message, type, is_read, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $status = 0;  // Assuming the default status for a notification is 'unread'
            $type = 'set_appointment';
            $stmt->bind_param("iissi", $patient_id, $user_admin_id, $message, $type, $status);
            
            if (!$stmt->execute()) {
                echo "Error inserting notification: " . $stmt->error;
                $stmt->close();
                return false;
            }
        
            // Close the statement
            $stmt->close();
            return true;  // Return true after all operations are successful
        }
        
        
        public function get_all_appointments_by_member_id($member_id) {
            // Prepare the SQL statement with a LEFT JOIN to get doctor details
            $query = "
                SELECT 
                    a.*, 
                    p.first_name AS patient_first_name, 
                    p.last_name AS patient_last_name, 
                    p.member_id,
                    d.first_name AS doctor_first_name, 
                    d.last_name AS doctor_last_name,
                    ds.sub_category as service_name
                FROM 
                    appointments AS a
                JOIN 
                    patients AS p ON a.patient_id = p.patient_id
                LEFT JOIN 
                    doctors AS d ON p.assigned_doctor = d.account_id
                LEFT JOIN 
                    dental_services AS ds ON a.services = ds.id 
                WHERE 
                    p.member_id = ?
                ORDER BY 
                    a.appointment_date DESC, 
                    a.appointment_time DESC";
        
            // Initialize the statement
            $stmt = $this->db->prepare($query);
            if (!$stmt) {
                throw new Exception("Database statement could not be prepared: " . $this->db->error);
            }
        
            // Bind the member ID parameter
            $stmt->bind_param("s", $member_id); // Use "s" for string parameter type
        
            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Database query failed: " . $stmt->error);
            }
        
            // Get the result set
            $result = $stmt->get_result();
        
            // Fetch all appointments as an associative array
            $appointments = $result->fetch_all(MYSQLI_ASSOC);
        
            // Free the result set and close the statement
            $result->free();
            $stmt->close();
        
            // Return the appointments
            return $appointments;
        }
        

        public function view_appointment_by_id ($id) {
             // Prepare the SQL statement to fetch appointment details
            $query = "SELECT * FROM appointments WHERE id = ?";
            
            // Initialize the statement
            $stmt = $this->db->prepare($query);
            if (!$stmt) {
                throw new Exception("Database statement could not be prepared: " . $this->db->error);
            }

            // Bind the appointment ID parameter
            $stmt->bind_param("i", $id); // Assuming 'id' is an integer

            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Database query failed: " . $stmt->error);
            }

            // Get the result set
            $result = $stmt->get_result();

            // Fetch the appointment details as an associative array
            $appointment = $result->fetch_assoc();

            // Free the result set and close the statement
            $result->free();
            $stmt->close();

            // Return the appointment details
            return $appointment;

        }

        public function delete_appointment($appointmentId, $patient_id, $notif_to) {
            // Delete the appointment
            $query = "DELETE FROM appointments WHERE id = ?";
            
            if ($stmt = $this->db->prepare($query)) {
                $stmt->bind_param("i", $appointmentId);
        
                if ($stmt->execute()) {
                    // Appointment successfully deleted
                    $stmt->close();
        
                    // Add a deletion notification
                    $message = "Appointment with ID " . $appointmentId . " has been deleted.";
                    $status = 0; // Assuming the default status for a notification is 'unread'
                    $type = 'appointment_deletion';
        
                    $stmt = $this->db->prepare("INSERT INTO notifications (user_id, notif_to, message, type, is_read, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                    $stmt->bind_param("iissi", $notif_to, $notif_to, $message, $type, $status);
        
                    if (!$stmt->execute()) {
                        echo "Error inserting deletion notification: " . $stmt->error;
                        $stmt->close();
                        return false;
                    }
        
                    // Close the statement
                    $stmt->close();
                    return true;
                } else {
                    // Error during deletion
                    $stmt->close();
                    return false;
                }
            }
        
            return false; // Error preparing the query
        }

        public function update_appointment($appointmentId, $appointmentDate, $appointmentTime, $status, $notes, $firstname, $lastname, $member_id, $user_id) {
            $query = "UPDATE appointments SET 
                        appointment_date = ?, 
                        appointment_time = ?, 
                        status = ?, 
                        notes = ? 
                      WHERE id = ?";
            
            // Prepare and execute the statement
            if ($stmt = $this->db->prepare($query)) {
                // Bind the parameters
                $stmt->bind_param("ssssi", $appointmentDate, $appointmentTime, $status, $notes, $appointmentId);
                
                // Execute the statement and check if successful
                $result = $stmt->execute();
                $stmt->close(); // Close the statement
        
                if ($result) {
                    // If the appointment was successfully updated, insert a notification
        
                    // Retrieve patient_id associated with the appointment
                    $stmt = $this->db->prepare("SELECT patient_id FROM appointments WHERE id = ?");
                    $stmt->bind_param("i", $appointmentId);
                    $stmt->execute();
                    $stmt->bind_result($patient_id);
                    $stmt->fetch();
                    $stmt->close();
        
                    // Prepare the notification message
                    $message = "Appointment for " . $firstname . " " . $lastname . " on " . $appointmentDate . " at " . $appointmentTime . " has been updated. Status: " . $status . ". Reason: " . $notes;
        
                    // Insert notification
                    $type = "appointment_update";
                    $is_read = 0; // Assuming unread notifications have a value of 0
        
                    // Fix the INSERT statement to match the columns and values correctly
                    $stmt = $this->db->prepare("INSERT INTO notifications (user_id, notif_to, message, type, is_read, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        
                    // Bind and execute the notification insert
                    $stmt->bind_param("iissi", $patient_id, $user_id, $message, $type, $is_read);
        
                    if (!$stmt->execute()) {
                        echo "Error inserting notification: " . $stmt->error;
                    }
        
                    $stmt->close(); // Close the notification statement
                }
        
                return $result; // Return the result of the update (true on success, false on failure)
            } else {
                return false; // Return false if the statement could not be prepared
            }
        }
        

        public function get_appointments_summary() {
            $query = "SELECT status, COUNT(*) as count FROM appointments WHERE appointment_date >= CURDATE() GROUP BY status";
        
            $result = $this->db->query($query);
            $summary = [];
        
            while ($row = $result->fetch_assoc()) {
                $summary[$row['status']] = $row['count'];
            }
        
            return $summary;
        } 

       // Method to get count of confirmed appointments
        public function get_confirmed_appointments_count($member_id) {
            $query = "SELECT COUNT(*) as count FROM appointments
                     LEFT JOIN patients p ON p.patient_id = appointments.patient_id
                     WHERE status = 'Confirmed' AND p.member_id = '$member_id'";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc();
            return $row['count']; // Return the count of confirmed appointments
        }

        // Method to get count of canceled appointments
        public function get_canceled_appointments_count($member_id) {
            $query = "SELECT COUNT(*) as count FROM appointments 
                    LEFT JOIN patients p ON p.patient_id = appointments.patient_id
                    WHERE status = 'Canceled' AND p.member_id = '$member_id'";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc();
            return $row['count']; // Return the count of canceled appointments
        }

        // Your existing method for getting upcoming appointments
        public function get_upcoming_appointments($member_id) {
            $query = "SELECT appointment_date, appointment_time, ds.sub_category as services FROM appointments 
                  LEFT JOIN patients p ON p.patient_id = appointments.patient_id
                  LEFT JOIN dental_services ds ON ds.id = appointments.services
                  WHERE appointment_date = CURDATE() + INTERVAL 2 DAY 
                  AND appointments.status = 'Confirmed' AND member_id = '$member_id'";
            $result = $this->db->query($query);
            
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }

        public function get_todays_appointments($member_id) {
            // Define the query to select today's confirmed appointments
            $query = "SELECT appointment_date, appointment_time, notes, ds.sub_category as services
                      FROM appointments 
                      LEFT JOIN patients p ON p.patient_id = appointments.patient_id
                      LEFT JOIN dental_services ds ON ds.id = appointments.services
                      WHERE appointments.appointment_date = CURDATE() AND appointments.status = 'Confirmed' AND p.member_id = '$member_id'";
        
            // Execute the query
            $result = $this->db->query($query);
        
            // Check if the query was successful and return the results
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }

        public function automatic_cancel_appointment($member_id, $appointmentId) {
            // Get today's date and current time
            $today = date('Y-m-d H:i:s');
        
            // Prepare the cancellation SQL query
            $sql = "UPDATE appointments 
                    SET status = 'Canceled', canceled_at = NOW(), notes = 'Scheduled appointment is still pending and past the set appointment time.' 
                    WHERE id = ?";
        
            $stmt = $this->db->prepare($sql);
            if ($stmt === false) {
                return "Failed to prepare cancellation query.";
            }
        
            // Bind parameters: 'i' for integer (appointmentId)
            $stmt->bind_param('i', $appointmentId);
            $stmt->execute();
            
            // Free the statement
            $stmt->close();
        
            // Fetch the doctor's and patient's email addresses
            $patientEmail = $this->get_patient_email($member_id);
        
            // Prepare the email content for the patient
            $subjectPatient = "Your Appointment Has Been Canceled - " . $appointmentId;
            $messagePatient = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    /* Reuse the same styles as above */
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='email-header'>
                        Appointment Cancellation Notification
                    </div>
                    <div class='email-body'>
                        <p>Dear Patient,</p>
                        <p>Your appointment schedule has been automatically canceled due to it remaining pending and past the scheduled time.</p>
                        <p>Details:</p>
                        <ul>
                            <li><strong>Appointment ID:</strong> {$appointmentId}</li>
                            <li><strong>Status:</strong> Canceled</li>
                            <li><strong>Notes:</strong> Scheduled appointment is still pending and past the set appointment time.</li>
                        </ul>
                        <p>Thank you,<br>Your Clinic Team</p>
                    </div>
                    <div class='email-footer'>
                        &copy; " . date("Y") . " Roselle Santander Dental Clinic. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ";
        
            // Headers for HTML email
            $headers = "From: rosellesantander@rs-dentalclinic.com" . "\r\n" . 
            "Reply-To: rosellesantander@rs-dentalclinic.com" . "\r\n" .
            "X-Mailer: PHP/" . phpversion() . "\r\n" .
            "Content-Type: text/html; charset=UTF-8";  // Ensure the email is sent as HTML
                    
        
            // Send the email notification to the patient
            if (mail($patientEmail, $subjectPatient, $messagePatient, $headers)) {
                $_SESSION['patient_email_message'] = "Email notification sent to the patient.";
                $_SESSION['patient_email_type'] = "success";
            } else {
                $_SESSION['patient_email_message'] = "Failed to send email notification to the patient.";
                $_SESSION['patient_email_type'] = "danger";
            }
        }
        

        public function update_profile($firstname, $lastname, $contactnumber, $email, $gender, $address, $profilePicPath, $remarks, $member_id) {
            $sql = "UPDATE accounts 
                    SET firstname = ?, lastname = ?, contactnumber = ?, email = ?, gender = ?, address = ?, profile_picture = IFNULL(?, profile_picture), remarks = ?
                    WHERE member_id = ?";
            
            $stmt = $this->db->prepare($sql);
            if ($stmt === false) {
                return false;  // Handle error
            }
            
            // Debugging output
            error_log("Updating user with member_id: " . $member_id);

            // Bind parameters: 8 strings and 1 integer
            $stmt->bind_param('sssssssss', $firstname, $lastname, $contactnumber, $email, $gender, $address, $profilePicPath, $remarks, $member_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                return false;
            }
        }

        public function get_user_profile($member_id) {
            $sql = "SELECT * FROM accounts WHERE member_id = ?";
            
            // Prepare the statement
            $stmt = $this->db->prepare($sql);
            
            if ($stmt === false) {
                // Handle error in preparing statement
                return false;
            }
            
            // Bind the parameter (member_id)
            $stmt->bind_param('s', $member_id);
            
            // Execute the statement
            $stmt->execute();
            
            // Get the result
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Fetch the user data as an associative array
                return $result->fetch_assoc();
            } else {
                // No user found
                return false;
            }
        }


        public function update_medical_record($data) {
            // Step 1: Check if the patient record exists
            $checkQuery = "SELECT patient_id FROM patients WHERE patient_id = ?";
            if ($stmt = $this->db->prepare($checkQuery)) {
                $stmt->bind_param('i', $data['patient_id']);
                $stmt->execute();
                $stmt->store_result();
                
                // Data extraction for later use
                $guardianData = [
                    'guardian_name' => $data['guardian_name'],
                    'guardian_occupation' => $data['guardian_occupation'],
                    'patient_id' => $data['patient_id']
                ];
                
                // Consultation data
                $consultationData = [
                    'referral_source' => $data['referral_source'],
                    'reason_for_consultation' => $data['reason_for_consultation'],
                    'previous_dentist' => $data['previous_dentist'],
                    'last_dental_visit' => $data['last_dental_visit'],
                    'patient_id' => $data['patient_id']
                ];
                
                // Medical history data
                $medicalHistoryData = [
                    'physician_name' => $data['physician_name'],
                    'physician_specialty' => $data['physician_specialty'],
                    'physician_address' => $data['physician_address'],
                    'physician_phone_no' => $data['physician_phone_no'],
                    'good_health' => $data['good_health'],
                    'under_medical_treatment' => $data['under_medical_treatment'],
                    'medical_condition_treated' => $data['medical_condition_treated'],
                    'serious_illness' => $data['serious_illness'],
                    'illness_details' => $data['illness_details'],
                    'hospitalization' => $data['hospitalization'],
                    'hospitalization_reason' => $data['hospitalization_reason'],
                    'taking_medication' => $data['taking_medication'],
                    'medication_details' => $data['medication_details'],
                    'use_tobacco' => $data['use_tobacco'],
                    'use_drugs' => $data['use_drugs'],
                    'allergic_medicine' => $data['allergies'],
                    'pregnant' => $data['pregnant'],
                    'nursing' => $data['nursing'],
                    'taking_birth_control' => $data['birth_control'],
                    'blood_type' => $data['blood_type'],
                    'blood_pressure' => $data['blood_pressure'],
                    'illness_conditions' => $data['medical_conditions'],
                    'patient_id' => $data['patient_id']
                ];

                 // Ensure all values are set, use default values if not
                 $physician_name = $medicalHistoryData['physician_name'] ?? null;
                 $physician_specialty = $medicalHistoryData['physician_specialty'] ?? null;
                 $physician_address = $medicalHistoryData['physician_address'] ?? null;
                 $physician_phone_no = $medicalHistoryData['physician_phone_no'] ?? null;
                 $good_health = $medicalHistoryData['good_health'] ?? 0; // Default to 0 if not set
                 $under_medical_treatment = $medicalHistoryData['under_medical_treatment'] ?? 0; // Default to 0 if not set
                 $medical_condition_treated = $medicalHistoryData['medical_condition_treated'] ?? ''; // Default to empty string
                 $serious_illness = $medicalHistoryData['serious_illness'] ?? 0; // Default to 0 if not set
                 $illness_details = $medicalHistoryData['illness_details'] ?? ''; // Default to empty string
                 $hospitalization = $medicalHistoryData['hospitalization'] ?? 0; // Default to 0 if not set
                 $hospitalization_reason = $medicalHistoryData['hospitalization_reason'] ?? ''; // Default to empty string
                 $taking_medication = $medicalHistoryData['taking_medication'] ?? 0; // Default to 0 if not set
                 $medication_details = $medicalHistoryData['medication_details'] ?? ''; // Default to empty string
                 $use_tobacco = $medicalHistoryData['use_tobacco'] ?? 0; // Default to 0 if not set
                 $use_drugs = $medicalHistoryData['use_drugs'] ?? 0; // Default to 0 if not set
                 $allergic_medicine = $medicalHistoryData['allergic_medicine'] ?? ''; // Default to empty string
                 $pregnant = $medicalHistoryData['pregnant'] ?? 0; // Default to 0 if not set
                 $nursing = $medicalHistoryData['nursing'] ?? 0; // Default to 0 if not set
                 $taking_birth_control = $medicalHistoryData['taking_birth_control'] ?? 0; // Default to 0 if not set
                 $blood_type = $medicalHistoryData['blood_type'] ?? ''; // Default to empty string
                 $blood_pressure = $medicalHistoryData['blood_pressure'] ?? ''; // Default to empty string
                 $illness_conditions = $medicalHistoryData['illness_conditions'] ?? ''; // Default to empty string
        
                if ($stmt->num_rows > 0) {
                    // Step 2: Record exists, perform updates
                    $stmt->close();  // Close the previous statement
                    
                    // Update patient information
                    $updatePatientQuery = "UPDATE patients SET 
                        member_id = ?, 
                        last_name = ?, 
                        first_name = ?, 
                        middle_name = ?, 
                        birthdate = ?, 
                        age = ?,  
                        sex = ?, 
                        nickname = ?, 
                        religion = ?, 
                        nationality = ?, 
                        cellphone_no = ?, 
                        email = ?, 
                        home_address = ?, 
                        occupation = ? 
                    WHERE patient_id = ?"; 
        
                    $stmt = $this->db->prepare($updatePatientQuery);
                    $stmt->bind_param(
                        'ssssssssssssssi',
                        $data['member_id'],
                        $data['last_name'],
                        $data['first_name'],
                        $data['middle_name'],
                        $data['birthdate'],
                        $data['age'],  
                        $data['sex'],  
                        $data['nickname'],
                        $data['religion'], 
                        $data['nationality'],
                        $data['cellphone_no'],
                        $data['email'],
                        $data['home_address'],  
                        $data['occupation'],
                        $data['patient_id']  // Assuming this is the patient ID
                    );
                    $stmt->execute();
                    
                    // Update guardian information
                    $updateGuardianQuery = "UPDATE guardians SET 
                        guardian_name = ?, 
                        guardian_occupation = ? 
                    WHERE patient_id = ?"; 
                    
                    $stmt = $this->db->prepare($updateGuardianQuery);
                    $stmt->bind_param(
                        'ssi',
                        $guardianData['guardian_name'],
                        $guardianData['guardian_occupation'],
                        $guardianData['patient_id']
                    );
                    $stmt->execute();
        
                    // Update consultation details
                    $updateConsultationQuery = "UPDATE consultations SET 
                        referral_source = ?, 
                        reason_for_consultation = ?, 
                        previous_dentist = ?, 
                        last_dental_visit = ?
                    WHERE patient_id = ?"; 
                    
                    $stmt = $this->db->prepare($updateConsultationQuery);
                    $stmt->bind_param(
                        'ssssi',
                        $consultationData['referral_source'],
                        $consultationData['reason_for_consultation'],
                        $consultationData['previous_dentist'],
                        $consultationData['last_dental_visit'],
                        $consultationData['patient_id'],
                    );
                    $stmt->execute();
        
                    // Update medical history
                    $updateMedicalHistoryQuery = "UPDATE medical_history SET 
                        physician_name = ?, 
                        physician_specialty = ?, 
                        physician_address = ?, 
                        physician_phone_no = ?, 
                        good_health = ?, 
                        under_medical_treatment = ?, 
                        medical_condition_treated = ?, 
                        serious_illness = ?, 
                        illness_details = ?, 
                        hospitalization = ?, 
                        hospitalization_reason = ?, 
                        taking_medication = ?, 
                        medication_details = ?, 
                        use_tobacco = ?, 
                        use_drugs = ?, 
                        allergic_medicine = ?, 
                        pregnant = ?, 
                        nursing = ?, 
                        taking_birth_control = ?, 
                        blood_type = ?, 
                        blood_pressure = ?, 
                        illness_conditions = ? 
                    WHERE patient_id = ?"; 
                    
                    $stmt = $this->db->prepare($updateMedicalHistoryQuery);
                    // Bind parameters
                    $stmt->bind_param(
                        'ssssiisisisisiisiiisssi',
                        $medicalHistoryData['physician_name'],
                        $medicalHistoryData['physician_specialty'],
                        $medicalHistoryData['physician_address'],
                        $medicalHistoryData['physician_phone_no'],
                        $medicalHistoryData['good_health'],
                        $medicalHistoryData['under_medical_treatment'],
                        $medicalHistoryData['medical_condition_treated'],
                        $medicalHistoryData['serious_illness'],
                        $medicalHistoryData['illness_details'],
                        $medicalHistoryData['hospitalization'],
                        $medicalHistoryData['hospitalization_reason'],
                        $medicalHistoryData['taking_medication'],
                        $medicalHistoryData['medication_details'],
                        $medicalHistoryData['use_tobacco'],
                        $medicalHistoryData['use_drugs'],
                        $medicalHistoryData['allergic_medicine'],
                        $medicalHistoryData['pregnant'],
                        $medicalHistoryData['nursing'],
                        $medicalHistoryData['taking_birth_control'],
                        $medicalHistoryData['blood_type'],
                        $medicalHistoryData['blood_pressure'],
                        $medicalHistoryData['illness_conditions'],
                        $medicalHistoryData['patient_id'] 
                    );
                    $stmt->execute();
        
                    return true;
                } else {
                    // Step 3: No record exists, perform inserts
                    $stmt->close();  // Close the previous statement
                    
                    // Insert into patients
                    $insertPatientQuery = "INSERT INTO patients (member_id, last_name, first_name, middle_name, birthdate, age, sex, nickname, religion, nationality, cellphone_no, email, home_address, occupation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($insertPatientQuery);
                    $stmt->bind_param(
                        'ssssssssssssss',
                        $data['member_id'],
                        $data['last_name'],
                        $data['first_name'],
                        $data['middle_name'],
                        $data['birthdate'],
                        $data['age'],  
                        $data['sex'],  
                        $data['nickname'],
                        $data['religion'], 
                        $data['nationality'],
                        $data['cellphone_no'],
                        $data['email'],
                        $data['home_address'],  
                        $data['occupation']
                    );
                    $stmt->execute();
                    $patientId = $this->db->insert_id; // Get the last inserted ID
        
                    // Insert into guardians
                    $insertGuardianQuery = "INSERT INTO guardians (patient_id, guardian_name, guardian_occupation) VALUES (?, ?, ?)";
                    $stmt = $this->db->prepare($insertGuardianQuery);
                    $stmt->bind_param(
                        'iss',
                        $patientId, // Foreign key reference
                        $guardianData['guardian_name'],
                        $guardianData['guardian_occupation']
                    );
                    $stmt->execute();
        
                    // Insert into consultations
                    $insertConsultationQuery = "INSERT INTO consultations (patient_id, referral_source, reason_for_consultation, previous_dentist, last_dental_visit) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($insertConsultationQuery);
                    $stmt->bind_param(
                        'issss',
                        $patientId, // Foreign key reference
                        $consultationData['referral_source'],
                        $consultationData['reason_for_consultation'],
                        $consultationData['previous_dentist'],
                        $consultationData['last_dental_visit'],
                    );
                    $stmt->execute();
        
                    // Insert into medical history
                    $insertMedicalHistoryQuery = "INSERT INTO medical_history (
                        patient_id, 
                        physician_name, 
                        physician_specialty, 
                        physician_address, 
                        physician_phone_no, 
                        good_health, 
                        under_medical_treatment, 
                        medical_condition_treated, 
                        serious_illness, 
                        illness_details, 
                        hospitalization, 
                        hospitalization_reason, 
                        taking_medication, 
                        medication_details, 
                        use_tobacco, 
                        use_drugs, 
                        allergic_medicine, 
                        pregnant, 
                        nursing, 
                        taking_birth_control, 
                        blood_type, 
                        blood_pressure, 
                        illness_conditions
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($insertMedicalHistoryQuery);

                    // Bind parameters
                    $stmt->bind_param(
                        'issssiisisisisiisiiisss',
                        $patientId, // Foreign key reference
                        $physician_name,
                        $physician_specialty,
                        $physician_address,
                        $physician_phone_no,
                        $good_health,
                        $under_medical_treatment,
                        $medical_condition_treated,
                        $serious_illness,
                        $illness_details,
                        $hospitalization,
                        $hospitalization_reason,
                        $taking_medication,
                        $medication_details,
                        $use_tobacco,
                        $use_drugs,
                        $allergic_medicine,
                        $pregnant,
                        $nursing,
                        $taking_birth_control,
                        $blood_type,
                        $blood_pressure,
                        $illness_conditions
                    );
                    
                    // Execute the statement
                    if ($stmt->execute()) {
                        return true;
                    } else { 
                        // Handle error
                        echo "Error: " . $stmt->error;
                    }
                }
            }
            return false;
        }
        
        public function get_all_patient_record($patient_id) {
            // Prepare a SQL statement to fetch the patient records
            $query = "
                SELECT 
                    p.patient_id,
                    p.member_id,
                    p.first_name,
                    p.last_name,
                    p.middle_name,
                    p.birthdate,
                    p.age,
                    p.sex,
                    p.nickname,
                    p.religion,
                    p.nationality,
                    p.cellphone_no,
                    p.email,
                    p.home_address,
                    p.occupation,
                    g.guardian_name,
                    g.guardian_occupation,
                    mh.physician_name,
                    mh.physician_specialty,
                    mh.physician_address,
                    mh.physician_phone_no,
                    mh.good_health,
                    mh.under_medical_treatment,
                    mh.medical_condition_treated,
                    mh.serious_illness,
                    mh.illness_details,
                    mh.hospitalization,
                    mh.hospitalization_reason,
                    mh.taking_medication,
                    mh.medication_details,
                    mh.use_tobacco,
                    mh.use_drugs,
                    mh.pregnant,
                    mh.nursing,
                    mh.taking_birth_control,
                    mh.blood_type,
                    mh.blood_pressure,
                    c.referral_source,
                    c.reason_for_consultation,
                    c.previous_dentist,
                    c.last_dental_visit
                FROM patients p
                LEFT JOIN guardians g ON p.patient_id = g.patient_id
                LEFT JOIN medical_history mh ON p.patient_id = mh.patient_id
                LEFT JOIN consultations c ON p.patient_id = c.patient_id
                WHERE p.patient_id = ?
            ";
        
            // Prepare and execute the statement
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            
            // Fetch the results
            $result = $stmt->get_result();
            $patientRecords = [];
        
            // Check if any records were found
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $patientRecords[] = $row;
                }
            }
        
            return $patientRecords; // Return all patient records as an array
        }

        public function get_patientid_by_member_id ($id) {
            // Prepare the SQL statement to fetch appointment details
           $query = "SELECT patient_id FROM patients WHERE member_id = ?";
           
           // Initialize the statement
           $stmt = $this->db->prepare($query);
           if (!$stmt) {
               throw new Exception("Database statement could not be prepared: " . $this->db->error);
           }

           // Bind the appointment ID parameter
           $stmt->bind_param("s", $id); // Assuming 'id' is an integer

           // Execute the statement
           if (!$stmt->execute()) {
               throw new Exception("Database query failed: " . $stmt->error);
           }

           // Get the result set
           $result = $stmt->get_result();

           // Fetch the appointment details as an associative array
           $patient_id = $result->fetch_assoc();

           // Free the result set and close the statement
           $result->free();
           $stmt->close();
           return $patient_id;

       }

        public function get_patient_allergies($patient_id) {
            $query = "SELECT allergic_medicine FROM medical_history WHERE patient_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Decode JSON data into a PHP array
                return json_decode($row['allergic_medicine'], true); 
            }
        
            return []; // Return an empty array if no conditions found
        }

        public function get_patient_medical_conditions($patient_id) {
            $query = "SELECT illness_conditions FROM medical_history WHERE patient_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Decode JSON data into a PHP array
                return json_decode($row['illness_conditions'], true); 
            }
        
            return []; // Return an empty array if no conditions found
        }

        public function get_current_password($member_id) {
            $query = "SELECT password FROM accounts WHERE member_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            $result = $stmt->get_result();


            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc(); // Fetch the password from the result row
                return $row['password']; // Return the password from the database
            }

            return false; // Return false if no password is found (e.g., member_id doesn't exist)
        }

        public function update_password($member_id, $new_password) {
        
            // SQL query to update the password for the given member_id
            $update_query = "UPDATE accounts SET password = ? WHERE member_id = ?";
            $update_stmt = $this->db->prepare($update_query); // Use $this->db for the database connection
            $update_stmt->bind_param("si", $new_password, $member_id); // Bind the hashed password and member_id
            $result = $update_stmt->execute(); // Execute the query

            return $result; // Return true if the update was successful, false otherwise
        }
        
        public function get_recent_notifications_by_member($member_id, $limit = 3) {
            $stmt = $this->db->prepare("
                SELECT notifications.*, patients.member_id
                FROM notifications
                JOIN patients ON notifications.notif_to = patients.patient_id
                WHERE patients.member_id = ?
                ORDER BY notifications.created_at DESC
                LIMIT ?
            ");
            
            // Bind parameters: `member_id` and `limit`
            $stmt->bind_param("si", $member_id, $limit);
            $stmt->execute();
            
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
       
        public function update_notification_read($notificationId) {
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
            $stmt->bind_param("i", $notificationId);
            return $stmt->execute();
        }

        // Get notification by ID to display in the modal
        public function get_notification_by_id($notificationId) {
            $stmt = $this->db->prepare("SELECT * FROM notifications WHERE id = ?");
            $stmt->bind_param("i", $notificationId);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        public function get_all_notifications($member_id) {
            $query = "
                SELECT p.*, a.member_id, a.first_name, a.last_name, a.patient_id
                FROM notifications p
                LEFT JOIN patients a ON p.notif_to = a.patient_id
                WHERE a.member_id = ?"; 
            
            // Prepare the SQL statement
            if ($stmt = $this->db->prepare($query)) {
                // Bind the member_id parameter
                $stmt->bind_param("s", $member_id);
                
                // Execute the query
                $stmt->execute();
                
                // Fetch the result
                $result = $stmt->get_result();
                
                // Initialize an array to store the notifications
                $notifications_with_details = [];
                
                // Loop through the result and add each notification to the array
                while ($row = $result->fetch_assoc()) {
                    $notifications_with_details[] = $row;
                }
                
                // Close the statement
                $stmt->close();
                
                // Return the notifications array
                return $notifications_with_details;
            } else {
                // Return an empty array if query preparation fails
                return [];
            }
        }

        public function get_doctor_details($id) {
            // Prepare the query to fetch doctor's details (email, id, and account_id) by the provided doctor ID
            $stmt = $this->db->prepare("SELECT doctors.doctor_id, doctors.email, doctors.account_id, doctors.first_name, doctors.last_name, accounts.member_id
                                            FROM doctors 
                                            LEFT JOIN accounts ON doctors.account_id = accounts.id
                                    WHERE doctors.account_id = ?");
            
            // Bind the input parameter (account_id)
            $stmt->bind_param("i", $id);
            
            // Execute the statement
            $stmt->execute();
            
            // Store the result
            $stmt->store_result();
            
            // Check if the doctor exists
            if ($stmt->num_rows > 0) {
                // Bind the result to the variables (id, email, account_id)
                $stmt->bind_result($doctor_id, $email, $account_id, $first_name, $last_name, $member_id);
                
                // Fetch the data
                $stmt->fetch();
                
                // Close the statement
                $stmt->close();
                
                // Return the doctor details as an associative array
                return array(
                    'doctor_id' => $doctor_id,
                    'email' => $email,
                    'account_id' => $account_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'member_id' => $member_id
                );
            } else {
                // Close the statement
                $stmt->close();
                
                // Return false if no doctor is found with the given ID
                return false;
            }
        }   
        
        public function get_doctor_details_member_id($id) {
            // Prepare the query to fetch doctor's details (email, id, and account_id) by the provided doctor ID
            $sql = "SELECT doctors.doctor_id, doctors.email, doctors.account_id, doctors.first_name, doctors.last_name, accounts.member_id
                    FROM doctors
                    LEFT JOIN accounts ON doctors.account_id = accounts.id
                    WHERE accounts.member_id = ?";
            
            // Prepare the statement
            if ($stmt = $this->db->prepare($sql)) {
                // Bind the parameter
                $stmt->bind_param("s", $id);
                
                // Execute the statement
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                // Fetch the data
                $doctor_details = $result->fetch_assoc();

                // Close the statement
                $stmt->close();

                // Check if the query returned any results
                if ($doctor_details) {
                    return $doctor_details;
                } else {
                    // Handle the case when no data is returned
                    return false; // You can return a specific message if needed
                }
            } else {
                // Handle error if the query fails
                return false;
            }
        }
        
        public function get_doctor_admin() {
            // Prepare the query to fetch the account id where user_type is 'super_admin'
            $stmt = $this->db->prepare("SELECT id FROM accounts WHERE user_type = ?");
            
            // Bind the parameter 'super_admin' for user_type
            $user_type = 'super_admin';
            $stmt->bind_param("s", $user_type);
            
            // Execute the statement
            $stmt->execute();
            
            // Store the result
            $stmt->store_result();
            
            // Check if a record is found
            if ($stmt->num_rows > 0) {
                // Bind the result to the user_id variable
                $stmt->bind_result($user_id);
                
                // Fetch the data
                $stmt->fetch();
                
                // Close the statement
                $stmt->close();
                
                // Return the user_id (not doctor_id)
                return $user_id;
            } else {
                // Close the statement
                $stmt->close();
                
                // Return false if no super_admin is found
                return false;
            }
        }

        public function get_patient_email($member_id) {
            // Prepare the SQL query to fetch the patient's email using a positional placeholder
            $query = "SELECT email FROM patients WHERE member_id = ?";
        
            // Prepare the statement
            $stmt = $this->db->prepare($query);
        
            // Bind the member_id parameter to the query
            $stmt->bind_param('s', $member_id); // 's' means string (use 'i' if it's an integer)
        
            // Execute the query
            $stmt->execute();
        
            // Bind result variables
            $stmt->bind_result($email);
        
            // Fetch the result
            if ($stmt->fetch()) {
                // Return the email if found
                return $email;
            } else {
                // Return false if no result is found
                return false;
            }
        }

        public function patient_record_existence($member_id) {
            // Query to check if patient record exists for the given member ID
            $query = "SELECT COUNT(*) AS count FROM patients WHERE member_id = ?";
        
            // Prepare the statement
            $stmt = $this->db->prepare($query);
        
            // Bind the member_id parameter (assuming it's a string; adjust type as needed)
            $stmt->bind_param("s", $member_id);
        
            // Execute the query
            $stmt->execute();
        
            // Get the result
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        
            // Return true if count is greater than 0, else false
            return $row['count'];
        }

        public function getBookedAppointments($appointmentDate, $appointmentTime, $doctorId) {
            // SQL query to check if the appointment time is already booked
            $query = "
                SELECT a.appointment_date, a.appointment_time 
                FROM appointments a
                LEFT JOIN patients p ON a.patient_id = p.patient_id 
                WHERE p.assigned_doctor = ? AND a.appointment_date = ? AND a.appointment_time = ?
            ";
        
            // Prepare the SQL statement
            $stmt = $this->db->prepare($query);
        
            // Check if the statement was prepared successfully
            if ($stmt === false) {
                die('MySQL prepare failed: ' . $this->db->error);
            }
        
            // Bind parameters to the prepared statement
            // $doctorId is an integer, $appointmentDate and $appointmentTime are strings
            $stmt->bind_param("iss", $doctorId, $appointmentDate, $appointmentTime);
        
            // Execute the query
            $stmt->execute();
        
            // Get the result
            $result = $stmt->get_result();
        
            // Return the result
            return $result;
        }

        public function getPaymentStatus($member_id) {
            $sql = "
               SELECT 
                    a.id AS appointment_id,
                    pt.member_id AS patient_member_id,
                    COALESCE(pp.status, 'Pending') AS status,
                    pp.file_name,
                    COALESCE(pp.remarks, 'No payment uploaded') AS remarks,
                    a.services,
                    ds.sub_category
                FROM 
                    appointments a
                LEFT JOIN 
                    proof_of_payment pp ON a.id = pp.appointment_id
                LEFT JOIN 
                    patients pt ON a.patient_id = pt.patient_id
                LEFT JOIN 
                    dental_services ds ON ds.id = a.services
                WHERE 
                    pt.member_id = ?
            ";
        
            $stmt = $this->db->prepare($sql);
        
            if ($stmt) {
                $stmt->bind_param('s', $member_id);
                $stmt->execute();
                $result = $stmt->get_result();
        
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
        
                $stmt->close();
                return $data;
            } else {
                error_log("Query failed: " . $this->db->error);
                return [];
            }
        }
        
        
        public function savePaymentProof($appointmentId, $member_id, $fileName, $remarks, $status) {
            // Use NOW() for the current date/time when the proof is uploaded
            $sql = "INSERT INTO proof_of_payment (appointment_id, member_id, file_name, uploaded_at, status, remarks) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            
            if ($stmt) {
                // Bind parameters: 
                // - i for integer (appointmentId)
                // - s for string (member_id, fileName, status)
                // - s for string (remarks)
                $currentDateTime = date('Y-m-d H:i:s');
                $stmt->bind_param('isssss', $appointmentId, $member_id, $fileName, $currentDateTime, $status, $remarks);
                
                $result = $stmt->execute();  // Execute the statement
                $stmt->close();  // Close the statement
                return $result;  // Return true/false based on execution success
            } else {
                // Log any error during query preparation
                error_log("Database query preparation failed: " . $this->db->error);
                return false;
            }
        }
        
        
        public function getAppointmentId($member_id) {
                // SQL Query to fetch account ID using LEFT JOIN
                $sql = "
                    SELECT 
                        pp.id
                    FROM 
                        patients pt
                    LEFT JOIN 
                        appointments pp ON pt.patient_id = pp.patient_id
                    WHERE 
                        pt.member_id = ?
                ";

            $stmt = $this->db->prepare($sql);

            if ($stmt) {
                // Bind the parameter
                $stmt->bind_param('s', $member_id); // Assuming account_id is an integer

                // Execute the query
                $stmt->execute();

                // Fetch the result
                $result = $stmt->get_result();

                // Initialize an array to store data
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                // Close the statement
                $stmt->close();

                return $data; // Return fetched data
            } else {
                // Log the error if the query preparation fails
                error_log("Database query preparation failed: " . $this->db->error);
                return null;
            }
        }

        public function get_dental_services() {
            $sql = "SELECT category, sub_category, id, price
                    FROM dental_services 
                    ORDER BY category, sub_category";
            $result = $this->db->query($sql);
    
            $services = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $services[$row['category']][] = [
                        'id' => $row['id'],
                        'sub_category' => $row['sub_category'],
                        'price' => $row['price']
                    ];
                }
            }
            return $services; // Returns an associative array grouped by category
        }

        public function get_dental_service_by_id($id) {
            // Prepare the query to fetch the service by its ID
            $sql = "SELECT category, sub_category, id, price 
                    FROM dental_services 
                    WHERE id = ?"; // Using a parameterized query for security
            
            // Prepare and bind parameters
            if ($stmt = $this->db->prepare($sql)) {
                $stmt->bind_param("i", $id); // Bind the ID as an integer parameter
                $stmt->execute();
                $result = $stmt->get_result();
        
                // Check if the result exists
                if ($result->num_rows > 0) {
                    // Fetch the service data
                    $row = $result->fetch_assoc();
                    return [
                        'id' => $row['id'],
                        'category' => $row['category'],
                        'sub_category' => $row['sub_category'],
                        'price' => $row['price']
                    ];
                } else {
                    return null; // No service found for the given ID
                }
            }
            return null; // Error executing query
        }
        

        public function getSelectedServicesAndPaymentDetails($appointment_id) {
            $details = [];
            
            // SQL query to fetch services, prices, and down payment for the given appointment ID
            $query = "
                SELECT 
                    ds.sub_category, 
                    ds.price,
                    ds.down_payment
                FROM 
                    appointments AS a
                LEFT JOIN 
                    dental_services AS ds 
                ON 
                    a.services = ds.id
                WHERE 
                    a.id = ?
            ";
            
            // Prepare and execute the query
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $appointment_id);  // Bind appointment_id as integer
            $stmt->execute();
            $servicesResult = $stmt->get_result();
            
            $services = [];
            $totalPrice = 0;
            $totalDownPayment = 0;
            
            // Loop through the results to calculate total price, down payment and gather services
            while ($service = $servicesResult->fetch_assoc()) {
                $services[] = $service; // Add service to the array
                $totalPrice += $service['price']; // Accumulate the total price
                $totalDownPayment += $service['down_payment']; // Accumulate the total down payment
            }
            
            // Calculate remaining balance
            $remainingBalance = $totalPrice - $totalDownPayment;
            
            // Populate the details array with services, pricing, and payment breakdown
            $details['services'] = $services; // Use 'services' as the key for clarity
            $details['total_price'] = number_format($totalPrice, 2);  // Format as currency
            $details['down_payment'] = number_format($totalDownPayment, 2); // Format as currency
            $details['remaining_balance'] = number_format($remainingBalance, 2); // Format as currency
            
            return $details;
        }
        
        
        
        
        
        
        
        
               
    }
?>