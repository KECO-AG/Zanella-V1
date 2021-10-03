<?php
require_once '../common.php';
// Login Check & User Level
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

$username = trim(substr($_SESSION['username'],0,100));
$pw_old = trim(substr($_POST['pw_old'],0,100));
$pw_new = trim(substr($_POST['pw_new1'],0,100));

if ($_POST['pw_new1'] == $_POST['pw_new2'])
{
	if (strlen($_POST['pw_new1']) > 5 && strlen($_POST['pw_new1']) < 10) // new pw stimmt ueberein && länge OK
	{
		$sql = "SELECT user.password FROM user WHERE user.login = '".$username."'";
		$result = $DB->query($sql);
		$pw_old_ori = $result[0]['password'];
		
		if ($pw_old_ori == md5($pw_old))
		{
			// Update new PW
			$sql = "UPDATE user SET password='".md5($pw_new)."' WHERE login='".$username."'";
			$DB->query($sql);
			(header("Location: ../profil.php?message=1")); // PW erfolgreich geändert
		}
		else 
		{
			(header("Location: ../change_pw.php?message=3")); // PW alt stimmt nicht
		}
	}
	else
	{
		(header("Location: ../change_pw.php?message=2")); // PW zu lang oder kurz
	}
}
else 
{
	(header("Location: ../change_pw.php?message=1")); // NEW PW stimmt nicht überein
}

?>