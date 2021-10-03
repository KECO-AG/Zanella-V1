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
$PAGE_TITLE = "Hobelwarenlager - Neues Paket";

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
echo "<h1>Hobelwarenlager - Neues Paket</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

if(isset($_GET['lastITEM']))
{
	$sql_lastITEM = "
		SELECT
		  za_hwl_produkte.name AS produktname,
		  hwl_comments.comments,
		  hwl_items.paket,
		  hwl_items.preis,
		  hwl_items.dicke,
		  hwl_items.breite,
		  hwl_items.laenge,
		  hwl_items.stk,
		  hwl_items.`date`,
		  hwl_items.id,
		  hwl_items.produkt
		FROM
		  hwl_items
		  INNER JOIN za_hwl_produkte ON (hwl_items.produkt = za_hwl_produkte.id)
		  LEFT OUTER JOIN hwl_comments ON (hwl_items.id = hwl_comments.hwl_lager_id)
		WHERE
		  hwl_items.id = ".$DB->escapeString($_GET['lastITEM'])."
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
		echo "<p align='left'><b>Letzter Eintrag: </b><br />Paket-Nr.: ".$lastITEM[0]['paket']."</p>";
	}

	?>

    <form name="newentry" action="/scripts/script-hwl.php?action=neuesPaket" method="post">
    <table>
      <tr>
        <th width="150">Produkt</th>
        <td width="450">
        <select name='produkt' class='standardSelect'>
        <?php
        $sql_produkte = "SELECT * FROM za_hwl_produkte ORDER BY sort ASC";
        $result_produkte = $DB->query($sql_produkte);
		if (!empty($lastITEM)) {
			echo "<option value='".$lastITEM[0]['produkt']."'>".$lastITEM[0]['produktname']."</option>\n";
		}
        foreach ($result_produkte as $produkt)
        {
        	echo "<option value='".$produkt['id']."'>".$produkt['name']."</option>\n";
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
		document.newentry.paketnr.focus();
	</script>
</fieldset>
</div>
<?php
$HTML->printFoot($start_time);
?>