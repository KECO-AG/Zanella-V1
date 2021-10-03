<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Bitte alle Felder ausfuellen!";
$msg[2] = "";
$msg[3] = "";

// Definition Seitentitel
$PAGE_TITLE = "Transportauftrag erfassen";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
?>
<link type="text/css" href="/inc/css/smoothness/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
<script type="text/javascript" src="inc/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="inc/js/jquery-ui-1.8.9.custom.min.js"></script>
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
</script>
<?php
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Transportauftrag erfassen</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt
?>
<br />
<div align="left">
<fieldset style='padding:2px;width:600px;border:1px solid grey;'>
	<legend>Neuer Auftrag</legend>
	<form action="/scripts/tra-neu.php?action=add" method="post">
	<table>
	<tr>
		<td>
		Priorit&auml;t:&nbsp;&nbsp;&nbsp;
		</td>
		<td>
		<select name="prio">
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
		<input type="text" class="standardField" name="auftrag" style="width:300px" />*
		</td>
	</tr>
	<tr>
		<td>
		Bemerkung:
		</td>
		<td>
		<textarea  class="standardTextarea" rows="8" cols="50" name="bemerkung"></textarea>
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
	<tr>
		<td></td>
		<td>
		<input type='submit' class='standardSubmit' name='Speichern' value='Auftrag Hinzuf&uuml;gen' />
		<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />
		</td>
	</tr>
	</table>
	</form>
</fieldset>
</div>
<br />
<hr />


<?php
// Ende Inhalt
$HTML->printFoot($start_time);
?>