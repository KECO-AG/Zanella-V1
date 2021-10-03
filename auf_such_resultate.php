<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "";
$msg[2] = "";
$msg[3] = "";

// Definition Seitentitel
$PAGE_TITLE = "";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line

$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Auftragsverwaltung Suchergebnisse</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

$suchbegriff = $DB->escapeString($_GET['searchvalue']);

$AUF->suchResultate($suchbegriff);
$TRA->suchResultate($suchbegriff);

// Ende Inhalt
$HTML->printFoot($start_time);
?>