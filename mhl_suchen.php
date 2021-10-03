<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "";
$msg[2] = "";
$msg[3] = "";

// Definition Seitentitel
$PAGE_TITLE = "Paket suchen";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line

$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Paket suchen</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt
?>
<br />
<div align="left">
	<fieldset style='padding:5px;width:500px;border:1px solid grey;'>
	<legend>Paket nach Nummer suchen</legend>
		<form action="?action=suchnr" method="post">
		  Paket #: <input type="text" class="standardField" name="paketnr" />
		  <input type='submit' class='standardSubmit' name='Suchen' value='Suchen' />
		</form>
	</fieldset>
</div>
<hr />
<?php
if (isset($_GET['action']))
{
	if ($_GET['action'] == 'suchnr')
	{
		if (isset($_POST['paketnr']))
		{
			$paketnr = $DB->escapeString($_POST['paketnr']);

			// Suche nach Paket Nummer
			echo "<h2>Suchergebnisse Balkenlager f&uuml;r: ".$paketnr."</h2>\n";

			// Tabelle
			echo "<table class='grey'>\n";

			// Tabellen Header
			echo "  <tr>\n";
			echo "    <th width='90'>Holzart</th>\n";
			echo "    <th width='90'>Trocknung</th>\n";
			echo "    <th width='60'>Qualit&auml;t</th>\n";
			echo "    <th width='60'>Gestapelt</th>\n";
			echo "    <th width='45'>Paket</th>\n";
			echo "    <th width='50'>Dicke</th>\n";
			echo "    <th width='50'>Breite</th>\n";
			echo "    <th width='50'>L&auml;nge</th>\n";
			echo "    <th width='35'>Stk.</th>\n";
			echo "    <th width='60'>Paket m3</th>\n";
			echo "    <th width='60'>Platte m2</th>\n";
			echo "    <th width='20'>B</th>\n";
			echo "    <th width='20'>L</th>\n";
			echo "  </tr>\n";
			// Ergebnisse
			$sql_search_results = "
				SELECT
				  za_holzarten.name,
				  za_holzqualitaet.name AS holzqualitaet,
				  za_holztrocknung.name AS holztrocknung,
				  mhl_comments.comments,
				  mhl_items.paket,
				  mhl_items.preis,
				  mhl_items.dicke,
				  mhl_items.breite,
				  mhl_items.laenge,
				  mhl_items.stk,
				  mhl_items.`date`,
				  mhl_items.id,
				  ((mhl_items.dicke*mhl_items.breite*mhl_items.laenge)*mhl_items.stk)/1000000000 as m3,
				  (mhl_items.breite*mhl_items.laenge)/1000000 as m2,
				  mhl_items.deleted
				FROM
				  mhl_items
				  INNER JOIN za_holzarten ON (mhl_items.holzart = za_holzarten.id)
				  INNER JOIN za_holztrocknung ON (mhl_items.trocknung = za_holztrocknung.id)
				  INNER JOIN za_holzqualitaet ON (mhl_items.qualitaet = za_holzqualitaet.id)
				  LEFT OUTER JOIN mhl_comments ON (mhl_items.id = mhl_comments.mhl_lager_id)
				WHERE
				  mhl_items.paket = '".$paketnr."'
			";
			$search_results = $DB->query($sql_search_results);

			foreach ($search_results as $result)
			{
				$deletedITEM = "";
				if ($result['deleted'] != NULL) { $deletedITEM = " bgcolor='red'"; }

				$deletedITEMedit = "<a href='/mhl_update.php?action=edit&id=".$result['id']."&holzart=' alt='Bearbeiten'><img src='/images/icon_edit.png' width='19' height='19' border='0' alt='Bearbeiten' /></a>";
				//if ($result['deleted'] != NULL) { $deletedITEMedit = "&nbsp;"; }

				$deletedITEMdelete = "<a href='/scripts/script-mhl.php?action=delete&id=".$result['id']."&holzart=' alt='L&ouml;schen'><img src='/images/icon_delete.png' width='19' height='19' border='0' alt='L&ouml;schen' /></a>";
				if ($result['deleted'] != NULL) {
					$deletedITEMdelete = "&nbsp;";
				}


				echo "  <tr".$deletedITEM.">\n";
				echo "    <td>".$result['name']."</td>\n";
				echo "    <td>".$result['holztrocknung']."</td>\n";
				echo "    <td>".$result['holzqualitaet']."</td>\n";
				echo "    <td>".$result['date']."</td>\n";
				echo "    <td align='right'>".$result['paket']."</td>\n";
				echo "    <td align='right'>".$result['dicke']."</td>\n";
				echo "    <td align='right'>".$result['breite']."</td>\n";
				echo "    <td align='right'>".$result['laenge']."</td>\n";
				echo "    <td align='right'>".$result['stk']."</td>\n";
				echo "    <td align='right'>".$result['m3']."</td>\n";
				echo "    <td align='right'>".$result['m2']."</td>\n";
				echo "    <td>".$deletedITEMedit."</td>\n";
				echo "    <td>".$deletedITEMdelete."</td>\n";
				echo "  </tr>\n";
			}
			echo "  <tr>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "  </tr>\n";

			echo "</table>\n";
			if ($search_results == NULL)
			{
				echo "<div id=\"message\"><p>Leider nichts gefunden.</p></div>\n";
			}

		}
	}
}
// Suche Rohhobler
if (isset($_GET['action']))
{
	if ($_GET['action'] == 'suchnr')
	{
		if (isset($_POST['paketnr']))
		{
			$paketnr = $DB->escapeString($_POST['paketnr']);

			// Suche nach Paket Nummer
			echo "<h2>Suchergebnisse Rohhobler f&uuml;r: ".$paketnr."</h2>\n";

			// Tabelle
			echo "<table class='grey'>\n";

			// Tabellen Header
			echo "  <tr>\n";
			echo "    <th width='90'>Holzart</th>\n";
			echo "    <th width='90'>Trocknung</th>\n";
			echo "    <th width='60'>Qualit&auml;t</th>\n";
			echo "    <th width='60'>Gestapelt</th>\n";
			echo "    <th width='45'>Paket</th>\n";
			echo "    <th width='50'>Dicke</th>\n";
			echo "    <th width='50'>Breite</th>\n";
			echo "    <th width='50'>L&auml;nge</th>\n";
			echo "    <th width='35'>Stk.</th>\n";
			echo "    <th width='60'>Paket m3</th>\n";
			echo "    <th width='60'>Platte m2</th>\n";
			echo "    <th width='20'>B</th>\n";
			echo "    <th width='20'>L</th>\n";
			echo "  </tr>\n";
			// Ergebnisse
			$sql_search_results = "
				SELECT
				  za_holzarten.name,
				  za_holzqualitaet.name AS holzqualitaet,
				  za_holztrocknung.name AS holztrocknung,
				  rhl_comments.comments,
				  rhl_lager.paket,
				  rhl_lager.preis,
				  rhl_lager.dicke,
				  rhl_lager.breite,
				  rhl_lager.laenge,
				  rhl_lager.stk,
				  rhl_lager.`date`,
				  rhl_lager.id,
				  ((rhl_lager.dicke*rhl_lager.breite*rhl_lager.laenge)*rhl_lager.stk)/1000000000 as m3,
				  (rhl_lager.breite*rhl_lager.laenge)/1000000 as m2,
				  rhl_lager.deleted
				FROM
				  rhl_lager
				  INNER JOIN za_holzarten ON (rhl_lager.holzart = za_holzarten.id)
				  INNER JOIN za_holztrocknung ON (rhl_lager.trocknung = za_holztrocknung.id)
				  INNER JOIN za_holzqualitaet ON (rhl_lager.qualitaet = za_holzqualitaet.id)
				  LEFT OUTER JOIN rhl_comments ON (rhl_lager.id = rhl_comments.rhl_lager_id)
				WHERE
				  rhl_lager.paket = '".$paketnr."'
			";
			$search_results = $DB->query($sql_search_results);

			foreach ($search_results as $result)
			{
				$deletedITEM = "";
				if ($result['deleted'] != NULL) { $deletedITEM = " bgcolor='red'"; }

				$deletedITEMedit = "<a href='/rhl_update.php?action=edit&id=".$result['id']."&holzart=' alt='Bearbeiten'><img src='/images/icon_edit.png' width='19' height='19' border='0' alt='Bearbeiten' /></a>";
				//if ($result['deleted'] != NULL) { $deletedITEMedit = "&nbsp;"; }

				$deletedITEMdelete = "<a href='/scripts/script-rhl.php?action=delete&id=".$result['id']."&holzart=' alt='L&ouml;schen'><img src='/images/icon_delete.png' width='19' height='19' border='0' alt='L&ouml;schen' /></a>";
				if ($result['deleted'] != NULL) {
					$deletedITEMdelete = "&nbsp;";
				}


				echo "  <tr".$deletedITEM.">\n";
				echo "    <td>".$result['name']."</td>\n";
				echo "    <td>".$result['holztrocknung']."</td>\n";
				echo "    <td>".$result['holzqualitaet']."</td>\n";
				echo "    <td>".$result['date']."</td>\n";
				echo "    <td align='right'>".$result['paket']."</td>\n";
				echo "    <td align='right'>".$result['dicke']."</td>\n";
				echo "    <td align='right'>".$result['breite']."</td>\n";
				echo "    <td align='right'>".$result['laenge']."</td>\n";
				echo "    <td align='right'>".$result['stk']."</td>\n";
				echo "    <td align='right'>".$result['m3']."</td>\n";
				echo "    <td align='right'>".$result['m2']."</td>\n";
				echo "    <td>".$deletedITEMedit."</td>\n";
				echo "    <td>".$deletedITEMdelete."</td>\n";
				echo "  </tr>\n";
			}
			echo "  <tr>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "  </tr>\n";

			echo "</table>\n";
			if ($search_results == NULL)
			{
				echo "<div id=\"message\"><p>Leider nichts gefunden.</p></div>\n";
			}

		}
	}
}
// Hobelwaren Suche
if (isset($_GET['action']))
{
	if ($_GET['action'] == 'suchnr')
	{
		if (isset($_POST['paketnr']))
		{
			$paketnr = $DB->escapeString($_POST['paketnr']);

			// Suche nach Paket Nummer
			echo "<h2>Suchergebnisse Hobelwaren f&uuml;r: ".$paketnr."</h2>\n";

			// Tabelle
			echo "<table class='grey'>\n";

			// Tabellen Header
			echo "  <tr>\n";
			echo "    <th width='45'>Produkt</th>\n";
			echo "    <th width='45'>Paket</th>\n";
			echo "    <th width='50'>Dicke</th>\n";
			echo "    <th width='50'>Breite</th>\n";
			echo "    <th width='50'>L&auml;nge</th>\n";
			echo "    <th width='35'>Stk.</th>\n";
			echo "    <th width='60'>Total m2</th>\n";
			echo "    <th width='80'>Gestapelt</th>\n";
			echo "    <th width='20'>B</th>\n";
			echo "    <th width='20'>L</th>\n";
			echo "  </tr>\n";
			// Ergebnisse
			$sql_search_results = "
				SELECT
				  za_hwl_produkte.name,
				  hwl_comments.comments,
				  hwl_items.paket,
				  hwl_items.preis,
				  hwl_items.dicke,
				  hwl_items.breite,
				  hwl_items.laenge,
				  hwl_items.stk,
				  hwl_items.`date`,
				  hwl_items.id,
				  ((hwl_items.breite*hwl_items.laenge)/1000000)*hwl_items.stk as m2,
				  hwl_items.deleted
				FROM
				  hwl_items
				  INNER JOIN za_hwl_produkte ON (hwl_items.produkt = za_hwl_produkte.id)
				  LEFT OUTER JOIN hwl_comments ON (hwl_items.id = hwl_comments.hwl_lager_id)
				WHERE
				  hwl_items.paket = '".$paketnr."'
			";
			$search_results = $DB->query($sql_search_results);

			foreach ($search_results as $result)
			{
				$deletedITEM = "";
				if ($result['deleted'] != NULL) { $deletedITEM = " bgcolor='red'"; }

				$deletedITEMedit = "<a href='/hwl_update.php?action=edit&id=".$result['id']."&holzart=' alt='Bearbeiten'><img src='/images/icon_edit.png' width='19' height='19' border='0' alt='Bearbeiten' /></a>";
				//if ($result['deleted'] != NULL) { $deletedITEMedit = "&nbsp;"; }

				$deletedITEMdelete = "<a href='/scripts/script-hwl.php?action=delete&id=".$result['id']."&holzart=' alt='L&ouml;schen'><img src='/images/icon_delete.png' width='19' height='19' border='0' alt='L&ouml;schen' /></a>";
				if ($result['deleted'] != NULL) {
					$deletedITEMdelete = "&nbsp;";
				}


				echo "  <tr".$deletedITEM.">\n";
				echo "    <td>".$result['name']."</td>\n";
				echo "    <td align='right'>".$result['paket']."</td>\n";
				echo "    <td align='right'>".$result['dicke']."</td>\n";
				echo "    <td align='right'>".$result['breite']."</td>\n";
				echo "    <td align='right'>".$result['laenge']."</td>\n";
				echo "    <td align='right'>".$result['stk']."</td>\n";
				echo "    <td align='right'>".$result['m2']."</td>\n";
				echo "    <td>".$result['date']."</td>\n";
				echo "    <td>".$deletedITEMedit."</td>\n";
				echo "    <td>".$deletedITEMdelete."</td>\n";
				echo "  </tr>\n";
			}
			echo "  <tr>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "  </tr>\n";

			echo "</table>\n";
			if ($search_results == NULL)
			{
				echo "<div id=\"message\"><p>Leider nichts gefunden.</p></div>\n";
			}

		}
	}
}
else
{
	echo "<h2>Suchergebnisse</h2>\n";
	echo "<p>Bitte einen zu suchenden Wert angeben.</p>\n";
}

// Ende Inhalt
$HTML->printFoot($start_time);
?>