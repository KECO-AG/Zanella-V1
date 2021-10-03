<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Job hinzugef&uuml;gt!";
$msg[2] = "";
$msg[3] = "";

// Definition Seitentitel
$PAGE_TITLE = "Home Einschnittverwaltung";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
?>
<script type="text/javascript">
function toggle(it) {
  if ((it.style.backgroundColor == "none") || (it.style.backgroundColor == ""))
    {it.style.backgroundColor = "yellow";}
  else
    {it.style.backgroundColor = "";}
}
</script>
<?php 
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Einschnitt Verwaltung - Uebersicht</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

$ESV->daySummary('10');
$ESV->monatsTotaleJahr(date('Y'));
$ESV->monatsTotaleJahr(date('Y')-1);


$ESV->printMAStats(date('Y'));
$ESV->printHolzStats(date('Y'));
echo "<br />";

$ESV->printMAStats(date('Y')-1);
$ESV->printHolzStats(date('Y')-1);
echo "<br />";

$ESV->printMAStats(date('Y')-2);
$ESV->printHolzStats(date('Y')-2);
echo "<br />";

$ESV->printMAStats(date('Y')-3);
$ESV->printHolzStats(date('Y')-3);
echo "<br />";

// Ende Inhalt
$HTML->printFoot($start_time);
?>