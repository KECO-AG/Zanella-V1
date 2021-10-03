<?php
require_once '../common.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=lager-liste.xls");

if (isset($_GET['holzart']))
{
	if (is_numeric($_GET['holzart']))
	{
		$sql = "
			SELECT 
			  za_holzqualitaet.name AS qualiname,
			  za_holztrocknung.name AS trocknungname,
			  rhl_lager.dicke,
			  rhl_lager.breite,
			  rhl_lager.laenge,
			  rhl_lager.id,
			  rhl_lager.paket,
			  rhl_lager.preis,
			  rhl_lager.stk,
			  rhl_comments.comments
			FROM
			  rhl_lager
			  INNER JOIN za_holzarten ON (rhl_lager.holzart = za_holzarten.id)
			  INNER JOIN za_holzqualitaet ON (rhl_lager.qualitaet = za_holzqualitaet.id)
			  INNER JOIN za_holztrocknung ON (rhl_lager.trocknung = za_holztrocknung.id)
			  LEFT OUTER JOIN rhl_comments ON (rhl_lager.id = rhl_comments.rhl_lager_id)
			WHERE
			  rhl_lager.holzart = '".$_GET['holzart']."'
			ORDER BY
			  za_holzqualitaet.sort,
			  za_holztrocknung.sort,
			  rhl_lager.dicke,
			  rhl_lager.breite		
		";
		$result = $DB->query($sql);		
		
		echo "<table class='grey'>\n";
		echo "<tr>\n";
		echo "  <th width='90'>Qualit&auml;t</th>\n";
		echo "  <th width='90'>Trocknung</th>\n";
		echo "  <th width='50'>Paket Nr.</th>\n";
		echo "  <th width='50'>Stk.</th>\n";
		echo "  <th width='50'>Dicke</th>\n";
		echo "  <th width='50'>Breite</th>\n";
		echo "  <th width='50'>L&auml;nge</th>\n";
		echo "  <th>Paket m3</th>\n";
		echo "  <th>Platte m2</th>\n";
		echo "</tr>\n";
		foreach ($result as $paket) 
		{
			// Calc totals
			$paket_m3 = round((($paket['dicke']*$paket['breite']*$paket['laenge'])/1000000000)*$paket['stk'],2);
			$platte_m2 = round(($paket['breite']*$paket['laenge'])/1000000,2);
			//echo $paket['paket']."<br />\n";
			echo "<tr>\n";
			echo "  <td>".$paket['qualiname']."</td>\n";
			echo "  <td>".$paket['trocknungname']."</td>\n";
			echo "  <td align='right'>".$paket['paket']."</td>\n";
			echo "  <td align='right'>".$paket['stk']."</td>\n";
			echo "  <td align='right'>".$paket['dicke']."</td>\n";
			echo "  <td align='right'>".$paket['breite']."</td>\n";
			echo "  <td align='right'>".$paket['laenge']."</td>\n";
			echo "  <td align='right'>".$paket_m3."</td>\n";
			echo "  <td align='right'>".$platte_m2."</td>\n";
			echo "</tr>\n";
		}
		
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