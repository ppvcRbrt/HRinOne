<?php
require_once('Models/CandidateInfoQueries.php');
session_start();

$view = new stdClass();
$candQuery = new CandidateInfoQueries();

if(!empty($_COOKIE["candName"]))
{
    $candID = $candQuery->getCandIDByName($_COOKIE["candName"]);
    $view->candID = $candID;
}

require("Views/searchResults.phtml");