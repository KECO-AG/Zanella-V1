<?php
require_once 'common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Eintrag gel&auml;ndert.";
$msg[2] = "Eintrag gel&ouml;scht.";
$msg[3] = "Sie sind nicht befugt diesen Eintrag zu l&ouml;schen!";

// Definition Seitentitel
$PAGE_TITLE = "";

$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
?>
<style>
#comment-wrapper {
  position: relative;
}

#commentWrapper { /* required to avoid jumping */
  right: 10px;
  position: absolute;
  margin-left: 10px;
  width:150px;
}

#comment {
  position: absolute;
  top: 0;
  right: 10px;
  border: 1px solid grey;
  background-color: #CCC;
}

#comment.fixed {
  position: fixed;
  top: 0;
  right: 10px;
  }

</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script>
$(function () {

  var msie6 = $.browser == 'msie' && $.browser.version < 7;

  if (!msie6) {
    var top = $('#comment').offset().top - parseFloat($('#comment').css('margin-top').replace(/auto/, 0));
    $(window).scroll(function (event) {
      // what the y position of the scroll is
      var y = $(this).scrollTop();

      // whether that's below the form
      if (y >= top) {
        // if so, ad the fixed class
        $('#comment').addClass('fixed');
      } else {
        // otherwise remove it
        $('#comment').removeClass('fixed');
      }
    });
  }
});
</script>
<script type="text/javascript">
function calculate(f)
{
var nums = f.num;
var ntext = f.numtext;
var nitem = f.numitem;
var result = 0;
var items = '';
for(var i=0;i<nums.length;i++)
{
if(nums[i].checked)
{
result+=parseFloat(ntext[i].value);
items+=nitem[i].value+', ';
}
}
f.answer.value=result;

//if you want to fix to 2 decimal places
//f.answer.value=Number(result).toFixed(2);

f.allitems.value=items;
}
</script>
<?php

$HTML->printBody();
$HTML->printNavi();
$HTML->printStartContent();

// Holzarten für Menü
$sql_holzarten = "
	SELECT
	  za_hwl_produkte.id,
	  za_hwl_produkte.name
	FROM
	  hwl_items
	  INNER JOIN za_hwl_produkte ON (hwl_items.produkt = za_hwl_produkte.id)
	WHERE
	  hwl_items.deleted IS NULL
	GROUP BY
	  hwl_items.produkt
	ORDER BY
	  za_hwl_produkte.sort ASC
";
$result_holzarten = $DB->query($sql_holzarten);
 // Menü
echo "<br />";
echo "<p>\n";
foreach ($result_holzarten as $holzarten)
{
	echo "<a href='hwl_lager-liste.php?holzart=".$holzarten['id']."'>".$holzarten['name']."</a> ";
}
echo "</p>\n";
// End Menü

