<?php

//Fehlerreporting
error_reporting(E_ALL);

//Definition des Projektnamens
define('UNTERNEHMEN', "Zanella Holz AG - Turtmann");
define('CREATOR', "<a href=\"http://www.horizon-it.ch\">horizon IT GmbH</a> - IT solutions for you!");
define('PROJECT',"");

//Für Verzweigungen, die aufs File-Verzeichnis zeigen sollen.
define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT'].PROJECT);

//Für Web-Adressierung
define('HTTP_ROOT',"http://".$_SERVER['HTTP_HOST'].PROJECT);

//Datenbanksettings und weiter Systemweite Einstellungen
require_once DOCUMENT_ROOT."/inc/settings.php";

//Alle Klassen einbinden
require_once DOCUMENT_ROOT."/inc/includeAllClasses.php";

//HTML-Objekt erstellen
$HTML = new HTML();

//Datenbankobjekt erstellen
$DB = new DB();

//global verfügbares Sicherheitsfunktionen-Objekt
$SECURITY = new Security();

$LOGIN = new Login();

//global verfügbares Session-Objekt.
$SESSION = new MySessionHandler();

// ESV global verfügbar
$ESV = new ESV();

// RHL global verfügbar machen
$RHL = new RHL();

// TRA global verfügbar machen
$TRA = new TRA();

// AUF global verfügbar machen
$AUF = new AUF();

// HWL global verfügbar machen
$HWL = new HWL();

// MHL global verfügbar machen
$MHL = new MHL();

?>