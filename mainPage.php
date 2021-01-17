<?php
require_once('Models/UserQueries.php');

session_start();

$view = new stdClass();
$privilege = new UserQueries();

$view->userPriv = $privilege->getPrivileges('rob'); //just get some items

require_once('Views/mainPage.phtml');