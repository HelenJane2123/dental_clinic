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
            $checkQuery = "SELECT patient_info_id FROM patient_information_record WHERE patient_info_id = ?";
            if ($stmt = $this->db->prepare($checkQuery)) {
                $stmt->bind_param('i', $_SESSION['user_id']);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows > 0) {
                    // Step 2: Record exists, perform update
                    $stmt->close();  // Close the previous statement before preparing a new one
        
                    $updateQuery = "UPDATE patient_information_record SET 
                                first_name = ?, 
                                middle_name = ?, 
                                birthdate = ?, 
                                age = ?, 
                                sex = ?, 
                                nickname = ?, 
                                religion = ?, 
                                nationality = ?, 
                                occupation = ?, 
                                guardian_name = ?, 
                                guardian_occupation = ?, 
                                referral_source = ?, 
                                reason_for_consultation = ?, 
                                previous_dentist = ?, 
                                last_dental_visit = ?, 
                                physician_name = ?, 
                                physician_specialty = ?, 
                                physician_address = ?, 
                                physician_phone_no = ?, 
                                good_health = ?, 
                                under_medical_treatment = ?, 
                                serious_illness = ?, 
                                illness_details = ?, 
                                hospitalization = ?, 
                                hospitalization_reason = ?, 
                                taking_medication = ?, 
                                medication_details = ?, 
                                use_tobacco = ?, 
                                use_drugs = ?, 
                                allergies = ?, 
                                medical_conditions = ?, 
                                pregnant = ?, 
                                nursing = ?, 
                                birth_control = ?, 
                                blood_type = ?, 
                                blood_pressure = ? 
                              WHERE patient_info_id = ?";
            
                    if ($stmt = $this->db->prepare($updateQuery)) {
                        $stmt->bind_param(
                            'sssssssssssssssssssssssssssssssssi',
                            $data['first_name'], 
                            $data['middle_name'], 
                            $data['birthdate'], 
                            $data['age'], 
                            $data['sex'], 
                            $data['nickname'], 
                            $data['religion'], 
                            $data['nationality'], 
                            $data['occupation'], 
                            $data['guardian_name'], 
                            $data['guardian_occupation'], 
                            $data['referral_source'], 
                            $data['reason_for_consultation'], 
                            $data['previous_dentist'], 
                            $data['last_dental_visit'], 
                            $data['physician_name'], 
                            $data['physician_specialty'], 
                            $data['physician_address'], 
                            $data['physician_phone_no'], 
                            $data['good_health'], 
                            $data['under_medical_treatment'], 
                            $data['serious_illness'], 
                            $data['illness_details'], 
                            $data['hospitalization'], 
                            $data['hospitalization_reason'], 
                            $data['taking_medication'], 
                            $data['medication_details'], 
                            $data['use_tobacco'], 
                            $data['use_drugs'], 
                            $data['allergies'], 
                            $data['medical_conditions'], 
                            $data['pregnant'], 
                            $data['nursing'], 
                            $data['birth_control'], 
                            $data['blood_type'], 
                            $data['blood_pressure'],
                            $_SESSION['user_id']
                        );
        
                        if ($stmt->execute()) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {
                    // Step 3: No record exists, perform insert
                    $stmt->close();  // Close the previous statement before preparing a new one
        
                    $insertQuery = "INSERT INTO patient_information_record 
                        (first_name, middle_name, birthdate, age, sex, nickname, religion, nationality, occupation, guardian_name, 
                         guardian_occupation, referral_source, reason_for_consultation, previous_dentist, last_dental_visit, 
                         physician_name, physician_specialty, physician_address, physician_phone_no, good_health, under_medical_treatment, 
                         serious_illness, illness_details, hospitalization, hospitalization_reason, taking_medication, medication_details, 
                         use_tobacco, use_drugs, allergies, medical_conditions, pregnant, nursing, birth_control, blood_type, blood_pressure, 
                         patient_info_id)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
                    if ($stmt = $this->db->prepare($insertQuery)) {
                        $stmt->bind_param(
                            'sssssssssssssssssssssssssssssssssii',
                            $data['first_name'], 
                            $data['middle_name'], 
                            $data['birthdate'], 
                            $data['age'], 
                            $data['sex'], 
                            $data['nickname'], 
                            $data['religion'], 
                            $data['nationality'], 
                            $data['occupation'], 
                            $data['guardian_name'], 
                            $data['guardian_occupation'], 
                            $data['referral_source'], 
                            $data['reason_for_consultation'], 
                            $data['previous_dentist'], 
                            $data['last_dental_visit'], 
                            $data['physician_name'], 
                            $data['physician_specialty'], 
                            $data['physician_address'], 
                            $data['physician_phone_no'], 
                            $data['good_health'], 
                            $data['under_medical_treatment'], 
                            $data['serious_illness'], 
                            $data['illness_details'], 
                            $data['hospitalization'], 
                            $data['hospitalization_reason'], 
                            $data['taking_medication'], 
                            $data['medication_details'], 
                            $data['use_tobacco'], 
                            $data['use_drugs'], 
                            $data['allergies'], 
                            $data['medical_conditions'], 
                            $data['pregnant'], 
                            $data['nursing'], 
                            $data['birth_control'], 
                            $data['blood_type'], 
                            $data['blood_pressure'],
                            $_SESSION['user_id']  // Use the session user ID
                        );
        
                        if ($stmt->execute()) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
        
            return false;
        }
        
        
    }
?>