<?php
require_once '../common.php';

// Seite erstellt in... -->> $HTML->printFoot($start_time)!!
//$start_time = $HTML->pageCreation();

// Login Check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

// Check User-Level
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Nachrichtdefinition
$msg[1] = "Eintrag entfernt.";
$msg[2] = "";
$msg[3] = "Sie sind nicht berechtigt diesen Eintrag zu l&ouml;schen.";

// Seitentitel
$PAGE_TITLE = "Job Details";

//$HTML->printHead($PAGE_TITLE); // insert JS if needed, after this line
//$HTML->printBody();
//$HTML->printNavi();
//$HTML->printStartContent();

$jobID = htmlentities($_GET['jobID']);

$sql_job = "
SELECT 
  za_mitarbeiter.name,
  za_mitarbeiter.vorname,
  esv_jobs.id,
  esv_jobs.job_datum,
  esv_jobs.stunden,
  esv_jobs.blattwechsel,
  esv_jobs.bemerkung
FROM
  esv_jobs
  INNER JOIN za_mitarbeiter ON (esv_jobs.ma_id = za_mitarbeiter.id)
WHERE
  esv_jobs.id = ".$jobID."
";

$result_job = $DB->query($sql_job);

echo "<h1>Details zu Job: ".$result_job[0]['name']." ".$result_job[0]['vorname']." vom ".date('d.m.y',strtotime($result_job[0]['job_datum']))."</h1>";
// Fehlermeldung
if(isset($_GET["message"]))
{
	echo "<div id=\"message\"><p>".$msg[$_GET['message']]."</p></div>\n";
} // END Fehlermeldung
?>

<fieldset style='margin-left:25px; padding:2px;width:500px;border:1px solid grey;'>
  <legend>Job Details</legend>
    <table>
      <tr>
        <td width="200">Mitarbeiter</td>
        <td width="200"><?php echo $result_job[0]['name']." ".$result_job[0]['vorname']; ?></td>
      </tr>
      <tr>
        <td>Job vom</td>
        <td><?php echo date('d.m.y',strtotime($result_job[0]['job_datum'])); ?></td>
      </tr>
      <tr>
        <td>Total Stunden</td>
        <td><?php echo $result_job[0]['stunden']; ?></td>
      </tr>
      <tr>
        <td>Total Blattwechsel</td>
        <td><?php echo $result_job[0]['blattwechsel']; ?></td>
      </tr>
      <tr>
        <td>Total <a href="#RS">RS</a> in m3</td>
        <td>
    <?php 
    $sql_total_RS = "
		SELECT *,SUM(m3_total) AS m3_job_total
		FROM
		  esv_schnitt
		WHERE
		  esv_schnitt.job_id = ".$jobID." AND 
		  esv_schnitt.schnittart = '1'
		";   
    $result_RS = $DB->query($sql_total_RS);
    echo round($result_RS[0]['m3_job_total'],2)." m3";
    ?>        
        </td>
      </tr>
      <tr>
        <td>Total <a href="#MS">MS</a> in m3</td>
        <td>
    <?php 
    $sql_total_MS = "
		SELECT *,SUM(m3_total) AS m3_job_total
		FROM
		  esv_schnitt
		WHERE
		  esv_schnitt.job_id = ".$jobID." AND 
		  esv_schnitt.schnittart = '2'
		";   
    $result_MS = $DB->query($sql_total_MS);
    echo round($result_MS[0]['m3_job_total'],2)." m3";
    ?>        
        </td>
      </tr>
      <tr>
        <td>Total <a href="#GS">GS</a> in m3</td>
        <td>
    <?php 
    $sql_total_GS = "
		SELECT *,SUM(m3_total) AS m3_job_total
		FROM
		  esv_schnitt
		WHERE
		  esv_schnitt.job_id = ".$jobID." AND 
		  esv_schnitt.schnittart = '3'
		";   
    $result_GS = $DB->query($sql_total_GS);
    echo round($result_GS[0]['m3_job_total'],2)." m3";
    ?>        
        </td>
      </tr>
      <tr>
        <td>Total Tagesleistung</td>
        <td><?php echo round($result_RS[0]['m3_job_total']+$result_MS[0]['m3_job_total']+$result_GS[0]['m3_job_total'],2)." m3"; ?></td>
      </tr>
      <tr>
        <td valign="top">Bemerkung</td>
        <td><?php echo htmlspecialchars($result_job[0]['bemerkung']); ?></td>
      </tr>
    </table>
