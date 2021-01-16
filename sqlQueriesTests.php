<?php
require_once('Models/IndicatorsQueries.php');
session_start();

$view = new stdClass();

//$domainQuery = new DomainQueries();
$ind = new IndicatorsQueries();

$view->ind = $ind->GetIndicator('Motivation');




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
