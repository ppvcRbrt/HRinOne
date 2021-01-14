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
$pdf->SetProtection(array('print'),'haha');
$title = 'Feedback Report';//title of document
$pdf->SetTitle($title);
$pdf->SetAuthor('PACE Instructor');//author of document
$pdf->PrintFirstSection('Introduction','text/paceIntro.txt');
$pdf->PrintTitle('Overview Of Development Centre Exercises','info');
$pdf->PrintSectionFromTxt('Interview Exercise','text/intExercise.txt');


$pdf->PrintTitle('FEEDBACK','feedback');
$pdf->PrintSectionFromStr("FEEDBACK", $feedback);
$pdf->Output();

require_once('Views/index.phtml');

