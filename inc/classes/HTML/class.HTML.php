<?php
class HTML
{
	public function pageCreation()
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$start_time = $time;
		return $start_time;
	}
	public function printHead($PAGE_TITLE)
	{
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
		echo "<head>\n";
		echo "<link type=\"text/css\" href=\"".HTTP_ROOT."/inc/css/default.css.php\" rel=\"stylesheet\" media=\"screen\" />\n";
		echo "<title>";
		if(empty($PAGE_TITLE))
		{
			echo "";
		}
		else
		{
			echo $PAGE_TITLE." :: ";
		}
		echo "".HTML_TITLE."</title>\n";
		echo "<link rel=\"shortcut icon\" href=\"http://zanella.horizonit.ch/favicon.ico\" type=\"image/x-icon\" />\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
	}

	public function printBody($css = null)
	{
		echo "</head>\n";
		echo "<body>\n";
		echo "<div id=\"container\">\n";
		echo "<div id=\"banner\" ><p><a id=\"TOP\">Zanella Holz - Turtmann</a></p>\n";
		echo "</div>\n";
		echo "<div id=\"mainnavi\" >\n";
		echo "<p><code>";
			if(isset($_SESSION["username"]))
			{
				echo "Angemeldet als: <b><a href=\"profil.php\">".$_SESSION['username']."</a></b> <a href=\"./scripts/logout.php\">Logout!</a>";
			}
		echo "</code></p>\n";
		echo "</div>\n";
	}

	public function printNavi()
	{
	?>
	<div id="left">
	<ul class="borderbottom">
		<li><a href="/">Home</a></li>
		<li><a href="/tra_index.php">Transportverwaltung</a>
			<ul>
				<li><a href="/tra_neu-job.php">Neuer Auftrag</a></li>
				<li><a href="/tra_erledigt.php">Erledigt</a></li>
			</ul>
		</li>
		<li><a href="/auf_index.php">Termin / Auftrag</a>
			<ul>
				<li><a href="/auf_neu-job.php">Neuer Auftrag</a></li>
				<li><a href="/auf_erledigt.php">Erledigt</a></li>
			</ul>
		</li>
		<li><a href="/esv_index.php">Einschnittverwaltung</a>
			<ul>
				<li><a href="/esv_job_list.php">Job Liste</a></li>
				<li><a href="/esv_newjob.php">Neuer Job</a></li>
			</ul>
		</li>
		<?php
	if ($_SESSION['level'] <= 4)
	{
	?>
		<li><a href="/lager.php">Lager</a>
			<ul>
				<li><a href="/rhl_index.php">Rohhobler</a>
					<ul>
						<li><a href="/rhl_newpack.php">Neues Paket</a></li>
						<li><a href="/rhl_lager-liste.php">Lager Liste</a></li>
						<li><a href="/rhl_suchen.php">Suchen</a></li>
						<li><a href="/rhl_latestItems.php">Letzte erfass.</a></li>
						<?php if ($_SESSION['level'] <= 3) { ?>
						<li><a href="/rhl_deleted.php">Gel&ouml;schte Pakete</a></li>
						<?php } ?>
					</ul>
				</li>
				<li><a href="/hwl_index.php">Hobelwaren</a>
					<ul>
						<li><a href="/hwl_newpack.php">Neues Paket</a></li>
						<li><a href="/hwl_lager-liste.php">Lager Liste</a></li>
						<li><a href="/hwl_suchen.php">Suchen</a></li>
						<li><a href="/hwl_latestItems.php">Letzte erfass.</a></li>
						<?php if ($_SESSION['level'] <= 3) { ?>
						<li><a href="/hwl_deleted.php">Gel&ouml;schte Pakete</a></li>
						<?php } ?>
					</ul>
				</li>
				<li><a href="/mhl_index.php">Balkenlager</a>
					<ul>
						<li><a href="/mhl_newpack.php">Neues Paket</a></li>
						<li><a href="/mhl_lager-liste.php">Lager Liste</a></li>
						<li><a href="/mhl_suchen.php">Suchen</a></li>
						<li><a href="/mhl_latestItems.php">Letzte erfass.</a></li>
						<?php if ($_SESSION['level'] <= 3) { ?>
						<li><a href="/mhl_deleted.php">Gel&ouml;schte Pakete</a></li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</li>
		<?php
	}
		?>
		<li><a href="/profil.php">Administration</a>
			<ul>
			    <li><a href="/profil.php">Profil von <?php echo $_SESSION['username']?></a></li>
			    <?php if ($_SESSION['level'] <= 2) { echo "<li><a href=\"/za_newuser.php\">Neuer Benutzer</a></li>\n"; }?>
				<?php if ($_SESSION['level'] <= 2) { echo "<li><a href=\"/za_mitarbeiter.php\">Mitarbeiter</a></li>\n"; }?>
				<?php if ($_SESSION['level'] <= 2) { echo "<li><a href=\"/za_holzarten.php\">Holzarten</a></li>\n"; }?>
				<?php if ($_SESSION['level'] <= 2) { echo "<li><a href=\"/za_hwl_produkte.php\">Hobelwaren</a></li>\n"; }?>
				<?php if ($_SESSION['level'] <= 2) { echo "<li><a href=\"/za_holztrocknung.php\">Holz Trocknung</a></li>\n"; }?>
				<?php if ($_SESSION['level'] <= 2) { echo "<li><a href=\"/za_holzqualitaet.php\">Holz Qualit&auml;t</a></li>\n"; }?>

			</ul>
		</li>

	</ul>
	</div>
	<?php
	}

	public function printStartContent()
	{
		echo "<div id=\"content\">\n"; // Div content
	}

	public function printFoot($start_time = NULL)
	{
		echo "\n</div>\n";
		echo "<div id=\"footer\">\n";
		echo "<p align=\"right\"><a href=\"#TOP\">nach oben</a></p>\n";
		echo "<p><a href=\"http://www.horizon-it.ch/\" target=\"_blank\">horizon IT GmbH</a> :: IT solutions for you!</p>\n";
		echo "</div>\n";
		echo "</div>\n";
		if ($start_time != NULL)
		{
			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$finish_time = $time;
			$total_time = round(($finish_time - $start_time), 4);
			echo "<div align=\"center\"><p><code>Seite erstellt in: ".$total_time." sekunden.</code></p></div>\n";
		}
		echo "</body>\n";
		echo "</html>\n";
	}

	public function printHeadline($headline, $withoutBacklink = false)
	{

		echo "<div style='padding:2px;'>";
		echo "<span style='font-size:20pt;font-weight:bold;".
		     " color:steelblue;border-bottom:1px solid gray;'>";
		echo $headline;
		echo "</span><br />";
		if ($withoutBacklink == false)
		{
			echo "<a href='".HTTP_ROOT."/index.php' style='font-weight:normal;color:black;".
			     "text-decoration:none;'>Zur&uuml;ck</a><br />";
		}
		echo "</div>";

	}

	public function printMessage($msg)
	{
		if (isset($_GET['message']))
		{
			if (!is_numeric($_GET['message']))
			{
				echo "<div id=\"message\"><p>Fehlermeldung nicht definiert! XSS Versuch? ;)</p></div>\n";
			}
			else
			{
				echo "<div id=\"message\"><p>".$msg[$_GET['message']]."</p></div>\n";
			}
		}
	}

}
?>