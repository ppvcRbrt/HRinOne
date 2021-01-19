<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');
require_once('Models/CandidateInfoQueries.php');


session_start();

$view = new stdClass();
$candQueries = new CandidateInfoQueries();
$assessmentTypeQueries = new AssessmentTypeQueries();

if(isset($_POST["search"]))
{
    if(isset($_POST["candName"]))
    {
        setcookie("candName", $_POST["candName"]);
        header("location:searchResults.php");
        exit();
    }
}
if(isset($_GET['candID']))
{
    $candID = $_GET['candID'];
    $view->candName = $candQueries->getCandidateName($candID);
    $view->candDom = $candQueries->getCandidateWorkDomName($candID);
    setcookie("candidateID", $candID);
    setcookie("domain", $view->candDom[0]);
    header("location:createTemplate.php");
    exit();
}

if(isset($_COOKIE["candidateID"]))
{
    if(!empty($_COOKIE["candidateID"]))
        $view->candName = $candQueries->getCandidateName($_COOKIE["candidateID"]);
        $view->candDom = $candQueries->getCandidateWorkDomName($_COOKIE["candidateID"]);
}
if(isset($_COOKIE["domain"]))
{
    if(!empty($_COOKIE["domain"]))
        $view->assessmentTy = $assessmentTypeQueries->getAssessmentTypeByWorkDom($_COOKIE["domain"]);
}

if(isset($_POST["selectedAssessmentType"]))
{
    setcookie("assessmentType", $_POST["assessmentTypes"]);
    header("location:createTemplate.php");
    exit();
}

if(isset($_POST["maxPost"]))
{
    setcookie("maxSections", $_POST["maxSections"]);
    header("location:createTemplate.php");
    exit();
}

require("Views/createTemplate.phtml");