<?php
require_once '../common.php';
// Login Check & User Level
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Preis ändern
if ($_GET['action'] == 'upd')
{
	$sort = $DB->escapeString($_POST['sort']);
	$id = $DB->escapeString($_POST['id']);
	$sql_upd = "UPDATE za_holzarten SET za_holzarten.sort = ".$sort." WHERE za_holzarten.id = ".$id."";
	$DB->query($sql_upd);
	(header("Location: ../za_holzarten.php?message=1"));
	//print_r($_POST);
}

// Neue Holart
if ($_GET['action'] == 'add')
{
	$sort = $DB->escapeString($_POST['sort']);
	$name = $DB->escapeString($_POST['name']);
	$sql_add = "INSERT INTO za_holzarten (name,sort) VALUES ('".$name."', '".$sort."')";
	$DB->query($sql_add);
	(header("Location: ../za_holzarten.php?message=2"));
	//print_r($_POST);
}
?>