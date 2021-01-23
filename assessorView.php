<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');
require_once('Models/CandidateInfoQueries.php');
require_once('Models/AssessmentQueries.php');
require_once('Models/AssessmentInfoQueries.php');
require_once('Models/IndicatorsQueries.php');
require_once("Models/assessorViewFunctions.php");

session_start();

$view = new stdClass();
$assessmentQuery = new AssessmentQueries();
$assessmentTypeQuery = new AssessmentTypeQueries();
$assessmentInfoQuery = new AssessmentInfoQueries();
$sectionQuery = new SectionQueries();
$view->templates = $assessmentQuery->getAll();

if(isset($_POST["search"]))
{
    if(isset($_POST["candNameAssessor"]))
    {
        setcookie("candNameAssessor", $_POST["candNameAssessor"]);
        header("location:searchResults.php");
        exit();
    }
}
if(isset($_GET['candID']))
{
    $candID = $_GET['candID'];
    setcookie("candidateID", $candID);
    $_SESSION["assessmentTypeNames"] = array();
    $ids = $assessmentQuery->getAssessmentTypeIDForCandidate((int)$candID);
    $x = 0;
    foreach($ids as $currentAssessmentTypeID)
    {
        $id = $currentAssessmentTypeID->getAssessmentTypeID();
        $assessmentTypeNames = $assessmentTypeQuery->GetAssessmentTypeName((int)$id[0]);
        array_push($_SESSION["assessmentTypeNames"],$assessmentTypeNames[0]);
        $x++;
    }
    //header("location:assessorView.php");
    //exit();
}
if(isset($_POST["selectedAssessmentType"]))
{
    if(!empty($_POST["assessmentTypes"]))
    {
        if(isset($_COOKIE["candidateID"]))
        {
            if(!empty($_COOKIE["candidateID"]))
            {
                $_SESSION['sectionIDs'] = array();
                $_SESSION['questionIDs'] = array('qID'=>array(),'secID'=>array());
                $_SESSION['indicatorIDs'] = array('indID'=>array(), 'qID'=>array());

                $sectionIDs = array();
                $questionIDs = array('qID'=>array(),'secID'=>array());
                $indicatorIDs = array('indID'=>array(), 'qID'=>array());
                $assessmentTypeID = $assessmentTypeQuery->GetAssessmentTypeID($_POST["assessmentTypes"]);
                $assessmentInfo = $assessmentInfoQuery->getInfoByCandID((int)$_COOKIE["candidateID"],(int)$assessmentTypeID[0]);
                foreach($assessmentInfo as $currentInfo)
                {
                    $sectionID = $currentInfo->getSectionID();
                    array_push($sectionIDs, $sectionID);

                    $questionID = $currentInfo->getQuestionID();
                    array_push($questionIDs['qID'], $questionID);
                    array_push($questionIDs['secID'], $sectionID);

                    $indID = $currentInfo->getIndicatorID();
                    array_push($indicatorIDs['indID'], $indID);
                    array_push($indicatorIDs['qID'], $questionID);
                }
                $sectionIDs = array_unique($sectionIDs);
                $_SESSION['sectionIDs'] = $sectionIDs;
                $_SESSION['questionIDs'] = $questionIDs;
                $_SESSION['indicatorIDs'] = $indicatorIDs;
            }
        }
        $x = 0;
        foreach($_SESSION['sectionIDs'] as $currentSectionID)
        {
            setcookie("currentPagePerSecID", $currentSectionID);
            $x++;
            if($x == 1)
            {
                break;
            }
        }
        setcookie("selectedAssessmentType", $_POST["assessmentTypes"]);
        header("location:assessorView.php");
        exit();
    }
}
if(isset($_GET["sectionID"]))
{

    setcookie("currentPagePerSecID", $_GET["sectionID"]);
    $lastPage = end($_SESSION["sectionIDs"]);
    if($_GET["sectionID"] == $lastPage)
    {
        setcookie("letMeSubmit", "true");
    }
    else
    {
        $_COOKIE["letMeSubmit"] = "";
    }
    header("location:assessorView.php");
    exit();
}
if(isset($_POST["sectionFinished"])) {
    $assessorViewFunction = new assessorViewFunctions();
    //need to use array_search() on these to find the index of the question from the value of the section
    $allQuestions = $assessorViewFunction->getAllQuestionsWithSections();
    $allIndicators = $assessorViewFunction->getAllIndicatorsWithQuestions();
    $indicatorsID = array();
    for ($x = 0; $x < count($allQuestions); $x++)
    {
        if(isset($_POST["indicatorValueQ".$x]))
        {
            array_push($indicatorsID,(int)$_POST["indicatorValueQ".$x]);
        }
    }
    $sectionFeedback = array();
    foreach($indicatorsID as $currentID)
    {
        $indicatorQuery = new IndicatorsQueries();
        $feedback = $indicatorQuery->getIndFeedback($currentID);
        array_push($sectionFeedback, $feedback[0]);
    }
}
if(!empty($_SESSION["sectionIDs"]))
{
    foreach($_SESSION["sectionIDs"] as $currentSectionID)
    {
        if(isset($_POST[$currentSectionID]))
        {
            $getSectionName = $sectionQuery->getSectionNameByID($currentSectionID);
        }
    }
}

require('Views/assessorView.phtml');