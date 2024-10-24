<?php
    session_start();
    session_destroy(); // Destroys all sessions
    header('Location: index.php'); // Redirect to login page after logout
    exit();
?>