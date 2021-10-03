<?php
/*
 * TODO:
 *
 *
 *
 */

require_once '../common.php';
// Login check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

//User lvl check muss in jedem case-statement eingebaut werden.
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

if ($_GET['action'] == 'add')
{
	if(empty($_POST['prio']) || empty($_POST['auftrag']) || empty($_POST['datum'])) //
	{
		(header("Location: ../tra_neu-job.php?message=1"));
	}
	else
	{
		$prio 		=	$DB->escapeString($_POST['prio']);
		$auftrag	=	$DB->escapeString($_POST['auftrag']);
		$bemerkung	=	$DB->escapeString($_POST['bemerkung']);
		$datum 		= 	$DB->escapeString($_POST['datum']);
		$uID		=	$_SESSION['uID'];

		$sql	=	"INSERT INTO `tra_items` (`id`, `datum`, `prio`, `bemerkung`, `erledigt`, `auftrag`, `creator`) VALUES (NULL, '".$datum."', '".$prio."', '".$bemerkung."', NULL, '".$auftrag."', '".$uID."');";
		$DB->query($sql);

		(header("Location: ../tra_index.php"));
	}
}

// no cache
header('Pragma: no-cache');
// HTTP/1.1
header('Cache-Control: no-cache, must-revalidate');
// date in the past
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
?>