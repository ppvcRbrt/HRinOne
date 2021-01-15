<?php
require_once('Models/CandidateInfoQueries.php');
session_start();

$view = new stdClass();

$candidate = new CandidateInfoQueries();

$view->candidate = $candidate->getCandidateName(1); //just get some items


require_once('Views/sqlQueriesTests.phtml');
