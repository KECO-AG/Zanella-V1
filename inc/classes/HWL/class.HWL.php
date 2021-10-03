<?php
class HWL
{
	private $DB = null;

	public function __construct()
	{
		$this->DB = $GLOBALS['DB'];
	}

	public function neuesPaket()
	{

	}

	public function statLagerTotal()
	{
		$sql =
		"
		SELECT
		  za_hwl_produkte.id,
		  za_hwl_produkte.name,
		  SUM(((hwl_items.breite * hwl_items.laenge) / 1000000) * hwl_items.stk) AS total_m2,
		  SUM(hwl_items.preis) AS total_wert,
  		  COUNT(hwl_items.id) AS total_pakete
		FROM
		  hwl_items
		  INNER JOIN za_hwl_produkte ON (hwl_items.produkt = za_hwl_produkte.id)
		WHERE
		  hwl_items.deleted IS NULL
		GROUP BY
		  za_hwl_produkte.name
		ORDER BY
		  za_hwl_produkte.sort
		";
		$result = $this->DB->query($sql);

		// Tabellekopf
		echo "<table class='grey'>\n";
		echo "<tr>\n";
		echo "  <th width='300'>Holzart</th>\n";
		echo "  <th width='100'>Total Pakete</th>\n";
		echo "  <th width='100'>Lager in m2</th>\n";
		echo "  <th width='100'>Lager in CHF</th>\n";
		echo "</tr>\n";
		// Werte
		$tot_pakete = 0;
		$tot_m2 = 0;
		$tot_chf = 0;
		foreach ($result as $holzart)
		{
			$tot_pakete = $tot_pakete+$holzart['total_pakete'];
			$tot_m2 = $tot_m2+$holzart['total_m2'];
			$tot_chf = $tot_chf+$holzart['total_wert'];
			echo "<tr>\n";
			echo "  <td><a href='hwl_lager-liste.php?holzart=".$holzart['id']."' title='".$holzart['name']."'>".$holzart['name']."</a></td>\n";
			echo "  <td align='right'>".$holzart['total_pakete']."</td>\n";
			echo "  <td align='right'>".sprintf("%01.2f", $holzart['total_m2'])."</td>\n";
			echo "  <td align='right'>".sprintf("%01.2f", $holzart['total_wert'])."</td>\n";
			echo "</tr>\n";
		}
		//Totale
		echo "<tr class='total'>\n";
		echo "  <td><b>Totale</b></td>\n";
		echo "  <td align='right'>".$tot_pakete."</td>\n";
		echo "  <td align='right'>".sprintf("%01.2f", $tot_m2)."</td>\n";
		echo "  <td align='right'>".sprintf("%01.2f", $tot_chf)."</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
}
?>