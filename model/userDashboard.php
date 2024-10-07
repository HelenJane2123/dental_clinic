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

        public function register_appointment($member_id, $first_name, $last_name, $contactNumber, $emailAddress, $appointmentType, $appointmentDate, $appointmentTime, $services, $notes) {
            // Check for existing patient by member ID
            $stmt = $this->db->prepare("SELECT patient_id, status FROM patients WHERE member_id = ? and patient_id = ?");
            $stmt->bind_param("ss", $member_id, $patient_id);
            $stmt->execute();
            $stmt->store_result();
        
            if ($stmt->num_rows > 0) {
                // Existing patient found
                $stmt->bind_result($patient_id, $existing_status);
                $stmt->fetch();
        
                if ($appointmentType === 'newPatient') {
                    // New patient but existing member ID: insert as an alternate patient with status 'new'
                    $stmt->close(); // Close the previous statement
        
                    // Insert new patient details under the same member ID (using alt_patient_id)
                    $stmt = $this->db->prepare("INSERT INTO patients (member_id, first_name, last_name, contact_number, email, status) VALUES (?, ?, ?, ?, ?, 'new')");
                    $stmt->bind_param("sssss", $member_id, $first_name, $last_name, $contactNumber, $emailAddress);
        
                    if (!$stmt->execute()) {
                        echo "Error inserting new alternate patient: " . $stmt->error;
                        $stmt->close();
                        return false;
                    }
        
                    // Get the new alternate patient_id for the appointment
                    $alt_patient_id = $this->db->insert_id;
                } else {
                    // Existing patient, use the existing patient_id
                    $alt_patient_id = $patient_id;
        
                    // Update the status to 'old' if the appointment type is 'old'
                    if ($appointmentType === 'old') {
                        $updateStmt = $this->db->prepare("UPDATE patients SET status = 'old' WHERE patient_id = ?");
                        $updateStmt->bind_param("s", $alt_patient_id);
                        if (!$updateStmt->execute()) {
                            echo "Error updating patient status: " . $updateStmt->error;
                        }
                        $updateStmt->close();
                    }
                }
            } else {
                // No existing patient found, insert new patient
                $stmt->close();
                $status = 'new';
                $stmt = $this->db->prepare("INSERT INTO patients (member_id, first_name, last_name, contact_number, email, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $member_id, $first_name, $last_name, $contactNumber, $emailAddress, $status);
        
                if (!$stmt->execute()) {
                    echo "Error inserting new patient: " . $stmt->error;
                    $stmt->close();
                    return false;
                }
        
                // Get the newly inserted patient ID
                $alt_patient_id = $this->db->insert_id;
            }
        
            // Close the previous statement before inserting the appointment
            $stmt->close();
        
            // Insert appointment record using the new or existing patient ID
            $stmt = $this->db->prepare("INSERT INTO appointments (patient_id, member_id, appointment_date, appointment_time, services, notes, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
            $stmt->bind_param("ssssss", $alt_patient_id, $member_id, $appointmentDate, $appointmentTime, $services, $notes);
        
            if ($stmt->execute()) {
                return true; // Return true if the appointment is successfully inserted
            } else {
                echo "Error inserting appointment: " . $stmt->error;
                return false; // Return false if there's an error
            }
        
            // Close the statement
            $stmt->close();
        }
        
        public function get_all_appointments_by_member_id($member_id) {
            // Prepare the SQL statement
            $query = "SELECT a.*, p.first_name, p.last_name 
                            FROM appointments AS a
                            JOIN patients AS p ON a.patient_id = p.patient_id
                            WHERE a.member_id = ?";

            // Initialize the statement
            $stmt = $this->db->prepare($query); // Use $this->db instead of $this->conn
            if (!$stmt) {
                throw new Exception("Database statement could not be prepared: " . $this->db->error);
            }

            // Bind the member ID parameter
            $stmt->bind_param("s", $member_id); // Change to "s" if member_id is a string

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

        public function delete_appointment($appointmentId) {
            $query = "DELETE FROM appointments WHERE id = ?";
            
            if ($stmt = $this->db->prepare($query)) {
                $stmt->bind_param("i", $appointmentId);
        
                if ($stmt->execute()) {
                    return true; // Appointment successfully deleted
                } else {
                    return false; // Error during deletion
                }
        
                $stmt->close();
            }
        
            return false; // Error preparing the query
        }

        public function update_appointment($appointmentId, $appointmentDate, $appointmentTime, $status, $notes) {
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
        
                return $result; // Return the result of the execute (true on success, false on failure)
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
            $query = "SELECT COUNT(*) as count FROM appointments WHERE status = 'Confirmed' AND member_id = '$member_id'";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc();
            return $row['count']; // Return the count of confirmed appointments
        }

        // Method to get count of canceled appointments
        public function get_canceled_appointments_count($member_id) {
            $query = "SELECT COUNT(*) as count FROM appointments WHERE status = 'Canceled' AND member_id = '$member_id'";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc();
            return $row['count']; // Return the count of canceled appointments
        }

        // Your existing method for getting upcoming appointments
        public function get_upcoming_appointments($member_id) {
            $query = "SELECT appointment_date, appointment_time, services FROM appointments 
                  WHERE appointment_date = CURDATE() + INTERVAL 2 DAY 
                  AND status = 'Confirmed' AND member_id = '$member_id'";
            $result = $this->db->query($query);
            
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }

        public function get_todays_appointments($member_id) {
            // Define the query to select today's confirmed appointments
            $query = "SELECT appointment_date, appointment_time, notes, services
                      FROM appointments 
                      WHERE appointment_date = CURDATE() AND status = 'Confirmed' AND member_id = '$member_id'";
        
            // Execute the query
            $result = $this->db->query($query);
        
            // Check if the query was successful and return the results
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }

        public function automatic_cancel_appointment($member_id,$appointmentId) {
            // Get today's date and current time
            $today = date('Y-m-d');

            // Prepare the cancellation SQL query
            $sql = "UPDATE appointments 
                SET status = 'Canceled', canceled_at = NOW(), notes = 'Scheduled appointment is still pending and past the set appointment time.'
                WHERE id = ? AND member_id = ?";

            $stmt = $this->db->prepare($sql);
            if ($stmt === false) {
                return "Failed to prepare cancellation query.";
            }

            // Bind parameters: 'i' for integer (appointmentId) and 'i' for integer (member_id)
            $stmt->bind_param('ii', $appointmentId, $member_id);
            $stmt->execute();
            // Free the statement
            $stmt->close();
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
                ];
                
                // Consultation data
                $consultationData = [
                    'reason_for_consultation' => $data['reason_for_consultation'],
                    'previous_dentist' => $data['previous_dentist'],
                    'last_dental_visit' => $data['last_dental_visit'],
                    'physician_name' => $data['physician_name'],
                    'physician_specialty' => $data['physician_specialty'],
                    'physician_address' => $data['physician_address'],
                    'physician_phone_no' => $data['physician_phone_no'],
                ];
                
                // Medical history data
                $medicalHistoryData = [
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
                ];
        
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
                    WHERE patient_info_id = ?"; 
        
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
                        $_SESSION['user_id']  // Assuming this is the patient ID
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
                        reason_for_consultation = ?, 
                        previous_dentist = ?, 
                        last_dental_visit = ?, 
                        physician_name = ?, 
                        physician_specialty = ?, 
                        physician_address = ?, 
                        physician_phone_no = ? 
                    WHERE patient_id = ?"; 
                    
                    $stmt = $this->db->prepare($updateConsultationQuery);
                    $stmt->bind_param(
                        'sssssssi',
                        $consultationData['reason_for_consultation'],
                        $consultationData['previous_dentist'],
                        $consultationData['last_dental_visit'],
                        $consultationData['physician_name'],
                        $consultationData['physician_specialty'],
                        $consultationData['physician_address'],
                        $consultationData['physician_phone_no'],
                        $consultationData['patient_id']
                    );
                    $stmt->execute();
        
                    // Update medical history
                    $updateMedicalHistoryQuery = "UPDATE medical_history SET 
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
                    $stmt->bind_param(
                        'iiissssssssssssssi',
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
                        // Success
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
                    p.first_name,
                    p.last_name,
                    p.middle_name,
                    p.birthdate,
                    p.sex,
                    p.cellphone_no,
                    p.email,
                    p.home_address,
                    p.occupation,
                    g.guardian_name,
                    g.guardian_phone,
                    mh.physician_name,
                    mh.physician_specialty,
                    mh.hospitalization_reason,
                    c.consultation_date,
                    c.consultation_notes
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
                // Assuming allergies are stored as a serialized array or comma-separated values
                return explode(',', $row['allergic_medicine']); // Adjust if your format is different
            }
        
            return []; // Return an empty array if no allergies found
        }

        public function get_patient_medical_conditions($patient_id) {
            $query = "SELECT illness_conditions FROM medical_history WHERE patient_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Assuming allergies are stored as a serialized array or comma-separated values
                return explode(',', $row['illness_conditions']); // Adjust if your format is different
            }
        
            return []; // Return an empty array if no allergies found
        }
        
    }
?>