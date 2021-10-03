<?php 
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Passwort erfolgreich ge&auml;ndert.";
$msg[2] = "";
$msg[3] = "";

// Definition Seitentitel
$PAGE_TITLE = "Profil";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line

$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Benutzerprofil von \"".$_SESSION['username']."\"</h1>\n";

// Nachrichtdefinition
$msg[1] = "Passwort erfolgreich ge&auml;ndert.";
$msg[2] = "";
$msg[3] = "";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

echo "<p>Angemeldet seit: ".$_SESSION['loggedInSince']."<br />\n";
echo "Benutzer Level: ".$_SESSION['level']."<br />\n";
$ip = $LOGIN->getRealIP();
echo "Ihre IP Adresse: ".$ip." <br />";
echo "Ihre Initialen: ".$_SESSION['kz']."<br /></p>";
echo "<p><a href=\"change_pw.php\">Passwort aendern</a><br />\n";
echo "Vom System <a href=\"/scripts/logout.php\">abmelden</a>.</p>";

// Ende Inhalt
$HTML->printFoot($start_time);
?>