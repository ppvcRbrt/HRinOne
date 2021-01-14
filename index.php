<?php
/* Starts the session */
session_start();
if(!isset($_SESSION['UserData']['Username'])){
    require_once("login.php");
    require_once("Views/login.phtml");
}
else
{
    require_once("Views/demolink.phtml");
}
?>