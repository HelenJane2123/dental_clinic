<?php
// Database connection settings
$host = 'localhost';          // Database server, usually 'localhost' if running on your local machine
$username = 'root';           // MySQL username (default is 'root' for XAMPP, may vary on production)
$password = '';               // MySQL password (default is '' for XAMPP, may vary on production)
$database = 'dental_clinic';  // Name of your database

// Create a new MySQLi connection
$db = new mysqli($host, $username, $password, $database);

// Check for any connection errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Optionally, set the charset for the connection
$db->set_charset("utf8");
?>
