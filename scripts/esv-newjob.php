<?php
require_once '../common.php';
// Login Check & User Level
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

if(empty($_POST['datum']) || empty($_POST['mitarbeiter']) || empty($_POST['stunden']) || empty($_POST['jobart']))
{
	
	(header("Location: ../esv_newjob.php?message=1"));
}
else 
{
	$datum = date("Y-m-d",strtotime($_POST['datum']));
	//$datum = $DB->escapeString($_POST['datum']);
	$ma = $DB->escapeString($_POST['mitarbeiter']);
	$stunden = $DB->escapeString($_POST['stunden']);
	$bw = $DB->escapeString($_POST['bw']);
	$bemerkung = $DB->escapeString($_POST['bemerkung']);
	$art = $DB->escapeString($_POST['jobart']);
	
	$sql = "INSERT INTO esv_jobs (ma_id,job_datum,stunden,blattwechsel,bemerkung,art) VALUES ('".$ma."','".$datum."','".$stunden."','".$bw."','".$bemerkung."','".$art."')";
	$DB->query($sql);
	
	$sql = "SELECT LAST_INSERT_ID()";
	$id = $DB->query($sql);
	$id = $id[0]['LAST_INSERT_ID()'];
	
	(header("Location: ../esv_job_details.php?jobID=".$id.""));
	
	/*
	echo htmlentities($datum)."<br />";
	echo htmlentities($ma)."<br />";
	echo htmlentities($stunden)."<br />";
	echo htmlentities($bw)."<br />";
	echo htmlentities($bemerkung)."<br />";
	echo $sql;
	*/
}
?>