<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Bitte alle Angaben angeben.";
$msg[2] = "";
$msg[3] = "Sie sind nicht berechtigt Eintr&auml;ge zu l&ouml;schen!";

// Definition Seitentitel
$PAGE_TITLE = "";

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
$id = $DB->escapeString($_GET['id']);
$holzart_liste = $DB->escapeString($_GET['holzart']);
echo "<h1>Paket bearbeiten.</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt
$sql = "
SELECT
 hwl_items.id,
 hwl_items.produkt,
 hwl_items.paket,
 hwl_items.preis,
 hwl_items.dicke,
 hwl_items.breite,
 hwl_items.laenge,
 hwl_items.stk,
 hwl_items.`date`,
 za_hwl_produkte.name AS produkt_name,
 hwl_comments.comments,
 hwl_items.deleted,
 hwl_items.restposten
FROM
  hwl_items
  INNER JOIN za_hwl_produkte ON (hwl_items.produkt = za_hwl_produkte.id)
  LEFT JOIN hwl_comments ON (hwl_items.id = hwl_comments.hwl_lager_id)
WHERE
  hwl_items.id = '".$id."'
";
$result = $DB->query($sql);
?>
<div align="left">
<fieldset style='padding:5px;width:500px;border:1px solid grey;'>
	<legend>Paket Details: <?php echo $result[0]['paket']; ?></legend>
    <form action="/scripts/script-hwl.php?action=update&id=<?php echo $id; ?>&holzart=<?php echo $holzart_liste; ?>" method="post">
    <table>
      <tr>
        <th width="150">Produkt</th>
        <td width="450">
        <select name='produkt' class='standardSelect'>
        <option value='<?php echo $result[0]['produkt']; ?>'><?php echo $result[0]['produkt_name']; ?></option>
        <option disabled='disabled'>-------------</option>
        <?php
        $sql_holzart = "SELECT * FROM za_hwl_produkte ORDER BY sort ASC";
        $result_holzart = $DB->query($sql_holzart);
        foreach ($result_holzart as $holzart)
        {
        	echo "<option value='".$holzart['id']."'>".$holzart['name']."</option>\n";
        }
        ?>
        </select>
        </td>
      </tr>
      <tr>
        <th>Paket</th>
        <td><input type="text" class="standardField" name="paketnr" value="<?php echo $result[0]['paket']; ?>" />*</td>
      </tr>
      <tr>
        <th>Preis</th>
        <td><input type="text" class="standardField" name="preis" value="<?php echo $result[0]['preis']; ?>" /></td>
      </tr>
      <tr>
        <th>Dicke</th>
        <td><input type="text" class="standardField" name="dicke" value="<?php echo $result[0]['dicke']; ?>" />*, mm</td>
      </tr>
      <tr>
        <th>Breite</th>
        <td><input type="text" class="standardField" name="breite" value="<?php echo $result[0]['breite']; ?>" />*, mm</td>
      </tr>
      <tr>
        <th>L&auml;nge</th>
        <td><input type="text" class="standardField" name="laenge" value="<?php echo $result[0]['laenge']; ?>" />*, mm</td>
      </tr>
      <tr>
        <th>St&uuml;ck</th>
        <td><input type="text" class="standardField" name="stk" value="<?php echo $result[0]['stk']; ?>" /> Stk.</td>
      </tr>
      <tr>
        <th>Bemerkung</th>
        <td><textarea class="standardTextarea" rows="5" cols="25" name="bemerkung"><?php echo $result[0]['comments']; ?></textarea></td>
      </tr>
      <tr>
        <th>Datum</th>
        <td><input id="datepicker" type="text" class="standardField" name="datum" readonly="readonly" value="<?php echo $result[0]['date']; ?>" /></td>
      </tr>
      <tr>
        <th>Restposten</th>
        <td><input type='checkbox' name='restposten' value='1' <?php if($result[0]['restposten'] == 1) {echo "checked";} ?>></td>
      </tr>
      <tr>
        <th>Gel&ouml;scht?</th>
        <td><input type='checkbox' name='deleted' value='deleted' <?php if(isset($result[0]['deleted'])) {echo "checked";} ?>> Gel&ouml;scht am: <?php echo $result[0]['deleted']; ?>
         <?php
       	if (isset($result[0]['deleted'])) {
       		$deleteDate = $result[0]['deleted'];
       	}
        else {
        	$timestamp = new DateTime();
        	$deleteDate = $timestamp->format('Y-m-d G:i:s');;
        }
        ?>
		<input type="hidden" value="<?php echo $deleteDate; ?>" name="deleteDate" />
		</td>
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
</fieldset>
</div>
<?php

// Ende Inhalt
$HTML->printFoot($start_time);
?>