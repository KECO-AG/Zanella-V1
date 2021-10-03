<?php
require_once '../common.php';
// Login Check & User Level
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: ../login.php"));};


// item löschen

if ($_GET['action'] == 'del')
{
	if($_SESSION['level'] >3)
	{
		(header("Location: ../esv_job_details.php?message=3&jobID=".$DB->escapeString($_GET['jobID']).""));
	}
	else 
	{
		$sql = "DELETE FROM esv_schnitt WHERE id='".$DB->escapeString($_GET['id'])."'";
		$DB->query($sql);
		(header("Location: ../esv_job_details.php?message=1&jobID=".$DB->escapeString($_GET['jobID']).""));		
	}
}
if ($_GET['action'] == 'delJOB')
{
	$jobID = $DB->escapeString($_GET['jobID']);
	
	if($_SESSION['level'] >3)
	{
		(header("Location: ../esv_job_details.php?message=3&jobID=".$DB->escapeString($_GET['jobID']).""));
	}
	else 
	{
		$sql = "DELETE FROM esv_jobs WHERE id='".$jobID."'";
		$DB->query($sql);
		(header("Location: ../esv_job_list.php?message=1"));		
	}
	
}
?>