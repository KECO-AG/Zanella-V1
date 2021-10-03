<?php
require_once '../common.php';
// Login Check & User Level
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};
//if($_SESSION['level'] >=2){(header("Location: index.php"));}


if (isset($_GET['action']))
{
	if ($_GET['action'] == 'upd')
	{

		if (isset($_POST['erledigt'])) {
			$erledigt = "'".$DB->escapeString($_POST['ErledigtDate'])."'";
		}
		else {
			$erledigt = "NULL";
		}

		$id 	= 	$DB->escapeString($_POST['id']);

		$sql 	= 	"UPDATE tra_items SET
						tra_items.prio = '".$DB->escapeString($_POST['prio'])."',
						tra_items.auftrag = '".$DB->escapeString($_POST['auftrag'])."',
						tra_items.bemerkung = '".$DB->escapeString($_POST['bemerkung'])."',
						tra_items.datum = '".$DB->escapeString($_POST['datum'])."',
						tra_items.erledigt = ".$erledigt."
						WHERE
						tra_items.id = '".$id."'";
		$DB->query($sql);
		(header("Location: ../tra_index.php"));
	}
	if ($_GET['action'] == 'upd-new')
	{
		if (isset($_POST['edit-erledigt-check'])) {
			//$erledigt = "'".$DB->escapeString($_POST['edit-ErledigtDate'])."'";
			if (empty($_POST['edit-ErledigtDate'])) {
				$erledigt	=	"'".$DB->escapeString($_POST['edit-datum'])."'";
			}
			else
			{
				$erledigt	=	"'".$DB->escapeString($_POST['edit-ErledigtDate'])."'";
			}
		}
		else {
			$erledigt = "NULL";
		}

		$id 	= 	$DB->escapeString($_POST['edit-id']);

		$sql 	= 	"UPDATE tra_items SET
						tra_items.prio = '".$DB->escapeString($_POST['edit-prio'])."',
						tra_items.auftrag = '".$DB->escapeString($_POST['edit-auftrag'])."',
						tra_items.bemerkung = '".$DB->escapeString($_POST['edit-bemerkung'])."',
						tra_items.datum = '".$DB->escapeString($_POST['edit-datum'])."',
						tra_items.erledigt = ".$erledigt."
						WHERE
						tra_items.id = '".$id."'";
		$DB->query($sql);
		(header("Location: ../tra_index.php"));
/*
   print('<pre>');
   print_r($_POST);
   print('</pre>');
*/
	}

	if ($_GET['action'] == 'ajax-get-data') {
		$id = $DB->escapeString($_POST['id']);
		// edit 16.02.2013  $sql = "SELECT * FROM `tra_items` WHERE `id` = ".$id."";
		$sql	=	"SELECT id, prio, auftrag, bemerkung, datum, erledigt, kz
					FROM `tra_items` 
					LEFT JOIN user ON tra_items.creator = user.uID
					WHERE `id` = ".$id."";
		$result	=	$DB->query($sql);

		//if(isset($result[0]['erledigt'])) {$erledigt = $result[0]['erledigt'];} else { $erledigt = $result[0]['datum']; }

		if($result[0]['kz'] == NULL) {$creator = 'SYS';}
		else {$creator = $result[0]['kz'];}
		
		$data = array(
				'id'		=>	$result[0]['id'],
				'prio'		=>	$result[0]['prio'],
				'auftrag'	=>	$result[0]['auftrag'],
				'bemerkung'	=>	$result[0]['bemerkung'],
				'datum'		=>	$result[0]['datum'],
				'erledigt'	=>	$result[0]['erledigt'],
				'creator'	=>	$creator,
				
				);
		header('Content-type: application/json');
		echo json_encode($data);
	}
}

?>