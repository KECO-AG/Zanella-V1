<?php
require_once 'common.php';

/*
 * GET Parameters
 * message, jobID, holzart, schnittart
 */

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Eintrag erfolgreich eingefuegt.";
$msg[2] = "Bitte alle Felder ausfuellen.";
$msg[3] = "";

// Seitentitel
$PAGE_TITLE = "Neuer Job-Eintrag";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Kontrolle ob jobID vorhanden
if(empty($_GET['jobID']))
{
	(header("Location: ../esv_job_list.php"));
	die();
}
$sql = "SELECT za_mitarbeiter.name, esv_jobs.id, esv_jobs.job_datum, esv_jobs.art AS job_art FROM esv_jobs INNER JOIN za_mitarbeiter ON (esv_jobs.ma_id = za_mitarbeiter.id) WHERE esv_jobs.id = ".$DB->escapeString($_GET['jobID'])." ";
$selected = $DB->query($sql);

if (empty($selected[0]['job_datum']))
{
	(header("Location: ../esv_job_list.php"));
	die();	
}

// Seitenüberschrift
echo "<h1>Neuer Eintrag zu Job</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

if(isset($_GET['lastITEM']))
{
	$sql_lastITEM = "
	SELECT 
	  za_schnittarten.name,
	  esv_jobs.job_datum,
	  esv_schnitt.stk,
	  esv_schnitt.laenge,
	  esv_schnitt.dm,
	  za_mitarbeiter.name AS ma_name,
	  za_mitarbeiter.vorname AS ma_vname,
	  za_holzarten.name AS holzart
	FROM
	  esv_schnitt
	  INNER JOIN za_schnittarten ON (esv_schnitt.schnittart = za_schnittarten.id)
	  INNER JOIN za_holzarten ON (esv_schnitt.holz_id = za_holzarten.id)
	  INNER JOIN esv_jobs ON (esv_schnitt.job_id = esv_jobs.id)
	  INNER JOIN za_mitarbeiter ON (esv_jobs.ma_id = za_mitarbeiter.id)
	WHERE
	  esv_schnitt.id = ".$DB->escapeString($_GET['lastITEM'])."	
		";
	$lastITEM = $DB->query($sql_lastITEM);
}

$sql_holzart = "SELECT * FROM za_holzarten ORDER BY sort ASC";

?>
<div align="center">
	<fieldset style='padding:5px;width:500px;border:1px solid grey;'>
	<legend>Schnitt Details</legend>
	<?php 
	if (!empty($lastITEM))
	{
		echo "<p align='left'><b>Letzter Eintrag:</b><br />Stk.: ".$lastITEM[0]['stk']."<br />Laenge: ".$lastITEM[0]['laenge']." m <br />DM: ".$lastITEM[0]['dm']." cm</p>";
	}
	
	 
	?>
	<form id="newentry" name="newentry" action="/scripts/esv-new-entry.php" method="post">
	<table>
	<tr>
		<td width="150">Job</td>
		<td>
			<?php
				echo "<input type=\"hidden\" name=\"jobs\" value=\"".$selected[0]['id']."\">\n";
				echo date('d.m.y',strtotime($selected[0]['job_datum']))." :: ".$selected[0]['name'];
			?>
		</td>
	</tr>
	<tr>
		<td>Holzart</td>
		<td>
		<select name="holzart" class="standardSelect">
		<?php 
			if(isset($_GET["holzart"]))
			{
				$sql = "SELECT * FROM za_holzarten WHERE za_holzarten.id = ".$DB->escapeString($_GET['holzart'])."";
				$selected = $DB->query($sql);
				echo "<option value=\"".$selected[0]['id']."\">".$selected[0]['name']."</option>\n";
			}
			?>
				<option disabled="disabled">&nbsp;</option>
			<?php 
			$holzart_results = $DB->query($sql_holzart);
			foreach ($holzart_results as $holzart)
			{
				echo "<option value=\"".$holzart['id']."\">".$holzart['name']."</option>\n";
			}
		?>			
		</select>*	
		</td>
	</tr>
	<tr>
		<td>Schnittart</td>
		<td>
		<select name="schnittart" class="standardSelect">
		<?php 
			if(isset($_GET["schnittart"]))
			{
				$sql = "SELECT * FROM za_schnittarten WHERE za_schnittarten.id = ".$DB->escapeString($_GET['schnittart'])."";
				$selected = $DB->query($sql);
				echo "<option value=\"".$selected[0]['id']."\">".$selected[0]['name']."</option>\n";
			}
			?>
				<option disabled="disabled">&nbsp;</option>
			<?php 
			$sql_schnittarten = "SELECT * FROM za_schnittarten WHERE za_schnittarten.job_art = '".$selected[0]['job_art']."' ORDER BY  name ASC";
			$schnittarten = $DB->query($sql_schnittarten);
			foreach ($schnittarten as $schnittart)
			{
				echo "<option value=\"".$schnittart['id']."\">".$schnittart['name']."</option>\n";
			}
		?>
		</select>*	
		</td>
	</tr>
	<tr>
		<td>St&uuml;ck</td>
		<td><input type="text" class="standardField" name="stk" />*</td>
	</tr>
	<tr>
		<td>L&auml;nge</td>
		<td><input type="text" class="standardField" name="laenge" />* m</td>
	</tr>
	<tr>
		<td>Durchmesser</td>
		<td><input type="text" class="standardField" name="dm" />* cm</td>
	</tr>
	<?php if ($selected[0]['job_art'] == 2){ ?>
	<tr>
		<td>Rinde</td>
		<td><input type="text" class="standardField" name="rinde" />%</td>
	</tr>
	<?php } ?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type='submit' class='standardSubmit' name='doLogin' value='Job Hinzuf&uuml;gen' />
			<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />
		</td>
	</tr>	
	</table>
	</form>
	<script type="text/javascript">
		document.newentry.stk.focus();	
	</script>
	<p><code>* Muss angegeben werden.</code></p>
	</fieldset>
</div>
<?php 
$HTML->printFoot($start_time);
?>