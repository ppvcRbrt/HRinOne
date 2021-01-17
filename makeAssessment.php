<?php
require_once('Models/IndicatorsQueries.php');
require_once('Models/SectionQueries.php');
session_start();

$view = new stdClass();

//$domainQuery = new DomainQueries();
$ind = new IndicatorsQueries();
$sec = new SectionQueries();
$view->sec = $sec->getAll();

if(isset($_POST["sectionName"]))
{
    $sec = $sec->GetSectionIDByName($_POST["sectionName"]);
    setcookie("sectionName", $sec, time() + (86400 * 30), "/");
}

if(isset($_POST["submitMaxScore"]))
{
    $indWeight = 100/$_POST["maxScore"];

    for($x = 0; $x <= $_POST["maxScore"]; $x++)
    {
    }
}
else
{

}
require_once("Views/makeAssessment.phtml");