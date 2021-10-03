<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2012
 */

require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Message 1";
$msg[2] = "Message 2";
$msg[3] = "Message 3";

// Seitentitel
$PAGE_TITLE = "Balkenlager - Uebersicht";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Balkenlager (<a href='/xls/mhl_lager-ganzes-lager.php'>.XLS Tabellen</a>)</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

$MHL->statLagerTotal();

$HTML->printFoot($start_time);
?>