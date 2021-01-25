<?php
/* Starts the session */
session_start();
/* Check Login form submitted */
$_SESSION['loggedIn'] = false;

if(isset($_POST['Submit'])){
    /* Define username and associated password array */
    $logins = array('1' => '1','username1' => 'password1','username2' => 'password2');

    /* Check and assign submitted Username and Password to new variable */
    $Username = isset($_POST['Username']) ? $_POST['Username'] : '';
    $_SESSION["uName"] = $Username;

    $Password = isset($_POST['Password']) ? $_POST['Password'] : '';

    /* Check Username and Password existence in defined array */
    if (isset($logins[$Username]) && $logins[$Username] == $Password){
        /* Success: Set session variables and redirect to Protected page  */
        $_SESSION['UserData']['Username']=$logins[$Username];
        $_SESSION['loggedIn'] = true;
        header("location:mainPage.php");
        exit;
    } else {
        /*Unsuccessful attempt: Set error message */
        $msg= "<span style='color:#ff0000'>Invalid Login Details</span>";
    }
}
?>

