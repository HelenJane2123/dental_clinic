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
    }
?>