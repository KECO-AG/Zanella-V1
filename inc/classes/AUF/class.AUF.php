<?php
class AUF
{
	private $DB = null;

	public function __construct()
	{
		$this->DB = $GLOBALS['DB'];
	}

	function getItems($tag)
	{
		$sql 		= "SELECT * FROM `auf_items` WHERE `erledigt` IS NULL AND `datum` = '".$tag."' ORDER BY `prio`, auf_items.auftrag ASC";
		$result 	= $this->DB->query($sql);
		foreach ($result as $item)
		{
			echo "<div id='".$item['id']."' class='drag t".$item['prio']."'><b>".$item['prio']." - ".$item['auftrag']."</b><div class=\"toggle\" style=\"display: none;\">".nl2br($item['bemerkung'])."</div></div>";
		}
	}

	function GetItemsExpired($tag)
	{
		$sql 		= "SELECT * FROM `auf_items` WHERE `erledigt` IS NULL AND `datum` = '".$tag."' ORDER BY `prio`, auf_items.auftrag ASC";
		$result 	= $this->DB->query($sql);
		foreach ($result as $item)
		{
			echo "<div id='".$item['id']."' class='drag tExpired'><b>".$item['prio']." - ".$item['auftrag']."</b><div class=\"toggle\" style=\"display: none;\">".nl2br($item['bemerkung'])."</div></div>";
		}
	}
	function GetItemsErledigt($tag)
	{
		$sql 		= "SELECT * FROM `auf_items` WHERE `erledigt` IS NOT NULL AND `datum` = '".$tag."' ORDER BY `prio`, auf_items.auftrag ASC";
		$result 	= $this->DB->query($sql);
		foreach ($result as $item)
		{
			echo "<div id='".$item['id']."' class='drag tErledigt'><b>".$item['prio']." - ".$item['auftrag']."</b><div class=\"toggle\" style=\"display: none;\">".nl2br($item['bemerkung'])."</div></div>";
		}
	}

	function nichtzugewiesen()
	{
		$sql 		= "SELECT * FROM `auf_items` WHERE `erledigt` IS NULL AND datum IS NULL ORDER BY `prio`, auf_items.auftrag ASC";
		$result 	= $this->DB->query($sql);
		foreach ($result as $item)
		{
			echo "<div id='".$item['id']."' class='drag t".$item['prio']."'><b>".$item['prio']." - ".$item['auftrag']."</b><div class=\"toggle\" style=\"display: none;\">".nl2br($item['bemerkung'])."</div></div>";
		}
	}

	function abgelaufen($tag)
	{
		$sql 		= "SELECT * FROM `auf_items` WHERE `erledigt` IS NULL AND `datum` < '".$tag."' ORDER BY `prio`, auf_items.auftrag ASC";
		$result 	= $this->DB->query($sql);
		foreach ($result as $item)
		{
			$datum = date('d.m.y',strtotime($item['datum']));
			echo "<div id='".$item['id']."' class='drag t".$item['prio']."'><b>".$item['prio']." - ".$item['auftrag']."</b><div class=\"toggle\" style=\"display: none;\"><b>! ".$datum."</b><br />".nl2br($item['bemerkung'])."</div></div>";
		}
	}

	function printItemsDay($tag)
	{
		$sql 		= "SELECT * FROM `auf_items` WHERE `erledigt` IS NULL AND `datum` = '".$tag."' ORDER BY `prio`, auf_items.auftrag ASC";
		$result 	= $this->DB->query($sql);
		echo "<ul class='printday'>\n";
		foreach ($result as $item)
		{
			echo "	<li><b>".$item['prio']." - ".$item['auftrag']."</b><div class=\"toggle\" style=\"display: none;\">".nl2br($item['bemerkung'])."</div></li>\n";
		}
		echo "</ul>\n";
	}

	function printItemsWeek($tag)
	{
		$sql 		= "SELECT * FROM `auf_items` WHERE `erledigt` IS NULL AND `datum` = '".$tag."' ORDER BY `prio`, auf_items.auftrag ASC";
		$result 	= $this->DB->query($sql);
		echo "<ul class='print'>\n";
		foreach ($result as $item)
		{
			echo "	<li class='print'><b>".$item['prio']." - ".$item['auftrag']."</b><div class=\"toggle\" style=\"display: none;\">".nl2br($item['bemerkung'])."</div></li>\n";
		}
		echo "</ul>\n";
	}
	function printErledigt()
	{
		$sql 		= "SELECT * FROM `auf_items` WHERE `erledigt` IS NOT NULL ORDER BY `datum` DESC";
		$result 	= $this->DB->query($sql);
		echo "<ul>\n";
		foreach ($result as $item)
		{
			$datum = date('d.m.y',strtotime($item['datum']));
			$erledigt = date('d.m.y G:i',strtotime($item['erledigt']));
			echo "	<li><p>".$item['prio']." - ".$item['auftrag']."<br />Geplant am: ".$datum."<br /> Erledigt am: ".$erledigt."<br />".nl2br($item['bemerkung'])."</p></li>\n";
		}
		echo "</ul>\n";
	}
	function suchResultate($suchbegriff)
	{
		$sql 		= "
						SELECT *
						FROM `auf_items`
						WHERE (
						CONVERT( `bemerkung`
						USING utf8 ) LIKE '%".$suchbegriff."%'
						OR CONVERT( `auftrag`
						USING utf8 ) LIKE '%".$suchbegriff."%'
						)
						ORDER BY datum DESC
						LIMIT 0, 25
						";
		$result 	= $this->DB->query($sql);

		echo "<div style=\"width:350px;float:left\">\n";
		echo "<h4>Auftragsverwaltung</h4>\n";
		if ($result == NULL) { echo "<p><b>Nichts gefunden!</b></p>"; }
		echo "<ul>\n";
		foreach ($result as $item)
		{
			$datum = date('d.m.y',strtotime($item['datum']));
			if ($item['erledigt'] == NULL) {
				$erledigt = "";
			} else {
				$erledigt = date('d.m.y G:i',strtotime($item['erledigt']));
			}
			echo "	<li><p><a href=\"./auf_edit.php?id=".$item['id']."\">".$item['prio']." - ".$item['auftrag']."</a><br />Geplant am: ".$datum."<br /> <b>Erledigt am: ".$erledigt."</b><br />".nl2br($item['bemerkung'])."</p></li>\n";
		}
		echo "</ul>\n";
		echo "</div>\n";
	}
}