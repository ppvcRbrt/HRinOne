<?php
require_once('Models/CandidateInfoQueries.php');
require_once('Models/FeedbackGenerator.php');
require_once('Models/AssessmentInfoQueries.php');
require_once("Models/UnsetAll.php");
//to be split into 3 pages
if(session_status() !== 2)
{
    session_start();
}

$view = new stdClass();
$candQuery = new CandidateInfoQueries();

if(!empty($_COOKIE["candName"]))
{
    $currentPageNav = "searchResults1";
    setcookie("currentPageNav", $currentPageNav);
    $unset = new UnsetAll();
    $unset->unsetEverything($currentPageNav);

    $candID = $candQuery->getCandIDByName($_COOKIE["candName"]);
    $view->candID = $candID;
}
if(!empty($_COOKIE["candNameAssessor"]))
{
    $currentPageNav = "searchResults2";
    setcookie("currentPageNav", $currentPageNav);
    $unset = new UnsetAll();
    $unset->unsetEverything($currentPageNav);

    $candID = $candQuery->getCandIDByName($_COOKIE["candNameAssessor"]);
    $view->candIDAssessor = $candID;
}
if(!empty($_COOKIE["candNameReport"]))
{
    $currentPageNav = "searchResults3";
    setcookie("currentPageNav", $currentPageNav);
    $unset = new UnsetAll();
    $unset->unsetEverything($currentPageNav);

    $candID = $candQuery->getCandIDByName($_COOKIE["candNameReport"]);
    $view->candIDReport = $candID;
}

require("Views/searchResults.phtml");