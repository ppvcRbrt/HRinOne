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
    if(file_exists($filePath))
    {
        $pdf->SetProtection(array('print'));
        $page->CoverPage($pdf,$candName[0],date('Y-m-d'));
        $page->StaticSection($pdf);
        $feedback = fopen($filePath, "r");
        if ($feedback) {
            while (($line = fgets($feedback)) !== false) {
                // process the line read.
                $assessmentTypeflag = "TYPE:";
                $sectionFlag = "SECTION:";
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