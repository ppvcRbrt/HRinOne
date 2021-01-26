<?php
require_once('Models/PageGenerator.php');
require_once('Models/CandidateInfoQueries.php');
require_once("Models/UnsetAll.php");

if(session_status() !== 2)
{
    session_start();
}

$view = new stdClass();
$candidateQuery = new CandidateInfoQueries();

$currentPageNav = "createReport";
setcookie("currentPageNav", $currentPageNav);
$unset = new UnsetAll();
$unset->unsetEverything($currentPageNav);

if(isset($_SESSION["loggedIn"]) and isset($_SESSION["privilege"])) {
    if ($_SESSION["loggedIn"] === true and ($_SESSION["privilege"] === "admin" or $_SESSION["privilege"] === "assessor")) {
        if (isset($_POST["search"])) {
            setcookie("candNameReport", $_POST["candName"]);
            header("location:searchResultsReportGenerator.php");
            exit();
        }
        if (isset($_GET["candID"])) {
            $candID = $_GET['candID'];
            $candName = $candidateQuery->getCandidateName($candID);

            $filePath = "candidatesFeedback/" . $candID . ".txt";
            $pdf = new FeedbackGenerator();
            $page = new PageGenerator();
            $sections = array();
            if (file_exists($filePath)) {
                $directory = new DirectoryIterator('text');
                $num = 0;
                foreach ($directory as $fileinfo) {
                    if ($fileinfo->isFile()) {
                        if ($fileinfo->getExtension() == 'txt')
                            $currentSectionName = $fileinfo->getFilename();
                        array_push($sections, $currentSectionName);
                        $num++;
                    }
                }
                $pdf->SetProtection(array('print'));
                $page->CoverPage($pdf, $candName[0], date('Y-m-d'));
                $page->StaticSection($pdf);
                $pdf->PrintSectionFromTxt($sections[0], 'text/Interview Exercise.txt', "b");
                $pdf->PrintSectionFromTxt($sections[1], 'text/Group Exercise.txt', "b");
                $pdf->PrintSectionFromTxt($sections[2], 'text/Presentation Exercise.txt', 'b');
                $pdf->AddPage();
                $checkScore = fopen($filePath, "r");
                if ($checkScore) {

                    $x = 0;
                    while (($line = fgets($checkScore)) !== false) {
                        $assessmentTypeflag = "TYPE:";
                        $scoreFlag = "SCORE:";
                        if (substr($line, 0, 5) === $assessmentTypeflag) {

                            $assessmentTypeName = substr($line, 5);
                            $assessmentTypeFixed = preg_replace("/\r|\n/", "", $assessmentTypeName);
                            $assessmentTypeNames[$assessmentTypeFixed] = [];
                            //array_push($assessmentTypeNames, $assessmentTypeFixed);
                        } else if (substr($line, 0, 6) === $scoreFlag) {
                            if (isset($assessmentTypeNames)) {
                                if (!empty($assessmentTypeFixed)) {
                                    $score = substr($line, 6);
                                    $scoreFixed = preg_replace("/\r|\n/", "", $score);
                                    array_push($assessmentTypeNames[$assessmentTypeFixed], (int)$score);
                                }
                            }
                        }
                    }
                }
                $feedback = fopen($filePath, "r");
                $allScoresPerSection = array();
                $getLines = new FeedbackGenerator();

                if ($feedback) {
                    while (($line = fgets($feedback)) !== false) {
                        // process the line read.
                        $assessmentTypeflag = "TYPE:";
                        $sectionFlag = "SECTION:";
                        $assessorFeedbackFlag = "FEEDBACK:";
                        $scoreFlag = "SCORE:";
                        if (substr($line, 0, 5) === $assessmentTypeflag) {
                            $assessmentTypeName = substr($line, 5);
                            $assessmentTypeFixed = preg_replace("/\r|\n/", "", $assessmentTypeName);
                            if (isset($assessmentTypeNames[$assessmentTypeFixed])) {
                                if (!empty($assessmentTypeNames[$assessmentTypeFixed])) {
                                    $scoreForAssType = array_sum($assessmentTypeNames[$assessmentTypeFixed]) / count($assessmentTypeNames[$assessmentTypeFixed]);
                                    $pdf->PrintTitle($assessmentTypeFixed . " " . $scoreForAssType . "/100", "b");
                                }
                            }

                        } else if (substr($line, 0, 8) === $sectionFlag) {
                            $sectionName = substr($line, 8);
                            $sectionNameFixed = preg_replace("/\r|\n/", "", $sectionName);
                            $pdf->PrintTitle($sectionNameFixed, "p");
                        } else if (substr($line, 0, 9) === $assessorFeedbackFlag) {
                            $assessorFeedback = substr($line, 9);
                            $assessorFeedbackFixed = preg_replace("/\r|\n/", "", $assessorFeedback);
                            $pdf->Write(8, "Assessor's Feedback : " . $assessorFeedbackFixed);
                        } else if (substr($line, 0, 6) === $scoreFlag) {
                            //$scorePerSection = substr($line, 6);
                            //$scorePerSectionFixed = preg_replace("/\r|\n/", "", $scorePerSection);
                            //array_push($allScoresPerSection, $scorePerSectionFixed);
                        } else {
                            $feedbackFixed = preg_replace("/\r|\n/", "", $line);
                            $pdf->Write(8, $line);
                        }
                    }

                    fclose($feedback);
                } else {
                    // error opening the file.
                }
                if(isset($_GET["generateRep"]))
                {
                    $filename="tempRep/".$_GET["candID"].".pdf";
                    $pdf->Output($filename,'F');
                    setcookie("reportGenerated", "true");
                    header("location:searchResultsReportGenerator.php");
                }
                else
                {
                    $pdf->Output();
                }
            }
        }
        require_once("Views/createReport.phtml");
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