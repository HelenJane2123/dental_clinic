<?php
    require_once(__DIR__ . '/../../lib/config.php'); 
    require_once(BASE_DIR . '/lib/authenticate.php');
	class Admin {
		public $db;
		public function __construct(){
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			if(mysqli_connect_errno()) {
				echo "Error: Could not connect to database.";
			        exit;
			}
		}
        /*** check if user email exist ***/
        public function isUserExist($email){
            $sql="SELECT * FROM accounts WHERE email='$email'";
            $check =  $this->db->query($sql);
            $count_row = $check->num_rows;
            if($count_row == 0) {
                return false;
            }
            else {
                return true;
            }
        }
		/*** check if user name exist ***/
        public function isUserName($username){
            $sql="SELECT * FROM accounts WHERE username='$username'";
            $check =  $this->db->query($sql);
            $count_row = $check->num_rows;
            if($count_row == 0) {
                return false;
            }
            else {
                return true;
            }
        }

        public function get_user_id_from_account($member_id) {
            // Prepare the SQL statement to avoid SQL injection
            $stmt = $this->db->prepare("SELECT id as user_id FROM accounts WHERE member_id = ?");
            
            // Bind the parameter
            $stmt->bind_param("i", $member_id); // Assuming member_id is an integer
        
            // Execute the statement
            if ($stmt->execute()) {
                // Get the result
                $result = $stmt->get_result();
        
                // Check if a row was returned
                if ($result->num_rows > 0) {
                    // Fetch the associative array
                    $row = $result->fetch_assoc();
                    return $row['user_id']; // Return the user_id
                } else {
                    return null; // No user found
                }
            } else {
                // Handle error in execution
                return null; // Or handle this differently as per your error handling strategy
            }
        }

        public function get_user_details_from_account($member_id) {
            // Prepare the SQL statement to avoid SQL injection
            $stmt = $this->db->prepare("SELECT * FROM accounts WHERE member_id = ?");
            
            // Bind the parameter
            $stmt->bind_param("i", $member_id); // Assuming member_id is an integer
        
            // Execute the statement
            if ($stmt->execute()) {
                // Get the result
                $result = $stmt->get_result();
        
                // Check if a row was returned
                if ($result->num_rows > 0) {
                    // Fetch the associative array
                    return $result->fetch_assoc(); // Return the entire row
                } else {
                    return null; // No user found
                }
            } else {
                // Handle error in execution
                // You may want to log the error or throw an exception
                return null; // Or handle this differently as per your error handling strategy
            }
        }
        

		/*** for registration process ***/
		public function reg_user($member_id, $first_name, $last_name, $mobile_number, $username, $password_1, $user_type,  $email_address, $date_created){
			$password = md5($password_1);
            $check =  $this->isUserExist($email_address);
			$check_username =  $this->isUsername($username);
            if (!$check || !$check_username){
                $sql1="INSERT INTO accounts SET member_id='$member_id', 
                            firstname='$first_name', 
                            lastname='$last_name', 
                            contactnumber='$mobile_number',
							username = '$username',
                            user_type = '$user_type',
                            password='$password_1',
							email='$email_address',
                            date_created='$date_created'";
                $result = mysqli_query($this->db,$sql1) or die(mysqli_connect_errno()."Data cannot inserted");
                return $result;
            }
            else{
                return false;
            }
		}

		
    	/*** starting the session ***/
	    public function get_session(){
	        return $_SESSION['login'];
	    }

        /*** validate login details ***/
        public function check_login($username, $password) {
            $sql = "SELECT password FROM accounts WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
        
            if ($stmt->num_rows === 0) {
                $stmt->close();
                return false; // No such user
            }
        
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            $stmt->close();
        
            return password_verify($password, $hashed_password); // Verify password
        }

         /*** Get all user accounts ***/
        public function getAllUsers() {
            $sql = "SELECT * FROM accounts";
            $result = $this->db->query($sql);
            
            if ($result) {
                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                return $users;
            } else {
                return false; // Return false on failure
            }
        }
            
        public function getUserByUsername($username) {
            $sql = "SELECT id, firstname, lastname, member_id, email, contactnumber FROM accounts WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return false;
            }
        }
    
        public function __destruct() {
            $this->db->close();
        }

        public function get_confirmed_appointment_count() {
            $query = "SELECT COUNT(*) as count 
            FROM appointments 
            WHERE status = 'confirmed'";
  
            $result = $this->db->query($query);
            $row = $result->fetch_assoc();
            
            return $row['count'];
        }

        public function get_canceled_appointment_count() {
            $query = "SELECT COUNT(*) as count 
            FROM appointments 
            WHERE status = 'canceled'";
  
            $result = $this->db->query($query);
            $row = $result->fetch_assoc();
            
            return $row['count'];
        }

        public function get_bookings_count() {
            $query = "SELECT COUNT(*) as count 
            FROM appointments";
  
            $result = $this->db->query($query);
            $row = $result->fetch_assoc();
            
            return $row['count'];
        }

        public function get_patient_count() {
            $query = "SELECT COUNT(*) as count 
            FROM patients";
  
            $result = $this->db->query($query);
            $row = $result->fetch_assoc();
            
            return $row['count'];
        }

        public function get_notifications($exclude_user_id = null) {
            // Base query to select all notifications, ordered by creation date
            $query = "SELECT * FROM notifications";
            
            // Add condition to exclude notifications based on the user ID, if provided
            if ($exclude_user_id !== null) {
                $query .= " WHERE user_id != ?";
            }
            
            // Append the ordering and limit
            $query .= " ORDER BY created_at DESC LIMIT 2";
            
            if ($stmt = $this->db->prepare($query)) {
                // Bind the user ID parameter if it’s not null
                if ($exclude_user_id !== null) {
                    $stmt->bind_param("i", $exclude_user_id);
                }
                
                // Execute the query
                $stmt->execute();
                
                // Fetch the result
                $result = $stmt->get_result();
                
                // Initialize an array to store notifications
                $notifications = [];
                
                // Loop through the result and add each notification to the array
                while ($row = $result->fetch_assoc()) {
                    $notifications[] = $row;
                }
                
                // Close the statement
                $stmt->close();
                
                // Return the array of notifications
                return $notifications;
            } else {
                // If the query preparation fails, return an empty array
                return [];
            }
        }

        public function update_notification($notificationId) {
            // Prepare the SQL statement to update the notification
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
            
            // Check if statement preparation was successful
            if (!$stmt) {
                echo "Error preparing statement: " . $this->db->error;
                return false; // Return false if the statement could not be prepared
            }
        
            // Bind the notification ID
            $stmt->bind_param("i", $notificationId);
        
            // Execute the statement and check for success
            if ($stmt->execute()) {
                $stmt->close(); // Close the statement
                return true; // Return true if the notification is successfully updated
            } else {
                // If there was an error, output the error message for debugging
                echo "Error updating notification: " . $stmt->error;
                $stmt->close(); // Close the statement
                return false; // Return false if there's an error
            }
        }

        public function get_all_notifications($exclude_user_id = null) {
            // SQL query to get patient details along with member ID, first name, and last name
            $query = "
                SELECT p.*, o.member_id, a.first_name AS first_name, a.last_name AS last_name, a.patient_id
                FROM notifications p
                LEFT JOIN patients a ON p.user_id = a.patient_id
                LEFT JOIN accounts o ON o.id = p.user_id";
            
            // Add a condition to exclude notifications for the specified user ID if provided
            if ($exclude_user_id !== null) {
                $query .= " WHERE p.user_id != ?";
            }
            
            // Prepare the SQL statement
            if ($stmt = $this->db->prepare($query)) {
                // Bind the exclude_user_id parameter if it’s provided
                if ($exclude_user_id !== null) {
                    $stmt->bind_param("i", $exclude_user_id);
                }
                
                // Execute the query
                $stmt->execute();
                
                // Fetch the result
                $result = $stmt->get_result();
                
                // Initialize an array to store the patients with details
                $patients_with_details = [];
                
                // Loop through the result and add each patient to the array
                while ($row = $result->fetch_assoc()) {
                    $patients_with_details[] = $row;
                }
                
                // Close the statement
                $stmt->close();
                
                // Return the array of patients with details
                return $patients_with_details;
            } else {
                // If the query preparation fails, return an empty array
                return [];
            }
        }
        

        public function get_all_patients() {
            // SQL query to get patient details along with member ID, first name, and last name
            $query = "SELECT *
                        FROM patients";

            // Prepare the SQL statement
            if ($stmt = $this->db->prepare($query)) {
            // Execute the query
            $stmt->execute();

            // Fetch the result
            $result = $stmt->get_result();

            // Initialize an array to store the patients with details
            $patients_with_details = [];

            // Loop through the result and add each patient to the array
            while ($row = $result->fetch_assoc()) {
                $patients_with_details[] = $row;
            }

            // Close the statement
            $stmt->close();

            // Return the array of patients with details
            return $patients_with_details;
            } else {
                // If the query preparation fails, return an empty array
                return [];
            }
        }

        public function get_all_appointment_bookings() {
            // SQL query to get appointment details along with patient and admin details
            $query = "
                SELECT 
                    appointments.id AS appointment_id,
                    appointments.appointment_date,
                    appointments.appointment_time,
                    appointments.services,
                    appointments.status,
                    appointments.notes,
                    patients.patient_id,
                    patients.member_id as patient_member_id,
                    patients.first_name AS patient_first_name,
                    patients.last_name AS patient_last_name,
                    approved_admin.firstname AS approved_first_name,
                    approved_admin.lastname AS approved_last_name,
                    canceled_admin.firstname AS canceled_first_name,
                    canceled_admin.lastname AS canceled_last_name,
                    rescheduled_admin.firstname AS rescheduled_first_name,
                    rescheduled_admin.lastname AS rescheduled_last_name,
                    appointments.updated_at
                FROM 
                    appointments
                LEFT JOIN 
                    patients ON appointments.patient_id = patients.patient_id
                LEFT JOIN 
                    accounts AS approved_admin ON appointments.approved_by = approved_admin.id
                LEFT JOIN 
                    accounts AS canceled_admin ON appointments.canceled_by = canceled_admin.id
                LEFT JOIN 
                    accounts AS rescheduled_admin ON appointments.rescheduled_by = rescheduled_admin.id
            ";
        
            // Prepare the SQL statement
            if ($stmt = $this->db->prepare($query)) {
                // Execute the query
                $stmt->execute();
        
                // Fetch the result
                $result = $stmt->get_result();
        
                // Initialize an array to store the appointments with patient and admin details
                $appointments_with_details = [];
        
                // Loop through the result and add each appointment with details to the array
                while ($row = $result->fetch_assoc()) {
                    $appointments_with_details[] = $row;
                }
        
                // Close the statement
                $stmt->close();
        
                // Return the array of appointments with patient and admin details
                return $appointments_with_details;
            } else {
                // If the query preparation fails, return an empty array
                return [];
            }
        }

        public function reschedule_appointment($appointment_id, $new_date, $new_time, $notes, $updated_by, $user_id) {
            // Logic to update the appointment and log the action
            $status = 'Re-schedule';
            $stmt = $this->db->prepare("UPDATE appointments SET appointment_date = ?, appointment_time = ?, status= ?, notes = ?, rescheduled_by = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $new_date, $new_time, $status, $notes, $updated_by, $appointment_id);
            
            if ($stmt->execute()) {
                $this->log_notification($appointment_id, 'Re-schedule', $notes, $updated_by, $user_id);
                return true;
            }
            return false;
        }
        
        public function cancel_appointment($appointment_id, $notes, $updated_by, $user_id) {
            $status = 'Canceled';
            $canceled_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            // Logic to cancel the appointment
            $stmt = $this->db->prepare("UPDATE appointments SET status = ?, notes = ?, canceled_at = ?, updated_at = ?, updated_by = ?, canceled_by = ? WHERE id = ?");
            // Bind the parameters; assuming updated_by is a string
            $stmt->bind_param("ssssssi", $status, $notes, $canceled_at, $updated_at, $user_id,  $user_id, $appointment_id);
            
            if ($stmt->execute()) {
                $this->log_notification($appointment_id, 'canceled', $notes, $updated_by, $user_id);
                return true;
            }
            return false;
        }
        
        public function approve_appointment($appointment_id, $notes, $updated_by, $user_id) {
            // Logic to approve the appointment
            $status = 'Confirmed';
            $updated_date = date('Y-m-d H:i:s'); // Get the current date and time in the appropriate format
            
            // Prepare the SQL statement to include updated_by and updated_date
            $stmt = $this->db->prepare("UPDATE appointments SET status = ?, notes = ?, updated_at = ?, updated_by = ?, approved_by = ? WHERE id = ?");
            
            // Bind the parameters; assuming updated_by is a string
            $stmt->bind_param("sssssi", $status, $notes, $updated_date, $user_id,  $user_id, $appointment_id);
            
            if ($stmt->execute()) {
                $this->log_notification($appointment_id, 'Confirmed', $notes, $updated_by, $user_id);
                return true;
            }
            return false;
        }
        
        private function log_notification($appointment_id, $action, $notes, $performed_by, $user_id) {
            // Fetch appointment details
            $stmt = $this->db->prepare("
                SELECT p.patient_id, p.first_name AS patient_first_name, p.last_name AS patient_last_name, 
                       a.appointment_date, a.appointment_time 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                WHERE a.id = ?
            ");
            $stmt->bind_param("i", $appointment_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $appointment = $result->fetch_assoc();
                
                $patientId = $appointment['patient_id'];
                $patientFirstName = $appointment['patient_first_name'];
                $patientLastName = $appointment['patient_last_name'];
                $appointmentDate = date('F j, Y', strtotime($appointment['appointment_date']));
                $appointmentTime = date('h:i A', strtotime($appointment['appointment_time']));
                
                // Fetch details of the person who performed the action
                $stmt = $this->db->prepare("
                    SELECT firstname, lastname 
                    FROM accounts 
                    WHERE id = ?
                ");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $accountResult = $stmt->get_result();
                $account = $accountResult->fetch_assoc();
                
                $performerFirstName = $account['firstname'];
                $performerLastName = $account['lastname'];
                
                // Prepare the notification message
                $message = "Appointment for " . htmlspecialchars($patientFirstName) . " " . htmlspecialchars($patientLastName) . 
                           " on " . $appointmentDate . " at " . $appointmentTime . 
                           " has been updated. Status: " . htmlspecialchars($action) . 
                           " by " . htmlspecialchars($performerFirstName) . " " . htmlspecialchars($performerLastName) . 
                           ". Reason: " . htmlspecialchars($notes);
                
                // Insert notification
                $stmt = $this->db->prepare("
                    INSERT INTO notifications (user_id, notif_to, message, type, is_read, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())
                ");
                
                $notificationType = 'appointment_update';
                $is_read = 0; // Assuming unread notifications have a value of 0
                
                // Bind user_id, patientId (notif_to), message, notificationType, and is_read
                $stmt->bind_param("iisss", $user_id, $patientId, $message, $notificationType, $is_read);
                
                return $stmt->execute(); // Return the success or failure of the notification insertion
            }
            
            return false; // If appointment not found, return false
        }
        
        public function get_today_appointments() {
            // Prepare the SQL query to select today's appointments
            $todayDate = date('Y-m-d'); // Get today's date in 'YYYY-MM-DD' format
        
            $stmt = $this->db->prepare("
                SELECT p.first_name AS patient_first_name, 
                       p.last_name AS patient_last_name, 
                       a.status 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id 
                WHERE DATE(a.appointment_date) = ?  -- Filter by today's date
                ORDER BY a.appointment_time ASC       -- Order by appointment time
            ");
        
            // Bind the date parameter to the query
            $stmt->bind_param("s", $todayDate);
            
            // Execute the statement
            $stmt->execute();
        
            // Fetch the results
            $result = $stmt->get_result();
        
            // Check if any results were returned
            if ($result->num_rows > 0) {
                // Fetch all appointments as an associative array
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        
            // If no appointments found, return an empty array
            return [];
        }
        
        
        
	}
?>