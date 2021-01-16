<?php
/* Starts the session */
session_start();
/* Destroy started session */
session_destroy();
/* Redirect to login page */
header("location:index.php");
exit;
?>