<?php


/**
 * Grundger�st einer HTML-Seite.
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
	 * Erstellt den "K�rper" eines HTML-Dokuments.
	 * 
	 * @param varchar Zus�tzliche Cascading Stylesheets
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
	 * Erstellt eine �berschrift f�r die Buch-CD
	 * 
	 * @param varchar �berschrifttext
	 * @param boolean Mit oder ohne "Zur�ck"-Verweis
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