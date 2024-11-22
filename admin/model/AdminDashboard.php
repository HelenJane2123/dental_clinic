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
        public function isUserExist($email) {
            $stmt = $this->db->prepare("SELECT * FROM accounts WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
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

        public function reset_password($token) {
            $currentDateTime = date("Y-m-d H:i:s"); // Get current time
        
            $stmt = $this->db->prepare("SELECT * FROM accounts WHERE reset_token = ? AND token_expiration >= ?");
            $stmt->bind_param("ss", $token, $currentDateTime);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows === 1) {
                return $result->fetch_assoc(); // Return user details
            } else {
                return false; // Token is invalid or expired
            }
        }

        public function forgot_password($token, $expiration, $email) {
            // Assuming you have a database connection established in your class
            $conn = $this->db; // Replace with your database connection instance
        
            // Prepare the SQL query to update the user's password reset token and expiration
            $query = "UPDATE accounts SET reset_token = ?, token_expiration = ? WHERE email = ?";
        
            if ($stmt = $conn->prepare($query)) {
                // Bind the parameters
                $stmt->bind_param('sss', $token, $expiration, $email);
        
                // Execute the statement
                if ($stmt->execute()) {
                    // Check if any row was updated
                    if ($stmt->affected_rows > 0) {
                        return true; // Successfully updated
                    }
                }
        
                // Close the statement
                $stmt->close();
            }
        
            return false; // Failed to update
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
            // Correct the SQL query to use AND instead of &
            $sql = "SELECT password FROM accounts WHERE username = ? AND user_type != 'patient'";
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
            $sql = "SELECT id, firstname, lastname, member_id, email, contactnumber, user_type FROM accounts WHERE username = ? AND user_type != 'patient'";
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
                $query .= " WHERE notif_to = ? ";
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
                SELECT p.*, a.member_id as patient_member_id, a.first_name AS first_name, a.last_name AS last_name, a.patient_id
                FROM notifications p
                LEFT JOIN patients a ON p.user_id = a.patient_id
                LEFT JOIN accounts o ON o.id = p.user_id";
            
            // Add a condition to exclude notifications for the specified user ID if provided
            if ($exclude_user_id !== null) {
                $query .= " WHERE p.notif_to = ? ORDER BY created_at DESC";
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
            $query = "SELECT patients.*, doctors.first_name AS doctor_first_name, doctors.last_name AS doctor_last_name
                        FROM patients
                    LEFT JOIN doctors ON patients.assigned_doctor = doctors.account_id
                    ORDER BY patients.patient_id DESC";

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

        

        public function get_all_patients_per_doctor($doctor_id) {
            // SQL query to get patient details along with assigned doctor details
            $query = "
                SELECT 
                    patients.*, 
                    doctors.first_name AS doctor_first_name, 
                    doctors.last_name AS doctor_last_name
                FROM 
                    patients
                LEFT JOIN 
                    doctors ON patients.assigned_doctor = doctors.account_id
                WHERE 
                    doctors.account_id = ?
                ORDER BY patients.patient_id DESC
            ";
        
            // Prepare the SQL statement
            if ($stmt = $this->db->prepare($query)) {
                // Bind the doctor_id parameter to the query
                $stmt->bind_param("i", $doctor_id);
        
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
                    patients.member_id AS patient_member_id,
                    patients.first_name AS patient_first_name,
                    patients.last_name AS patient_last_name,
                    completed_admin.firstname AS completed_first_name,
                    completed_admin.lastname AS completed_last_name,
                    approved_admin.firstname AS approved_first_name,
                    approved_admin.lastname AS approved_last_name,
                    canceled_admin.firstname AS canceled_first_name,
                    canceled_admin.lastname AS canceled_last_name,
                    rescheduled_admin.firstname AS rescheduled_first_name,
                    rescheduled_admin.lastname AS rescheduled_last_name,
                    appointments.completed_at as date_completed,
                    appointments.approved_at as date_approved,
                    appointments.canceled_at as date_canceled,
                    appointments.rescheduled_at as date_rescheduled,
                    pp.appointment_id as proof_id,
                    pp.file_name,
                    ds.sub_category as services_name
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
                LEFT JOIN 
                    accounts AS completed_admin ON appointments.completed_by = completed_admin.id
                LEFT JOIN
                	proof_of_payment AS pp ON pp.appointment_id = appointments.id
                LEFT JOIN
                	dental_services AS ds ON ds.id = appointments.services
                ORDER BY 
                    appointments.appointment_date DESC, 
                    appointments.appointment_time DESC;";
        
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

        public function get_all_appointment_bookings_per_doctor($doctor_id) {
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
                    patients.member_id AS patient_member_id,
                    patients.first_name AS patient_first_name,
                    patients.last_name AS patient_last_name,
                    approved_admin.firstname AS approved_first_name,
                    approved_admin.lastname AS approved_last_name,
                    canceled_admin.firstname AS canceled_first_name,
                    canceled_admin.lastname AS canceled_last_name,
                    rescheduled_admin.firstname AS rescheduled_first_name,
                    rescheduled_admin.lastname AS rescheduled_last_name,
                    appointments.completed_at as date_completed,
                    pp.appointment_id as proof_id,
                    pp.file_name,
                    ds.sub_category as services_name
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
                LEFT JOIN 
                    accounts AS completed_admin ON appointments.completed_by = completed_admin.id
                LEFT JOIN
                	proof_of_payment AS pp ON pp.appointment_id = appointments.id
                LEFT JOIN
                	dental_services AS ds ON ds.id = appointments.services
                WHERE 
                    patients.assigned_doctor = ?
                ORDER BY 
                    appointments.appointment_date DESC, 
                    appointments.appointment_time DESC;
            ";
        
            // Prepare the SQL statement
            if ($stmt = $this->db->prepare($query)) {
                // Bind the doctor_id parameter
                $stmt->bind_param("i", $doctor_id);
        
                // Execute the query
                $stmt->execute();
        
                // Fetch the result
                $result = $stmt->get_result();
        
                // Initialize an array to store the appointments with details
                $appointments_with_details = [];
        
                // Loop through the result and add each appointment to the array
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
            $rescheduled_at = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare("UPDATE appointments SET appointment_date = ?, appointment_time = ?, status= ?, notes = ?, rescheduled_by = ?, rescheduled_at = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $new_date, $new_time, $status, $notes, $user_id, $rescheduled_at, $appointment_id);
            
            if ($stmt->execute()) {
                $this->log_notification($appointment_id, 'Re-schedule', $notes, $updated_by, $user_id);
                return true;
            }
            return false;
        }

        public function complete_appointment($appointment_id, $notes, $updated_by, $user_id) {
            // Logic to update the appointment and log the action
            $status = 'Completed';
            $completed_at = date('Y-m-d H:i:s'); // Get the current date and time in the appropriate format
        
            // Prepare the SQL statement
            $stmt = $this->db->prepare("UPDATE appointments SET status = ?, notes = ?, completed_by = ?, completed_at = ? WHERE id = ?");
        
            // Bind the parameters for the query
            $stmt->bind_param("sssii", $status, $notes, $user_id, $completed_at, $appointment_id);
        
            // Execute the query and handle success/failure
            if ($stmt->execute()) {
                // Log the action in a notification (assuming a separate logging function exists)
                $this->log_notification($appointment_id, 'Completed', $notes, $updated_by, $user_id);
                return true;
            }
        
            return false;
        }
        
        public function cancel_appointment($appointment_id, $notes, $updated_by, $user_id) {
            $status = 'Canceled';
            $canceled_at = date('Y-m-d H:i:s');
            // Logic to cancel the appointment
            $stmt = $this->db->prepare("UPDATE appointments SET status = ?, notes = ?, canceled_at = ?, canceled_by = ? WHERE id = ?");
            // Bind the parameters; assuming updated_by is a string
            $stmt->bind_param("sssii", $status, $notes, $canceled_at, $user_id,  $appointment_id);
            
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
            $stmt = $this->db->prepare("UPDATE appointments SET status = ?, notes = ?, approved_by = ?, approved_at = ? WHERE id = ?");
            
            // Bind the parameters; assuming updated_by is a string
            $stmt->bind_param("ssisi", $status, $notes,  $user_id, $updated_date, $appointment_id);
            
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
                       a.status, a.appointment_date, a.appointment_time 
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id 
                WHERE DATE(a.appointment_date) = ? 
                ORDER BY a.appointment_time ASC     
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
        
        // Assuming this function is inside your Admin or UserDashboard class
        public function get_patient_email_by_appointment($patient_id) {
            // Prepare the SQL query to get the patient's email by patient_id
            $query = "SELECT email FROM patients WHERE patient_id = ?";
            
            // Prepare the statement
            if ($stmt = $this->db->prepare($query)) {
                // Bind the patient_id to the prepared statement
                $stmt->bind_param("i", $patient_id);  // Assuming patient_id is a string, use 'i' for integers
                
                // Execute the statement
                $stmt->execute();

                // Bind the result to a variable
                $stmt->bind_result($email);

                // Fetch the result
                if ($stmt->fetch()) {
                    // Return the email if found
                    return $email;
                } else {
                    // Return null or an empty string if no result
                    return null;
                }

                // Close the statement
                $stmt->close();
            } else {
                // Handle error if query preparation fails
                return null;
            }
        }

        public function get_patient_by_appointment_id($appointment_id) {
            // Prepare the SQL query to fetch patient details by appointment ID
            $query = "
                SELECT p.email, p.first_name, p.last_name, ds.sub_category as service_name, a.appointment_time, a.appointment_date
                FROM appointments a
                LEFT JOIN patients p ON p.patient_id = a.patient_id
                LEFT JOIN dental_services ds ON ds.id = a.services
                WHERE a.id = ?
            ";
        
            // Prepare the statement
            if ($stmt = $this->db->prepare($query)) {
                // Bind the appointment_id to the prepared statement
                $stmt->bind_param("i", $appointment_id);  // 'i' for integers
        
                // Execute the statement
                $stmt->execute();
        
                // Bind the result variables
                $stmt->bind_result($email, $first_name, $last_name, $service_name, $appointment_time, $appointment_date);
        
                // Fetch the result
                if ($stmt->fetch()) {
                    // Return patient details as an associative array
                    return [
                        'email' => $email,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'service_name' => $service_name,
                        'appointment_time' => $appointment_time,
                        'appointment_date' => $appointment_date
                    ];
                } else {
                    // Return null if no result
                    return null;
                }
        
                // Close the statement
                $stmt->close();
            } else {
                // Log or handle error if query preparation fails
                return null;
            }
        }

        public function get_doctor_details_with_account() {
            $query = "
                SELECT doctors.*, accounts.*, accounts.member_id
                FROM doctors
                LEFT JOIN accounts ON accounts.id = doctors.account_id
                WHERE accounts.user_type = 'admin'
            ";
            $doctorDetails = [];
        
            // Prepare the statement
            if ($stmt = $this->db->prepare($query)) {
                // Execute the statement
                $stmt->execute();
        
                // Get the result set from the query
                $result = $stmt->get_result();
        
                // Fetch all rows and store them in an associative array
                while ($row = $result->fetch_assoc()) {
                    // Access the member_id here
                    $memberId = $row['member_id'];
                    
                    // Add member_id to each doctor record
                    $row['member_id'] = $memberId;
                    $doctorDetails[] = $row;
                }
        
                // Close the statement
                $stmt->close();
            } else {
                // Handle error if query preparation fails
                return null;
            }
        
            return $doctorDetails; // Return an array of doctor details with account info
        }
        
        
        public function reg_doctor($member_id, $first_name, $last_name, $mobile_number, $username, $hashed_password, $user_type, $email_address, $date_created, $specialty) {
            // Insert into accounts table
            $query_accounts = "INSERT INTO accounts (member_id, firstname, lastname, contactnumber, username, password, user_type, email, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmt_accounts = $this->db->prepare($query_accounts)) {
                $stmt_accounts->bind_param("sssssssss", $member_id, $first_name, $last_name, $mobile_number, $username, $hashed_password, $user_type, $email_address, $date_created);
                
                if ($stmt_accounts->execute()) {
                    $account_id = $stmt_accounts->insert_id;
        
                    // Insert into doctors table
                    $query_doctors = "INSERT INTO doctors (account_id, first_name, last_name, email, contact_number, specialty, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    if ($stmt_doctors = $this->db->prepare($query_doctors)) {
                        $stmt_doctors->bind_param("issssss", $account_id, $first_name, $last_name, $email_address, $mobile_number, $specialty, $date_created);
                        
                        if ($stmt_doctors->execute()) {
                            $stmt_accounts->close();
                            $stmt_doctors->close();
                            return true;
                        }
                        $stmt_doctors->close();
                    }
                }
                $stmt_accounts->close();
            }
            return false;
        }

        public function get_doctor_details($id) {
            // Prepare the query to fetch doctor's details (email, id, and account_id) by the provided doctor ID
            $stmt = $this->db->prepare("SELECT doctor_id, email, account_id, first_name, last_name FROM doctors WHERE account_id = ?");
            
            // Bind the input parameter (account_id)
            $stmt->bind_param("i", $id);
            
            // Execute the statement
            $stmt->execute();
            
            // Store the result
            $stmt->store_result();
            
            // Check if the doctor exists
            if ($stmt->num_rows > 0) {
                // Bind the result to the variables (id, email, account_id)
                $stmt->bind_result($doctor_id, $email, $account_id, $first_name, $last_name);
                
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
                    'last_name' => $last_name
                );
            } else {
                // Close the statement
                $stmt->close();
                
                // Return false if no doctor is found with the given ID
                return false;
            }
        }  

        public function get_patient_details($id) {
            // Prepare the query to fetch patient's details (email, id, and account_id) by the provided patient ID
            $stmt = $this->db->prepare("SELECT patient_id, email,  first_name, last_name, member_id FROM patients WHERE patient_id = ?");
            
            // Bind the input parameter (patient_id)
            $stmt->bind_param("i", $id);
            
            // Execute the statement
            $stmt->execute();
            
            // Store the result
            $stmt->store_result();
            
            // Check if the patient exists
            if ($stmt->num_rows > 0) {
                // Bind the result to the variables (patient_id, email, account_id, first_name, last_name)
                $stmt->bind_result($patient_id, $email, $first_name, $last_name, $member_id);
                
                // Fetch the data
                $stmt->fetch();
                
                // Close the statement
                $stmt->close();
                
                // Return the patient details as an associative array
                return array(
                    'patient_id' => $patient_id,
                    'email' => $email,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'member_id' => $member_id
                );
            } else {
                // Close the statement
                $stmt->close();
                
                // Return false if no patient is found with the given ID
                return false;
            }
        }  

        public function delete_doctor($doctor_id) {
            // Start a transaction to ensure both deletions succeed or fail together
            $this->db->begin_transaction();
        
            try {
                // First, delete from the doctors table
                $query1 = "DELETE FROM doctors WHERE account_id = ?";
                $stmt1 = $this->db->prepare($query1);
                $stmt1->bind_param("i", $doctor_id);
        
                if (!$stmt1->execute()) {
                    throw new Exception("Error deleting from doctors table.");
                }
                $stmt1->close();
        
                // Next, delete from the accounts table
                $query2 = "DELETE FROM accounts WHERE id = ?";
                $stmt2 = $this->db->prepare($query2);
                $stmt2->bind_param("i", $doctor_id);
        
                if (!$stmt2->execute()) {
                    throw new Exception("Error deleting from accounts table.");
                }
                $stmt2->close();
        
                // Commit the transaction if both deletions are successful
                $this->db->commit();
                return true;
        
            } catch (Exception $e) {
                // Roll back the transaction on error
                $this->db->rollback();
                error_log("Error deleting doctor: " . $e->getMessage());
                return false;
            }
        }
        
        public function update_profile($firstname, $lastname, $contactnumber, $email, $gender, $address, $profilePicPath, $remarks, $member_id) {
            $sql = "UPDATE accounts 
                    SET firstname = ?, lastname = ?, contactnumber = ?, email = ?, gender = ?, address = ?, profile_picture = IFNULL(?, profile_picture), remarks = ?
                    WHERE member_id = ?";
            
            $stmt = $this->db->prepare($sql);
            if ($stmt === false) {
                error_log("Error preparing statement: " . $this->db->error);
                return false; // Handle error
            }
        
            // Debugging output
            error_log("Updating user with member_id: " . $member_id);
        
            // Bind parameters: 8 strings followed by 1 integer
            $stmt->bind_param('ssissssss', $firstname, $lastname, $contactnumber, $email, $gender, $address, $profilePicPath, $remarks, $member_id);
        
            // Execute and check for errors
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                error_log("Error executing update: " . $stmt->error); // Log SQL error
                $stmt->close();
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

        public function get_current_password($member_id) {
            $query = "SELECT password FROM accounts WHERE member_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $member_id);
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

        public function getMonthlyPatientCounts() {
            $query = "
                SELECT MONTH(appointment_date) AS month, COUNT(*) AS patient_count 
                FROM appointments 
                WHERE YEAR(appointment_date) = YEAR(CURDATE()) 
                GROUP BY month 
                ORDER BY month";
            
            // Prepare the SQL statement
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        
            // Get the result set from the query
            $result = $stmt->get_result();  // Get the result set
        
            $monthlyCounts = array_fill(0, 12, 0); // Array for 12 months initialized to zero
            
            // Fetch the results one by one
            while ($row = $result->fetch_assoc()) {  // Use fetch_assoc on the result object
                $monthlyCounts[(int)$row['month'] - 1] = $row['patient_count']; // Adjust for 0-indexing
            }
            
            return $monthlyCounts;
        }

        public function get_all_patients_without_doctor() {
            // SQL query to get patients without an assigned doctor and exclude 'super_admin' user_type from the accounts table
            $sql = "
                SELECT patients.*
                FROM patients
            ";
        
            // Prepare the statement
            $stmt = $this->db->prepare($sql);
            
            // Execute the statement
            $stmt->execute();
            
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch all rows into an associative array
            $patients = $result->fetch_all(MYSQLI_ASSOC);
            
            // Close the statement
            $stmt->close();
            
            return $patients;
        }

        public function get_dental_services() {
            // SQL query to get patients without an assigned doctor and exclude 'super_admin' user_type from the accounts table
            $sql = "
                SELECT dental_services.*
                FROM dental_services
            ";
        
            // Prepare the statement
            $stmt = $this->db->prepare($sql);
            
            // Execute the statement
            $stmt->execute();
            
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch all rows into an associative array
            $dental_services = $result->fetch_all(MYSQLI_ASSOC);
            
            // Close the statement
            $stmt->close();
            
            return $dental_services;
        }
        

        public function assign_patient($doctor_id, $patient_id) {
            // Prepare the SQL statement
            $sql = "UPDATE patients SET assigned_doctor = ? WHERE patient_id = ?";
            $stmt = $this->db->prepare($sql);
        
            // Bind the doctor_id and patient_id to the SQL statement
            $stmt->bind_param("ii", $doctor_id, $patient_id);
        
            // Execute the statement and check for success
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        }
        
        public function getPaymentStatus() {
            $sql = "
                SELECT 
                    a.id AS appointment_id,
                    pt.member_id AS patient_member_id,
                    COALESCE(pp.status, 'Pending') AS status,
                    pp.file_name,
                    pt.first_name as patient_first_name,
                    pt.last_name as patient_last_name,
                    d.first_name as doctor_first_name,
                    d.last_name as doctor_last_name,
                    COALESCE(pp.remarks, 'No payment uploaded') AS remarks
                FROM 
                    appointments a
                LEFT JOIN 
                    proof_of_payment pp ON a.id = pp.appointment_id
                LEFT JOIN 
                    patients pt ON a.patient_id = pt.patient_id
                LEFT JOIN
                    doctors d ON d.account_id = pt.assigned_doctor
                ORDER BY pp.uploaded_at DESC
            ";
        
            // Prepare the statement
            $stmt = $this->db->prepare($sql);
        
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
        
                // Fetch all data
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
        
                $stmt->close();
                return $data;
            } else {
                // Log an error if the query fails
                error_log("Query failed: " . $this->db->error);
                return [];
            }
        }

        public function updatePaymentStatus($appointmentId, $status) {
            // Update proof_of_payment table with the new status
            $sql = "UPDATE proof_of_payment SET status = ? WHERE appointment_id = ?";
            $stmt = $this->db->prepare($sql);
        
            if ($stmt) {
                $stmt->bind_param("si", $status, $appointmentId);
                $stmt->execute();
                $stmt->close();
        
                // Determine appointment status based on proof_of_payment status
                // If approved, set appointment status to 'Confirmed', if rejected, set to 'Cancelled' or 'Pending'
                $appointmentStatus = ($status === 'Approved') ? 'Confirmed' : 'Cancelled';
        
                // Fetch existing notes from the appointment table
                $fetchNotesSql = "SELECT notes FROM appointments WHERE id = ?";
                $fetchNotesStmt = $this->db->prepare($fetchNotesSql);
        
                if ($fetchNotesStmt) {
                    $fetchNotesStmt->bind_param("i", $appointmentId);
                    $fetchNotesStmt->execute();
                    $fetchNotesStmt->bind_result($existingNotes);
                    $fetchNotesStmt->fetch();
                    $fetchNotesStmt->close();
        
                    // Prepare the updated notes
                    $newNote = "";
                    if ($status === 'Approved') {
                        $newNote = "Patient has paid.";
                    } elseif ($status === 'Rejected') {
                        $newNote = "Payment was rejected.";
                    }
        
                    // Combine existing notes with new note
                    $updatedNotes = $existingNotes ? $existingNotes . " " . $newNote : $newNote;
        
                    // Update the appointment table with new status and notes
                    $updateAppointmentSql = "UPDATE appointments SET status = ?, notes = ? WHERE id = ?";
                    $appointmentStmt = $this->db->prepare($updateAppointmentSql);
        
                    if ($appointmentStmt) {
                        $appointmentStmt->bind_param("ssi", $appointmentStatus, $updatedNotes, $appointmentId);
                        $appointmentStmt->execute();
                        $appointmentStmt->close();
                        return true;
                    }
                }
            }
        
            return false;
        }
        
        // Example method in Admin class to delete a dental service
        public function delete_dental_services($id) {
            $query = "DELETE FROM dental_services WHERE id = ?";

            if ($stmt = $this->db->prepare($query)) {
                $stmt->bind_param("i", $id);
                return $stmt->execute();
            } else {
                return false;
            }
        }

        // Example method in Admin class to update a dental service
        public function update_dental_services($id, $category, $subCategory, $priceRange, $price, $down_payment) {
            $query = "UPDATE dental_services SET category = ?, sub_category = ?, price_range = ?, price = ?, down_payment = ? WHERE id = ?";

            if ($stmt = $this->db->prepare($query)) {
                $stmt->bind_param("sssssi", $category, $subCategory, $priceRange, $price, $down_payment, $id);
                return $stmt->execute();
            } else {
                return false;
            }
        }

        // Example method in Admin class to add dental service
        public function add_dental_service($category, $subCategory, $priceRange, $price, $down_payment) {
            $query = "INSERT INTO dental_services (category, sub_category, price_range, price, down_payment) 
                    VALUES (?, ?, ?, ?, ?)";

            if ($stmt = $this->db->prepare($query)) {
                $stmt->bind_param("sssdd", $category, $subCategory, $priceRange, $price, $down_payment);
                return $stmt->execute();
            } else {
                return false;
            }
        }

        // Method to get patient details by ID
        public function get_patient_by_id($patient_id) {
            $sql = "SELECT 
                        p.*, 
                        d.first_name AS doctor_first_name, 
                        d.last_name AS doctor_last_name, 
                        d.specialty AS doctor_specialty, 
                        d.email AS doctor_email
                    FROM patients p
                    LEFT JOIN doctors d ON p.assigned_doctor = d.account_id
                    WHERE p.patient_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Return the patient data along with doctor details
            } else {
                return null; // Return null if no patient found
            }
        }
        

        // Method to get medical history of a patient
        public function get_medical_history($patient_id) {
            $sql = "SELECT * FROM medical_history WHERE patient_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Assuming one row per patient
            } else {
                return null;
            }
        }

        // Method to get guardians' details of a patient
        public function get_guardians($patient_id) {
            $sql = "SELECT * FROM guardians WHERE patient_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Assuming one row per patient
            } else {
                return null;
            }
        }

        // Method to get consultations of a patient
        public function get_consultations($patient_id) {
            $sql = "SELECT * FROM consultations WHERE patient_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
           
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Assuming one row per patient
            } else {
                return null;
            }
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

        public function save_dental_record($patient_id, $date, $tooth_no, $procedure, $dentist, $amount_charged, $amount_paid, $balance, $next_appointment) {
            // First, check if a record for the patient already exists on the given date and tooth number
            $check_query = "SELECT id FROM dental_records WHERE patient_id = ? AND `date` = ? AND tooth_no = ?";
        
            if ($stmt = $this->db->prepare($check_query)) {
                // Bind parameters
                $stmt->bind_param('iss', $patient_id, $date, $tooth_no);
                
                // Execute and get the result
                $stmt->execute();
                $stmt->store_result();
        
                // If the record exists, update it, otherwise insert a new one
                if ($stmt->num_rows > 0) {
                    // Record exists, so update it
                    $update_query = "UPDATE dental_records SET 
                                        `procedure` = ?, 
                                        dentist = ?, 
                                        amount_charged = ?, 
                                        amount_paid = ?, 
                                        balance = ?, 
                                        next_appointment = ?
                                      WHERE patient_id = ? AND `date` = ? AND tooth_no = ?";
        
                    if ($update_stmt = $this->db->prepare($update_query)) {
                        // Bind parameters for the update query
                        $update_stmt->bind_param('ssdddsiss', 
                            $procedure, 
                            $dentist, 
                            $amount_charged, 
                            $amount_paid, 
                            $balance, 
                            $next_appointment, 
                            $patient_id, 
                            $date, 
                            $tooth_no
                        );
        
                        // Execute the update statement
                        if ($update_stmt->execute()) {
                            $update_stmt->close();
                            return true; // Update successful
                        } else {
                            $update_stmt->close();
                            return false; // Update failed
                        }
                    } else {
                        return false; // Update statement preparation failed
                    }
                } else {
                    // No existing record found, so insert a new one
                    $insert_query = "INSERT INTO dental_records 
                                        (patient_id, `date`, tooth_no, `procedure`, dentist, 
                                        amount_charged, amount_paid, balance, next_appointment)
                                     VALUES 
                                        (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
                    if ($insert_stmt = $this->db->prepare($insert_query)) {
                        // Bind parameters for the insert query
                        $insert_stmt->bind_param('issssddds', 
                            $patient_id, 
                            $date, 
                            $tooth_no, 
                            $procedure, 
                            $dentist, 
                            $amount_charged, 
                            $amount_paid, 
                            $balance, 
                            $next_appointment
                        );
        
                        // Execute the insert statement
                        if ($insert_stmt->execute()) {
                            $insert_stmt->close();
                            return true; // Insert successful
                        } else {
                            $insert_stmt->close();
                            return false; // Insert failed
                        }
                    } else {
                        return false; // Insert statement preparation failed
                    }
                }
            } else {
                return false; // Check query preparation failed
            }
        }
        

        public function get_dental_records($patient_id) {
            // Assuming you have a database connection $this->db (mysqli)
            $sql = "SELECT * FROM dental_records WHERE patient_id = ? ORDER BY date DESC";
            
            // Prepare the SQL statement
            $stmt = $this->db->prepare($sql);
            
            // Bind the patient_id to the query
            $stmt->bind_param('i', $patient_id);
        
            // Execute the query
            $stmt->execute();
            
            // Get the result of the query
            $result = $stmt->get_result();
            
            // Check if any records exist
            if ($result->num_rows > 0) {
                // Fetch all records into an associative array
                $records = [];
                while ($row = $result->fetch_assoc()) {
                    $records[] = $row;
                }
                return $records; // Return all records
            } else {
                return false; // No records found
            }
        
            // Close the prepared statement
            $stmt->close();
        }
        
        //send adjustment braces
        public function send_adjustment_notifications() {
            // Set up the date interval for adjustment (e.g., 30 days after completed_at)
            $adjustment_interval = 30; // Days
            $notification_days = [2, 3]; // Notify 2 and 3 days before adjustment
        
            // Query to find relevant appointments
            $query = "
                SELECT 
                    a.id AS appointment_id, 
                    a.patient_member_id,
                    a.completed_at,
                    ds.sub_category,
                    p.first_name, 
                    p.last_name, 
                    p.email
                FROM 
                    appointments AS a
                INNER JOIN 
                    dental_services AS ds 
                ON 
                    a.services = ds.id
                INNER JOIN 
                    patients AS p 
                ON 
                    a.patient_member_id = p.id
                WHERE 
                    ds.sub_category LIKE '%braces%'
                    AND a.status = 'Completed'
                    AND a.completed_at IS NOT NULL
            ";
        
            $result = $this->db->query($query);
        
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $appointment_id = $row['appointment_id'];
                    $patient_id = $row['patient_member_id'];
                    $completed_at = $row['completed_at'];
                    $patient_name = $row['first_name'] . ' ' . $row['last_name'];
                    $patient_email = $row['email'];
        
                    // Calculate the adjustment date
                    $adjustment_date = date('Y-m-d', strtotime($completed_at . " +{$adjustment_interval} days"));
        
                    foreach ($notification_days as $days_before) {
                        $notification_date = date('Y-m-d', strtotime($adjustment_date . " -{$days_before} days"));
                        $current_date = date('Y-m-d');
        
                        // Check if the notification date matches today's date
                        if ($notification_date === $current_date) {
                            // Send notification
                            $this->send_notification($patient_id, $patient_name, $appointment_id, $adjustment_date, $patient_email);
                        }
                    }
                }
            }
        }
        
        private function send_notification($patient_id, $patient_name, $appointment_id, $adjustment_date, $patient_email) {
            $subject = "Upcoming Adjustment Reminder";
            $message = "
                <p>Dear {$patient_name},</p>
                <p>This is a friendly reminder that your next braces adjustment is scheduled to occur on <strong>{$adjustment_date}</strong>.</p>
                <p>We encourage you to book your appointment now to avoid delays.</p>
                <p><a href='https://yourclinicwebsite.com/book'>Click here to book an appointment</a>.</p>
                <p>Thank you!</p>
                <p>Your Dental Clinic Team</p>
            ";
        
            // Send email
            mail($patient_email, $subject, $message, "Content-Type: text/html");
        
            // Log the notification in the database
            $stmt = $this->db->prepare("INSERT INTO notifications (patient_id, appointment_id, message, sent_at) VALUES (?, ?, ?, ?)");
            $sent_at = date('Y-m-d H:i:s');
            $stmt->bind_param("iiss", $patient_id, $appointment_id, $message, $sent_at);
            $stmt->execute();
        }

        public function get_all_appointment_per_date($start_date, $end_date) {
            // SQL query to fetch appointments within the date range
            $query = "SELECT 
                        appointments.id as appointment_id,
                        patients.member_id as patient_id, 
                        CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name,
                        appointments.appointment_date, 
                        appointments.appointment_time, 
                        CONCAT(doctors.first_name, ' ', doctors.last_name) AS doctor_name,
                        appointments.status as appointment_status,
                        dental_services.sub_category as service_name
                      FROM appointments 
                      LEFT JOIN patients ON patients.patient_id = appointments.patient_id
                      LEFT JOIN doctors ON doctors.account_id = patients.assigned_doctor
                      LEFT JOIN dental_services ON dental_services.id = appointments.services
                      WHERE appointment_date BETWEEN ? AND ?";
            
            // Database connection and prepared statement
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ss", $start_date, $end_date); // Bind start_date and end_date
            $stmt->execute();
            $result = $stmt->get_result();
        
            return $result; // Return the result set
        }
        
        public function get_all_dental_records_per_date($start_date, $end_date) {
            // SQL query to fetch dental records within the date range
            $query = "SELECT 
                        patients.member_id as patient_id,
                        CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name,
                        dental_records.date,
                        dental_records.tooth_no,
                        dental_records.procedure,
                        dental_records.dentist,
                        dental_records.amount_charged,
                        dental_records.amount_paid,
                        dental_records.balance,
                        dental_records.next_appointment
                      FROM dental_records 
                      LEFT JOIN patients ON patients.patient_id = dental_records.patient_id
                      WHERE created_at BETWEEN ? AND ?";
            
            // Database connection and prepared statement
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ss", $start_date, $end_date); // Bind start_date and end_date
            $stmt->execute();
            $result = $stmt->get_result();
        
            return $result; // Return the result set
        }
        
        public function get_all_proof_of_payment_per_date($start_date, $end_date) {
            // SQL query to fetch payment proofs within the date range
            $query = "SELECT 
                        patients.member_id as patient_id, 
                        CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name,
                        dental_services.sub_category as service_name,
                        proof_of_payment.status as payment_status,
                        proof_of_payment.remarks,
                        proof_of_payment.uploaded_at as payment_date
                      FROM proof_of_payment 
                      LEFT JOIN appointments ON appointments.id = proof_of_payment.appointment_id
                      LEFT JOIN patients ON patients.patient_id = appointments.patient_id
                      LEFT JOIN dental_services ON dental_services.id = appointments.services
                      WHERE uploaded_at BETWEEN ? AND ?";
            
            // Database connection and prepared statement
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ss", $start_date, $end_date); // Bind start_date and end_date
            $stmt->execute();
            $result = $stmt->get_result();
        
            return $result; // Return the result set
        }
        
	}
?>