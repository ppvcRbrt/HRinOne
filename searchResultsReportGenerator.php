<?php
require_once('Models/CandidateInfoQueries.php');
require_once('Models/FeedbackGenerator.php');
require_once('Models/AssessmentInfoQueries.php');
require_once("Models/UnsetAll.php");
require_once("Models/Mailer.php");

if(session_status() !== 2)
{
    session_start();
}

$view = new stdClass();
$candQuery = new CandidateInfoQueries();
$mailer = new Mailer();

$currentPageNav = "createReport";
setcookie("currentPageNav", $currentPageNav);
$unset = new UnsetAll();
$unset->unsetEverything($currentPageNav);

if(isset($_SESSION["loggedIn"]) and isset($_SESSION["privilege"]))
{
    if ($_SESSION["loggedIn"] === true and $_SESSION["privilege"] === "admin")
    {
        if(!empty($_COOKIE["candNameReport"]))
        {
            $candID = $candQuery->getCandIDByName($_COOKIE["candNameReport"]);
            $view->candIDReport = $candID;
        }
        if(isset($_POST["sendEmail"]))
        {
            $mailer->mailCandidate((int)$_POST["sendEmail"]);
        }
        require_once("Views/searchResultsReportGenerator.phtml");
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