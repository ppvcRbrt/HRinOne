<?php
require_once("Models/UserQueries.php");
require_once("Models/UserCategoryQueries.php");

session_start();

$userQuery = new UserQueries();
$userCatQuery = new UserCategoryQueries();
$view = new stdClass();

if(isset($_SESSION["loggedIn"]) and isset($_SESSION["privilege"]))
{
    if($_SESSION["loggedIn"] === true and $_SESSION["privilege"] === "admin")
    {
        if(isset($_POST["addUser"]))
        {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //here we will hash the password the user's inputted
            $userQuery->insertUser($_POST["userName"], $password, "genericEmail", $_POST["category"]);
            setcookie("searching", "");
            header("location:adminUserPage.php");
            exit();
        }
        if(isset($_POST["searchUser"]))
        {
            if(!empty($_POST["userName"]))
            {
                $userNames = $userQuery->searchForUserName($_POST["userName"]);
                $_SESSION["userNames"] = $userNames;
                $categories = array();
                foreach($userNames as $currentUser)
                {
                    $categoriesID = $currentUser->getUserCategoryID();
                    $categoryName = $userCatQuery->getCategory($categoriesID);
                    array_push($categories ,$categoryName[0]);
                }
                $_SESSION["categories"] = $categories;
                setcookie("searching", "true");
                header("location:adminUserPage.php");
                exit();
            }

        }
        if(isset($_POST["deleteUser"]))
        {
            $userQuery->deleteUser($_POST["deleteUser"]);
            setcookie("searching", "false");
            header("location:adminUserPage.php");
            exit();
        }
        require_once("Views/adminUserPage.phtml");

    }
    else
    {
        echo "<span>You're not logged in or you dont have the privilege required to access this information, sorry</span>";
    }

}
else
{
    echo "<span>You're not logged in or you dont have the privilege required to access this information, sorry</span>";
}
