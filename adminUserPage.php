<?php
require_once("Models/UserQueries.php");
require_once("Models/UserCategoryQueries.php");
require_once("Models/UnsetAll.php");

if(session_status() !== 2)
{
    session_start();
}

$userQuery = new UserQueries();
$userCatQuery = new UserCategoryQueries();
$view = new stdClass();

$currentPageNav = "adminUserPage";
setcookie("currentPageNav", $currentPageNav);
$unset = new UnsetAll();
$unset->unsetEverything($currentPageNav);

if(isset($_SESSION["loggedIn"]) and isset($_SESSION["privilege"]))
{
    if($_SESSION["loggedIn"] === true and $_SESSION["privilege"] === "admin")
    {
        /**
         * If the user clicked on the add user button, then we will hash their password and insert into the database with post values
         */
        if(isset($_POST["addUser"]))
        {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //here we will hash the password the user's inputted
            $userQuery->insertUser($_POST["userName"], $password, "genericEmail", $_POST["category"]);
            setcookie("searching", "");
            header("location:adminUserPage.php");
            exit();
        }

        /**
         * If the user clicked on "search" button then we will query the db for that username and return
         * a $_SESSION array of user names and user categories
         */
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

        /**
         * If the user clicked on the "DELETE" button then we delete that user from the db
         */
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
        echo "<span>You're not logged in or you dont have the privilege required to access this information, sorry.<a href = 'index.php'>Go Back to home page</a></span>";
    }

}
else
{
    echo "<span>You're not logged in or you dont have the privilege required to access this information, sorry.<a href = 'index.php'>Go Back to home page</a></span>";
}
