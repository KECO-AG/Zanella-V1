<?php


/**
 * Abstraktionsschicht f�r die Datenbank (nutzt nur MySQL)
 * 
 * Verbindet zur Datenbank und kapselt alle Anfragen an die 
 * Datenbank....
 * 
 */

class DB
{

	//Datenbankverbindungsobjekt 
	public $MySQLiObj = null;

	//Letzte SQL-Abfrage
	public $lastSQLQuery = null;

	//Status der letzten Anfrage 
	public $lastSQLStatus = null;

	/**
	 * Verbindet zur Datenbank und gibt ggf. eine
	 * Fehlermeldung zur�ck.
	 * 
	 */
	public function __construct()
	{

		//Erstellen eines MySQLi-Objektes
		$this->MySQLiObj = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

		//Pr�fen, ob ein Fehler aufgetreten ist.      
		if (mysqli_connect_errno())
		{
			echo "Keine Verbindung zum MySQL-Server m&ouml;glich.";
			trigger_error("MySQL-Connection-Error", E_USER_ERROR);
			die();
		}

	}

	/**
	 * Beendet die Verbindung zur Datenbank bei Beenden eines
	 * Skriptes.
	 * 
	 */
	public function __destruct()
	{
		$this->MySQLiObj->close();
	}

	/**
	 * F�hrt eine SQL-Anfrage durch.
	 * 
	 * Der optionale Parameter bestimmt, ob das Ergebnis als
	 * Array-Struktur zur�ckgegeben wird oder als normales MySQL-Resultset
	 * 
	 * @param text Die SQL-Anfrage
	 * @param boolean Parameter, ob ein Resultset oder ein Array zur�ckgegeben werden soll
	 * 
	 * @return Array Gibt eine Ergebnismenge zur�ck
	 */
	public function query($sqlQuery, $resultset = false)
	{
	    //Letzte SQL-Abfrage aufzeichnen:
	    $this->lastSQLQuery = $sqlQuery;

		//Hier kann sp�ter die Protokoll-Methode doLog() 
		//aktiviert werden
		//$this->doLog($sqlQuery);

		$result = $this->MySQLiObj->query($sqlQuery);

		//Das Ergebnis als MySQL-Result plain zur�ckgeben
		if ($resultset == true)
		{
			//Status setzen
			if ($result == false)
			{
				$this->lastSQLStatus = false;
			}
			else
			{
				$this->lastSQLStatus = true;
			}

			return $result;
		}

		$return = $this->makeArrayResult($result);

		return $return;
	}

	/**
	 * Fehlermeldung der letzten Abfrage
	 * 
	 * @return varchar Die letzte Fehlermeldung wird zur�ckgegeben
	 */
	public function lastSQLError()
	{
		return $this->MySQLiObj->error;
	}

	/**
	 * Array-Struktur der Anfrage
	 * 
	 * L�sst ein Ergebnis aussehen, wie das von DBX
	 * 
	 * @param MySQLiObject Das Ergebnisobjekt einer MySQLi-Anfrage 
	 * 
	 * @param boolean/Array Gibt entweder true, false oder eine Ergebnismenge zur�ck
	 */
	private function makeArrayResult($ResultObj)
	{

		if ($ResultObj === false)
		{
			//Fehler trat auf (z.B. Prim�rschl�ssel schon vorhanden)
			$this->lastSQLStatus = false;
			return false;

		}
		else
			if ($ResultObj === true)
			{
				//UPDATE- INSERT etc. es wird nur TRUE zur�ckgegeben.
				$this->lastSQLStatus = true;
				return true;

			}
			else
				if ($ResultObj->num_rows == 0)
				{
					//Kein Ergebnis eines SELECT, SHOW, DESCRIBE oder EXPLAIN-Statements
					$this->lastSQLStatus = true;
					return array ();

				}
				else
				{

					$array = array ();

					while ($line = $ResultObj->fetch_array(MYSQL_ASSOC))
					{
						//Alle Bezeichner in $line klein schreiben
						array_push($array, $line);
					}

					//Status der Abfrage setzen
					$this->lastSQLStatus = true;
					//Das Array sieht nun genauso aus, wie das Ergebnis von dbx
					return $array;
				}

	}

    /**
     * Maskiert einen Parameter f�r die Benutzung in einer SQL-Anfrage
     * 
     * @param varchar Attributwert
     * 
     * @return Gibt den �bergebenen Wert maskiert zur�ck
     */
	public function escapeString($value)
	{

		return $this->MySQLiObj->real_escape_string($value);

	}

	/**
	 * Protokolliert alle Datenbankzugriffe  
	 * 
	 * @param text Eine SQL-Anfrage, die "gelogt" werden soll
	 */
	private function doLog($sqlQuery)
	{

		//Nur wenn kein SELECT
		$substr = substr($sqlQuery, 0, 6);

		//Eintragen
		if ($substr != "SELECT")
		{

			$sql = "INSERT INTO logging (sql,datum,name) VALUES ".
                   " ('".mysql_escape_string($sqlQuery)."',".
                   date("H:i   d.m.Y", time()).",'".
                   $_SESSION['name']."')";
                   
			//Eintragen
			$this->MySQLiObj->query($sql);
		}

	}

}
?>