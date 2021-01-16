<?php
require_once('Models/AssessmentTypeQueries.php');
session_start();

$view = new stdClass();

//$domainQuery = new DomainQueries();
$assType = new AssessmentTypeQueries();
$assType->InsertAssessmentType("Interview","Bla bla bla bla bla Bla BLA",11);





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
