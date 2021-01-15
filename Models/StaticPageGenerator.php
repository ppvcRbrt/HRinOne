<?php
require('FeedbackGenerator.php');
require('FeedbackQueries.php');

class StaticPageGenerator
{
    function CoverPage($pdf)
    {
        //we add some space at the top of page and print the first image
        $pdf->AddPage();
        $pdf->Ln(10);
        $pdf->PrintImage("./images/fpageMotiv.png");

        //add more space and print the title and add some space at the bottom of title
        $pdf->Ln(30);
        $pdf->FirstPageTitle("PACE REPORT");
        $pdf->Ln(30);

        //set the font size to smaller since the first page title's font size is bigger
        $pdf->SetFontSize('14');
        $pdf->Write(5,'Name : xxxxxxx Date : xxxxxxxxxxxxxx');
    }

    function StaticSection($pdf)
    {
        //will print the first section with a title then print another title and then remove some space from next line
        $pdf->PrintFirstSection('Introduction','text/paceIntro.txt');
        $pdf->PrintTitle('Overview Of Development Centre Exercises','b');
        $pdf->Ln(-12);

        //print the sections from text file with titles
        $pdf->PrintSectionFromTxt('Interview Exercise','text/intExercise.txt',"b");
        $pdf->PrintSectionFromTxt('Group Exercise', 'text/grpExercise.txt', "b");
        $pdf->PrintSectionFromTxt('Presentation Exercise', 'text/presentationExercise.txt', 'b');

        //finally start printing on next page since this section is finished
        $pdf->AddPage();
    }

}