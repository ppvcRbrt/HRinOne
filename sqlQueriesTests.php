<?php
require_once('Models/IndicatorsQueries.php');
require_once('Models/SectionQueries.php');
session_start();

$view = new stdClass();

//$domainQuery = new DomainQueries();
$ind = new IndicatorsQueries();
$sec = new SectionQueries();

if(isset($_POST["submit"]))
{
    $sec = $sec->GetSectionIDByName($_POST["sectionName"]);
    $indWeight = 100/$_POST["maxScore"];
    foreach($sec as $section)
    {
        $sec = $section->getSectionID();
    }
    for($x = 0; $x <= $_POST["maxScore"]; $x++)
    {
    }
}
else
{

}




//Function to delete non unique shit
#$domains = $domainQuery->GetDomainByName("Medicine"); //insert into Db domain
#
#$domainNames = [];
#
#foreach($domains as $currentDomain)
#{
#    array_push($domainNames, $currentDomain->getDomain());
#}
#if(sizeof($domainNames)>1)
#{
#    $domainQuery->DeleteDomain($domainNames[0]);
#    $domainQuery->InsertDomain($domainNames[0]);
#}
#var_dump($domainNames);

require_once('Views/sqlQueriesTests.phtml');
