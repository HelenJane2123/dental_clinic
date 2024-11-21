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
		public function reg_user($member_id, $first_name, $last_name, $mobile_number, $agree_terms, $username, $password_1, $email_address, $date_created, $verification_code) {
            $password = md5($password_1);
            // Add '0' at the start of the mobile number if it doesn't already start with '0'
            if (substr($mobile_number, 0, 1) !== '0') {
                $mobile_number = '0' . $mobile_number;
            }
    
            // Prepare the SQL statement
            $sql = "INSERT INTO accounts (member_id, firstname, lastname, contactnumber, termscondition, username, password, user_type, email, date_created, verification_code) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'patient', ?, ?, ?)";
    
            // Initialize the prepared statement
            $stmt = $this->db->prepare($sql);
    
            if ($stmt) {
                // Bind the parameters to the statement
                $stmt->bind_param(
                    "ssssssssss", 
                    $member_id, 
                    $first_name, 
                    $last_name, 
                    $mobile_number, 
                    $agree_terms, 
                    $username, 
                    $password, 
                    $email_address, 
                    $date_created, 
                    $verification_code
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
            $sql = "SELECT id, firstname, lastname, member_id, email, contactnumber FROM accounts WHERE (username = ? OR email = ?)";
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

        public function reset_password($token) {
            // Prepare the SQL statement to fetch user by token
            $query = $this->db->prepare("SELECT * FROM accounts WHERE reset_token = ?");
            // Bind the token as a parameter
            $query->bind_param("s", $token);
            // Execute the query
            $query->execute();
            // Get the result
            $result = $query->get_result();
            // Check if a row exists and return it, or return false if no match
            return $result->fetch_assoc() ?: false;
        }
        
        
        public function update_password($hashedPassword, $token) {
            // Prepare the SQL statement
            $update = $this->db->prepare("UPDATE accounts SET password = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ? and user_type='patient'");
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