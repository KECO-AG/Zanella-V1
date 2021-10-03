<?php
require_once '../common.php';
// Login Check & User Level
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};
if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Mitarbeiter hinzufügen
if ($_GET['action'] == 'add')
{
	if (!empty($_POST['name']))
	{
		$sql = "INSERT INTO za_mitarbeiter (name, vorname) VALUES ('".$DB->escapeString($_POST['name'])."','".$DB->escapeString($_POST['vorname'])."')";
		$DB->query($sql);
		(header("Location: ../za_mitarbeiter.php?message=1"));
	}
	else 
	{
		(header("Location: ../za_mitarbeiter.php?message=3"));
	}
}

// Mitarbeiter löschen
if ($_GET['action'] == 'del')
{
	$sql_job_count = "SELECT COUNT(*) AS anz_jobs FROM esv_jobs WHERE ma_id='".$DB->escapeString($_GET['id'])."'";
	$job_count = $DB->query($sql_job_count);
	if ($job_count[0]['anz_jobs'] > 0)
	{
		(header("Location: ../za_mitarbeiter.php?message=4"));
	}
	else 
	{
		$sql = "DELETE FROM za_mitarbeiter WHERE id='".$DB->escapeString($_GET['id'])."'";
		$DB->query($sql);
		(header("Location: ../za_mitarbeiter.php?message=2"));
	}
}
?>