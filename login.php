<?php
require_once("Models/UserQueries.php");
require_once("Models/UserCategoryQueries.php");

if(session_status() !== 2)
{
    session_start();
}

$_SESSION['loggedIn'] = false;
$userQuery = new UserQueries();
$userCatQuery = new UserCategoryQueries();

/**
 * if the user clicked on the "Login" button
 */
if(isset($_POST['Submit'])){
    $user = $userQuery->getUserID($_POST["Username"]);
    $userID = $user[0];

    $password = $userQuery->getUserPassword($userID);
    $userPassword = $password[0];
    /**
     * if we have a captcha response then we can continue with the login
     */
    if(isset($_POST["g-recaptcha-response"]))
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify'; //url of google's verification site
        $data = array(
            'secret' => '6LcIHz4aAAAAACPlquJbI81-v0dxGItOsOwW0Asq', //secret key -> this will be needed to be edited for live deployment
            'response' => $_POST["g-recaptcha-response"] //the posted response
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

        /**
         * if the user failed the captcha send them back to the index page
         */
        if ($captcha_success->success==false) {
            setcookie("isPassword", "false");
            header("location:index.php");
            exit;
        }
        /**
         * if the user passed the captcha continue with the login as normally
         */
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


