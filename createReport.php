<?php
require_once('Models/PageGenerator.php');
require_once('Models/CandidateInfoQueries.php');
session_start();

$view = new stdClass();
$candidateQuery = new CandidateInfoQueries();

if(isset($_POST["search"]))
{
    setcookie("candNameReport", $_POST["candName"]);
    header("location:searchResults.php");
    exit();
}
if(isset($_GET["candID"]))
{
    $candID = $_GET['candID'];
    $candName = $candidateQuery->getCandidateName($candID);

    $filePath = "candidatesFeedback/".$candID.".txt";
    $pdf = new FeedbackGenerator();
    $page = new PageGenerator();
    $sections = array();
    if(file_exists($filePath))
    {
        $directory = new DirectoryIterator('text');
        $num = 0;
        foreach ($directory as $fileinfo) {
            if ($fileinfo->isFile()) {
                if($fileinfo->getExtension() == 'txt')
                    $currentSectionName = $fileinfo->getFilename();
                    array_push($sections,$currentSectionName);
                    $num++;
            }
        }
        $pdf->SetProtection(array('print'));
        $page->CoverPage($pdf,$candName[0],date('Y-m-d'));
        $page->StaticSection($pdf);
        $pdf->PrintSectionFromTxt($sections[0],'text/Interview Exercise.txt',"b");
        $pdf->PrintSectionFromTxt($sections[1], 'text/Group Exercise.txt', "b");
        $pdf->PrintSectionFromTxt($sections[2], 'text/Presentation Exercise.txt', 'b');
        $pdf->AddPage();
        $feedback = fopen($filePath, "r");
        if ($feedback) {
            while (($line = fgets($feedback)) !== false) {
                // process the line read.
                $assessmentTypeflag = "TYPE:";
                $sectionFlag = "SECTION:";
                $assessorFeedbackFlag = "FEEDBACK:";
                if(substr($line, 0,5) === $assessmentTypeflag)
                {
                    $assessmentTypeName = substr($line, 5);
                    $assessmentTypeFixed = preg_replace("/\r|\n/", "", $assessmentTypeName);
                    $pdf->PrintTitle($assessmentTypeFixed, "b");
                }
                else if(substr($line, 0, 8) === $sectionFlag)
                {
                    $sectionName = substr($line, 8);
                    $sectionNameFixed = preg_replace("/\r|\n/", "", $sectionName);
                    $pdf->PrintTitle($sectionNameFixed, "p");
                }
                else if(substr($line, 0, 9) === $assessorFeedbackFlag)
                {
                    $assessorFeedback = substr($line, 9);
                    $assessorFeedbackFixed = preg_replace("/\r|\n/", "", $assessorFeedback);
                    $pdf->Write(8,"Assessor's Feedback : " .$assessorFeedbackFixed);
                }
                else
                {
                    $feedbackFixed = preg_replace("/\r|\n/", "", $line);
                    $pdf->Write(8,$line);
                }
            }

            fclose($feedback);
        } else {
            // error opening the file.
        }
        $pdf->Output();
    }
}
require_once("Views/createReport.phtml");