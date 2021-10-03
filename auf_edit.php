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
$PAGE_TITLE = "Auftrag &auml;ndern";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
?>
<link type="text/css" href="/inc/css/smoothness/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
<script type="text/javascript" src="inc/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="inc/js/jquery-ui-1.8.9.custom.min.js"></script>
<script type="text/javascript">
	$(function() {
		$( "#datepicker" ).datepicker(
			{
				dateFormat: 'yy-mm-dd',
				firstDay: 1,
				showWeek: true,
			} );
	});
</script>
<script type="text/javascript">
	$(function() {
		$( "#erledigtPicker" ).datepicker(
			{
				dateFormat: 'yy-mm-dd',
				firstDay: 1,
				showWeek: true,
			} );
	});
</script>
<?php

$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Auftrag &auml;ndern.</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt
$id = $DB->escapeString($_GET['id']);

$sql	=	"SELECT * FROM `auf_items` WHERE `id` = ".$id."";
$result	=	$DB->query($sql);

?>
<br />
<div align="left">
<fieldset style='padding:2px;width:600px;border:1px solid grey;'>
	<legend>Auftrag &auml;ndern</legend>
	<form action="/scripts/auf-edit.php?action=upd" method="post">
	<table>
	<tr>
		<td>Erledigt?</td>
		<td><input type='checkbox' name='erledigt' value='erledigt' <?php if(isset($result[0]['erledigt'])) {echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>
		Priorit&auml;t:&nbsp;&nbsp;&nbsp;
		</td>
		<td>
		<select name="prio">
			<?php echo "<option value='".$result[0]['prio']."'>".$result[0]['prio']."</option>"; ?>
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
		<input type="text" class="standardField" name="auftrag" value="<?php echo $result[0]['auftrag']; ?>" />*
		</td>
	</tr>
	<tr>
		<td>
		Bemerkung:
		</td>
		<td>
		<textarea  class="standardTextarea" rows="8" cols="50" name="bemerkung"><?php echo $result[0]['bemerkung']; ?></textarea>
		</td>
	</tr>

	<tr>
		<td>
			Datum:
		</td>
		<td>
			<input id="datepicker" type="text" class="standardField" name="datum" readonly="readonly" value="<?php echo $result[0]['datum']; ?>"/>*<br />
		</td>
	</tr>
	<tr>
		<td>
			Erledigt:
		</td>
		<td>
			<input id="erledigtPicker" type="text" class="standardField" name="ErledigtDate" readonly="readonly" value="<?php if(isset($result[0]['erledigt'])) {echo $result[0]['erledigt'];} else { echo $result[0]['datum']; } ?>"/><br />
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
		<input type='hidden' name='id' value='<?php echo $result[0]['id']; ?>' />
		<input type='submit' class='standardSubmit' name='Speichern' value='Auftrag Hinzuf&uuml;gen' />
		<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />
		</td>
	</tr>
	</table>
	</form>
</fieldset>
</div>

<?php
// Ende Inhalt
$HTML->printFoot($start_time);
?>