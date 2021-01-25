<?php
require_once("Models/UnsetAll.php");
if(session_status() !== 2)
{
    session_start();
}

$currentPage = "Home";
setcookie("currentPageNav", $currentPage);
$unset = new UnsetAll();
$unset->unsetEverything($currentPage);
require_once('Views/index.phtml');