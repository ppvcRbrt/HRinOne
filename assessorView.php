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
require_once("Models/UnsetAll.php");

if(session_status() !== 2)
{
    session_start();
}

$view = new stdClass();
$assessmentQuery = new AssessmentQueries();
$assessmentTypeQuery = new AssessmentTypeQueries();
$assessmentInfoQuery = new AssessmentInfoQueries();
$sectionQuery = new SectionQueries();
$view->templates = $assessmentQuery->getAll();

$currentPageNav = "assessorView";
setcookie("currentPageNav", $currentPageNav);
$unset = new UnsetAll();
$unset->unsetEverything($currentPageNav);

if(isset($_SESSION["loggedIn"]) and isset($_SESSION["privilege"]))
{
    if($_SESSION["loggedIn"] === true and ($_SESSION["privilege"] === "admin" or $_SESSION["privilege"] === "assessor"))
    {
        if(isset($_POST["search"]))
        {
            /**
             * If the user clicked on the search button then take them to the search results page
             */
            if(isset($_POST["candNameAssessor"]))
            {
                setcookie("candNameAssessor", $_POST["candNameAssessor"]);
                header("location:searchResultsAssessorView.php");
                exit();
            }
        }

        /**
         * If the user is returning from the search results page with a get,
         * check which assignment types there are for this candidate and push them into a session array
         */
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
        }

        /**
         * If the user selected an assessment type and if we have a cookie with the candidate id
         *
         */
        if(isset($_POST["selectedAssessmentType"]))
        {
            if(!empty($_POST["assessmentTypes"]))
            {
                if(isset($_COOKIE["candidateID"]))
                {
                    if(!empty($_COOKIE["candidateID"]))
                    {
                        /**
                         * We will check if a txt file already exists if not create one named after the candidate id
                         */
                        if(file_exists("candidatesFeedback/".$_COOKIE["candidateID"]. ".txt"))
                        {
                            $writeAssessmentType = fopen("candidatesFeedback/".$_COOKIE["candidateID"].".txt", "a+");
                        }
                        else
                        {
                            $writeAssessmentType = fopen("candidatesFeedback/".$_COOKIE["candidateID"].".txt", "w");
                        }
                        /**
                         * Write the assessment type name into the text file and give it a flag before
                         * and we create a session array with all the section ids and key => value pairs of questionID=>sectionID
                         * and indicatorID=>questionID
                         */
                        fwrite($writeAssessmentType, "TYPE:".$_POST["assessmentTypes"]);
                        fwrite($writeAssessmentType, "\n");
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
                        /**
                         * we need to count how many sections we have so we "array_unique()" the array and we set the session to its value
                         */
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
        /**
         * if the user clicked on the next button then we get the next section id and set a cookie as it
         */
        if(isset($_GET["sectionID"]))
        {
            unset($_SESSION["indicatorIDForSection"]);
            setcookie("currentPagePerSecID", $_GET["sectionID"]);
            $lastPage = end($_SESSION["sectionIDs"]);
            setcookie("letMeGoNext", "false");
            setcookie("assessorFeedback", "");
            header("location:assessorView.php");
            exit();
        }

        /**
         * if the user clicked on section finished
         */
        if(isset($_POST["sectionFinished"])) {
            if(file_exists("candidatesFeedback/".$_COOKIE["candidateID"]. ".txt"))
            {
                $writeFeedback = fopen("candidatesFeedback/".$_COOKIE["candidateID"].".txt", "a+");
            }
            else
            {
                $writeFeedback = fopen("candidatesFeedback/".$_COOKIE["candidateID"].".txt", "w");
            }
            if(isset($_POST["sectionName"]))
            {
                fwrite($writeFeedback, "SECTION:".$_POST["sectionName"]);
                fwrite($writeFeedback, "\n");
            }

            $assessorViewFunction = new assessorViewFunctions();
            $allQuestions = $assessorViewFunction->getAllQuestionsWithSections();
            $allIndicators = $assessorViewFunction->getAllIndicatorsWithQuestions();
            $_SESSION["questionIndicatorFeedback"] = array();
            $_SESSION["indicatorNames"] = array();
            $_SESSION["indicatorIDForSection"] = array();

            $indicatorsID = array();

            /**
             * Here we push into session variables and indicator names and ids per section
             */
            for ($x = 0; $x < count($allQuestions); $x++)
            {
                if(isset($_POST["indicatorValueQ".$x]))
                {
                    array_push($indicatorsID,(int)$_POST["indicatorValueQ".$x]);
                    array_push($_SESSION["indicatorNames"], "indicatorValueQ".$x);
                    array_push($_SESSION["indicatorIDForSection"],(int)$_POST["indicatorValueQ".$x]);
                }
            }
            $sectionFeedback = array();
            $questionScores = array();
            /**
             * we use the indicatorIDs array created above to get indicator auto generated feedback and score/weight
             */
            foreach($indicatorsID as $currentID)
            {
                $indicatorQuery = new IndicatorsQueries();
                $feedback = $indicatorQuery->getIndFeedback($currentID);
                $scoreAndWeight = $indicatorQuery->getIndScoreAndWeight($currentID);
                array_push($questionScores,(int)$scoreAndWeight[1]);
                array_push($sectionFeedback, $feedback[0]);
                fwrite($writeFeedback, $feedback[0]);
                fwrite($writeFeedback, "\n");
            }

            /**
             * if the assessor left a comment we will write the indicator feedback and score to the .txt file
             */
            if(isset($_POST["assessorFeedback"]))
            {
                $sectionScore = array_sum($questionScores)/count($questionScores);

                if(!empty($_POST["assessorFeedback"]))
                {
                    fwrite($writeFeedback, "FEEDBACK:".$_POST["assessorFeedback"]."\n");
                    fwrite($writeFeedback, "SCORE:".$sectionScore."\n");
                   // fwrite($writeFeedback, "SCORE:".)
                    setcookie("assessorFeedback", $_POST["assessorFeedback"]);
                }
            }
            /**
             * if the user finished the last section, reset all cookies and session variables and start from beginning
             */
            if(isset($_POST["lastSection"]))
            {
                setcookie("letMeGoNext", "");
                setcookie("candNameAssessor", "");
                setcookie("candidateID", "");
                setcookie("currentPagePerSecID", "");
                setcookie("assessorFeedback", "");
                setcookie("selectedAssessmentType", "");
                unset($_SESSION["assessmentTypeNames"]);
                unset($_SESSION["sectionIDs"]);
                unset($_SESSION["questionIDs"]);
                unset($_SESSION["indicatorIDs"]);
                unset($_SESSION["indicatorIDForSection"]);
                unset($_SESSION["questionIndicatorFeedback"]);
                unset($_SESSION["indicatorNames"]);

            }

            setcookie("letMeGoNext", "true");
            header("location:assessorView.php");
            exit();
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
    }
    else
    {
        echo "<span>You're not logged in or you dont have the privilege required to access this information, sorry.<a href = 'index.php'>Go Back to home page</a></span>";
    }
}
else
{
    echo "<span>You're not logged in or you dont have the privilege required to access this information, sorry.<a href = 'index.php'>Go Back to home page</a></span>";
}

require('Views/assessorView.phtml');