<?php
if ($_SERVER['HTTP_HOST'] == "zanella.horizonit.ch") {
	//DATENBANKVERBINDUNGS-DATEN
	define('DB_SERVER',"127.0.0.1");
	define('DB_NAME',"zanella_V1");

	//Diese Angaben sollten Sie �ndern, denn hier stehen die XAMPP-Standardwerte
	//Datenbankbenutzer
	define('DB_USER',"root");
	//Benutzerpasswort
	define('DB_PASSWORD',"changeme");

} else {

	//DATENBANKVERBINDUNGS-DATEN
	define('DB_SERVER',"127.0.0.1");
	define('DB_NAME',"zanella_V1");

	//Diese Angaben sollten Sie �ndern, denn hier stehen die XAMPP-Standardwerte
	//Datenbankbenutzer
	define('DB_USER',"root");
	//Benutzerpasswort
	define('DB_PASSWORD',"changeme");

}

//HTML-TITEL
define('HTML_TITLE',"Zanella Holz - Turtmann");

//Titel der Seiten
define('GLOBAL_HTML_HEAD_TITEL',"Applikationen");

//Grundfarben des Frames:
define('CONTENT_BACKGROUND_COLOR',"#dedede");
define('MENU_BACKGROUND_COLOR',"#eeeeee");


//Schriftgr��en
define('FONT_SIZE_LITTLE',"8pt");
define('BUTTON_FONT_SIZE',"8pt");

?>