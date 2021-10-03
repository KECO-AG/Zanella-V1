<?php
require_once 'common.php';

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Wieviel Wochen anzeigen
$max_weeks	=	60;
if (isset($_GET['w']))
{
	if (is_numeric($_GET['w']))
	{
		if ($_GET['w'] >= $max_weeks)
		{
			$anz_wochen	=	$max_weeks;
		}
		else
		{
			$anz_wochen = $_GET['w'];
		}
	}
} else { $anz_wochen =	5;}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="author" content="horizon IT GmbH"/>
	<link rel="stylesheet" href="./inc/css/redips/style-tra.css" type="text/css" media="screen" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript">
		var wback = <?php if (isset($_GET['wback'])) { if (is_numeric($_GET['wback'])) {echo $_GET['wback'];} else echo "0";} else echo "0"; ?>;
	</script>
	<script type="text/javascript" src="./inc/js/redips-drag-min.js"></script>
	<script type="text/javascript" src="./inc/js/redips-init-tra.js"></script>
	<link type="text/css" href="inc/css/smoothness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
	<title>Transportverwaltung :: Zanella Holz AG</title>
	<script type="text/javascript" src="inc/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="inc/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="inc/js/jquery-ui-1.8.21.custom.min.js"></script>
	<script type="text/javascript" src="inc/js/jquery-cookie/jquery.cookie.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
		// jQuery functions go here.			
			var theState;
			if( $.cookie("viewState") ){ // check if cookie exists
				  // do something with cookie value
				  theState	=	$.cookie("viewState") ;
			}
			else{
				  // set cookie with default value
				  theState	=	"none";
				  $.cookie("viewState", theState);
			}

			// update css DEPENDING ON COOKIE
			$(".toggle").css("display",theState);
			
			// 2nd test
			$('#bt-toggle').click(function() {
				if($('.toggle').css("display") == 'block') {
					theState	=	"none";
					$('.toggle').css("display",theState);
					$.cookie("viewState", theState);
				} else {
					theState	=	"block";
					$('.toggle').css("display",theState);
					$.cookie("viewState", theState);
				}
			});			
			
		// END jQuery functions
		});		
	</script>
	<script type="text/javascript">
		$(function(){
			// Dialog
			$('#dialog').dialog({
				autoOpen: false,
				width: 600,
				buttons: {
						"Speichern": function() {$('#new').submit(); $(this).dialog("close");},
						"Abbrechen": function() {$(this).dialog("close");}
						}
			});
			// Dialog Link
			$('#dialog_link').click(function(){
				$('#dialog').dialog('open');
				return false;
			});
		});
	</script>
	<script type="text/javascript">
		$(function(){
			// Dialog
			$('#dialog-edit').dialog({
				autoOpen: false,
				width: 600,
				buttons: {
						"Speichern": function() {$('#edit').submit(); $(this).dialog("close");},
						"Abbrechen": function() {$(this).dialog("close");}
						}
			});
		});
	</script>
	<script type="text/javascript">
		$(function() {
			$( "#datepicker" ).datepicker(
				{
					dateFormat: 'yy-mm-dd',
					numberOfMonths: 2,
					firstDay: 1,
					showWeek: true,
					showAnim: 'slideDown',
				} );
		});
		$(function() {
			$( "#edit-datepicker" ).datepicker(
				{
					dateFormat: 'yy-mm-dd',
					numberOfMonths: 2,
					firstDay: 1,
					showWeek: true,
					showAnim: 'slideDown',
				} );
		});
		$(function() {
			$( "#edit-erledigtPicker" ).datepicker(
				{
					dateFormat: 'yy-mm-dd',
					firstDay: 1,
					showWeek: true,
				} );
	});
	</script>
