<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Nachrichtdefinition
$msg[1] = "Die neuen Passw&ouml;rter stimmten nicht &uuml;berein!";
$msg[2] = "Passwort darf min. 6 & max 9 Zeichen lang sein!";
$msg[3] = "Altes Passwort falsch!";

// Definition Seitentitel
$PAGE_TITLE = "Passwort &auml;ndern";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

echo "<h1>Passwort &auml;ndern</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

?>
<div align="center">
<fieldset style='padding:2px;width:250px;border:1px solid grey;'>
<legend>Passwort wechsel</legend>
<form action="/scripts/changePW.php" method="post">
	Altes Passwort: <br /> 
	<input class="standardField" type="password" size="30" name="pw_old" />
	<br />
	<hr />
	Neues Passwort (6-9 Zeichen): <br /> 
	<input class="standardField" type="password" size="30" name="pw_new1" />
	<br />
	Passwort wiederholen: <br /> 
	<input class="standardField" type="password" size="30" name="pw_new2" /><br />
	<input type='submit' class='standardSubmit' name='doLogin' value='PW &Auml;ndern' />
	<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />
</form>
</fieldset>
</div>

<?php 
// Ende Inhalt
$HTML->printFoot($start_time);
?>