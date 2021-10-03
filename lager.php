<?php
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
$PAGE_TITLE = "Rohholz Lager - Uebersicht";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Rohhobler (<a href='/xls/rhl_lager-ganzes-lager.php'>.XLS Tabellen</a>)</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

if (isset($_GET['holzart']))
{
	if (is_numeric($_GET['holzart']))
	{
		echo "numeric";
		$HTML->printFoot($start_time);
		die();
	}
	else
	{
		echo "not numeric!";
	}
}

$RHL->statLagerTotal();
echo "<h1>Hobelwaren Lager</h1>";
$HWL->statLagerTotal();
echo "<h1>Balken Lager</h1>";
$MHL->statLagerTotal();
echo "<br />\n";

$HTML->printFoot($start_time);
?>