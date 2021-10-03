<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
if($_SESSION['level'] > 2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "User erstellt.";
$msg[2] = "User existiert bereits.";
$msg[3] = "Ihr User-LVL erlaubt diese Aktion nicht.";
$msg[4] = "Bitte ein Passwort :-)";
$msg[5] = "User gel&ouml;scht.";

// Seitentitel
$PAGE_TITLE = "Benutzer Verwaltung";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Neuen Benutzer erstellen</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

?>
<br />
	<fieldset style='padding:2px;width:500px;border:1px solid grey;'>
	<legend>Neuer Benutzer</legend>
		<form action="/scripts/za-newuser.php?action=add" method="post">
		<p>
		Username: <input type="text" class="standardField" name="name" /> <br />
		Passwort: <input type="password" class="standardField" name="passwort" /> <br />
		User LVL: <select name="userLVL">
		             <option value="4">4</option>
		             <option value="3">3</option>
		             <option value="2">2</option>		             
				  </select>
		</p>
		<input type='submit' class='standardSubmit' name='Speichern' value='Benutzer Hinzuf&uuml;gen' />
		<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />
		</form>
		<br />
		<p>
		User LVL 2: Admin. Darf l&ouml;schen und sieht Testmodule.<br />
		User LVL 3: Darf l&ouml;schen.<br />
		User LVL 4: Darf keine Eintr&auml;ge l&ouml;schen.<br />
		</p>
	</fieldset>
<br />
<hr />
<table class="grey">
  <tr>
    <th width="200">Login</th>
    <th width="100">Berechtigung</th>
    <th>&nbsp;</th>
  </tr>
  <?php 
  $sql = "SELECT * FROM user WHERE level > 1 ORDER BY  login ASC";
  $result = $DB->query($sql);
  foreach ($result as $user)
  {
  ?>
  <tr>
    <td><?php echo htmlentities($user['login']); ?></td>
    <td><?php echo htmlentities($user['level']); ?></td>
    <td><a href='/scripts/za-newuser.php?action=del&login=<?php echo htmlentities($user['login']); ?>'><img src='/images/icon_delete.png' width='19' height='19' /></a></td>
  </tr>
  <?php 
  }
  ?>
</table>
<?php
$HTML->printFoot($start_time);
?>