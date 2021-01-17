<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
session_start();

$view = new stdClass();

$domainQuery = new DomainQueries();
$asType = new AssessmentTypeQueries();
$sectQuery = new SectionQueries();

$view->dom = $domainQuery->getAll();
$view->assess = $asType->getAll();
$view->sections = $sectQuery->getAll();

if(isset($_POST["submit"]))
{
    if(!empty($_POST["workDomName"]))
    {
        setcookie("workDomName", $_POST["workDomName"]);
        $domID = $domainQuery->GetDomainID($_POST["workDomName"]);
        setcookie("workDomID", $domID[0]);
    }
    else if(!empty($_POST["workDomToAdd"]))
    {
        $domainQuery->InsertDomain($_POST["workDomToAdd"]);
    }

    if(!empty($_POST["assessmentType"]))
    {
        setcookie("assessmentTypeName", $_POST["assessmentType"]);
    }
    else if(!empty($_POST["assTypeToAdd"]))
    {
        $asType->InsertAssessmentType($_POST["assTypeToAdd"], $_POST["assTypeDesc"], $_COOKIE["workID"]);
        setcookie("assessmentTypeName", $_POST["assTypeToAdd"]);
        setcookie("assessmentTypeDesc", $_POST["assTypeDesc"]);
        header("location:addToDatabase.php");
    }

    if(!empty($_POST["section"]))
    {
        setcookie("section", $_POST["section"]);
    }
    header("location:addToDatabase.php");
}

require_once("Views/addToDatabase.phtml");