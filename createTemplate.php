<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');
require_once('Models/CandidateInfoQueries.php');
require_once('Models/AssessmentQueries.php');

session_start();

$view = new stdClass();
$candQueries = new CandidateInfoQueries();
$assessmentTypeQueries = new AssessmentTypeQueries();
$sectionQueries = new SectionQueries();
$domainQueries = new DomainQueries();
$questionQueries = new QuestionQueries();
$assessmentQuery = new AssessmentQueries();

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
    $domainID = $domainQueries->GetDomainID($view->candDom[0]);
    setcookie("domainID", $domainID[0]);
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
if(isset($_COOKIE["maxSections"]))
{
    $maxSections = $_COOKIE["maxSections"];
    $_SESSION["sectionNames"] = array();

    for($x =0; $x<$maxSections; $x++)
    {
        array_push($_SESSION["sectionNames"],"sectionChoose".$x);
    }
    if(!empty($_COOKIE["maxSections"]))
    {
        $assessmentTypeID = $assessmentTypeQueries->GetAssessmentTypeID($_COOKIE["assessmentType"]);
        setcookie("assessmentTypeID",$assessmentTypeID[0]);
        $view->sections = $sectionQueries->getSectionsByAssessmentTypeID((int)$assessmentTypeID[0],$_COOKIE["domainID"]);
    }
}
if(isset($_POST["sectionSubmit"]))
{
    $maxSectsForQuestionInput = $_COOKIE["maxSections"];
    $x = 0;
    $_SESSION["sectionHeader"] = array();
    foreach($_SESSION["sectionNames"] as $currentSectName)
    {
        $_SESSION["questions".$x] = array();
        if(isset($_POST[$currentSectName]))
        {
            array_push($_SESSION["sectionHeader"],$_POST[$currentSectName]);
            //select query goes here
            $questions = $questionQueries->GetQuestionsByInfo($_POST[$currentSectName],$_COOKIE["assessmentType"],$_COOKIE["domain"]);
            foreach($questions as $currentQuestion)
            {
                $name = $currentQuestion->getQuestion();
                array_push($_SESSION["questions".$x], $name);
            }
            $x++;
        }
        if($x == $maxSectsForQuestionInput)
        {
            header("location:createTemplate.php");
            exit();
        }
    }
}
if(isset($_POST["maxQperSectionSubmit"]))
{
    $maximumSections = (int)$_COOKIE["maxSections"];
    $currentCount = 0;
    for($x = 0; $x < (int)$_COOKIE["maxSections"];$x++)
    {
        $_SESSION["maxQperSect".$x] = array();
        if(isset($_POST["maxQuestionsPerSect".$x]))
        {
            array_push($_SESSION["maxQperSect".$x], $_POST["maxQuestionsPerSect".$x]);
        }
        $currentCount++;
    }
    if($currentCount == $maximumSections)
    {
        header("location:createTemplate.php");
        exit();
    }
}
if(isset($_POST["done"]))
{
    $assessmentQuery->InsertAssessment("","",(int)$_COOKIE["candidateID"],(int)$_COOKIE["assessmentTypeID"]);
    setcookie("candName","");
    setcookie("domain","");
    setcookie("candidateID","");
    setcookie("domainID","");
    setcookie("assessmentType","");
    setcookie("assessmentTypeID","");
    for($x = 0; $x < (int)$_COOKIE["maxSections"];$x++)
    {
        unset($_SESSION["maxQperSect".$x]);
        unset($_SESSION['questions'.$x]);
    }
    setcookie("maxSections","");
    unset($_SESSION['sectionNames']);
    unset($_SESSION['sectionHeader']);

    header("location:createTemplate.php");
    exit();
}

require("Views/createTemplate.phtml");