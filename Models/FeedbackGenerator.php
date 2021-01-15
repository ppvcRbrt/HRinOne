<?php
require("./fpdf_protection.php");

class FeedbackGenerator extends FPDF_Protection {
    public $fontSize = 12;
    public $cellHeight = 6; //i recommend setting cell height to half of fontsize so when filled with colour it looks noice
    protected $text;
    public $blue = [191,229,242];
    public $purp = [156,139,162];
    /**
     * This function will specify the logo, the font and the title of the document
     */
    function Header()
    {
        global $title;

        $this->Image('images/logoPace.png',10,6,30);
        $this->SetFont('Arial','B',15);
       //below we create the border around the title and specify it
        $w = $this->GetStringWidth($title)+6;
        $this->SetX((210-$w)/2);
        $this->SetLineWidth(.5);
        $this->Cell($w,9,$title,1,1,'C',false);
        $this->Ln(10);
    }

    /**
     * Will add pagination to the footer of our document,
     * can add anything here according to document specification
     */
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Text color in gray
        $this->SetTextColor(128);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

    /**
     * This function deals with the specification of the font and fill of the Intro Titles
     * @param $label: the current title of the Intro Section to be printed to the PDF
     */
    function IntroTitle($label)
    {
        $this->SetFont('Arial','',$this->fontSize);
        $this->SetFillColor(191,229,242);

        //NOTE : the cell can be used to set kind of like padding around the edges of words
        $this->Cell(0,$this->cellHeight, "$label",0,1,'L',true);
        $this->Ln(4);
    }

    /**
     * This function deals with the spec of the font and fill of Feedback titles
     * @param $label: the current title of the Feedback Section to be printed to the PDF
     */
    function FeedbackTitle($label)
    {
        $this->SetFont('Arial','',$this->fontSize);
        $this->SetFillColor(156,139,162);

        //NOTE : the cell can be used to set padding around the edges of words
        $this->Cell(0,$this->cellHeight, "$label",0,1,'L',true);
        $this->Ln(4);
    }

    /**
     * This will take a .txt file and print it to the PDF
     * @param $file: directory to where the text file is
     */
    function SectionBodyFromTxt($file)
    {
        $txt = file_get_contents($file);
        $this->SetFont('Times','',$this->fontSize);
        $this->MultiCell(0,5,$txt);
        $this->Ln(); //NOTE : This is a line break, you can also specify the height as a parameter ;)
        $this->SetFont('','I');
        $this->Cell(0,5,'EOF HERE');
    }

    /**
     * Will take a string and print to PDF
     * @param $txt: string to be printed to PDF
     */
    function SectionBodyFromStr($txt)
    {
        // Times 12
        $this->SetFont('Times','',$this->fontSize);
        // Output justified text
        $this->MultiCell(0,5,$txt);
        // Line break
        $this->Ln();
        // Mention in italics
        $this->SetFont('','I');
        $this->Cell(0,5,'EOF HERE');
    }

    /**
     * Function that will print the top of the page with all the needed headings
     * @param $title: title of document
     * @param $file: intro file
     */
    function PrintFirstSection($title, $file)
    {
        $this->AddPage();
        $this->IntroTitle($title);
        $this->SectionBodyFromTxt($file);
    }

    /**
     * Function that will print any other section beginning from a .txt file
     * @param $title: title of section
     * @param $file: directory of .txt file for section body
     */
    function PrintSectionFromTxt($title, $file, $bOrp)
    {
        $this->Ln();
        $this->PrintTitle($title, $bOrp);
        $this->SectionBodyFromTxt($file);
    }

    /**
     * Function that will print any other section beginning from a string
     * @param $title: title of section
     * @param $txt: section body
     */
    function PrintSectionFromStr($title, $txt, $bOrp)
    {
        $this->Ln();
        $this->PrintTitle($title, $bOrp);
        $this->SectionBodyFromStr($txt);
    }

    /**
     * Function that just prints the title
     * @param $title
     * @param $bOrP: enter "b" for blue title fill or "p" for purple title fill
     */
    function PrintTitle($title,$bOrP)
    {
        $this->Ln();
        if($bOrP == "b")
        {
            $this->IntroTitle($title);
        }
        if($bOrP == "p")
        {
            $this->FeedbackTitle($title);
        }
    }

}


?>