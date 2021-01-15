<?php
require('Models/StaticPageGenerator.php');

//here we create a new feedback generator
$pdf = new FeedbackGenerator();
$page = new StaticPageGenerator();
//we set a password, can be later randomly generated then sent to users
$pdf->SetProtection(array('print'));

$page->CoverPage($pdf);
$page->StaticSection($pdf);
$pdf->Output();


