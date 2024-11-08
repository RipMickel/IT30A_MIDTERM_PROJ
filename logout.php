<?php
session_start(); // Start the session

// Destroy all session data
session_destroy();

// Optionally, clear session variables
$_SESSION = [];

// Redirect to the login page (or another page)
header("Location: index.php"); // Change to your landing page
exit;
?>
