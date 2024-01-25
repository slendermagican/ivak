<!-- logout.php -->

<?php
// Start the session
session_start();

// Clear all session data
session_unset();
session_destroy();

// Redirect the user to a specific page (e.g., home page)
header("Location: index.php");
exit();
?>
