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
$PAGE_TITLE = "EXCEL Tabellen des Massivholz Lagers";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>EXCEL Tabellen des Massivholz Lagers (<a href='/xls/mhl_lager-ganzes-lager.php'>.XLS Tabellen</a>)</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

$heute			=	new DateTime();

// Holzarten für Menü
$sql_holzarten = "
	SELECT
	  za_holzarten.id,
	  za_holzarten.name
	FROM
	  mhl_items
	  INNER JOIN za_holzarten ON (mhl_items.holzart = za_holzarten.id)
	GROUP BY
	  mhl_items.holzart
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

?>