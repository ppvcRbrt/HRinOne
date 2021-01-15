<?php
require('./Models/FeedbackGenerator.php');
require('./Models/FeedbackQueries.php');

//here we're just creating a query on the go to test our ability to get info from the db
$query = new FeedbackQueries();
$feedback = "";
foreach($query->getAll() as $currentInfo) {
   $feedback = $currentInfo->getFeedback();
   $score = $currentInfo->getScore();
}

//here we create a new feedback generator
$pdf = new FeedbackGenerator();
//we set a password, can be later randomly generated then sent to users
$pdf->SetProtection(array('print'));
//$title = 'PACE Report';//title of document
$pdf->AddPage();
$pdf->Ln(10);
$pdf->PrintImage("images/fpageMotiv.png");
//$pdf->SetTitle($title);
$pdf->Ln(30);
$pdf->FirstPageTitle("PACE REPORT");
$pdf->Ln(30);

$pdf->SetFontSize('14');
$pdf->Write(5,'Name : xxxxxxx Date : xxxxxxxxxxxxxx');

$pdf->SetAuthor('PACE Instructor');//author of document

$pdf->PrintFirstSection('Introduction','text/paceIntro.txt');
$pdf->PrintTitle('Overview Of Development Centre Exercises','b');
$pdf->Ln(-12);
$pdf->PrintSectionFromTxt('Interview Exercise','text/intExercise.txt',"b");

$pdf->PrintSectionFromTxt('Group Exercise', 'text/grpExercise.txt', "b");

$pdf->PrintSectionFromTxt('Presentation Exercise', 'text/presentationExercise.txt', 'b');

$pdf->AddPage();
$pdf->PrintSectionFromStr("FEEDBACK", $feedback, "p");
$pdf->Output();


