<?php


/**
 * Sicherheitsfunktionen
 * 
 * 
 */

class Security
{

	/**
	* Diese Methode korrigiert alle �bergebenen Parameter (Slashes)
	* 
	* Egal ob POST oder GET-Parameter, die Methode berichtigt beide Arrays und
	* entfernt die Slashes, die durch PHP automatisch eingef�gt wurden.
	* 
	*/
	public function globalStripSlashes()
	{

		if (get_magic_quotes_gpc() == 1)
		{
			$_GET = array_map(array ($this, 'recursiveStripSlashes'), $_GET);
			$_POST = array_map(array ($this, 'recursiveStripSlashes'), $_POST);
		}

	}

	/**
	 * Rekursive Hilfsfunktion zur Entfernung von Backslashes
	 * 
	 * @param varchar Wert, dessen Slashes entfernt werden sollen
	 * 
	 * @return Gibt den �bergebenen Wert ohne Slashes zur�ck
	 */
	private function recursiveStripSlashes($value)
	{
		//Pr�fen, ob der Wert ein Array ist
		if (is_array($value))
		{
			//Rekursiver Aufruf dieser Methode 
			return array_map(array ($this, 'recursiveStripSlashes'), $value);
		}
		else
		{
			//R�ckgabe des berichtigten Wertes
			return stripslashes($value);
		}
	}

	/**
	 * �berpr�ft, ob ein Benutzer angemeldet ist.
	 * 
	 * @return boolean Loginstatus ist true oder false
	 */
	public function checkLoginStatus()
	{
		if (isset ($_SESSION['username']))
		{
			return true;
		}
		else
		{
			return false;

		}
	}

	/**
	 * Erstellt sichere Passw�rte auf Zufallsbasis
	 * 
	 * @return varchar Gibt ein neu generiertes Password zur�ck
	 */
	public function generatePassword()
	{
		//Muster eines sicheren Passworts
		//beispielsweise: Ks#64z
		$pwd = "";
		for ($i = 0; $i < 6; $i ++)
		{
			switch (rand(0, 3))
			{
				case 0 : //Gro�buchstabe anf�gen
					$pwd = $pwd.chr(rand(65, 90));
					break;
				case 1 : //Kleinbuchstabe
					$pwd = $pwd.chr(rand(97, 122));
					break;
				case 2 : //Sonderzeichen
					$pwd = $pwd.chr(rand(33, 38));
					break;
				case 3 : //Ziffer
					$pwd = $pwd.rand(0, 9);
					break;
			}
		}
		return $pwd;
	}

	/**
	 * Verifiziert ein �bergebenes Passwort
	 * 
	 * @param varchar zu verifizierendes Passwort
	 * 
	 * @return boolean Passwort ist entweder g�ltig oder nicht 
	 */
	public function verifyPassword($password)
	{

		//Die einzelnen Regeln �berpr�fen:
		//L�nge mindestens 8 Zeichen:

		if (strlen($password) < 8)
			return false;

		//Dann verifizieren, dass nur die erlaubten Sonderzeichen
		//sowie Ziffern und Buchstaben drin sind.

		//Diese Zeichen d�rfen drin vorkommen.
		$regexp = '/[^\!|\"|\#|\$|\%|\&|\d|a-zA-Z0-9]/';
		//sind aber verneint...wenn also etwas anderes drin vorkommt..
		//wird die 1 zur�ckgegeben.	 
		$i = preg_match($regexp, $password);
		if ($i == 1)
			return false;

		$empty = array ();
		//Mindestens zwei Ziffern:

		$i = preg_match_all('/[0-9]/', $password, $empty);
		if ($i < 2)
			return false;

		//Gro�- und Kleinbuchstaben:
		//also abbrechen, wenn nicht ein einziger 
		//Gro�buchstabe vorhanden ist.

		$i = preg_match_all('/[A-Z]/', $password, $empty);
		if ($i == 0)
			return false;

		//Auch abbrechen, wenn nicht ein einziger 
		//Kleinbuchstabe vorhanden ist.
		$i = preg_match_all('/[a-z]/', $password, $empty);
		if ($i == 0)
			return false;

		return true;

	}

}
?>