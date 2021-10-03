<?php
/**
 * SessionHandler
 *
 */
class MySessionHandler {

	private $DB = null;

	/**
	 * Konstruktor
	 */
	public function __construct()
	{
        $this->DB = $GLOBALS['DB'];

		// Den SessionHandler auf die Methoden
		// dieser Klasse setzen
        session_set_save_handler(array ($this, '_open'),
								 array ($this, '_close'),
								 array ($this, '_read'),
								 array ($this, '_write'),
								 array ($this, '_destroy'),
								 array ($this, '_gc'));


		// Session starten
		session_start();
		//session_write_close();
		register_shutdown_function('session_write_close');
	}

	/**
	 * �ffnen der Session
	 *
	 * @return boolean Gibt immer true zur�ck
	 */
	public function _open($path, $name) {

		return true;
	}

	/**
	 * Session schlie�en
	 *
	 * @return boolean Gibt immer true zur�ck
	 */
	public function _close() {

		//Ruft den Garbage-Collector auf.
		$this->_gc(0);
		return true;
	}

	/**
	 * Session-Daten aus der Datenbank auslesen
	 *
	 * @return varchar Gibt entweder die Sitzungswerte oder einen leeren String zur�ck
	 */
	public function _read($sesID) {

		$sessionStatement = "SELECT * FROM sessions"." WHERE id = '$sesID'";
		$result = $this->DB->query($sessionStatement);

		if ($result === false) {
			return '';
		}

		if (count($result) > 0) {

			return $result[0]["value"];
		} else {
			return '';
		}
	}

	/**
	 * Neue Daten in die Datenbank schreiben
	 *
	 * @param varchar eindeutige Sessionid
	 * @param Array Alle Daten der Session
	 *
	 * @return boolean Gibt den Status des Schreibens zur�ck
	 */
	public function _write($sesID, $data) {
        //Nur schreiben, wenn Daten �bergeben werden
        if($data == null)
        {
        	return true;
        }

		//Statement um eine bestehende Session "upzudaten"
		$sessionStatement = "UPDATE sessions "." SET lastUpdated='".time()."', value='$data' WHERE id='$sesID'";
		$result = $this->DB->query($sessionStatement);


		//Ergebnis pr�fen
		if ($result === false) {
			//Fehler in der Datenbank
			return false;
		}
		if ($this->DB->MySQLiObj->affected_rows) {
			//bestehende Session "upgedated"
			return true;
		}

		//Ansonsten muss eine neue Session erstellt werden
		$sessionStatement = "INSERT INTO sessions "." (id, lastUpdated, start, value)"." VALUES ('$sesID', '".time()."', '".time()."', '$data')";
		$result = $this->DB->query($sessionStatement);

		//Ergebnis zur�ckgeben
		return $result;
	}

	/**
	 * Session aus der Datenbank l�schen
	 *
	 * @param varchar eindeutige Session-Nr.
	 *
	 * @return boolean Gibt den Status des Zerst�rens zur�ck
	 */
	public function _destroy($sesID) {

		$sessionStatement = "DELETE FROM sessions "." WHERE id = '$sesID'";

		$result = $this->DB->query($sessionStatement);
		//Ergebnis zur�ckgeben (true|false)
		return $result;
	}

	/**
	 * M�ll-Sammler ;-)
	 *
	 * L�scht abgelaufene Sessions aus der Datenbank
	 *
	 * @return boolean Gibt den Status des Bereinigens zur�ck
	 */
	public function _gc($life) {

		//Zeitpunkt, zu dem die Session als abgelaufen gilt.
		//Hier 15 min
		$sessionLife = strtotime("-180 minutes");

		$sessionStatement = "DELETE FROM sessions "." WHERE lastUpdated < $sessionLife";
		$result = $this->DB->query($sessionStatement);
        //Ergebnis zur�ckgeben
		return $result;
	}
}
?>