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
	  za_holzarten.id,
	  za_holzarten.name
	FROM
	  mhl_items
	  INNER JOIN za_holzarten ON (mhl_items.holzart = za_holzarten.id)
	WHERE
	  mhl_items.deleted IS NULL
	GROUP BY
	  mhl_items.holzart
	ORDER BY
	  za_holzarten.sort ASC
";
$result_holzarten = $DB->query($sql_holzarten);
 // Menü
echo "<br />";
echo "<p>\n";
foreach ($result_holzarten as $holzarten)
{
	echo "<a href='mhl_lager-liste.php?holzart=".$holzarten['id']."'>".$holzarten['name']."</a> ";
}
echo "</p>\n";
// End Menü

if (isset($_GET['holzart']))
{
	if (is_numeric($_GET['holzart']))
	{
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
			  mhl_items.holzart = '".$_GET['holzart']."' AND
			  mhl_items.deleted IS NULL
		";
		$result_total_holzart = $DB->query($sql_total_holzart);

		echo "<h1>Lager Liste: ".$result[0]['name']." Total: ".round($result_total_holzart[0]['total_holzart'],1)." m3 (<a href='/xls/mhl_items-liste_xls.php?holzart=".$_GET['holzart']."'>.xls</a>)</h1>\n";
		// Fehlermeldung (Nachrichtendefinition)
		$HTML->printMessage($msg);


		// Calc Box & Form
		echo "<form name='myform'>\n";
		echo "<div id='commentWrapper'>\n";
		echo "  <div id='comment'>\n";
		echo "    Total <input type='text' name='answer' size='10'> m3<br />\n";
		echo "    <b>Gew&auml;hlte Pakete:</b><br />\n";
		echo "    <textarea name='allitems' rows='10' ></textarea>\n";
		echo "    <br /><input type='reset' value='Berechnung loeschen'>\n";
		echo "  </div>\n";
		echo "</div>\n";

		// Tabellen
		echo "<table class='grey'>\n";
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
		echo "    <th width='20'>R</th>\n";
		echo "    <th width='20'>i</th>\n";
		echo "    <th width='20'>B</th>\n";
		echo "    <th width='20'>L</th>\n";
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
			  mhl_items.holzart = '".$_GET['holzart']."' AND
			  mhl_items.deleted IS NULL
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
					  mhl_comments.comments,
					  mhl_items.restposten
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

					$restposten = "";
					if ($items['restposten'] == 1) { $restposten = " bgcolor='yellow'"; }

					echo "  <tr".$restposten.">\n";
					//echo "  <tr>\n";
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
					echo "    <td>\n";
					echo "      <input type='checkbox' name='num' onclick='calculate(this.form)'>\n";
					echo "      <input type='hidden' name='numtext' value='".$m3."' onchange='calculate(this.form)'>\n";
					echo "      <input type='hidden' name='numitem' value='".$items['paket']."' onchange='calculate(this.form)'>\n";
					echo "</td>\n";
					if ($items['comments'] == NULL) { echo "    <td>&nbsp</td>\n"; }
					else {echo "    <td><a href='#' title='".$items['comments']."'><img src='/images/icon_info.png' width='19' height='19' border='0' alt='".$items['comments']."' /></a></td>\n";}
					echo "    <td><a href='/mhl_update.php?action=edit&id=".$items['id']."&holzart=".$_GET['holzart']."' alt='Bearbeiten'><img src='/images/icon_edit.png' width='19' height='19' border='0' alt='Bearbeiten' /></a></td>\n";
					echo "    <td><a href='/scripts/script-mhl.php?action=delete&id=".$items['id']."&holzart=".$_GET['holzart']."' alt='L&ouml;schen'><img src='/images/icon_delete.png' width='19' height='19' border='0' alt='L&ouml;schen' /></a></td>\n";
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
				echo "    <td><a href='#TOP'><img src='/images/icon_up.png' width='19' height='19' border='0' alt='Nach oben' / </a></td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
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
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "    <td>&nbsp;</td>\n";
				echo "  </tr>\n";
			} // END t_q
		} // END d_b
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