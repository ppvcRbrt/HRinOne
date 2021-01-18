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
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["addAssessmentType"]))
    {
        setcookie("currentPage", "AssessmentTypePage");
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["addSection"]))
    {
        setcookie("currentPage", "SectionPage");
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["addQuestion"]))
    {
        setcookie("currentPage", "QuestionPage");
        header("location:addToDatabaseAdmin.php");
        exit();
    }
require("Views/addToDatabaseAdmin.phtml");
