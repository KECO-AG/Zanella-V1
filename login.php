<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

if(isset($_SESSION['username']))
{
	(header("Location: index.php"));
}
$PAGE_TITLE = "Login";
$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
$HTML->printBody();
$HTML->printStartContent();
//if(isset($_GET["error"])){echo "nochmals versuchen";}


if(isset($_GET["error"]))
{
	if($_GET["error"] == 1)
	{
		echo "<div id=\"message\"><p>Login fehlgeschlagen! Bitte nochmals versuchen.</p></div>";
	}
}

$LOGIN->printLoginForm("/scripts/checkLogin.php");
echo "<p>";
echo "<b>Info:</b> <br />";
$ip = $LOGIN->getRealIP();
echo "Ihre IP Adresse: ".$ip." <br />";
$result = $DB->query("SELECT count(*) as count FROM badlogin WHERE ip = '".$ip."' AND active = '1'"); 
echo "Total Login-Versuche: ".$result[0]['count'];
echo "</p>\n";

$HTML->printFoot($start_time);
?>