if (isset($_GET['holzart']))
{
	if (is_numeric($_GET['holzart']))
	{
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
			  hwl_items.produkt = '".$_GET['holzart']."' AND
			  hwl_items.deleted IS NULL
		";
		$result_total_holzart = $DB->query($sql_total_holzart);

		echo "<h1>Lager Liste: ".$result[0]['name']." Total: ".round($result_total_holzart[0]['total_holzart'],1)." m2 (<a href='/xls/hwl_items-liste_xls.php?holzart=".$_GET['holzart']."'>.xls</a>)</h1>\n";
		// Fehlermeldung (Nachrichtendefinition)
		$HTML->printMessage($msg);


		// Calc Box & Form
		echo "<form name='myform'>\n";
		echo "<div id='commentWrapper'>\n";
		echo "  <div id='comment'>\n";
		echo "    Total <input type='text' name='answer' size='10'> m2<br />\n";
		echo "    <b>Gew&auml;hlte Pakete:</b><br />\n";
		echo "    <textarea name='allitems' rows='10' ></textarea>\n";
		echo "    <br /><input type='reset' value='Berechnung loeschen'>\n";
		echo "  </div>\n";
		echo "</div>\n";

		// Tabellen
		echo "<table class='grey'>\n";
		// Tabellen Header
		echo "  <tr>\n";
		echo "    <th width='45'>Paket</th>\n";
		echo "    <th width='50'>Dicke</th>\n";
		echo "    <th width='50'>Breite</th>\n";
		echo "    <th width='50'>L&auml;nge</th>\n";
		echo "    <th width='35'>Stk.</th>\n";
		echo "    <th width='60'>Total m2</th>\n";
		echo "    <th width='80'>Gestapelt</th>\n";
		echo "    <th width='20'>R</th>\n";
		echo "    <th width='20'>i</th>\n";
		echo "    <th width='20'>B</th>\n";
		echo "    <th width='20'>L</th>\n";
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


				$restposten = "";
				if ($items['restposten'] == 1) { $restposten = " bgcolor='yellow'"; }

				echo "  <tr".$restposten.">\n";
				//echo "  <tr>\n";
				echo "    <td align='right'>".$items['paket']."</td>\n";
				echo "    <td align='right'>".$items['dicke']."</td>\n";
				echo "    <td align='right'>".$items['breite']."</td>\n";
				echo "    <td align='right'>".$items['laenge']."</td>\n";
				echo "    <td align='right'>".$items['stk']."</td>\n";
				echo "    <td align='right'>".$m2."</td>\n";
				echo "    <td align='right'>".$items['date']."</td>\n";
				echo "    <td>\n";
				echo "      <input type='checkbox' name='num' onclick='calculate(this.form)'>\n";
				echo "      <input type='hidden' name='numtext' value='".$m2."' onchange='calculate(this.form)'>\n";
				echo "      <input type='hidden' name='numitem' value='".$items['paket']."' onchange='calculate(this.form)'>\n";
				echo "	  </td>\n";
				if ($items['comments'] == NULL) { echo "    <td>&nbsp</td>\n"; }
				else {echo "    <td><a href='#' title='".$items['comments']."'><img src='/images/icon_info.png' width='19' height='19' border='0' alt='".$items['comments']."' /></a></td>\n";}
				echo "    <td><a href='/hwl_update.php?action=edit&id=".$items['id']."&holzart=".$_GET['holzart']."' alt='Bearbeiten'><img src='/images/icon_edit.png' width='19' height='19' border='0' alt='Bearbeiten' /></a></td>\n";
				echo "    <td><a href='/scripts/script-hwl.php?action=delete&id=".$items['id']."&holzart=".$_GET['holzart']."' alt='L&ouml;schen'><img src='/images/icon_delete.png' width='19' height='19' border='0' alt='L&ouml;schen' /></a></td>\n";
				echo "  </tr>\n";
			}
			echo "  <tr>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td><b>Total</b></td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td align='right'><b>".sprintf("%01.3f", $total_m2)."</b></td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td><a href='#TOP'><img src='/images/icon_up.png' width='19' height='19' border='0' alt='Nach oben' / </a></td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "    <td>&nbsp;</td>\n";
			echo "  </tr>\n";
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
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "    <td>&nbsp;</td>\n";
		echo "  </tr>\n";

		echo "</table>\n";
		echo "</form>\n";

	}
	else // Holzart parameter falsch
	{
		// Seitenüberschrift
		echo "<h1>Lager Liste </h1>\n";
		// Fehlermeldung (Nachrichtendefinition)
		$HTML->printMessage($msg);
		echo "<p>Bitte eine Holzart ausw&auml;hlen.</p>\n";
	}
}
else // Keine Holzart Gewählt
{
	// Seitenüberschrift
	echo "<h1>Lager Liste </h1>\n";
	// Fehlermeldung (Nachrichtendefinition)
	$HTML->printMessage($msg);
	echo "<p>Bitte eine Holzart ausw&auml;hlen.</p>\n";
}



// Ende Inhalt
$HTML->printFoot($start_time);
?>