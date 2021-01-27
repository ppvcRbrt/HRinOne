<?php
require_once("Models/UserQueries.php");
require_once("Models/UserCategoryQueries.php");

/* Starts the session */
session_start();
/* Check Login form submitted */
$_SESSION['loggedIn'] = false;
$userQuery = new UserQueries();
$userCatQuery = new UserCategoryQueries();

if(isset($_POST['Submit'])){
    /* Define username and associated password array */

    //$logins = array('1' => '1','username1' => 'password1','username2' => 'password2');
    $user = $userQuery->getUserID($_POST["Username"]);
    $userID = $user[0];

    $password = $userQuery->getUserPassword($userID);
    $userPassword = $password[0];


    if($userID and password_verify($_POST["Password"], $userPassword))
    {
        $userCat = $userQuery->getPrivileges($userID);
        $userCat = $userCat[0];
        $privilege = $userCatQuery->getCategory($userCat);
        $privilege = $privilege[0];

        $_SESSION["loggedIn"] = true;
        $_SESSION["privilege"] = $privilege;
        setcookie("isPassword", true);
        header("location:index.php");
        exit;
    }
    else
    {
        setcookie("isPassword", "false");
        header("location:index.php");
        exit;
    }
}


