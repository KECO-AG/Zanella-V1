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
$msg[3] = "Die Seite die Sie aufruften ben?tigt einen h?heren Userlevel. Sorry!";

// Seitentitel
$PAGE_TITLE = "Zanella :: Holz Verwaltung";

$HTML->printHead($PAGE_TITLE); // JS nach dieser Linie einf?gen
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

// Seiten?berschrift
echo "<h1>Zanella :: Holz Verwaltung</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

echo "<h2>Statistik Einschnittverwaltung</h2>\n";
$ESV->monatsTotaleJahr(date('Y'));
$ESV->monatsTotaleJahr(date('Y', strtotime('-1 years')));

echo "<h2>Statistik Rohholzverwaltung</h2>\n";
if($_SESSION['level'] <=2){ $RHL->statLagerTotal(); }



echo "<br />\n";
// Ende Inhalt
$HTML->printFoot($start_time);
?>