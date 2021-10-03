<?php
require_once '../common.php';
// Login Check & User Level
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: ../login.php"));};


// item löschen

if ($_GET['action'] == 'add')
{
	if($_SESSION['level'] >2)
	{
		(header("Location: ../za_newuser.php?message=3"));
	}
	else 
	{
		$sql = "SELECT * FROM user WHERE user.login = '".$DB->escapeString($_POST['name'])."'";
		$usercheck = $DB->query($sql);
		if (empty($_POST['passwort'])) 
		{
			(header("Location: ../za_newuser.php?message=4")); 
			die();
		}
		if ($usercheck[0]['login'] == NULL)
		{
			$sql = "INSERT INTO user (login,password,level) VALUES ('".$DB->escapeString($_POST['name'])."', '".md5($_POST['passwort'])."', '".$DB->escapeString($_POST['userLVL'])."')";
			$DB->query($sql);
			
			(header("Location: ../za_newuser.php?message=1"));
			die();	
		}	
		else 
		{
			(header("Location: ../za_newuser.php?message=2"));
			die();
		}
	}
}
if ($_GET['action'] == 'del')
{
	if($_SESSION['level'] >2)
	{
		(header("Location: ../za_newuser.php?message=3"));
		die();
	}	
	$sql = "DELETE FROM user WHERE login='".$DB->escapeString($_GET['login'])."'";
	$DB->query($sql);
	(header("Location: ../za_newuser.php?message=5"));
	die();
}

?>