<?php
require_once('Models/DomainQueries.php');
require_once('Models/AssessmentTypeQueries.php');
require_once('Models/SectionQueries.php');
require_once('Models/QuestionQueries.php');

session_start();

$view = new stdClass();

$domainQuery = new DomainQueries();
$asTypeQuery = new AssessmentTypeQueries();
$sectQuery = new SectionQueries();
$quesQuery = new QuestionQueries();

$view->dom = $domainQuery->getAll();
$view->assess = $asTypeQuery->getAll();
$view->sections = $sectQuery->getAll();
$view->questions = $quesQuery->getAll();


    if(isset($_POST["addWorkDom"]))
    {
        setcookie("currentPage", "WorkDomPage");
        setcookie("sectionAdded", "false");
        $domainQuery->InsertDomain($_POST["workDomToAdd"]);
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["addAssessmentType"]))
    {
        $domainID = $domainQuery->GetDomainID($_POST["workDom"]);
        $asTypeQuery->InsertAssessmentType($_POST["assTypeToAddName"],$_POST["assTypeToAddDesc"],$domainID);
        setcookie("sectionAdded", "false");
        setcookie("currentPage", "AssessmentTypePage");
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
    if(isset($_POST["selectAssessmentTypeSubmit"]))
    {
        setcookie("sectionAdded", "true");
        setcookie("currentPage", "SectionPage");
        setcookie("assessmentTypeSelected", $_POST["selectAssessmentTypeName"]);
        header("location:addToDatabaseAdmin.php");
        exit();
    }
    if(isset($_POST["maxInsertSubmit"]))
    {
        $_SESSION['sectionNames'] = array();

        for($x=0;$x<=$_POST["maxSection"];$x++)
        {
            array_push($_SESSION['sectionNames'],"nameSection".$x);
        }
        setcookie("sectionAdded", "true");
        setcookie("currentPage", "SectionPage");
        setcookie("maxNoOfSections", $_POST["maxSection"]);
        header("location:addToDatabaseAdmin.php");
        exit();
    }

    if(isset($_POST["addSection"])) {
        $domainID = $domainQuery->GetDomainID($_COOKIE["workDomSelected"]);
        $assTypeID = $asTypeQuery->GetAssessmentTypeID($_COOKIE["assessmentTypeSelected"]);
        setcookie("asTypeID",$assTypeID[0]);
        if(isset($_COOKIE["maxNoOfSections"]))
        {
            if(isset($_SESSION['sectionNames']))
            {
                $x = 0;
                $sectNameNo = count($_SESSION['sectionNames']);
                foreach($_SESSION['sectionNames'] as $currentSection)
                {
                    if(!empty($_POST[$currentSection]))
                    {
                        $weight = 100/(int)$_COOKIE["maxNoOfSections"];
                        $sectQuery->InsertSection($_POST[$currentSection],$_POST["descSection".$x],$weight,(int)$_COOKIE["asTypeID"]);
                        $_SESSION['currentSectionInfo'.$x] = [$_POST[$currentSection],$_POST["maxScoreSection".$x],$_POST["descSection".$x]];
                        $_SESSION[$currentSection] = "done";
                        $x++;
                        setcookie("sectionAdded", "true");
                        setcookie("currentPage", "SectionPage");
                        //header("location:addToDatabaseAdmin.php");

                        //exit();
                    }
                    else
                    {
                        $x++;
                        setcookie("sectionAdded", "true");
                        setcookie("currentPage", "SectionPage");
                        //header("location:addToDatabaseAdmin.php");
                        //exit();
                    }
                    if($x == $sectNameNo -1)
                    {
                        header("location:addToDatabaseAdmin.php");
                        exit();
                    }
                }

            }
        }
        //$sectQuery->InsertSection($_POST["assTypeToAddName"],$_POST["assTypeToAddDesc"],);

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
require("Views/addToDatabaseAdmin.phtml");
