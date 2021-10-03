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
$msg[2] = "Paket eingefügt!";
$msg[3] = "Message 3";

// Seitentitel
$PAGE_TITLE = "Rohhobel Lager - Neues Paket";

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
echo "<h1>Rohhobel Lager - Neues Paket</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

if(isset($_GET['lastITEM']))
{
	$sql_lastITEM = "
		SELECT
		  za_holzarten.name AS holzname,
		  za_holzqualitaet.name AS holzqualitaet,
		  za_holztrocknung.name AS holztrocknung,
		  rhl_comments.comments,
		  rhl_lager.paket,
		  rhl_lager.preis,
		  rhl_lager.dicke,
		  rhl_lager.breite,
		  rhl_lager.laenge,
		  rhl_lager.stk,
		  rhl_lager.`date`,
		  rhl_lager.id,
		  rhl_lager.holzart,
		  rhl_lager.qualitaet,
		  rhl_lager.trocknung,
		  ((rhl_lager.dicke*rhl_lager.breite*rhl_lager.laenge)*rhl_lager.stk)/1000000000 as m3,
		  (rhl_lager.breite*rhl_lager.laenge)/1000000 as m2
		FROM
		  rhl_lager
		  INNER JOIN za_holzarten ON (rhl_lager.holzart = za_holzarten.id)
		  INNER JOIN za_holztrocknung ON (rhl_lager.trocknung = za_holztrocknung.id)
		  INNER JOIN za_holzqualitaet ON (rhl_lager.qualitaet = za_holzqualitaet.id)
		  LEFT OUTER JOIN rhl_comments ON (rhl_lager.id = rhl_comments.rhl_lager_id)
		WHERE
		  rhl_lager.id = ".$DB->escapeString($_GET['lastITEM'])." AND
		  rhl_lager.deleted IS NULL
	";
	$lastITEM = $DB->query($sql_lastITEM);
}
// Inhalt

?>
<div align="left">
<fieldset style='padding:5px;width:500px;border:1px solid grey;'>
	<legend>Paket Details</legend>
	<?php
	if (!empty($lastITEM))
	{
		echo "<p align='left'><b>Letzter Eintrag: </b><br />Paket-Nr.: ".$lastITEM[0]['paket']." - Holzart: ".$lastITEM[0]['holzname']."</p>";
	}

	?>

    <form name="newentry" action="/scripts/script-rhl.php?action=neuesPaket" method="post">
    <table>
      <tr>
        <th width="150">Holzart</th>
        <td width="450">
        <select name='holzart' class='standardSelect'>
        <?php
        $sql_holzart = "SELECT * FROM za_holzarten ORDER BY sort ASC";
        $result_holzart = $DB->query($sql_holzart);
        if (!empty($lastITEM)) {
        	echo "<option value='".$lastITEM[0]['holzart']."'>".$lastITEM[0]['holzname']."</option>\n";
        }
        foreach ($result_holzart as $holzart)
        {
        	echo "<option value='".$holzart['id']."'>".$holzart['name']."</option>\n";
        }
        ?>
        </select>
        </td>
      </tr>
      <tr>
        <th>Qualit&auml;t</th>
        <td>
        <select name='qualitaet' class='standardSelect'>
        <?php
        $sql_qualitaet = "SELECT * FROM za_holzqualitaet ORDER BY sort ASC";
        $result_qualitaet = $DB->query($sql_qualitaet);
		if (!empty($lastITEM)) {
			echo "<option value='".$lastITEM[0]['qualitaet']."'>".$lastITEM[0]['holzqualitaet']."</option>\n";
		}
        foreach ($result_qualitaet as $qualitaet)
        {
        	echo "<option value='".$qualitaet['id']."'>".$qualitaet['name']."</option>\n";
        }
        ?>
        </select>
        </td>
      </tr>
      <tr>
        <th>Trocknung</th>
        <td>
          <select name='trocknung' class='standardSelect'>
          <?php
          $sql_trocknung = "SELECT * FROM za_holztrocknung ORDER BY sort ASC";
          $result_trocknung = $DB->query($sql_trocknung);
		  if (!empty($lastITEM)) {
			  echo "<option value='".$lastITEM[0]['trocknung']."'>".$lastITEM[0]['holztrocknung']."</option>\n";
		  }
          foreach ($result_trocknung as $trocknung)
          {
          	echo  "<option value='".$trocknung['id']."'>".$trocknung['name']."</option>\n";
          }
          ?>

          </select>
        </td>
      </tr>
      <tr>
        <th>Paket</th>
        <td><input type="text" class="standardField" name="paketnr" />*</td>
      </tr>
      <tr>
        <th>Preis</th>
        <td><input type="text" class="standardField" name="preis" /></td>
      </tr>
      <tr>
        <th>Dicke</th>
        <td><input type="text" class="standardField" name="dicke" />*, mm</td>
      </tr>
      <tr>
        <th>Breite</th>
        <td><input type="text" class="standardField" name="breite" />*, mm</td>
      </tr>
      <tr>
        <th>L&auml;nge</th>
        <td><input type="text" class="standardField" name="laenge" />*, mm</td>
      </tr>
      <tr>
        <th>St&uuml;ck</th>
        <td><input type="text" class="standardField" name="stk" /> Stk.</td>
      </tr>
      <tr>
        <th>Bemerkung</th>
        <td><textarea class="standardTextarea" rows="5" cols="25" name="bemerkung"></textarea></td>
      </tr>
      <tr>
        <th>Datum</th>
        <td><input id="datepicker" type="text" class="standardField" name="datum" readonly="readonly" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>* muss angegeben werden.</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
          <input type="submit" class='standardSubmit' value="Senden" />
          <input type="reset" class='standardSubmit' value="L&ouml;schen" />
        </td>
      </tr>
    </table>
    </form>
	<script type="text/javascript">
		document.newentry.holzart.focus();
	</script>
</fieldset>
</div>
<?php
$HTML->printFoot($start_time);
?>