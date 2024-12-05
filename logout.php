<?php
session_start();
session_destroy();
header("Location: home.php"); // Redirect back to the home page
exit();
?>
