<?php

//Fehlerreporting
error_reporting(E_ALL);

//Definition des Projektnamens
define('UNTERNEHMEN', "Zanella Holz AG - Turtmann");
define('CREATOR', "<a href=\"http://www.horizon-it.ch\">horizon IT GmbH</a> - IT solutions for you!");
define('PROJECT',"");

//F�r Verzweigungen, die aufs File-Verzeichnis zeigen sollen.
define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT'].PROJECT);

//F�r Web-Adressierung
define('HTTP_ROOT',"http://".$_SERVER['HTTP_HOST'].PROJECT);

//Datenbanksettings und weiter Systemweite Einstellungen
require_once DOCUMENT_ROOT."/inc/settings.php";

//Alle Klassen einbinden
require_once DOCUMENT_ROOT."/inc/includeAllClasses.php";

//HTML-Objekt erstellen
$HTML = new HTML();

//Datenbankobjekt erstellen
$DB = new DB();

//global verf�gbares Sicherheitsfunktionen-Objekt
$SECURITY = new Security();

$LOGIN = new Login();

//global verf�gbares Session-Objekt.
$SESSION = new MySessionHandler();

// ESV global verf�gbar
$ESV = new ESV();

// RHL global verf�gbar machen
$RHL = new RHL();

// TRA global verf�gbar machen
$TRA = new TRA();

// AUF global verf�gbar machen
$AUF = new AUF();

// HWL global verf�gbar machen
$HWL = new HWL();

// MHL global verf�gbar machen
$MHL = new MHL();

?>