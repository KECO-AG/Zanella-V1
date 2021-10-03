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
			(header("Location: ../hwl_newpack.php?message=1"));
		}
		else
		{
			$produkt = $DB->escapeString($_POST['produkt']);
			$paketnr = $DB->escapeString($_POST['paketnr']);
			$preis = $DB->escapeString($_POST['preis']);
			$dicke = $DB->escapeString($_POST['dicke']);
			$breite = $DB->escapeString($_POST['breite']);
			$laenge = $DB->escapeString($_POST['laenge']);
			$stk = $DB->escapeString($_POST['stk']);
			$bemerkung = $DB->escapeString($_POST['bemerkung']);
			$datum = $DB->escapeString($_POST['datum']);


			$sql = "INSERT INTO hwl_items (produkt,paket,preis,dicke,breite,laenge,stk,date,deleted) VALUES ('".$produkt."', '".$paketnr."', '".$preis."', '".$dicke."', '".$breite."', '".$laenge."', '".$stk."','".$datum."', NULL)";
			$DB->query($sql);

			$sql = "SELECT LAST_INSERT_ID()";
			$id = $DB->query($sql);
			$id = $id[0]['LAST_INSERT_ID()'];

			if(!empty($_POST['bemerkung']))
			{
				$sql_bemerkung = "INSERT INTO hwl_comments (hwl_lager_id,comments) VALUES ('".$id."','".$bemerkung."')";
				$DB->query($sql_bemerkung);
			}

			(header("Location: ../hwl_newpack.php?message=2&lastITEM=".$id.""));
		}
		break;


	// Lager Liste
	case 'update':
		if(empty($_POST['paketnr']) || empty($_POST['dicke']) || empty($_POST['breite']) || empty($_POST['laenge'])) // Stk entfernt 29062011
		{
			// Redirect
			$paket_id = $DB->escapeString($_GET['id']);
			$holzart_liste = $DB->escapeString($_GET['holzart']);
			(header("Location: ../hwl_update.php?id=".$paket_id."&message=1&holzart=".$holzart_liste.""));
		}
		else
		{
			$paket_id = $DB->escapeString($_GET['id']);
			$holzart_liste = $DB->escapeString($_GET['holzart']);

			$produkt = $DB->escapeString($_POST['produkt']);
			//$qualitaet = $DB->escapeString($_POST['qualitaet']);
			//$trocknung = $DB->escapeString($_POST['trocknung']);
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
			UPDATE hwl_items
			SET
			  produkt = '".$produkt."',
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
			  hwl_items.id = '".$paket_id."'
			";
			$sql_comment_upd = "
			UPDATE hwl_comments
			SET
			  comments = '".$bemerkung."'
			WHERE
			  hwl_comments.hwl_lager_id = '".$paket_id."'
			";
			$sql_comment_new = "INSERT INTO hwl_comments (hwl_lager_id,comments) VALUES ('".$paket_id."','".$bemerkung."')";
			$DB->query($sql_update);

			// Kontrolle ob Kommentar vorhanden
			$sql_check_comment = "SELECT * FROM hwl_comments WHERE hwl_comments.hwl_lager_id = '".$paket_id."'";
			$check_comment = $DB->query($sql_check_comment);

			if ($bemerkung == NULL)
			{
				// Keine Bemerkung mehr, alte l�schen oder nichts
				$sql_del_old_comments = "DELETE FROM hwl_comments WHERE hwl_lager_id='".$paket_id."'";
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
			(header("Location: ../hwl_lager-liste.php?message=1&holzart=".$holzart_liste.""));
		}
		break;

	case 'delete':
		$id = $DB->escapeString($_GET['id']);
		$holzart = $DB->escapeString($_GET['holzart']);
		if($_SESSION['level'] >=4)
		{
			(header("Location: ../hwl_lager-liste.php?holzart=".$holzart."&message=3"));
			die();
		}
		$timestamp = new DateTime();
		$sql = "UPDATE hwl_items SET hwl_items.deleted = '".$timestamp->format('Y-m-d G:i:s')."' WHERE id='".$id."'";
		$DB->query($sql);
		/*
		$sql = "DELETE FROM hwl_lager WHERE id='".$id."'";
		$sql_comment = "DELETE FROM hwl_comments WHERE hwl_lager_id='".$id."'";
		$DB->query($sql);
		$DB->query($sql_comment);
		*/
		(header("Location: ../hwl_lager-liste.php?holzart=".$holzart."&message=2"));
		break;


	case 'delete_def':  // not in use 10.10.2011
		$id = $DB->escapeString($_GET['id']);
		$holzart = $DB->escapeString($_GET['holzart']);
		if($_SESSION['level'] >=4)
		{
			(header("Location: ../hwl_lager-liste.php?holzart=".$holzart."&message=3"));
			die();
		}
		$sql = "DELETE FROM hwl_items WHERE id='".$id."'";
		$sql_comment = "DELETE FROM hwl_comments WHERE hwl_items_id='".$id."'";
		$DB->query($sql);
		$DB->query($sql_comment);
		(header("Location: ../hwl_lager-liste.php?holzart=".$holzart."&message=2"));
		break;

	default:
		break;
}
?>