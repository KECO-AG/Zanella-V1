<?php


//TODO: Beim Schreiben von Daten überprüfen, ob nicht Daten überschrieben werden.
//DEADLOCK bzw. Öffnen A , Öffen B schreiben B , schreiben A....und schon ist dumm geklaufen
//Passiert nur bei zwei offenen Fenstern des selben Users

//Wie?
//http://www.zend.com/zend/spotlight/code-gallery-wade8.php?article=code-gallery-wade8&kind=sl&id=2752&open=1&anc=0&view=1#notes

class SessionHandler2 {

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
	}

	/**
	 * Öffnen der Session
	 */
	public function _open($path, $name) {

	 echo "_open";


		$locked = true;
		$loop  = 0;


		while($locked && ($loop < 20))
		{
			$sessionStatement = "UPDATE sessions SET locked = 1 "
                               ." WHERE id = '".session_id()."' AND locked = 0";
            $result = $this->DB->query($sessionStatement);

			//print_r($result);
			//echo $sessionStatement;

			if ($this->DB->MySQLiObj->affected_rows == 1) {
				echo "Not locked.<br />";
				$locked = false;
			}else{
				//Überprüfen, ob überhaupt vorhanden
				$sessionStatement = "SELECT * FROM sessions "
                                   ." WHERE id = '".session_id()."'";
                $result = $this->DB->query($sessionStatement);

				if(count($result) != 0){
					//Datensatz vorhanden aber wohl (dem Statement oben entsprechend)
					//gelockt.
					echo "Locked.<br />";
				    $loop++;
			        usleep(100000);
			        if($loop == 19){
			        //Behandlungsroutine für komplett gelockte Tables?!
			        die();
			        }
				}else{
					//Muss erst angelegt werden...also ok.
				   echo "not locked...muss angelegt werden. Und zwar hier drin!";
				   //Ansonsten muss eine neue Session erstellt werden
		           $sessionStatement = "INSERT INTO sessions "." (id, locked)"." VALUES ('$sesID',1)";
		           $result = $this->DB->query($sessionStatement);
		           //Wenn hier keine Zeile geschaffen wurde, hat dieses Skript die Race Conditions verloren
		           //dies erfolgreich war....die Session wurde zwischenzeitlich von einem anderen Skript angelegt.

		           if($this->DB->MySQLiObj->affected_rows == 1){
		             $locked = false;
		           }

				}
			}

		}


		return true;
	}

	/**
	 * Session schließen
	 */
	public function _close() {
		 echo "_close";

		 $sessionStatement = "UPDATE sessions SET locked = 0 "
                               ." WHERE id = '".session_id()."' AND locked = 1";
            $result = $this->DB->query($sessionStatement);

		//Ruft den Garbage-Collector auf.
		$this->_gc(0);
		return true;
	}

	/**
	 * Session-Daten aus der Datenbank auslesen
	 *
	 */
	public function _read($sesID) {

		 echo "_read";

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
	 */
	public function _write($sesID, $data) {

        echo "_write";
        if($data == null){
        	return true;
        }

		//Statement um eine bestehende Session "upzudaten"
		$sessionStatement = "UPDATE sessions "." SET validTill='".time()."', value='$data' WHERE id='$sesID'";
		$result = $this->DB->query($sessionStatement);

		//Ergebnis prüfen
		if ($result === false) {
			//Fehler in der Datenbank
			return false;
		}
		if ($this->DB->MySQLiObj->affected_rows) {
			//bestehende Session "upgedated"
			return false;
		}

		//Ansonsten muss eine neue Session erstellt werden
		$sessionStatement = "INSERT INTO sessions "." (id, validTill, start, value,locked)"." VALUES ('$sesID', '".time()."', '".time()."', '$data',1)";
		$result = $this->DB->query($sessionStatement);

		//Ergebnis prüfen
		if ($result === false) {
			//Datenbankfehler...nicht erreichbar..Mail an Admin!!
			return false;
		} else {
			//Session wurde angelegt.
			return true;
		}
	}

	/**
	 * Session aus der Datenbank löschen
	 *
	 * @param varchar eindeutige Session-Nr.
	 */
	public function _destroy($sesID) {
		echo "_destroy";

		$sessionStatement = "DELETE FROM sessions "." WHERE id = '$sesID'";
		$result = $this->DB->query($sessionStatement);
		if ($result === false) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Müll-Sammler ;-)
	 *
	 * Löscht abgelaufene Sessions aus der Datenbank
	 */
	public function _gc($life) {

		 echo "_gc";
		//Zeitpunkt, zu dem die Session als abgelaufen gilt.
		//Hier 15 min
		$sessionLife = strtotime("-240 minutes");

		$sessionStatement = "DELETE FROM sessions "." WHERE validTill < $sessionLife";
		$result = $this->DB->query($sessionStatement);

		if ($result === false) {
			//Datenbankfehler:
			return false;
		} else {
			//Garbage gelöscht.
			return true;
		}
	}
}
?>





