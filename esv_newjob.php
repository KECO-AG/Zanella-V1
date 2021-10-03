<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Bitte alle ben&ouml;tigten Felder ausf&uuml;llen.";
$msg[2] = "Job hinzugef&uuml;gt!";
$msg[3] = "Nachricht 3";

// Definition Seitentitel
$PAGE_TITLE = "Neuen Job erstellen";

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
				maxDate: '+0'
			} );		
	});
</script>
<?php 
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Neuen Job erstellen.</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt
?>
<br />
<div align="center">
	<fieldset style='padding:5px;width:500px;border:1px solid grey;'>
	<legend>Job Details</legend>
	<form action="/scripts/esv-newjob.php" method="post">
	<table>
	<tr>
		<td width="150">Datum</td>
		<td><input name="datum" type="text" id="datepicker" class="standardSelect" readonly>*</td>
	</tr>
	<tr>
		<td>Mitarbeiter</td>
		<td>
		<select name="mitarbeiter" class="standardSelect">
			<option></option>
			<?php 
			$sql = "SELECT * FROM za_mitarbeiter ORDER BY name ASC";
			$result = $DB->query($sql);
			foreach ($result as $ma)
			{
				echo "<option value=\"".$ma['id']."\">".$ma['name']." ".$ma['vorname']."</option>";
			}
			?>
		</select>*	
		</td>
	</tr>
		<tr>
		<td>Jobart</td>
		<td>
		<select name="jobart" class="standardSelect">
			<option value="1">BBS</option>
			<option value="2">Gatter</option>
		</select>*	
		</td>
	</tr>
	<tr>
		<td>Stunden</td>
		<td><input type="text" class="standardField" name="stunden" />*</td>
	</tr>
	<tr>
		<td>Total Blattwechsel</td>
		<td><input type="text" class="standardField" value="0" name="bw" />*</td>
	</tr>
	<tr>
		<td>Bemerkung</td>
		<td><textarea cols="20" rows="5" class="standardTextarea" name="bemerkung"></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type='submit' class='standardSubmit' name='doLogin' value='Job Hinzuf&uuml;gen' />
			<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />
		</td>
	</tr>	
	</table>
	</form>
	<p><code>* Muss angegeben werden.</code></p>
	</fieldset>
</div>
<?php 
// Ende Inhalt
$HTML->printFoot($start_time);
?>