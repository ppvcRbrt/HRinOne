<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');
require_once('Models/CandidateInfoQueries.php');
require_once('Models/AssessmentQueries.php');
require_once('Models/AssessmentInfoQueries.php');

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

                $assessmentInfo = $assessmentInfoQuery->getInfoByCandID($_COOKIE["candidateID"]);
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

        setcookie("selectedAssessmentType", $_POST["assessmentTypes"]);
        header("location:assessorView.php");
        exit();
    }
}


require('Views/assessorView.phtml');