</head>
<body bgcolor="#CCFFFF">
	<h4>Transportverwaltung :: <a href="/">HOME</a> :: <a href="#" id="dialog_link">Neuer Auftrag</a> :: <a href="./tra_erledigt.php">Erledigten Liste</a> -- Anzahl Wochen: <a href="?w=1<?php if(isset($_GET['wback'])) {echo "&wback=".$_GET['wback'];} ?>">1</a> - <a href="?w=2<?php if(isset($_GET['wback'])) {echo "&wback=".$_GET['wback'];} ?>">2</a> - <a href="?w=4<?php if(isset($_GET['wback'])) {echo "&wback=".$_GET['wback'];} ?>">4</a> - <a href="?w=8<?php if(isset($_GET['wback'])) {echo "&wback=".$_GET['wback'];} ?>">8</a> - <a href="?w=12<?php if(isset($_GET['wback'])) {echo "&wback=".$_GET['wback'];} ?>">12</a> -- Wochen zur&uuml;ck: <a href="?wback=0<?php if(isset($_GET['w'])) {echo "&w=".$_GET['w'];} ?>">0</a> - <a href="?wback=1<?php if(isset($_GET['w'])) {echo "&w=".$_GET['w'];} ?>">1</a> - <a href="?wback=2<?php if(isset($_GET['w'])) {echo "&w=".$_GET['w'];} ?>">2</a> - <a href="?wback=4<?php if(isset($_GET['w'])) {echo "&w=".$_GET['w'];} ?>">4</a> - <a href="?wback=8<?php if(isset($_GET['w'])) {echo "&w=".$_GET['w'];} ?>">8</a> - <a href="?wback=12<?php if(isset($_GET['w'])) {echo "&w=".$_GET['w'];} ?>">12</a> -- <button id="bt-toggle">Details anzeigen / ausblenden</button></h4>
<?php

// Berechnung des Montags der Woche
$heute			=	new DateTime();
$startday		=	clone $heute;
if ($startday->format('D') == 'Sun') {	$modify = 6;}
else { $modify = $startday->format('w') - 1;}
$startday		=	$startday->modify('-'.$modify.' day');
if(isset($_GET['wback'])) {
	$days = $_GET['wback']*7;
	$startday = $startday->modify('-'.$days.' day');
}

?>
	<div id="drag">
	<table id="table3">
	<colgroup><col width="100"/></colgroup>
		<tr>
			<td class="mark"><form name="search" action="tra_such_resultate.php" method="get"><b>Suchen:</b> <input type="text" name="searchvalue" /> </form></td>
			<td class="trash" title="Trash"><b>L&ouml;schen</b></td>
			<td class="mark"><b><font color="white">Weiss</font></b> = geplant, <b><font color="red">Rot</font></b> = &uuml;berschritten, <b><font color="green">Gr&uuml;n</font></b> = erledigt</td>
		</tr>
	</table>
	<hr />
	<table id="table1" class="dragdrop">
	<colgroup><col width="274"/><col width="274"/><col width="274"/><col width="274"/><col width="274"/><col width="274"/><col width="274"/></colgroup>
	<tr>
		<td class="mark"><b>Montag</b></td>
		<td class="mark"><b>Dienstag</b></td>
		<td class="mark"><b>Mittwoch</b></td>
		<td class="mark"><b>Donnerstag</b></td>
		<td class="mark"><b>Freitag</b></td>
		<td class="mark"><b>Woche</b></td>
		<td class="mark"><b>Auf Abruf</b></td>
	</tr>
