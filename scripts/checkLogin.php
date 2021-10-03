<?php
require_once '../common.php';
if($LOGIN->checkLoginData()==true){(header("Location: ../index.php"));}
else{
	(header("Location: ../login.php?error=1"));
}	
?>