</fieldset>
<br />
<?php 
if ($result_RS[0]['m3_job_total'] > NULL)
{
	?>
	<fieldset>
	  <legend><a id="RS" name="Rundschnitt">Rundschnitt</a></legend>
	  <table class="grey">
	    <tr>
	      <th width="100">Holzart</th>
	      <th width="50">Stk.</th>
	      <th width="100">L&auml;nge m</th>
	      <th width="100">&#0216; cm</th>
	      <th width="75">Total m3</th>
	    </tr>
	<?php 
	$sql_RS = "
	SELECT 
	  za_holzarten.name,
	  esv_schnitt.id,
	  esv_schnitt.stk,
	  esv_schnitt.laenge,
	  esv_schnitt.dm,
	  esv_schnitt.m3_total
	FROM
	  esv_schnitt
	  INNER JOIN za_holzarten ON (esv_schnitt.holz_id = za_holzarten.id)
	WHERE
	  esv_schnitt.job_id = ".$jobID." AND 
	  esv_schnitt.schnittart = '1'
	ORDER BY za_holzarten.sort, esv_schnitt.laenge, esv_schnitt.dm
	";
	$result_RS_items = $DB->query($sql_RS);
	foreach ($result_RS_items as $RS_items)
	{
		?>
	    <tr>
	      <td><?php echo $RS_items['name']; ?></td>
	      <td align="right"><?php echo $RS_items['stk']; ?></td>
	      <td align="right"><?php echo $RS_items['laenge']; ?></td>
	      <td align="right"><?php echo $RS_items['dm']; ?></td>
	      <td align="right"><?php echo round($RS_items['m3_total'],2); ?></td>
	    </tr>	
		<?php 
	}
	?>
	  </table>
	</fieldset>
	<br />
	<?php
}
if ($result_MS[0]['m3_job_total'] > NULL)
{
	?>
	<fieldset>
	  <legend><a id="MS" name="Modelschnitt">Modelschnitt</a></legend>
	  <table class="grey">
	    <tr>
	      <th width="100">Holzart</th>
	      <th width="50">Stk.</th>
	      <th width="100">L&auml;nge m</th>
	      <th width="100">&#0216; cm</th>
	      <th width="75">Total m3</th>
	    </tr>
	<?php 
	$sql_MS = "
	SELECT 
	  za_holzarten.name,
	  esv_schnitt.id,
	  esv_schnitt.stk,
	  esv_schnitt.laenge,
	  esv_schnitt.dm,
	  esv_schnitt.m3_total
	FROM
	  esv_schnitt
	  INNER JOIN za_holzarten ON (esv_schnitt.holz_id = za_holzarten.id)
	WHERE
	  esv_schnitt.job_id = ".$jobID." AND 
	  esv_schnitt.schnittart = '2'
	ORDER BY za_holzarten.sort, esv_schnitt.laenge, esv_schnitt.dm
	";
	$result_MS_items = $DB->query($sql_MS);
	foreach ($result_MS_items as $MS_items)
	{
		?>
	    <tr>
	      <td><?php echo $MS_items['name']; ?></td>
	      <td align="right"><?php echo $MS_items['stk']; ?></td>
	      <td align="right"><?php echo $MS_items['laenge']; ?></td>
	      <td align="right"><?php echo $MS_items['dm']; ?></td>
	      <td align="right"><?php echo round($MS_items['m3_total'],2); ?></td>
	     </tr>	
		<?php 
	}
	?>
	  </table>
	</fieldset>
	<br />
	<?php
}
if ($result_GS[0]['m3_job_total'] > NULL)
{
	?>
	<fieldset>
	  <legend><a id="GS" name="Gatter.chnitt">Gatterschnitt</a></legend>
	  <table class="grey">
	    <tr>
	      <th width="100">Holzart</th>
	      <th width="50">Stk.</th>
	      <th width="100">L&auml;nge m</th>
	      <th width="100">&#0216; cm</th>
	      <th width="100">Rinde in %</th>
	      <th width="75">Total m3</th>
	    </tr>
	<?php 
	$sql_GS = "
	SELECT 
	  za_holzarten.name,
	  esv_schnitt.id,
	  esv_schnitt.stk,
	  esv_schnitt.laenge,
	  esv_schnitt.dm,
	  esv_schnitt.m3_total,
	  esv_schnitt.rinde
	FROM
	  esv_schnitt
	  INNER JOIN za_holzarten ON (esv_schnitt.holz_id = za_holzarten.id)
	WHERE
	  esv_schnitt.job_id = ".$jobID." AND 
	  esv_schnitt.schnittart = '3'
	ORDER BY za_holzarten.sort, esv_schnitt.laenge, esv_schnitt.dm
	";
	$result_GS_items = $DB->query($sql_GS);
	foreach ($result_GS_items as $GS_items)
	{
		?>
	    <tr>
	      <td><?php echo $GS_items['name']; ?></td>
	      <td align="right"><?php echo $GS_items['stk']; ?></td>
	      <td align="right"><?php echo $GS_items['laenge']; ?></td>
	      <td align="right"><?php echo $GS_items['dm']; ?></td>
	      <td align="right"><?php echo $GS_items['rinde']; ?></td>
	      <td align="right"><?php echo round($GS_items['m3_total'],2); ?></td>
	    </tr>	
		<?php 
	}
	?>
	  </table>
	</fieldset>
	<br />
	<?php
}
?>
<?php 
//$HTML->printFoot($start_time);
?>