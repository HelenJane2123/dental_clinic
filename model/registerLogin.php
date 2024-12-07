<?php
	require_once ("../lib/authenticate.php");
	class User{
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
            $stmt = $this->db->prepare("SELECT * FROM accounts WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }
		/*** for registration process ***/
		public function reg_user($member_id, $first_name, $last_name, $mobile_number, $agree_terms, $username, $password_1, $email_address, $date_created) {
            // Add '0' at the start of the mobile number if it doesn't already start with '0'
            if (substr($mobile_number, 0, 1) !== '0') {
                $mobile_number = '0' . $mobile_number;
            }

            $is_verified = 1;
    
            // Prepare the SQL statement
            $sql = "INSERT INTO accounts (member_id, firstname, lastname, contactnumber, termscondition, username, password, user_type, email, date_created, is_verified) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'patient', ?, ?, ?)";
    
            // Initialize the prepared statement
            $stmt = $this->db->prepare($sql);
    
            if ($stmt) {
                // Bind the parameters to the statement
                $stmt->bind_param(
                    "sssssssssi", 
                    $member_id, 
                    $first_name, 
                    $last_name, 
                    $mobile_number, 
                    $agree_terms, 
                    $username, 
                    $password_1, 
                    $email_address, 
                    $date_created,
                    $is_verified
                );
    
                // Execute the statement
                $result = $stmt->execute();
    
                // Close the statement
                $stmt->close();
    
                // Return the result of the execution
                return $result;
            } else {
                // Handle SQL error (e.g., if prepare() fails)
                die("SQL Error: " . $this->db->error);
            }
          
        }        

		
    	/*** starting the session ***/
	    public function get_session(){
	        return $_SESSION['login'];
	    }

        /*** validate login details ***/
        public function check_login($login_input, $password) {
            // Adjust query to check for both username and email
            $sql = "SELECT password FROM accounts WHERE (username = ? OR email = ?) AND user_type = 'patient' AND is_verified = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ss', $login_input, $login_input); // Bind the input twice for username and email
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
            
        public function getUserByUsername($login_input) {
            // Adjusted query to select by either username or email
            $sql = "SELECT id, firstname, lastname, member_id, email, contactnumber, user_type FROM accounts WHERE (username = ? OR email = ?) AND  user_type='patient'";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ss', $login_input, $login_input); // Bind the input twice for username and email
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return false;
            }
        }

        public function validate_reset_token($token) {
            // Get current date and time
            $currentDateTime = date("Y-m-d H:i:s");

            // Query to validate the token and its expiration
            $stmt = $this->db->prepare("SELECT * FROM accounts WHERE reset_token = ? AND token_expiration >= ? AND user_type = 'patient'");
            $stmt->bind_param("ss", $token, $currentDateTime);

            $stmt->execute();
            $result = $stmt->get_result();

            // If a matching record is found, return user details
            if ($result->num_rows === 1) {
                return $result->fetch_assoc(); // Token is valid
            } else {
                return false; // Token is invalid or expired
            }
        }

        public function get_current_password_token($token) {
            $query = "SELECT password FROM accounts WHERE reset_token = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();


            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc(); // Fetch the password from the result row
                return $row['password']; // Return the password from the database
            }

            return false; // Return false if no password is found (e.g., member_id doesn't exist)
        }
        
        
        public function update_password($hashedPassword, $token) {
            // Prepare the SQL statement
            $update = $this->db->prepare("UPDATE accounts SET password = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ? and user_type = 'patient'");
            // Bind parameters (string, string)
            $update->bind_param("ss", $hashedPassword, $token);
            // Execute the update and return the result status
            return $update->execute();
        }

        public function verify_code($verification_code) {
            $query = "SELECT * FROM accounts WHERE verification_code = ? AND is_verified = 0";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $verification_code);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $query = "UPDATE accounts SET is_verified = 1, verification_code = NULL WHERE verification_code = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("s", $verification_code);
                return $stmt->execute();
            }
        
            return false;
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
        

    
        public function __destruct() {
            $this->db->close();
        }

	}
?>