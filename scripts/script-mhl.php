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

switch ($_GET['action'])
{
	case 'neuesPaket':
		if(empty($_POST['paketnr']) || empty($_POST['dicke']) || empty($_POST['breite']) || empty($_POST['laenge'])) // Stk entfernt 29062011
		{
			(header("Location: ../mhl_newpack.php?message=1"));
		}
		else
		{
			$holzart = $DB->escapeString($_POST['holzart']);
			$qualitaet = $DB->escapeString($_POST['qualitaet']);
			$trocknung = $DB->escapeString($_POST['trocknung']);
			$paketnr = $DB->escapeString($_POST['paketnr']);
			$preis = $DB->escapeString($_POST['preis']);
			$dicke = $DB->escapeString($_POST['dicke']);
			$breite = $DB->escapeString($_POST['breite']);
			$laenge = $DB->escapeString($_POST['laenge']);
			$stk = $DB->escapeString($_POST['stk']);
			$bemerkung = $DB->escapeString($_POST['bemerkung']);
			$datum = $DB->escapeString($_POST['datum']);


			$sql = "INSERT INTO mhl_items (holzart,qualitaet,trocknung,paket,preis,dicke,breite,laenge,stk,date,deleted) VALUES ('".$holzart."', '".$qualitaet."', '".$trocknung."', '".$paketnr."', '".$preis."', '".$dicke."', '".$breite."', '".$laenge."', '".$stk."','".$datum."', NULL)";
			$DB->query($sql);

			$sql = "SELECT LAST_INSERT_ID()";
			$id = $DB->query($sql);
			$id = $id[0]['LAST_INSERT_ID()'];

			if(!empty($_POST['bemerkung']))
			{
				$sql_bemerkung = "INSERT INTO mhl_comments (mhl_lager_id,comments) VALUES ('".$id."','".$bemerkung."')";
				$DB->query($sql_bemerkung);
			}

			(header("Location: ../mhl_newpack.php?message=2&lastITEM=".$id.""));
		}
		break;


	// Lager Liste
	case 'update':
		if(empty($_POST['paketnr']) || empty($_POST['dicke']) || empty($_POST['breite']) || empty($_POST['laenge'])) // Stk entfernt 29062011
		{
			// Redirect
			$paket_id = $DB->escapeString($_GET['id']);
			$holzart_liste = $DB->escapeString($_GET['holzart']);
			(header("Location: ../mhl_update.php?id=".$paket_id."&message=1&holzart=".$holzart_liste.""));
		}
		else
		{
			$paket_id = $DB->escapeString($_GET['id']);
			$holzart_liste = $DB->escapeString($_GET['holzart']);

			$holzart = $DB->escapeString($_POST['holzart']);
			$qualitaet = $DB->escapeString($_POST['qualitaet']);
			$trocknung = $DB->escapeString($_POST['trocknung']);
			$paketnr = $DB->escapeString($_POST['paketnr']);
			$preis = $DB->escapeString($_POST['preis']);
			$dicke = $DB->escapeString($_POST['dicke']);
			$breite = $DB->escapeString($_POST['breite']);
			$laenge = $DB->escapeString($_POST['laenge']);
			$stk = $DB->escapeString($_POST['stk']);
			$bemerkung = $DB->escapeString($_POST['bemerkung']);
			$datum = $DB->escapeString($_POST['datum']);

			if (isset($_POST['deleted'])) {
				$deleted = "'".$DB->escapeString($_POST['deleteDate'])."'";
			}
			else {
				$deleted = "NULL";
			}
			if (@$_POST['restposten'] == "1") {
				$restposten = "1";
			}
			else {
				$restposten = "0";
			}

			$sql_update = "
			UPDATE mhl_items
			SET
			  holzart = '".$holzart."',
			  qualitaet = '".$qualitaet."',
			  trocknung = '".$trocknung."',
			  paket = '".$paketnr."',
			  preis = '".$preis."',
			  dicke = '".$dicke."',
			  breite = '".$breite."',
			  laenge = '".$laenge."',
			  stk = '".$stk."',
			  date = '".$datum."',
			  deleted = ".$deleted.",
			  restposten = '".$restposten."'
			WHERE
			  mhl_items.id = '".$paket_id."'
			";
			$sql_comment_upd = "
			UPDATE mhl_comments
			SET
			  comments = '".$bemerkung."'
			WHERE
			  mhl_comments.mhl_lager_id = '".$paket_id."'
			";
			$sql_comment_new = "INSERT INTO mhl_comments (mhl_lager_id,comments) VALUES ('".$paket_id."','".$bemerkung."')";
			$DB->query($sql_update);

			// Kontrolle ob Kommentar vorhanden
			$sql_check_comment = "SELECT * FROM mhl_comments WHERE mhl_comments.mhl_lager_id = '".$paket_id."'";
			$check_comment = $DB->query($sql_check_comment);

			if ($bemerkung == NULL)
			{
				// Keine Bemerkung mehr, alte löschen oder nichts
				$sql_del_old_comments = "DELETE FROM mhl_comments WHERE mhl_lager_id='".$paket_id."'";
				$DB->query($sql_del_old_comments);
			}
			else
			{
				// Bemerkung vorhanden
				if ($check_comment == NULL)
				{
					// Insert Statement
					$DB->query($sql_comment_new);
				}
				else
				{
					// Update Statement
					$DB->query($sql_comment_upd);
				}
			}

			// Redirect
			(header("Location: ../mhl_lager-liste.php?message=1&holzart=".$holzart_liste.""));
		}
		break;

	case 'delete':
		$id = $DB->escapeString($_GET['id']);
		$holzart = $DB->escapeString($_GET['holzart']);
		if($_SESSION['level'] >=4)
		{
			(header("Location: ../mhl_lager-liste.php?holzart=".$holzart."&message=3"));
			die();
		}
		$timestamp = new DateTime();
		$sql = "UPDATE mhl_items SET mhl_items.deleted = '".$timestamp->format('Y-m-d G:i:s')."' WHERE id='".$id."'";
		$DB->query($sql);
		/*
		$sql = "DELETE FROM mhl_lager WHERE id='".$id."'";
		$sql_comment = "DELETE FROM mhl_comments WHERE mhl_lager_id='".$id."'";
		$DB->query($sql);
		$DB->query($sql_comment);
		*/
		(header("Location: ../mhl_lager-liste.php?holzart=".$holzart."&message=2"));
		break;


	case 'delete_def':  // not in use 10.10.2011
		$id = $DB->escapeString($_GET['id']);
		$holzart = $DB->escapeString($_GET['holzart']);
		if($_SESSION['level'] >=4)
		{
			(header("Location: ../mhl_lager-liste.php?holzart=".$holzart."&message=3"));
			die();
		}
		$sql = "DELETE FROM mhl_items WHERE id='".$id."'";
		$sql_comment = "DELETE FROM mhl_comments WHERE mhl_items_id='".$id."'";
		$DB->query($sql);
		$DB->query($sql_comment);
		(header("Location: ../mhl_lager-liste.php?holzart=".$holzart."&message=2"));
		break;

	default:
		break;
}
?>