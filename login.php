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
    if(isset($_POST["g-recaptcha-response"]))
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => '6LcIHz4aAAAAACPlquJbI81-v0dxGItOsOwW0Asq',
            'response' => $_POST["g-recaptcha-response"]
        );
        $options = array(
            'http' => array (
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success=json_decode($verify);

        if ($captcha_success->success==false) {
            setcookie("isPassword", "false");
            header("location:index.php");
            exit;
        }
        if ($captcha_success->success==true) {
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
    }

}


