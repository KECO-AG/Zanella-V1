<?php
require_once '../common.php';

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

$heute			=	new DateTime();
if (isset($_GET['holzart']))
{
	if (is_numeric($_GET['holzart']))
	{
		$sql_holzart = "SELECT * FROM za_hwl_produkte WHERE za_hwl_produkte.id = '".$_GET['holzart']."'";
		$result = $DB->query($sql_holzart);
		$holzart = $result[0]['name'];
		$holzart	=	str_replace(" ", "_", $holzart);
		$holzart	=	str_replace(".", "-", $holzart);
		$holzart	=	str_replace(",", "-", $holzart);
		$dateiname	=	$heute->format('Y-m-d')."_".$holzart.".xls";
		//$dateiname	=	$heute->format('Y-m-d')."_ Test.xls";
	}
}

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=".$dateiname."");

if (isset($_GET['holzart']))
{
	if (is_numeric($_GET['holzart']))
	{

		// Tabellen
		echo "<table border='1'>\n";

		// Holzart gewählt und korrekt
		// Seitenüberschrift
		$sql_holzart = "SELECT * FROM za_hwl_produkte WHERE za_hwl_produkte.id = '".$_GET['holzart']."'";
		$result = $DB->query($sql_holzart);
		$sql_total_holzart = "
			SELECT
			  SUM(((hwl_items.breite*hwl_items.laenge)/1000000)*hwl_items.stk) AS total_holzart
			FROM
			  hwl_items
			WHERE
			  hwl_items.produkt = '".$_GET['holzart']."' AND hwl_items.deleted IS NULL
		";
		$result_total_holzart = $DB->query($sql_total_holzart);

		echo "  <tr>\n";
		echo "	  <th colspan='7'>Lager Liste: ".$result[0]['name']." Total: ".round($result_total_holzart[0]['total_holzart'],1)." m3</th>\n";
		echo "  </tr>\n";


		// Tabellen Header
		echo "  <tr>\n";
		echo "    <th width='45'>Paket</th>\n";
		echo "    <th width='50'>Dicke</th>\n";
		echo "    <th width='50'>Breite</th>\n";
		echo "    <th width='50'>L&auml;nge</th>\n";
		echo "    <th width='35'>Stk.</th>\n";
		echo "    <th width='60'>Total m2</th>\n";
		echo "    <th width='80'>Gestapelt</th>\n";
		echo "  </tr>\n";

		// Inhalt
		// Dick & Breite wählen
		$sql_d_b = "
			SELECT
			  hwl_items.dicke,
			  hwl_items.breite
			FROM
			  hwl_items
			WHERE
			  hwl_items.produkt = '".$_GET['holzart']."' AND
			  hwl_items.deleted IS NULL
			GROUP BY
			  hwl_items.dicke,
			  hwl_items.breite
		";
		$result_d_b = $DB->query($sql_d_b);

		foreach ($result_d_b as $d_b)
		{
			$dicke = $d_b['dicke'];
			$breite = $d_b['breite'];

			$sql_items = "
				SELECT *
				FROM
				  hwl_items
				  LEFT JOIN hwl_comments ON (hwl_items.id = hwl_comments.hwl_lager_id)
				WHERE
				  hwl_items.produkt = '".$_GET['holzart']."' AND
				  hwl_items.dicke = '".$dicke."' AND
				  hwl_items.breite = '".$breite."' AND
				  hwl_items.deleted IS NULL
				ORDER BY
				  hwl_items.laenge
			";
			$result_sql_items = $DB->query($sql_items);

			$total_m2 = 0;

			foreach ($result_sql_items as $items) {
				$m2		=	sprintf("%01.3f", ($items['breite']*$items['laenge'])/1000000*$items['stk']);
				$total_m2 = $total_m2+$m2;

				echo "  <tr>\n";
				echo "    <td align='right'>".$items['paket']."</td>\n";
				echo "    <td align='right'>".$items['dicke']."</td>\n";
				echo "    <td align='right'>".$items['breite']."</td>\n";
				echo "    <td align='right'>".$items['laenge']."</td>\n";
				echo "    <td align='right'>".$items['stk']."</td>\n";
				echo "    <td align='right'>".$m2."</td>\n";
				echo "    <td align='right'>".$items['date']."</td>\n";
				echo "  </tr>\n";
			}
			echo "  <tr>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td><b>Total</b></td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td align='right'><b>".sprintf("%01.3f", $total_m2)."</b></td>\n";
			echo "  </tr>\n";
			echo "  <tr>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "  </tr>\n";
		}

		// Leerzeile
		echo "  <tr>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "  </tr>\n";

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