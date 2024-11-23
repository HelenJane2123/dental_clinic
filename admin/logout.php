<?php
session_start();
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session

// Optionally, you can also unset specific session variables
unset($_SESSION['username']);

// Redirect to the login page
header('Location: index.php');
exit();
?>