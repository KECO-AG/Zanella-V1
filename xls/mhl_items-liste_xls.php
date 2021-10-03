<?php
require_once '../common.php';

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

$heute			=	new DateTime();
if (isset($_GET['holzart']))
{
	if (is_numeric($_GET['holzart']))
	{
		$sql_holzart = "SELECT * FROM za_holzarten WHERE za_holzarten.id = '".$_GET['holzart']."'";
		$result = $DB->query($sql_holzart);
		$holzart = $result[0]['name'];
		$holzart	=	str_replace(" ", "_", $holzart);
		$holzart	=	str_replace(".", "", $holzart);
		$dateiname	=	$heute->format('Y-m-d')."_".$holzart.".xls";
	}
}

header("Content-type: application/vnd-ms-excel");
/*
header("Content-Disposition: attachment; filename=lager-liste.xls");
*/
header("Content-Disposition: attachment; filename=".$dateiname."");

if (isset($_GET['holzart']))
{
	if (is_numeric($_GET['holzart']))
	{

		// Tabellen
		echo "<table border='1'>\n";

		// Holzart gewählt und korrekt
		// Seitenüberschrift
		$sql_holzart = "SELECT * FROM za_holzarten WHERE za_holzarten.id = '".$_GET['holzart']."'";
		$result = $DB->query($sql_holzart);
		$sql_total_holzart = "
			SELECT
			  SUM(((mhl_items.dicke*mhl_items.breite*mhl_items.laenge)/1000000000)*mhl_items.stk) AS total_holzart
			FROM
			  mhl_items
			WHERE
			  mhl_items.holzart = '".$_GET['holzart']."' AND mhl_items.deleted IS NULL
		";
		$result_total_holzart = $DB->query($sql_total_holzart);

		echo "  <tr>\n";
		echo "	  <th colspan='11'>Lager Liste: ".$result[0]['name']." Total: ".round($result_total_holzart[0]['total_holzart'],1)." m3</th>\n";
		echo "  </tr>\n";


		// Tabellen Header
		echo "  <tr>\n";
		echo "    <th width='90'>Trocknung</th>\n";
		echo "    <th width='90'>Qualit&auml;t</th>\n";
		echo "    <th width='80'>Gestapelt</th>\n";
		echo "    <th width='45'>Paket</th>\n";
		echo "    <th width='50'>Dicke</th>\n";
		echo "    <th width='50'>Breite</th>\n";
		echo "    <th width='50'>L&auml;nge</th>\n";
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
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "  </tr>\n";
		// Dicke & Breite wählen
		$sql_d_b = "
			SELECT
			  mhl_items.dicke,
			  mhl_items.breite
			FROM
			  mhl_items
			WHERE
			  mhl_items.holzart = '".$_GET['holzart']."' AND mhl_items.deleted IS NULL
			GROUP BY
			  mhl_items.dicke,
			  mhl_items.breite
		";
		$result_d_b = $DB->query($sql_d_b);
		foreach ($result_d_b as $d_b)
		{ // d_b
			$dicke = $d_b['dicke'];
			$breite = $d_b['breite'];

			$sql_t_q = "
				SELECT
				  mhl_items.trocknung,
				  mhl_items.qualitaet
				FROM
				  mhl_items
				WHERE
				  mhl_items.holzart = '".$_GET['holzart']."' AND
				  mhl_items.dicke = '".$dicke."' AND
				  mhl_items.breite = '".$breite."' AND
				  mhl_items.deleted IS NULL
				GROUP BY
				  mhl_items.trocknung,
				  mhl_items.qualitaet
				ORDER BY
				  mhl_items.qualitaet
			";
			$result_t_q = $DB->query($sql_t_q);
			foreach ($result_t_q as $t_q)
			{ // t_q
				$trocknung = $t_q['trocknung'];
				$qualitaet = $t_q['qualitaet'];

				$sql_items = "
					SELECT
					  mhl_items.id,
					  mhl_items.paket,
					  mhl_items.preis,
					  mhl_items.laenge,
					  mhl_items.stk,
					  mhl_items.`date`,
					  za_holztrocknung.name AS trocknung,
					  za_holzqualitaet.name AS qualitaet,
					  mhl_comments.comments
					FROM
					  mhl_items
					  INNER JOIN za_holzqualitaet ON (mhl_items.qualitaet = za_holzqualitaet.id)
					  INNER JOIN za_holztrocknung ON (mhl_items.trocknung = za_holztrocknung.id)
					  LEFT JOIN mhl_comments ON (mhl_items.id = mhl_comments.mhl_lager_id)
					WHERE
					  mhl_items.holzart = '".$_GET['holzart']."' AND
					  mhl_items.dicke = '".$dicke."' AND
					  mhl_items.breite = '".$breite."' AND
					  mhl_items.trocknung = '".$trocknung."' AND
					  mhl_items.qualitaet = '".$qualitaet."' AND
					  mhl_items.deleted IS NULL
					ORDER BY
					  mhl_items.laenge,
					  mhl_items.paket
				";
				$result_items = $DB->query($sql_items);
				$total_m3 = 0; // Auf NULL setzen

				foreach ($result_items as $items)
				{
					$m3 = sprintf("%01.3f", (($dicke*$breite*$items['laenge'])*$items['stk'])/1000000000);
					$total_m3 = $total_m3+$m3;

					echo "  <tr>\n";
					echo "    <td>".$items['trocknung']."</td>\n";
					echo "    <td>".$items['qualitaet']."</td>\n";
					//echo "    <td align='right'>".date('d.m.y',strtotime($items['date']))."</td>\n";
					echo "    <td align='right'>".$items['date']."</td>\n";
					echo "    <td align='right'>".$items['paket']."</td>\n";
					echo "    <td align='right'>".$dicke."</td>\n";
					echo "    <td align='right'>".$breite."</td>\n";
					echo "    <td align='right'>".$items['laenge']."</td>\n";
					echo "    <td align='right'>".$items['stk']."</td>\n";
					echo "    <td align='right'>".$m3."</td>\n";
					echo "    <td align='right'>".sprintf("%01.3f", ($breite*$items['laenge'])/1000000)."</td>\n";
					if ($items['comments'] == NULL) { echo "    <td>&nbsp</td>\n"; }
					else {echo "    <td>".$items['comments']."</td>\n";}
					echo "  </tr>\n";

				}
				// Total

				echo "  <tr>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td><b>Total</b></td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td align='right'><b>".sprintf("%01.3f", $total_m3)."</b></td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "  </tr>\n";
				// Leerzeile
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
			} // END t_q
		} // END d_b
		echo "</table>\n";
	}
	else
	{
		die();
	}
}
else
{
	die();
}
?>