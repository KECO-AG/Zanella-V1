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
$PAGE_TITLE = "Gel&ouml;schte Pakete";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line

$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Seitenüberschrift
echo "<h1>Liste der gel&ouml;schten Pakete</h1>";

// Fehlermeldung (Nachrichtendefinition)
$HTML->printMessage($msg);

// Inhalt

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
echo "    <th width='60'>Gel&ouml;scht</th>\n";
echo "    <th width='20'>B</th>\n";
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
	  rhl_lager.deleted,
	  ((rhl_lager.dicke*rhl_lager.breite*rhl_lager.laenge)*rhl_lager.stk)/1000000000 as m3,
	  (rhl_lager.breite*rhl_lager.laenge)/1000000 as m2

	FROM
	  rhl_lager
	  INNER JOIN za_holzarten ON (rhl_lager.holzart = za_holzarten.id)
	  INNER JOIN za_holztrocknung ON (rhl_lager.trocknung = za_holztrocknung.id)
	  INNER JOIN za_holzqualitaet ON (rhl_lager.qualitaet = za_holzqualitaet.id)
	  LEFT OUTER JOIN rhl_comments ON (rhl_lager.id = rhl_comments.rhl_lager_id)
	WHERE
	  rhl_lager.deleted IS NOT NULL
	ORDER BY rhl_lager.deleted DESC
";
$search_results = $DB->query($sql_search_results);

foreach ($search_results as $result)
{
	echo "  <tr>\n";
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
	echo "    <td>".$result['deleted']."</td>\n";
	echo "    <td><a href='/rhl_update.php?action=edit&id=".$result['id']."&holzart=' alt='Bearbeiten'><img src='/images/icon_edit.png' width='19' height='19' border='0' alt='Bearbeiten' /></a></td>\n";
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