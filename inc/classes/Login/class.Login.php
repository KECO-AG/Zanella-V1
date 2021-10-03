<?php
require_once DOCUMENT_ROOT."/inc/classes/Login/config.IntrusionDetectionLogin.php";

class Login{
	
   private $DB;
   public function __construct()
   {
   	$this->DB = $GLOBALS['DB'];
   }
   
	public function printLoginForm($checkScript = null){
   // Login erlaubt?
   if ($this->loginAllowed())
   {
	echo "<div align=\"center\">\n";
	echo "<fieldset style='padding:2px;width:180px;border:1px solid grey;'>\n";
	echo "<legend>Login</legend>\n";
	echo "<form id='noSpaces' action='".$checkScript."' method='post'>\n";
	echo "Login:<br />\n";
	echo "<input type='text' class='standardField' name='login' size='30' /><br />";
	echo "Password:<br />\n";
	echo "<input type='password' class='standardField' name='password' size='30' /><br />\n";
	echo "<input type='submit' class='standardSubmit' name='doLogin' value='Anmelden' />\n";
	echo "<input type='reset' class='standardSubmit' name='reset' value='L&ouml;schen' />\n";
	echo "</form>\n";
	echo "</fieldset>\n";  	
	echo "</div>\n";
   } // END IF
   else 
   {
 	echo "<div align=\"center\">\n";
   	echo "<fieldset style='padding:2px;width:180px;border:1px solid red;'>\n";
	echo "<legend>Login</legend>\n";
	echo "<span style='color:red'>Sie d&uuml;rfen sich zur Zeit nicht am System anmelden!</span>";
	echo "</fieldset>\n";  
	echo "</div>";	
   } // END ELSE
   } // func printLoginForm
   
   /**
    * Prüft, ob eine korrekte Benutzername-Password-Kombination
    * eingegeben wurde.
    * 
    * Erweiterte Version!
    * 
    * @return boolean Gibt zurück, ob der Login erfolgreich war oder nicht. 
    */
   public function checkLoginData(){
   	
   	// Edit 1702
   	if (!$this->loginAllowed())
   		return false;
   	// Editd 1702 - IP
   	$ip = $this->getRealIP();
   	
   	$sql = "SELECT * FROM user";
   	//Direkt auf das globale Objekt zugreifen
   	$result = $GLOBALS['DB']->query($sql);
   	
   	//Eingaben noch "trimmen" auf max. 100 Zeichen
   	//ohne führende Leerzeichen 
   	$login    = trim(substr($_POST['login'],0,100));
   	$password = trim(substr($_POST['password'],0,100));
   
   	
   	for($i=0;$i<count($result);$i++){
   		
   		if($login == $result[$i]['login']){
   			//Loginname ist vorhanden.
   			//Prüfen, ob das Password stimmt.
   			if(md5($password) == $result[$i]['password']){
   				//Session_id neu setzen: gegen SESSION FIXATION
   				session_regenerate_id();
   				//Daten des Benutzers in die Session eintragen
   				$_SESSION['username']		= 	$login;
                $_SESSION['loggedInSince']	= 	date("d.m.Y  H:i",time());
                $_SESSION['level'] 			= 	$result[$i]['level'];
				$_SESSION['uID']			=	$result[$i]['uID'];
				$_SESSION['kz']				=	$result[$i]['kz'];
   				return true;
   			}//ENDIF
   		}//ENDIF
   	}//ENDFOR
   
   //Wenn die Methode hier ankommt, ist keine Authentifizierung
   //möglich und es wird false zurückgegeben.
   // Register Bad Login
   
	//Eintragen eines falschen Loginversuchs:
	$sql = "INSERT INTO badlogin (ip,timestamp,triedUsername) VALUES ("."'".$ip."','".time()."','".$this->DB->escapeString($login)."')";
	$this->DB->query($sql);
	$this->setPotentialIntrusion();   
	return false;
   }

   // Intrusion
   private function setPotentialIntrusion()
   {
   	// IP des Users
   	$ip = $this->getRealIP();
   	
   	// Anzahl Versuche prüfen
   	$sql = "SELECT count(*) as count FROM badlogin "." WHERE ip = '".$ip."' AND active = 1";
   	$result = $this->DB->query($sql);
   	
   	$badLogins = $result[0]['count'];
   	
   	if ($badLogins > MAX_ALLOWED_BAD_LOGINS)
   	{
   		// Sperre für IP-Bereich setzen
   		$banSQL = "INSERT INTO bannedip (ip,setAt,until) VALUES ("."'".$ip."','".time()."','". (time() + LOGIN_BAN_TIME)."')";
   		$this->DB->query($banSQL);
   		//Anschließend die aufgelaufenen badLogins des IP-Bereichs inaktiv setzen
		$setInactiv = "UPDATE badlogin SET active = 0 WHERE ip = '".$ip."'";
		$this->DB->query($setInactiv);
   	}
   }
   
   public function loginAllowed()
	{
		//Schauen, ob gerade eine Sperre für diesen IP-Bereich besteht.
		//Die aktuelle Zeit muss kleiner als "until" sein, und die 
		//IP muss stimmen.
		$askBan = "SELECT * FROM bannedip WHERE "."until > '".time()."' AND ip = '".$this->getRealIP()."'";
		$result = $this->DB->query($askBan);
		
		//Wenn ein Datensatz vorhanden, dann ist der IP-Bereich gesperrt.
		if (count($result) != 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

   public function checkLoginStatus()
   {
   	if(isset($_SESSION['username']))
   	{
   		return TRUE;
   	}
   	else
   	{
   		return FALSE;
   	}
   }

   // IP Ermitteln
   public function getRealIP()
   {
   	if (isset($_SERVER["HTTP_X_FORWARDER_FOR"]))
   	{
   		$realip = $_SERVER["HTTP_X_FORWARDER_FOR"];
   	}
   	elseif (isset ($_SERVER["HTTP_CLIENT_IP"]))
   	{
   		$realip = $_SERVER["HTTP_CLIENT_IP"];
   	}
   	else 
   	{
   		$realip = $_SERVER["REMOTE_ADDR"];
   	}
   	return $realip;
   }
   
}
?>