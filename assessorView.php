<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');
require_once('Models/CandidateInfoQueries.php');
require_once('Models/AssessmentQueries.php');

$view = new stdClass();
$assessmentQuery = new AssessmentQueries();
$assessmentTypeQuery = new AssessmentTypeQueries();

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
        setcookie("selectedAssessmentType", $_POST["assessmentTypes"]);
        header("location:assessorView.php");
        exit();
    }
}


require('Views/assessorView.phtml');