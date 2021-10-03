<?php

/**
 * Die Basisklassen werden eingefügt!
 *
 */

//Fehlerbehandlungsklasse
//require_once DOCUMENT_ROOT."/inc/classes/ErrorHandling/class.ErrorHandling.php";

//Datenbankklasse
require_once DOCUMENT_ROOT."/inc/classes/DB/class.DBMySQL.php";

//HTML-Klasse
require_once DOCUMENT_ROOT."/inc/classes/HTML/class.HTML.php";

//Sicherheitsklasse
require_once DOCUMENT_ROOT."/inc/classes/Security/class.Security.php";

//Sitzungsklasse
require_once DOCUMENT_ROOT."/inc/classes/Session/class.SessionHandler.php";

// Login Klasse
require_once DOCUMENT_ROOT."/inc/classes/Login/class.Login.php";

// ESV Einschnittverwaltung
require_once DOCUMENT_ROOT."/inc/classes/ESV/class.ESV.php";

// RHL Rohholzlager
require_once DOCUMENT_ROOT.'/inc/classes/RHL/class.RHL.php';

// TRA Transportverwaltung
require_once DOCUMENT_ROOT.'/inc/classes/TRA/class.TRA.php';

// AUF Auftragsverwaltung
require_once DOCUMENT_ROOT.'/inc/classes/AUF/class.AUF.php';

// HWL HobelWarenLager
require_once DOCUMENT_ROOT.'/inc/classes/HWL/class.HWL.php';

// MHL HobelWarenLager
require_once DOCUMENT_ROOT.'/inc/classes/MHL/class.MHL.php';

?>