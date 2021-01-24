<?php
require_once('Models/CandidateInfoQueries.php');
require_once('Models/FeedbackGenerator.php');
require_once('Models/AssessmentInfoQueries.php');

session_start();

$view = new stdClass();
$candQuery = new CandidateInfoQueries();


if(!empty($_COOKIE["candName"]))
{
    $candID = $candQuery->getCandIDByName($_COOKIE["candName"]);
    $view->candID = $candID;
}
if(!empty($_COOKIE["candNameAssessor"]))
{
    $candID = $candQuery->getCandIDByName($_COOKIE["candNameAssessor"]);
    $view->candIDAssessor = $candID;
}
if(!empty($_COOKIE["candNameReport"]))
{
    $candID = $candQuery->getCandIDByName($_COOKIE["candNameReport"]);
    $view->candIDReport = $candID;
}

require("Views/searchResults.phtml");