<?php
require_once '../common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

$seitentitel = "Transportverwaltung";

// ***********************************************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link type="text/css" href="../inc/css/print.css.php" rel="stylesheet" media="screen" />
	<link type="text/css" href="../inc/css/print.css.php" rel="stylesheet" media="print" />
	<title><?php echo $seitentitel; ?> :: Zanella Holz - Turtmann</title>
	<link rel="shortcut icon" href="http://zanella.horizonit.ch/favicon.ico" type="image/x-icon" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<script type="text/javascript" src="../inc/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript">
	function toggleByClass(className) {
	     $("."+className).toggle();
	}
	</script>
<body>

<?php
// Seitenüberschrift
echo "<h1>".$seitentitel." <button onclick=\"toggleByClass('toggle');\">Details</button></h1>";

// Inhalt

if (isset($_GET['print']))
{
	if ($_GET['print'] == 'day')
	{
		if (isset($_GET['day']))
		{
			$day	=	date('Y-m-d',strtotime($_GET['day']));
			echo "<h1>Auftr&auml;ge vom: ".date('d.m.Y',strtotime($_GET['day']))."</h1>\n";

			$TRA->printItemsDay($day);

		}
	}

	if ($_GET['print'] == 'week')
	{
		if (isset($_GET['week']))
		{
			$startday		=	new DateTime($_GET['week']);
			if ($startday->format('D') == 'Sun') {
				$modify 	=	6;
			}
			else {$modify = $startday->format('w') - 1;
			}

			$startday = $startday->modify('-'.$modify.' day'); // $tag = Montag der Woche

			$montag			=	clone $startday;
			$dienstag		=	clone $startday->add(new DateInterval('P1D'));
			$mittwoch		=	clone $startday->add(new DateInterval('P1D'));
			$donnerstag		=	clone $startday->add(new DateInterval('P1D'));
			$freitag		=	clone $startday->add(new DateInterval('P1D'));
			$woche			=	clone $startday->add(new DateInterval('P1D')); // Samstag
			$abruf			=	clone $startday->add(new DateInterval('P1D')); // Sonntag

			echo "<h1>Auftr&auml;ge der Woche ".$montag->format('W')."</h1>\n";
			echo "<div class='print'>\n";
			echo "<table class='grey'>\n";
			echo "<tr>";
			echo "  <th width='400'>Mo - ".$montag->format('d.m.Y')."</th>";
			echo "  <th width='400'>Di - ".$dienstag->format('d.m.Y')."</th>";
			echo "  <th width='400'>Mi - ".$mittwoch->format('d.m.Y')."</th>";
			echo "  <th width='400'>Do - ".$donnerstag->format('d.m.Y')."</th>";
			echo "  <th width='400'>Fr - ".$freitag->format('d.m.Y')."</th>";
			echo "</tr>";
			echo "<tr>";
			echo "  <td valign='top'>\n";
					$TRA->printItemsDay($montag->format('Y-m-d'));
			echo "  </td>\n";
			echo "  <td valign='top'>\n";
					$TRA->printItemsDay($dienstag->format('Y-m-d'));
			echo "  </td>\n";
			echo "  <td valign='top'>\n";
					$TRA->printItemsDay($mittwoch->format('Y-m-d'));
			echo "  </td>\n";
			echo "  <td valign='top'>\n";
					$TRA->printItemsDay($donnerstag->format('Y-m-d'));
			echo "  </td>\n";
			echo "  <td valign='top'>\n";
					$TRA->printItemsDay($freitag->format('Y-m-d'));
			echo "  </td>\n";
			echo "</tr>";
			echo "</table>";
			echo "<hr />\n";
			echo "<h1>F&uuml;r Woche ".$montag->format('W')." versprochen:</h3>";

					$TRA->printItemsWeek($woche->format('Y-m-d'));
			echo "<br clear='all' />\n";
			echo "<h1>Woche ".$montag->format('W')." auf Abruf:</h1>";
					$TRA->printItemsWeek($abruf->format('Y-m-d'));
			echo "</div>\n";
			}
	}

	if ($_GET['print'] == 'weeks')
	{
		if (is_numeric($_GET['anz']))
		{
			$weeks	=	$_GET['anz'];

			// Berechnung des Montags der Woche
			$heute			=	new DateTime();
			$startday		=	clone $heute;

			if ($heute->format('D') == 'Sun') {
				$modify 	=	6;
			}
			else {
				$modify		=	$startday->format('w') - 1;
			}

			$tag		=	$startday->modify('-'.$modify.' day');


			echo "<h1>Auftr&auml;ge der n&auml;chsten ".$weeks." Wochen.</h1>\n";

			for ($w = 1; $w <= $weeks; $w++)
			{
				echo "<h1>Woche ".$tag->format('W')."</h1>\n";

				for ($i = 1; $i <= 6; $i++)
				{
					if ($i == 6) {echo "<h1>Versprochen f&uuml;r diese Woche</h1>\n";}
					else {echo "<h1>".$tag->format('d.m.Y')."</h1>\n";}
					echo "<ul>\n";
					$TRA->printItemsDay($tag->format('Y-m-d'));
					echo "</ul>\n";

					$tag->add(new DateInterval('P1D'));
				}
			}

		}
	}

}
else
{
	echo "Was solls....";
}
?>
</body>
</html>
