<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');
require_once('Models/IndicatorsQueries.php');
require_once('Models/CandidateInfoQueries.php');
require_once("Models/UnsetAll.php");

if(session_status() !== 2)
{
    session_start();
}

$view = new stdClass();
$currentPageNav = "addToDatabase";
setcookie("currentPageNav", $currentPageNav);
$unset = new UnsetAll();
$unset->unsetEverything($currentPageNav);

//here we create all our new queries
$domainQuery = new DomainQueries();
$asTypeQuery = new AssessmentTypeQueries();
$sectQuery = new SectionQueries();
$quesQuery = new QuestionQueries();
$indQuery = new IndicatorsQueries();
$candidQuery = new CandidateInfoQueries();

//get all so that the user can see all the different options
$view->dom = $domainQuery->getAll();
$view->assess = $asTypeQuery->getAll();
$view->sections = $sectQuery->getAll();
$view->sectionIDs = $sectQuery->getAllIDs();
$view->questions = $quesQuery->getAll();

if(isset($_SESSION["loggedIn"]) and isset($_SESSION["privilege"])) {
    if ($_SESSION["loggedIn"] === true and $_SESSION["privilege"] === "admin") {
        /**
         * if the user clicked on Add button under "Work Domain Pill" we set a cookie with "the current page"
         * we also set a cookie that says that we have not added a section
         * we then insert what the user posted
         * finally we do a refresh
         */
        if (isset($_POST["addWorkDom"])) {
            setcookie("currentPage", "WorkDomPage");
            setcookie("sectionAdded", "false");
            $domainQuery->InsertDomain($_POST["workDomToAdd"]);
            header("location:addToDatabaseAdmin.php");
            exit();
        }

        if (isset($_POST["addDelegate"])) {
            if (!empty($_POST["candidateName"]) and !empty($_POST["candidateEmail"])) {
                setcookie("currentPage", "delegatePage");
                setcookie("sectionAdded", "false");
                $refno = time() . rand(10 * 45, 100 * 98);
                $domID = $domainQuery->GetDomainID($_POST["workDomForCandid"]);
                $candidQuery->InsertCandidate($_POST["candidateName"], $_POST["candidateEmail"], $refno, $domID[0]);
                header("location:addToDatabaseAdmin.php");
                exit();
            }
        }


        /**
         * If the user clicked on the Add button under "Add Assessment Type" Pill we set a cookie with the current page and
         * we also set a cookie with the section
         * We then get the domain ID from what the user has chosen using $domainQuery variable
         * Insert into assessmentType using the posted variables
         * finally refresh
         */
        if (isset($_POST["addAssessmentType"])) {
            setcookie("sectionAdded", "false");
            setcookie("currentPage", "AssessmentTypePage");
            $domainID = $domainQuery->GetDomainID($_POST["workDom"]);
            $asTypeQuery->InsertAssessmentType($_POST["assTypeToAddName"], $_POST["assTypeToAddDesc"], $domainID);
            header("location:addToDatabaseAdmin.php");
            exit();
        }

        /**
         * If the user has clicked on the pill "Add Sections" and has selected a work domain,
         * we just set a cookie that reflects the fact that the user wants to add a section
         * we then set the current page to this
         * finally we set a cookie with the work domain selected and refresh
         */
        if (isset($_POST["selectWorkDomain"])) {
            setcookie("sectionAdded", "true");
            setcookie("currentPage", "SectionPage");
            setcookie("workDomSelected", $_POST["workDomNameSect"]);
            header("location:addToDatabaseAdmin.php");
            exit();
        }
        /**
         * If the user has selected a work domain(above), and has selected an assessment type
         * set cookie current page
         * set cookie section added to true to reflect the fact that the user is trying to add a section
         * set a cookie with the assessment type selected
         * get the assessment type Id
         * set a cookie with that ID
         */
        if (isset($_POST["selectAssessmentTypeSubmit"])) {
            setcookie("sectionAdded", "true");
            setcookie("currentPage", "SectionPage");
            setcookie("assessmentTypeSelected", $_POST["selectAssessmentTypeName"]);
            $assTypeID = $asTypeQuery->GetAssessmentTypeID($_POST["selectAssessmentTypeName"]);
            setcookie("asTypeID", $assTypeID[0]);
            header("location:addToDatabaseAdmin.php");
            exit();
        }
        /**
         * If the user has gone through the above two bits of code and has entered a maximum ammount of sections,
         * we create a session array called "sectionNames"
         * we then push some predifined names(the name is defined in the .phtml file) to this Session array
         * we then create a cookie with the maximum amount of sections
         */
        if (isset($_POST["maxInsertSubmit"])) {
            $_SESSION['sectionNames'] = array();

            for ($x = 0; $x <= $_POST["maxSection"]; $x++) {
                array_push($_SESSION['sectionNames'], "nameSection" . $x);
            }
            setcookie("sectionAdded", "true");
            setcookie("currentPage", "SectionPage");
            setcookie("maxNoOfSections", $_POST["maxSection"]);
            header("location:addToDatabaseAdmin.php");
            exit();
        }

        /**
         * If the user has pressed on any of the add section buttons
         * Get domain ID
         */
        if (isset($_POST["addSection"])) {
            $domainID = $domainQuery->GetDomainID($_COOKIE["workDomSelected"]);
            //if we have set a cookie with max number of sections
            if (isset($_COOKIE["maxNoOfSections"])) {
                //if we have set a session array with section names
                if (isset($_SESSION['sectionNames'])) {
                    $x = 0;
                    $sectNameNo = count($_SESSION['sectionNames']);
                    //we loop here through all our section names and do operations on them
                    foreach ($_SESSION['sectionNames'] as $currentSection) {
                        /**
                         * if the user has entered a section name
                         * the weight of each section will be = 100/ammount of sections
                         * we then insert into the database the needed sectiom
                         * we create a session variable with the name of "currentSectionInfo0/1/2/3 etc.." which will hold all the info
                         * about the current section
                         * we then create a session variable with the name of our current section
                         */
                        if (!empty($_POST[$currentSection])) {
                            $weight = 100 / (int)$_COOKIE["maxNoOfSections"];
                            $sectQuery->InsertSection($_POST[$currentSection], $_POST["descSection" . $x], $weight, (int)$_COOKIE["asTypeID"]);
                            $_SESSION['currentSectionInfo' . $x] = [$_POST[$currentSection], $_POST["maxScoreSection" . $x], $_POST["descSection" . $x]];
                            $_SESSION[$currentSection] = "done";
                            $x++;
                            setcookie("sectionAdded", "true");
                            setcookie("currentPage", "SectionPage");
                            //header("location:addToDatabaseAdmin.php");

                            //exit();
                        } //if it is empty then don't do too much
                        else {
                            $x++;
                            setcookie("sectionAdded", "true");
                            setcookie("currentPage", "SectionPage");
                            //header("location:addToDatabaseAdmin.php");
                            //exit();
                        }
                        //if we're at the end of the loop refresh
                        if ($x == $sectNameNo - 1) {
                            header("location:addToDatabaseAdmin.php");
                            exit();
                        }
                    }

                }
            }
            //$sectQuery->InsertSection($_POST["assTypeToAddName"],$_POST["assTypeToAddDesc"],);

        } //if the user has pressed on any button other than add section button
        else {
            //we get rid of the "selected" cookies because this means the user has added something other than a section
            if (isset($_COOKIE["workDomSelected"]) and isset($_COOKIE["assessmentTypeSelected"]) and strcmp($_COOKIE["sectionAdded"], "false") == 0) {
                setcookie("workDomSelected", "");
                setcookie("assessmentTypeSelected", "");
                header("location:addToDatabaseAdmin.php");
                exit();
                //unset($_COOKIE["assessmentTypeSelected"]);
            }
        }
        /**
         * if the user pressed on the add button under the "Add Question" pill
         * we create a session array that will hold all the info we need for the question
         * we get the section ID
         * we fnnally insert a question
         */
        if (isset($_POST["addQuestion"])) {
            setcookie("currentPage", "QuestionPage");
            setcookie("sectionAdded", "false");
            setcookie("questionToAdd", $_POST["questionToAdd"]);

            $selectedForQuestion = [$_POST["workDomNameQuestion"], $_POST["assessmentTypeNameQuestion"], (string)$_POST["SectionNameQuestion"]];
            $_SESSION["selectedForAddQuestion"] = $selectedForQuestion;
            $quesQuery->InsertQuestion($_POST["questionToAdd"], $_POST["SectionNameQuestion"]);
            header("location:addToDatabaseAdmin.php");
            exit();
        }

        if (isset($_POST["maxScore"])) {
            setcookie("currentPage", "QuestionPage");
            setcookie("sectionAdded", "false");
            if (!empty($_POST["maxScoreQuestion"])) {
                setcookie("maxScore", $_POST["maxScoreQuestion"]);
            }
            header("location:addToDatabaseAdmin.php");
            exit();
        }
        if (isset($_POST["addIndicator"])) {
            setcookie("currentPage", "QuestionPage");
            setcookie("sectionAdded", "false");
            $indNames = array();
            $indFeedback = array();
            $indScore = array();
            $isEnd = 0;
            if (!empty($_COOKIE["maxScore"])) {
                $id = $quesQuery->GetQuestionID($_COOKIE["questionToAdd"]);
                $indicatorScore1Weight = 100 / (int)$_COOKIE["maxScore"];
                for ($x = 0; $x < $_COOKIE["maxScore"]; $x++) {
                    array_push($indNames, "indicatorName" . $x);
                    array_push($indFeedback, "indicatorFeedback" . $x);
                    array_push($indScore, "indicatorScore" . $x);
                    $indWeight = 100 / (int)$_COOKIE["maxScore"];
                    if (isset($_POST[$indNames[$x]])) {
                        $indQuery->InsertIndicator($_POST[$indNames[$x]], $_POST[$indFeedback[$x]], $_POST[$indScore[$x]], (string)((int)$indicatorScore1Weight * (int)$_POST[$indScore[$x]]), (int)$id[0]);
                    }
                    $isEnd++;
                }
                if ($isEnd == (int)$_COOKIE["maxScore"]) {
                    setcookie("maxScore", "");
                    setcookie("questionToAdd", "");
                    header("location:addToDatabaseAdmin.php");
                    exit();
                }
            }
        }
        require("Views/addToDatabaseAdmin.phtml");
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
