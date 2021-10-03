<?php


/**
 * Grundgerüst einer HTML-Seite.
 * 
 */

class HTML
{

	/**
	 * Erstellt den Kopf eines HTML-Dokuments.
	 * 
	 */
	public function printHead()
	{

		echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\">\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>".HTML_TITLE."</title>\n";
		echo "<link rel='stylesheet' type='text/css' ".
             " href='".HTTP_ROOT."/inc/css/default.css.php'>\n";
		echo "<script src='".HTTP_ROOT."/inc/js/default.js' type='text/javascript'></script>";

	}

	/**
	 * Erstellt den "Körper" eines HTML-Dokuments.
	 * 
	 * @param varchar Zusätzliche Cascading Stylesheets
	 */
	public function printBody($css = null)
	{
		echo "</head>\n";
		echo "<body";
		if ($css != null)
		{
			echo " style='".$css."'";
		}
		echo ">\n";
	}

	/**
	 * Beendet ein HTML-Dokument.
	 * 
	 */
	public function printFoot()
	{

		echo " </body></html>";
	}

	/**
	 * Erstellt eine Überschrift für die Buch-CD
	 * 
	 * @param varchar Überschrifttext
	 * @param boolean Mit oder ohne "Zurück"-Verweis
	 */
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

}
?>