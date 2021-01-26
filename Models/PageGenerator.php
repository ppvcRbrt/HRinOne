<?php
require('FeedbackGenerator.php');
require('FeedbackQueries.php');

/**
 * Class StaticPageGenerator. This class represents the ability to set certain properties
 * to the PDF-generated page, such as adding text from a file, setting image formats and overall design.
 */

class PageGenerator
{
    function section($pdf, $assessmentTypeName, $sectionName, $sectionBody)
    {
        $pdf->PrintTitle($assessmentTypeName,"b");
        $pdf->PrintSectionFromStr($sectionName, $sectionBody, "p");
    }

    function CoverPage($pdf, $candidateName, $date)
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
        $pdf->Write(5,'Name : '.$candidateName.'         Date :'.$date);
    }

    function StaticSection($pdf)
    {
        //will print the first section with a title then print another title and then remove some space from next line
        $pdf->PrintFirstSection('Introduction','text/paceIntro.txt');
        $pdf->PrintTitle('Overview Of Development Centre Exercises','b');
        $pdf->Ln(-12);

    }

}