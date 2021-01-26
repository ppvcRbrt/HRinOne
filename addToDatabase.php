<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
session_start();

$view = new stdClass();

$domainQuery = new DomainQueries();
$asType = new AssessmentTypeQueries();

$view->dom = $domainQuery->getAll();
$view->assess = $asType->getAll();

if(isset($_POST["submit"]))
{
    if(isset($_POST["workDomToAdd"]))
    {
        $domainQuery->InsertDomain($_POST["workDomToAdd"]);
        header("location:addToDatabase.php");
    }
    if(isset($_POST["workDomName"]))
    {
        setcookie("workDomName", $_POST["workDomName"]);
        $domainQuery->GetDomainID($_POST["workDomName"]);
    }
    if(isset($_POST["assTypeToAdd"]))
    {
        //$asType->InsertAssessmentType($_POST["assTypeToAdd"], $_POST["assTypeDesc"], );
        header("location:addToDatabase.php");
    }
    if(isset($_POST["section"]))
    {
        setcookie("section", $_POST["section"]);
    }
}

require_once("Views/addToDatabase.phtml");