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
		public function reg_user($member_id, $first_name, $last_name, $mobile_number, $agree_terms, $username, $password_1, $email_address, $date_created){
			$password = md5($password_1);
            $check =  $this->isUserExist($email_address);
			$check_username =  $this->isUsername($username);
            if (!$check || !$check_username){
                $sql1="INSERT INTO accounts SET member_id='$member_id', 
                            firstname='$first_name', 
                            lastname='$last_name', 
                            contactnumber='$mobile_number',
                            termscondition='$agree_terms',
							username = '$username',
                            password='$password_1',
                            user_type='patient',
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
            $sql = "SELECT password FROM accounts WHERE username = ? and user_type = 'patient'";
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

	}
?>