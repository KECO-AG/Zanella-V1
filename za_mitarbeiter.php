<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));}
// Check User Level
if($_SESSION['level'] >2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Mitarbeiter hinzugef&uuml;gt!";
$msg[2] = "Mitarbeiter gel&ouml;scht!";
$msg[3] = "Bitte Name & Vorname angeben.";
$msg[4] = "Mitarbeiter hat Jobs! Darf nicht gel&ouml;scht werden.";

$PAGE_TITLE = "Mitarbeiter Liste";
$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Mitarbeiter</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

echo "<h2>Neuer Mitarbeiter</h2>";
?>
<br />
<div align="center">
	<fieldset style='padding:2px;width:500px;border:1px solid grey;'>
	<legend>Neuer Mitarbeiter hinzuf&uuml;gen</legend>
		<form action="/scripts/za-mitarbeiter.php?action=add" method="post">
		<p>
		Name: <input type="text" class="standardField" name="name" /> Vorname: <input type="text" class="standardField" name="vorname" />
		</p>
		<input type='submit' class='standardSubmit' name='doLogin' value='MA Hinzuf&uuml;gen' />
		<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />
		</form>
	</fieldset>
</div>
<br />
<hr />
<table class="grey">
  <tr align="left">
    <th width="100">Name</th>
    <th width="100">Vorname</th>
    <th width="50">Jobs</th>
    <th width="100">Statistik</th>
    <th width="20">Del</th>
  </tr>
  <?php 
  $sql = "SELECT * FROM za_mitarbeiter ORDER BY  name ASC";
  $result = $DB->query($sql);
  foreach ($result as $mitarbeiter)
  {
  ?>
  <tr>
    <td><?php echo htmlentities($mitarbeiter['name']); ?></td>
    <td><?php echo htmlentities($mitarbeiter['vorname']); ?></td>
    <td>
    <?php 
    $sql ="SELECT COUNT(*) AS anz_jobs FROM esv_jobs WHERE ma_id='".$mitarbeiter['id']."'";
    $result = $DB->query($sql);
    echo $result[0]['anz_jobs'];
    ?></td>
    <td><a href="za_ma_stats.php?maID=<?php echo htmlentities($mitarbeiter['id']); ?>">Link Stats</a></td>
    <td>
    <?php 
    if ($result[0]['anz_jobs'] == 0)	
    {
    	echo "<a href=\"/scripts/za-mitarbeiter.php?action=del&amp;id=".$mitarbeiter['id']."\"><img src=\"/images/icon_delete.png\" width='19' height='19' /></a>";
    }
    ?>
    </td>
  </tr>
  <?php 
  }
  ?>
</table>
<?php 
$HTML->printFoot($start_time);
?>