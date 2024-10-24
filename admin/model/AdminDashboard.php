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
            $sql = "SELECT firstname, lastname, member_id, email, contactnumber FROM accounts WHERE username = ?";
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

        public function get_notifications() {
            // Query to select all notifications
            $query = "SELECT * FROM notifications  ORDER BY created_at DESC LIMIT 2";
            
            if ($stmt = $this->db->prepare($query)) {
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

        public function get_all_notifications() {
            // SQL query to get patient details along with member ID, first name, and last name
                $query = "SELECT p.*, a.member_id, a.first_name AS first_name, a.last_name AS last_name, a.patient_id
                FROM notifications p
                LEFT JOIN patients a ON p.user_id = a.patient_id";

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


	}
?>