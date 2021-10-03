<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "test";
$msg[2] = "";
$msg[3] = "";

// Seitentitel
$PAGE_TITLE = "Mitarbeiter Statistik";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

$maID = htmlentities($_GET['maID']);

$sql_ma = "SELECT * FROM za_mitarbeiter WHERE id=".$maID."";
$result_ma = $DB->query($sql_ma);

// Seitenüberschrift
echo "<h1>Mitarbeiter Statistik: ".$result_ma[0]['name']." ".$result_ma[0]['vorname']."</h1>";

// Fehlermeldung
$HTML->printMessage($msg);

?>
<fieldset>
	<legend>2011</legend>
</fieldset>

<fieldset>
	<legend>2010</legend>
</fieldset>


<?php 
$HTML->printFoot($start_time);
?>