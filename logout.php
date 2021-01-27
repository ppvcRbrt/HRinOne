<?php
/* Starts the session */
if(session_status() !== 2)
{
    session_start();
}
/* Destroy started session */
session_destroy();
/* Redirect to login page */
header("location:index.php");
exit;
?>