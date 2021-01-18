<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');

session_start();

$view = new stdClass();

$domainQuery = new DomainQueries();
$asType = new AssessmentTypeQueries();
$sectQuery = new SectionQueries();
$quesQuery = new QuestionQueries();

$view->dom = $domainQuery->getAll();
$view->assess = $asType->getAll();
$view->sections = $sectQuery->getAll();
$view->questions = $quesQuery->getAll();


    if(isset($_POST["addWorkDom"]))
    {
        setcookie("currentPage", "WorkDomPage");
        setcookie("sectionAdded", "false");
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["addAssessmentType"]))
    {
        setcookie("sectionAdded", "false");
        setcookie("currentPage", "AssessmentTypePage");
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["addSection"]))
    {
        setcookie("sectionAdded", "true");
        setcookie("currentPage", "SectionPage");
        header("location:addToDatabaseAdmin.php");

        exit();
    }
    else
    {
        if(isset($_COOKIE["workDomSelected"]) and isset($_COOKIE["assessmentTypeSelected"]) and strcmp($_COOKIE["sectionAdded"], "false") == 0)
        {
            setcookie("workDomSelected", "");
            setcookie("assessmentTypeSelected", "");
            header("location:addToDatabaseAdmin.php");
            //unset($_COOKIE["assessmentTypeSelected"]);
        }
    }
    if(isset($_POST["addQuestion"]))
    {
        setcookie("currentPage", "QuestionPage");
        setcookie("sectionAdded", "false");
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["selectWorkDomain"]))
    {
        setcookie("sectionAdded", "true");
        setcookie("currentPage", "SectionPage");
        setcookie("workDomSelected", $_POST["workDomNameSect"]);
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["selectAssessmentType"]))
    {
        setcookie("sectionAdded", "true");
        setcookie("currentPage", "SectionPage");
        setcookie("assessmentTypeSelected", $_POST["assessmentTypeNameSect"]);
        header("location:addToDatabaseAdmin.php");
        exit();
    }
require("Views/addToDatabaseAdmin.phtml");
