<?php
require_once '../common.php';

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

$heute			=	new DateTime();
$dateiname		=	$heute->format('Y-m-d')."_Lagerbestand.xls";


header("Content-type: application/vnd-ms-excel");
/*
header("Content-Disposition: attachment; filename=lager-liste.xls");
*/
header("Content-Disposition: attachment; filename=".$dateiname."");


// Tabellen
echo "<table border='1'>\n";

echo "  <tr>\n";
echo "	  <th colspan='10'>Lagerbestand vom ".$heute->format('d.m.Y')." um ".$heute->format('H:i')."</th>\n";
echo "  </tr>\n";


// Tabellen Header
echo "  <tr>\n";
echo "    <th width='90'>Lager</th>\n";

echo "    <th width='270'>Holzart</th>\n"; //edit 18.12.2012
echo "    <th width='160'>Trocknung</th>\n"; //edit 18.12.201
echo "    <th width='160'>Qualit?t</th>\n"; //edit 18.12.201

echo "    <th width='120'>Gestapelt</th>\n";
echo "    <th width='60'>Paket</th>\n";
echo "    <th width='60'>Dicke</th>\n";
echo "    <th width='60'>Breite</th>\n";
echo "    <th width='60'>L&auml;nge</th>\n";
echo "    <th width='35'>Stk.</th>\n";
echo "    <th width='60'>Paket m3</th>\n";
echo "    <th width='60'>Platte m2</th>\n";
echo "    <th width='250'>Info</th>\n";
echo "  </tr>\n";
// Leerzeile
echo "  <tr>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";

echo "    <td>&nbsp;</td>\n"; //edit 18.12.2012
echo "    <td>&nbsp;</td>\n"; //edit 18.12.2012
echo "    <td>&nbsp;</td>\n"; //edit 18.12.2012

echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "  </tr>\n";



$sql_items = "
		SELECT
		  hwl_items.paket AS paket,
		  za_hwl_produkte.name AS holzart,
		  '-' AS trocknung,
		  '-' AS qualitaet,
		  hwl_items.dicke AS dicke,
		  hwl_items.breite AS breite,
		  hwl_items.laenge AS laenge,
		  hwl_items.stk AS stk,
		  hwl_items.date AS date,
		  hwl_comments.comments AS comment,
		  'Hobelware' AS lager
		FROM
		  hwl_items
		  LEFT JOIN hwl_comments ON (hwl_items.id = hwl_comments.hwl_lager_id)
		  INNER JOIN za_hwl_produkte ON (hwl_items.produkt = za_hwl_produkte.id)
		  
		WHERE hwl_items.deleted IS NULL
		UNION
		SELECT
		  mhl_items.paket AS paket,
		  za_holzarten.name AS holzart,
		  za_holztrocknung.name AS trocknung,
		  za_holzqualitaet.name AS qualitaet,
		  mhl_items.dicke AS dicke,
		  mhl_items.breite AS breite,
		  mhl_items.laenge AS laenge,
		  mhl_items.stk AS stk,
		  mhl_items.date AS date,
		  mhl_comments.comments AS comment,
		  'Balken' AS lager
		FROM
		  mhl_items
		  LEFT JOIN mhl_comments ON (mhl_items.id = mhl_comments.mhl_lager_id)
		  INNER JOIN za_holzarten ON (mhl_items.holzart = za_holzarten.id)
		  INNER JOIN za_holztrocknung ON (mhl_items.trocknung = za_holztrocknung.id) 
		  INNER JOIN za_holzqualitaet ON (mhl_items.qualitaet = za_holzqualitaet.id)
		WHERE mhl_items.deleted IS NULL
		UNION
		SELECT
		  rhl_lager.paket AS paket,
		  za_holzarten.name AS holzart,
		  za_holztrocknung.name AS trocknung,
		  za_holzqualitaet.name AS qualitaet,
		  rhl_lager.dicke AS dicke,
		  rhl_lager.breite AS breite,
		  rhl_lager.laenge AS laenge,
		  rhl_lager.stk AS stk,
		  rhl_lager.date AS date,
		  rhl_comments.comments AS comment,
		  'Rohhobler' AS lager
		FROM
		  rhl_lager
		  LEFT JOIN rhl_comments ON (rhl_lager.id = rhl_comments.rhl_lager_id)
		  INNER JOIN za_holzarten ON (rhl_lager.holzart = za_holzarten.id)
		  INNER JOIN za_holztrocknung ON (rhl_lager.trocknung = za_holztrocknung.id) 
		  INNER JOIN za_holzqualitaet ON (rhl_lager.qualitaet = za_holzqualitaet.id)
		WHERE rhl_lager.deleted IS NULL

		ORDER BY paket+0
";
$result_items = $DB->query($sql_items);

foreach ($result_items as $items)
{
	$m3 = sprintf("%01.3f", (($items['dicke']*$items['breite']*$items['laenge'])*$items['stk'])/1000000000);
	$m2 = sprintf("%01.3f", ($items['breite']*$items['laenge'])/1000000);

	echo "  <tr>\n";
	echo "    <td>".$items['lager']."</td>\n";
	
	echo "    <td align='right'>".$items['holzart']."</td>\n"; //edit 18.12.2012
	echo "    <td align='right'>".$items['trocknung']."</td>\n"; //edit 18.12.2012
	echo "    <td align='right'>".$items['qualitaet']."</td>\n"; //edit 18.12.2012
	
	echo "    <td align='right'>".$items['date']."</td>\n";
	echo "    <td align='right'>".$items['paket']."</td>\n";
	echo "    <td align='right'>".$items['dicke']."</td>\n";
	echo "    <td align='right'>".$items['breite']."</td>\n";
	echo "    <td align='right'>".$items['laenge']."</td>\n";
	echo "    <td align='right'>".$items['stk']."</td>\n";
	echo "    <td align='right'>".$m3."</td>\n";
	echo "    <td align='right'>".$m2."</td>\n";
	if ($items['comment'] == NULL) { echo "    <td>&nbsp</td>\n"; }
	else {echo "    <td>".$items['comment']."</td>\n";}
	echo "  </tr>\n";

}

// Leerzeile
echo "  <tr>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";

echo "    <td>&nbsp;</td>\n"; //edit 18.12.2012
echo "    <td>&nbsp;</td>\n"; //edit 18.12.2012
echo "    <td>&nbsp;</td>\n"; //edit 18.12.2012

echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "  </tr>\n";

echo "</table>\n";

?>