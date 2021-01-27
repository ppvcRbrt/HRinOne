<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');
require_once('Models/CandidateInfoQueries.php');
require_once('Models/AssessmentQueries.php');
require_once('Models/AssessmentInfoQueries.php');
require_once('Models/IndicatorsQueries.php');
require_once("Models/UnsetAll.php");

if(session_status() !== 2)
{
    session_start();
}

$view = new stdClass();
$candQueries = new CandidateInfoQueries();
$assessmentTypeQueries = new AssessmentTypeQueries();
$sectionQueries = new SectionQueries();
$domainQueries = new DomainQueries();
$questionQueries = new QuestionQueries();
$assessmentQuery = new AssessmentQueries();
$assessmentInfoQuery = new AssessmentInfoQueries();
$indicatorQuery = new IndicatorsQueries();

$currentPageNav = "createTemplate";
setcookie("currentPageNav", $currentPageNav);
$unset = new UnsetAll();
$unset->unsetEverything($currentPageNav);


if(isset($_SESSION["loggedIn"]) and isset($_SESSION["privilege"])) {
    if ($_SESSION["loggedIn"] === true and $_SESSION["privilege"] === "admin") {

        /**
         * if the user clicked on the search button take them to a search results page
         */
        if (isset($_POST["search"])) {
            if (isset($_POST["candName"])) {
                setcookie("candName", $_POST["candName"]);
                header("location:searchResultsCreateTemplate.php");
                exit();
            }
        }

        /**
         * if the user is returning from the search results page then use the candidate ID from
         * the $_GET[] to get his name and domain
         */
        if (isset($_GET['candID'])) {
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

        /**
         * if the cookie candidate id is set and not empty then set some variables with the name and work domain
         * that we can retrieve in the .phtml file
         */
        if (isset($_COOKIE["candidateID"])) {
            if (!empty($_COOKIE["candidateID"]))
                $view->candName = $candQueries->getCandidateName($_COOKIE["candidateID"]);
            $view->candDom = $candQueries->getCandidateWorkDomName($_COOKIE["candidateID"]);
        }
        if (isset($_COOKIE["domain"])) {
            if (!empty($_COOKIE["domain"]))
                $view->assessmentTy = $assessmentTypeQueries->getAssessmentTypeByWorkDom($_COOKIE["domain"]);
        }

        /**
         * If the user selected an assessment type set a cookie with that value
         */
        if (isset($_POST["selectedAssessmentType"])) {
            setcookie("assessmentType", $_POST["assessmentTypes"]);
            header("location:createTemplate.php");
            exit();
        }

        /**
         * if the user selected a max no of sections set a cookie with that value
         */
        if (isset($_POST["maxPost"])) {
            setcookie("maxSections", $_POST["maxSections"]);
            header("location:createTemplate.php");
            exit();
        }

        /**
         * if the cookie that we have set above is set then we create an array with all the
         * post names of the sections and set it to a session variable
         */
        if (isset($_COOKIE["maxSections"])) {
            $maxSections = $_COOKIE["maxSections"];
            $_SESSION["sectionNames"] = array();

            for ($x = 0; $x < $maxSections; $x++) {
                array_push($_SESSION["sectionNames"], "sectionChoose" . $x);
            }
            if (!empty($_COOKIE["maxSections"])) {
                $assessmentTypeID = $assessmentTypeQueries->GetAssessmentTypeID($_COOKIE["assessmentType"]);
                setcookie("assessmentTypeID", $assessmentTypeID[0]);
                $view->sections = $sectionQueries->getSectionsByAssessmentTypeID((int)$assessmentTypeID[0], (int)$_COOKIE["domainID"]);
            }
        }
        /**
         * If the user clicked on the section submit button, we create two session arrays
         * one for the section names and another for the section IDs
         */
        if (isset($_POST["sectionSubmit"])) {
            $maxSectsForQuestionInput = $_COOKIE["maxSections"];
            $x = 0;
            $_SESSION["sectionHeader"] = array();
            $_SESSION["sectionIDs"] = array();

            /**
             * Here we create another session variable that will hold all the question for a section
             */
            foreach ($_SESSION["sectionNames"] as $currentSectName) {
                $_SESSION["questionPerSect" . $x] = array();

                //if the section name inside the session array is = to what has been posted
                //then we push into the two session arrays we created above the section id and name
                if (isset($_POST[$currentSectName])) {
                    array_push($_SESSION["sectionHeader"], $_POST[$currentSectName]);
                    $currentSecID = $sectionQueries->GetSectionIDByName($_POST[$currentSectName]);
                    array_push($_SESSION["sectionIDs"], (int)$currentSecID[0]);

                    //here we get our questions per section
                    $questions = $questionQueries->GetQuestionsByInfo($_POST[$currentSectName], $_COOKIE["assessmentType"], $_COOKIE["domain"]);

                    //and then we push them into a session array
                    foreach ($questions as $currentQuestion) {
                        $name = $currentQuestion->getQuestion();
                        array_push($_SESSION["questionPerSect" . $x], $name);
                    }
                }                    $x++;

                if ($x == $maxSectsForQuestionInput) {
                    header("location:createTemplate.php");
                    exit();
                }
            }
        }
        /**
         * if the user clicked on the "Enter" button to add maximum questions per section
         * we will fill the session array "maxQperSect" with the maximum we got from the user
         */
        if (isset($_POST["maxQperSectionSubmit"])) {
            $maximumSections = (int)$_COOKIE["maxSections"];
            $currentCount = 0;
            for ($x = 0; $x < (int)$_COOKIE["maxSections"]; $x++) {
                $_SESSION["maxQperSect" . $x] = array();
                if (isset($_POST["maxQuestionsPerSect" . $x])) {
                    array_push($_SESSION["maxQperSect" . $x], $_POST["maxQuestionsPerSect" . $x]);
                }
                $currentCount++;
            }
            if ($currentCount == $maximumSections) {
                header("location:createTemplate.php");
                exit();
            }
        }

        /**
         * if the user pressed on the last button of the form which should be "Enter" again
         * then insert assessment into the assessment table get its id and then insert assessment info into the assessment_info table
         */
        if (isset($_POST["done"])) {
            $assessmentQuery->InsertAssessment("", "", (int)$_COOKIE["candidateID"], (int)$_COOKIE["assessmentTypeID"]);
            $assessmentID = $assessmentQuery->GetAssessmentID((int)$_COOKIE["candidateID"], (int)$_COOKIE["assessmentTypeID"]);
            $isDone = 0;
            //we need three loops here, one for the sections, one for the questions and finally one for the indicators per question
            for ($x = 0; $x < (int)$_COOKIE["maxSections"]; $x++) {
                $questionsCount = 0;
                for ($y = 0; $y < (int)$_SESSION["maxQperSect" . $x][0]; $y++) ;
                {
                    $questionID = $questionQueries->GetQuestionID($_POST["question" . $questionsCount . "PerSect" . $x]);
                    $indicators = $indicatorQuery->getIndicatorsByQuesID($questionID[0]);
                    //for($w=0; $w < count($_SESSION["questionPerSect".$x]); $w++)
                    $questionsCount++;
                    for ($z = 0; $z < count($indicators); $z++) {
                        $assessmentInfoQuery->InsertAssessmentInfo($assessmentID[0], $_SESSION["sectionIDs"][$x], $questionID[0], $indicators[$z]->getIndicatorID());
                    }

                }
                $isDone++;
            }
            //if the software is done inserting everything then unset all the cookies to make the user
            //start from the beginning
            if ($isDone == (int)$_COOKIE["maxSections"]) {
                setcookie("candName", "");
                setcookie("domain", "");
                setcookie("candidateID", "");
                setcookie("domainID", "");
                setcookie("assessmentType", "");
                setcookie("assessmentTypeID", "");
                setcookie("finished", "true");
                for ($x = 0; $x < (int)$_COOKIE["maxSections"]; $x++) {
                    unset($_SESSION["maxQperSect" . $x]);
                    unset($_SESSION['questionPerSect' . $x]);
                }
                setcookie("maxSections", "");
                unset($_SESSION['sectionNames']);
                unset($_SESSION['sectionHeader']);
                header("location:createTemplate.php");
                exit();
            }
        }
        require("Views/createTemplate.phtml");
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

