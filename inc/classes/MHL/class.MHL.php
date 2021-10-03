<?php
class MHL
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
		  za_holzarten.id,
		  za_holzarten.name,
		  SUM(((mhl_items.dicke * mhl_items.breite * mhl_items.laenge) / 1000000000) * mhl_items.stk) AS total_m3,
		  SUM(mhl_items.preis) AS total_wert,
  		  COUNT(mhl_items.id) AS total_pakete
		FROM
		  mhl_items
		  INNER JOIN za_holzarten ON (mhl_items.holzart = za_holzarten.id)
		WHERE
		  mhl_items.deleted IS NULL
		GROUP BY
		  za_holzarten.name
		ORDER BY
		  za_holzarten.sort
		";
		$result = $this->DB->query($sql);

		// Tabellekopf
		echo "<table class='grey'>\n";
		echo "<tr>\n";
		echo "  <th width='300'>Holzart</th>\n";
		echo "  <th width='100'>Total Pakete</th>\n";
		echo "  <th width='100'>Lager in m3</th>\n";
		echo "  <th width='100'>Lager in CHF</th>\n";
		echo "</tr>\n";
		// Werte
		$tot_pakete = 0;
		$tot_m3 = 0;
		$tot_chf = 0;
		foreach ($result as $holzart)
		{
			$tot_pakete = $tot_pakete+$holzart['total_pakete'];
			$tot_m3 = $tot_m3+$holzart['total_m3'];
			$tot_chf = $tot_chf+$holzart['total_wert'];
			echo "<tr>\n";
			echo "  <td><a href='mhl_lager-liste.php?holzart=".$holzart['id']."' title='".$holzart['name']."'>".$holzart['name']."</a></td>\n";
			echo "  <td align='right'>".$holzart['total_pakete']."</td>\n";
			echo "  <td align='right'>".sprintf("%01.2f", $holzart['total_m3'])."</td>\n";
			echo "  <td align='right'>".sprintf("%01.2f", $holzart['total_wert'])."</td>\n";
			echo "</tr>\n";
		}
		//Totale
		echo "<tr class='total'>\n";
		echo "  <td><b>Totale</b></td>\n";
		echo "  <td align='right'>".$tot_pakete."</td>\n";
		echo "  <td align='right'>".sprintf("%01.2f", $tot_m3)."</td>\n";
		echo "  <td align='right'>".sprintf("%01.2f", $tot_chf)."</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
}
?>