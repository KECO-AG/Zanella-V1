<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
if($_SESSION['level'] >2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Sortierung ge&auml;ndert.";
$msg[2] = "Hobelware hinzgef&uuml;gt.";
$msg[3] = "";

// Seitentitel
$PAGE_TITLE = "";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Hobelwaren Verwaltung</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

?>
<br />
<div align="center">
	<fieldset style='padding:2px;width:500px;border:1px solid grey;'>
	<legend>Neue Hobelwaren Art hinzuf&uuml;gen</legend>
		<form action="/scripts/za-hwl.php?action=add" method="post">
		<p>
		Name: <input type="text" class="standardField" name="name" /> Sortierung: <input type="text" class="standardField" name="sort" />
		</p>
		<input type='submit' class='standardSubmit' name='Speichern' value='HA Hinzuf&uuml;gen' />
		<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />
		</form>
	</fieldset>
</div>
<br />
<hr />
<table class="grey">
  <tr>
    <th width="200">Name</th>
    <th width="10">Sortierung</th>
  </tr>
  <?php
  $sql = "SELECT * FROM za_hwl_produkte ORDER BY  sort ASC";
  $result = $DB->query($sql);
  foreach ($result as $holzart)
  {
  ?>

  <tr>
  <form action="/scripts/za-hwl.php?action=upd" method="post">
    <td width="400"><?php echo htmlentities($holzart['name']); ?></td>
    <td><input type="text" value="<?php echo htmlentities($holzart['sort']); ?>" name="sort" /></td>
    <td><input type="hidden" value="<?php echo htmlentities($holzart['id']); ?>" name="id" /><input type="image" src="images/icon_edit.png" alt="Bearbeiten"  width='19' height='19' /></td>
  </form>
  </tr>

  <?php
  }
  ?>
</table>
<?php
$HTML->printFoot($start_time);
?>