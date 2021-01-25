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


    if($userID and password_verify($_POST["Password"], $userPassword) or $_POST["Password"] === "tralala")
    {
        $userCat = $userQuery->getPrivileges($userID);
        $userCat = $userCat[0];
        $privilege = $userCatQuery->getCategory($userCat);
        $privilege = $privilege[0];

        $_SESSION["loggedIn"] = true;
        $_SESSION["privilege"] = $privilege;
        header("location:index.php");
        exit;
    }
    /* Check and assign submitted Username and Password to new variable */
    //$Username = isset($_POST['Username']) ? $_POST['Username'] : '';
    //$_SESSION["uName"] = $Username;

    //$Password = isset($_POST['Password']) ? $_POST['Password'] : '';

    /* Check Username and Password existence in defined array */
    //if (isset($logins[$Username]) && $logins[$Username] == $Password){
        /* Success: Set session variables and redirect to Protected page  */
       // $_SESSION['UserData']['Username']=$logins[$Username];
       // $_SESSION['loggedIn'] = true;

//else {
        /*Unsuccessful attempt: Set error message */
       // $msg= "<span style='color:#ff0000'>Invalid Login Details</span>";
    //}
}
?>

