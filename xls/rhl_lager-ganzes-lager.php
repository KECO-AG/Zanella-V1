<?php
require_once '../common.php';
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
$PAGE_TITLE = "EXCEL Tabellen des Lagers";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seiten?berschrift
echo "<h1>EXCEL Tabellen des Lagers (<a href='/xls/rhl_lager-ganzes-lager.php'>.XLS Tabellen</a>)</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

$heute			=	new DateTime();

// Holzarten f?r Men?
$sql_holzarten = "
	SELECT
	  za_holzarten.id,
	  za_holzarten.name
	FROM
	  rhl_lager
	  INNER JOIN za_holzarten ON (rhl_lager.holzart = za_holzarten.id)
	GROUP BY
	  rhl_lager.holzart
	ORDER BY
	  za_holzarten.sort ASC
";
$result_holzarten = $DB->query($sql_holzarten);


foreach ($result_holzarten as $holzarten)
{
	$holzart	=	$holzarten['name'];
	$holzart	=	str_replace(" ", "_", $holzart);
	$holzart	=	str_replace(".", "", $holzart);
	$dateiname	=	$heute->format('Y-m-d')."_".$holzart.".xls";

	echo "<p><a href='rhl_lager-liste_xls.php?holzart=".$holzarten['id']."'>".$dateiname."</a></p>";
}

echo "<p><a href='rhl_lager-FULL_xls.php'>Ganzes Lager nach Paket Nummer</a></p>";

?>