<?php
for ($i = 1; $i <= $anz_wochen; $i++)
{
	// Anzuzeigende Tage
	$montag			=	clone $startday;
	$dienstag		=	clone $startday->add(new DateInterval('P1D'));
	$mittwoch		=	clone $startday->add(new DateInterval('P1D'));
	$donnerstag		=	clone $startday->add(new DateInterval('P1D'));
	$freitag		=	clone $startday->add(new DateInterval('P1D'));
	$woche			=	clone $startday->add(new DateInterval('P1D')); // Samstag
	$abruf			=	clone $startday->add(new DateInterval('P1D')); // Sonntag

	echo "	<tr>\n";
	echo "		<td class='mark'><b>".$montag->format('d.m.Y')."</b>&nbsp;&nbsp;<a href='./print/print_tra.php?print=day&day=".$montag->format('d.m.Y')."' target='_blank'><img src='./images/print_icon.png' target='_blank' width='16'></a></td>\n";
	echo "		<td class='mark'><b>".$dienstag->format('d.m.Y')."</b>&nbsp;&nbsp;<a href='./print/print_tra.php?print=day&day=".$dienstag->format('d.m.Y')."' target='_blank'><img src='./images/print_icon.png' target='_blank' width='16'></a></td>\n";
	echo "		<td class='mark'><b>".$mittwoch->format('d.m.Y')."</b>&nbsp;&nbsp;<a href='./print/print_tra.php?print=day&day=".$mittwoch->format('d.m.Y')."' target='_blank'><img src='./images/print_icon.png' target='_blank' width='16'></a></td>\n";
	echo "		<td class='mark'><b>".$donnerstag->format('d.m.Y')."</b>&nbsp;&nbsp;<a href='./print/print_tra.php?print=day&day=".$donnerstag->format('d.m.Y')."' target='_blank'><img src='./images/print_icon.png' target='_blank' width='16'></a></td>\n";
	echo "		<td class='mark'><b>".$freitag->format('d.m.Y')."</b>&nbsp;&nbsp;<a href='./print/print_tra.php?print=day&day=".$freitag->format('d.m.Y')."' target='_blank'><img src='./images/print_icon.png' target='_blank' width='16'></a></td>\n";
	echo "		<td class='mark'><b>".$woche->format('W')."</b>&nbsp;&nbsp;<a href='./print/print_tra.php?print=week&week=".$montag->format('d.m.Y')."' target='_blank'><img src='./images/print_icon.png' target='_blank' width='16'></a></td>\n";
	echo "		<td class='mark'><b>".$abruf->format('W')."</b>&nbsp;&nbsp;<a href='./print/print_tra.php?print=week&week=".$montag->format('d.m.Y')."' target='_blank'><img src='./images/print_icon.png' target='_blank' width='16'></a></td>\n";
	echo "	</tr>\n";

	echo "	<tr>\n";
	if ($heute > $montag) 		{echo "	<td class='mark'>"; $TRA->getItemsExpired($montag->format('Y-m-d')); $TRA->getItemsErledigt($montag->format('Y-m-d')); echo "</td>\n";} else {echo "	<td>"; $TRA->getItems($montag->format('Y-m-d')); $TRA->getItemsErledigt($montag->format('Y-m-d')); echo "</td>\n";}
	if ($heute > $dienstag) 	{echo "	<td class='mark'>"; $TRA->getItemsExpired($dienstag->format('Y-m-d')); $TRA->getItemsErledigt($dienstag->format('Y-m-d')); echo "</td>\n";} else {echo "	<td>"; $TRA->getItems($dienstag->format('Y-m-d')); $TRA->getItemsErledigt($dienstag->format('Y-m-d')); echo "</td>\n";}
	if ($heute > $mittwoch) 	{echo "	<td class='mark'>"; $TRA->getItemsExpired($mittwoch->format('Y-m-d')); $TRA->getItemsErledigt($mittwoch->format('Y-m-d')); echo "</td>\n";} else {echo "	<td>"; $TRA->getItems($mittwoch->format('Y-m-d')); $TRA->getItemsErledigt($mittwoch->format('Y-m-d')); echo "</td>\n";}
	if ($heute > $donnerstag) 	{echo "	<td class='mark'>"; $TRA->getItemsExpired($donnerstag->format('Y-m-d')); $TRA->getItemsErledigt($donnerstag->format('Y-m-d')); echo "</td>\n";} else {echo "	<td>"; $TRA->getItems($donnerstag->format('Y-m-d')); $TRA->getItemsErledigt($donnerstag->format('Y-m-d')); echo "</td>\n";}
	if ($heute > $freitag) 		{echo "	<td class='mark'>"; $TRA->getItemsExpired($freitag->format('Y-m-d')); $TRA->getItemsErledigt($freitag->format('Y-m-d')); echo "</td>\n";} else {echo "	<td>"; $TRA->getItems($freitag->format('Y-m-d')); $TRA->getItemsErledigt($freitag->format('Y-m-d')); echo "</td>\n";}
	if ($heute > $woche) 		{echo "	<td class='mark'>"; $TRA->getItemsExpired($woche->format('Y-m-d')); $TRA->getItemsErledigt($woche->format('Y-m-d')); echo "</td>\n";} else {echo "	<td>"; $TRA->getItems($woche->format('Y-m-d')); $TRA->getItemsErledigt($woche->format('Y-m-d')); echo "</td>\n";}
	if ($heute > $abruf) 		{echo "	<td class='mark'>"; $TRA->getItemsExpired($abruf->format('Y-m-d')); $TRA->getItemsErledigt($abruf->format('Y-m-d')); echo "</td>\n";} else {echo "	<td>"; $TRA->getItems($abruf->format('Y-m-d')); $TRA->getItemsErledigt($abruf->format('Y-m-d')); echo "</td>\n";}
	echo "	<tr>\n";

	// Restliche Wochentage addieren
	$startday->add(new DateInterval('P1D'));
}
?>
	</table>
	</div>
	<!-- Popup NEU -->
	<div id="dialog" title="Neuer Auftrag erfassen">
	<form action="/scripts/tra-neu.php?action=add" method="post" id="new">
	<table>
	<tr>
		<td>
		Priorit&auml;t:&nbsp;&nbsp;&nbsp;
		</td>
		<td>
		<select name="prio" id="prio">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
		</select>*
		</td>
	</tr>
	<tr>
		<td>
		Auftrag:
		</td>
		<td>
		<input type="text" class="standardField" name="auftrag" style="width:300px" id="auftrag" />*
		</td>
	</tr>
	<tr>
		<td>
		Bemerkung:
		</td>
		<td>
		<textarea  class="standardTextarea" rows="8" cols="40" name="bemerkung" id="bemerkung"></textarea>
		</td>
	</tr>

	<tr>
		<td>
			Datum:
		</td>
		<td>
			<input id="datepicker" type="text" class="standardField" name="datum" readonly="readonly" />*<br />
		</td>
	</tr>
	</table>
	</form>
	</div>
		<!-- Popup EDIT -->
	<div id="dialog-edit" title="Auftrag bearbeiten">
	<form action="/scripts/tra-edit.php?action=upd-new" method="post" id="edit">
	<table>
	<tr>
		<td>Erledigt?</td>
		<td><input type='checkbox' name='edit-erledigt-check' value='edit-erledigt-check' id='edit-erledigt-check' ></td>
	</tr>
	<tr>
		<td>
		Priorit&auml;t:&nbsp;&nbsp;&nbsp;
		</td>
		<td>
		<select name="edit-prio" id="edit-prio">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
		</select>*
		</td>
	</tr>
	<tr>
		<td>
		Auftrag:
		</td>
		<td>
		<input type="text" class="standardField" name="edit-auftrag" value="" id="edit-auftrag" />*
		</td>
	</tr>
	<tr>
		<td>
		Bemerkung:
		</td>
		<td>
		<textarea  class="standardTextarea" rows="8" cols="50" name="edit-bemerkung" id="edit-bemerkung"></textarea>
		</td>
	</tr>

	<tr>
		<td>
			Datum:
		</td>
		<td>
			<input id="edit-datepicker" type="text" class="standardField" name="edit-datum" readonly="readonly" value=""/>*<br />
		</td>
	</tr>
	<tr>
		<td>
			Erledigt:
		</td>
		<td>
			<input id="edit-erledigtPicker" type="text" class="standardField" name="edit-ErledigtDate" readonly="readonly" value=""/><br />
		</td>
	</tr>
	<tr>
		<td>
			Erfasst durch:
		</td>
		<td>
			<input id="edit-creator" readonly="readonly" value=""/>
		</td>
	</tr>
	<tr>
		<td><input type='hidden' name='edit-id' value='' id='edit-id' /></td>
		<td></td>
	</tr>
	</table>
	</form>
	</div>
</body>
</html>