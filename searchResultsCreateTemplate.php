<?php
require_once('Models/CandidateInfoQueries.php');
require_once('Models/FeedbackGenerator.php');
require_once('Models/AssessmentInfoQueries.php');
require_once("Models/UnsetAll.php");

if(session_status() !== 2)
{
    session_start();
}

$view = new stdClass();
$candQuery = new CandidateInfoQueries();

$currentPageNav = "createTemplate";
setcookie("currentPageNav", $currentPageNav);
$unset = new UnsetAll();
$unset->unsetEverything($currentPageNav);

/**
 * This page will show the search results of candidates from the "createTemplate" page
 */

if(isset($_SESSION["loggedIn"]) and isset($_SESSION["privilege"]))
{
    if ($_SESSION["loggedIn"] === true and $_SESSION["privilege"] === "admin")
    {
        if(!empty($_COOKIE["candName"]))
        {
            $candID = $candQuery->getCandIDByName($_COOKIE["candName"]);
            $view->candID = $candID;
        }
        require_once("Views/searchResultsCreateTemplate.phtml");
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