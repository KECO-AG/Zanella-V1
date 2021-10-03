<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
if($_SESSION['level'] >=4){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "";
$msg[2] = "";
$msg[3] = "";

// Definition Seitentitel
$PAGE_TITLE = "Liste der zuletzt erfassten Pakete";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line

$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Liste der zuletzt erfassten Pakete</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

// Tabelle
echo "<table class='grey'>\n";

// Tabellen Header
echo "  <tr>\n";
echo "    <th width='90'>Holzart</th>\n";
echo "    <th width='90'>Datum</th>\n";
echo "    <th width='60'>Paket</th>\n";
echo "    <th width='60'>Dicke</th>\n";
echo "    <th width='45'>Breite</th>\n";
echo "    <th width='50'>L&auml;nge</th>\n";
echo "    <th width='50'>Stk.</th>\n";
echo "    <th width='60'>Paket m3</th>\n";
echo "    <th width='60'>Platte m2</th>\n";
echo "    <th width='60'>Gel&ouml;scht</th>\n";
echo "    <th width='20'>B</th>\n";
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
	  hwl_items.deleted,
	  ((hwl_items.dicke*hwl_items.breite*hwl_items.laenge)*hwl_items.stk)/1000000000 as m3,
	  (hwl_items.breite*hwl_items.laenge)/1000000 as m2

	FROM
	  hwl_items
	  INNER JOIN za_hwl_produkte ON (hwl_items.produkt = za_hwl_produkte.id)
	  LEFT OUTER JOIN hwl_comments ON (hwl_items.id = hwl_comments.hwl_lager_id)
	ORDER BY hwl_items.ID DESC
	LIMIT 0,50
";
$search_results = $DB->query($sql_search_results);

foreach ($search_results as $result)
{
	echo "  <tr>\n";
	echo "    <td>".$result['name']."</td>\n";
	echo "    <td>".$result['date']."</td>\n";
	echo "    <td align='right'>".$result['paket']."</td>\n";
	echo "    <td align='right'>".$result['dicke']."</td>\n";
	echo "    <td align='right'>".$result['breite']."</td>\n";
	echo "    <td align='right'>".$result['laenge']."</td>\n";
	echo "    <td align='right'>".$result['stk']."</td>\n";
	echo "    <td align='right'>".$result['m3']."</td>\n";
	echo "    <td align='right'>".$result['m2']."</td>\n";
	echo "    <td>".$result['deleted']."</td>\n";
	echo "    <td><a href='/hwl_update.php?action=edit&id=".$result['id']."&holzart=' alt='Bearbeiten'><img src='/images/icon_edit.png' width='19' height='19' border='0' alt='Bearbeiten' /></a></td>\n";
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
echo "  </tr>\n";

echo "</table>\n";
if ($search_results == NULL)
{
	echo "<div id=\"message\"><p>Leider nichts gefunden.</p></div>\n";
}


// Ende Inhalt
$HTML->printFoot($start_time);
?>