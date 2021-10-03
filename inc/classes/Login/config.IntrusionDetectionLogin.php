<?php

//Maximal erlaubte Anzahl an "ungültigen" Anmeldeversuchen
//Default: 5 Versuche
define('MAX_ALLOWED_BAD_LOGINS',999999);

//Dauer der Sperrung des Anmeldeskriptes in Sekunden
//Als Test: 30 Sekunden
//Default sollte mindestens 1800 (also 30 Minuten) sein
define('LOGIN_BAN_TIME',1800);

?>