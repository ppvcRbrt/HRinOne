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

        /**
         * If the user clicked on the button search then take them to the search page
         */
        if (isset($_POST["search"])) {
            setcookie("candNameReport", $_POST["candName"]);
            header("location:searchResultsReportGenerator.php");
            exit();
        }

        /**
         * If user returned from the search page
         */
        if (isset($_GET["candID"])) {
            $candID = $_GET['candID'];
            $candName = $candidateQuery->getCandidateName($candID);


            $filePath = "candidatesFeedback/" . $candID . ".txt"; //our .txt file path based on candidate id
            $pdf = new FeedbackGenerator();
            $page = new PageGenerator();
            $sections = array();

            /**
             * we check if file exists with the candidate id
             */
            if (file_exists($filePath)) {
                $directory = new DirectoryIterator('text');
                $num = 0;
                foreach ($directory as $fileinfo) {
                    if ($fileinfo->isFile()) {
                        if ($fileinfo->getExtension() == 'txt')
                            $currentSectionName = $fileinfo->getFilename();
                        array_push($sections, $currentSectionName); //get all section names
                        $num++;
                    }
                }

                $pdf->SetProtection(array('print')); //here we set the protection of the pdf, so 'print' means users cannot copy from the pdf
                $page->CoverPage($pdf, $candName[0], date('Y-m-d')); //function that will print the cover page
                $page->StaticSection($pdf); //we will our static section here
                $pdf->PrintSectionFromTxt($sections[0], 'text/Interview Exercise.txt', "b"); //here you can choose which files to print
                $pdf->PrintSectionFromTxt($sections[1], 'text/Group Exercise.txt', "b"); //make sure you have the right names in the files
                $pdf->PrintSectionFromTxt($sections[2], 'text/Presentation Exercise.txt', 'b');
                $pdf->AddPage();
                $checkScore = fopen($filePath, "r");
                /**
                 * Here we will check the score for each section and add it to an assessment type
                 */
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

                /**
                 * The while loop will print our pdf line by line from the text files
                 */
                if ($feedback) {
                    while (($line = fgets($feedback)) !== false) {
                        // process the line read.
                        $assessmentTypeflag = "TYPE:"; //these flags will help our if statements know what type of line they've hit
                        $sectionFlag = "SECTION:";
                        $assessorFeedbackFlag = "FEEDBACK:";
                        $scoreFlag = "SCORE:";
                        /**
                         * if the current line is an assessment type then we print a blue tilte with the score for this assessment type
                         */
                        if (substr($line, 0, 5) === $assessmentTypeflag) {
                            $assessmentTypeName = substr($line, 5);
                            $assessmentTypeFixed = preg_replace("/\r|\n/", "", $assessmentTypeName);
                            if (isset($assessmentTypeNames[$assessmentTypeFixed])) {
                                if (!empty($assessmentTypeNames[$assessmentTypeFixed])) {
                                    $scoreForAssType = array_sum($assessmentTypeNames[$assessmentTypeFixed]) / count($assessmentTypeNames[$assessmentTypeFixed]);
                                    $pdf->PrintTitle($assessmentTypeFixed . " " . $scoreForAssType . "/100", "b");
                                }
                            }

                            /**
                             * else if the current line is  a section we print out a purple title
                             */
                        } else if (substr($line, 0, 8) === $sectionFlag) {
                            $sectionName = substr($line, 8);
                            $sectionNameFixed = preg_replace("/\r|\n/", "", $sectionName);
                            $pdf->PrintTitle($sectionNameFixed, "p");

                            /**
                             * if its assessor feedback we just print out the feedback
                             */
                        } else if (substr($line, 0, 9) === $assessorFeedbackFlag) {
                            $assessorFeedback = substr($line, 9);
                            $assessorFeedbackFixed = preg_replace("/\r|\n/", "", $assessorFeedback);
                            $pdf->Write(8, "Assessor's Feedback : " . $assessorFeedbackFixed);

                            /**
                             * if the current line is a score then we just ignore that
                             */
                        } else if (substr($line, 0, 6) === $scoreFlag) {
                            //do nothing
                        } /**
                         * finally just print the autogenerated feedback if no flag available
                         */
                        else {
                            $feedbackFixed = preg_replace("/\r|\n/", "", $line);
                            $pdf->Write(8, $line);
                        }
                    }

                    fclose($feedback);
                } else {
                    // error opening the file.
                }

                /**
                 * if we clicked on generate report from the search results for report generator then we create a temporary pdf
                 * that will be attached to an e-mail then deleted
                 */
                if(isset($_GET["generateRep"]))
                {
                    $filename="tempRep/".$_GET["candID"].".pdf";
                    $pdf->Output($filename,'F');
                    setcookie("reportGenerated", "true");
                    header("location:searchResultsReportGenerator.php");
                } /**
                 * if we clicked on just the view pdf button it will output the pdf in a browser page
                 */
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