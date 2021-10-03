<?php
// Einschnittverwaltung

class ESV
{
	private $DB = null;
	
	public function __construct()
	{
		$this->DB = $GLOBALS['DB'];
	}
	
	public function daySummary($anz)
	{
		$sql_dates = "
			SELECT 
			  esv_jobs.job_datum
			FROM
			  esv_jobs
			
			GROUP BY
			  esv_jobs.job_datum
			ORDER BY
			  esv_jobs.job_datum DESC
			LIMIT ".$anz."
		";
		
		echo "<h2>Die letzten ".$anz." Tage</h2>\n";
		echo "<table class='grey'>\n";
		echo "  <tr>\n";
		echo "    <th width='75'>Tag</th>\n";
		echo "    <th width='35'>Tot. Jobs</th>\n";
		echo "    <th width='50'>Std.</th>\n";
		echo "    <th width='35'>BW</th>\n";
		echo "    <th width='70'>MS m3</th>\n";
		echo "    <th width='70'>RS m3</th>\n";
		echo "    <th width='70'>Total BBS m3</th>\n";
		echo "    <th width='70'>m3/h BBS</th>\n";
		echo "    <th width='70'>Gatter m3</th>\n";
		echo "    <th width='70'>Tot. BBS & Gatter m3</th>\n";
		echo "  </tr>\n";
		
		$result_dates = $this->DB->query($sql_dates);
		foreach ($result_dates as $datum)
		{
			$sql_job_totals = "
				SELECT 
				  SUM(esv_jobs.stunden) AS tot_std,
				  SUM(esv_jobs.blattwechsel) AS tot_bw,
				  COUNT(esv_jobs.id) AS tot_jobs
				FROM
				  esv_jobs
				WHERE
				  esv_jobs.job_datum = '".$datum['job_datum']."'			
			";
			$sql_tot_ms = "
				SELECT 
				  SUM(esv_schnitt.m3_total) AS total
				FROM
				  esv_schnitt
				  INNER JOIN esv_jobs ON (esv_schnitt.job_id = esv_jobs.id)
				WHERE
				  esv_jobs.job_datum = '".$datum['job_datum']."'
				  AND esv_schnitt.schnittart = '2'
			";
			$sql_tot_rs = "
				SELECT 
				  SUM(esv_schnitt.m3_total) AS total
				FROM
				  esv_schnitt
				  INNER JOIN esv_jobs ON (esv_schnitt.job_id = esv_jobs.id)
				WHERE
				  esv_jobs.job_datum = '".$datum['job_datum']."'
				  AND esv_schnitt.schnittart = '1'
			";
			$sql_tot_gs = "
				SELECT 
				  SUM(esv_schnitt.m3_total) AS total
				FROM
				  esv_schnitt
				  INNER JOIN esv_jobs ON (esv_schnitt.job_id = esv_jobs.id)
				WHERE
				  esv_jobs.job_datum = '".$datum['job_datum']."'
				  AND esv_schnitt.schnittart = '3'
			";
			$sql_tot_bbs_std = "
				SELECT
				  SUM(esv_jobs.stunden) AS total_bbs_std
				FROM
				  esv_jobs
				WHERE
				  esv_jobs.job_datum = '".$datum['job_datum']."'
				  AND esv_jobs.art = '1'
			";
			
			
			$result_job_totals = $this->DB->query($sql_job_totals);
			$result_MS_total = $this->DB->query($sql_tot_ms);
			$result_RS_total = $this->DB->query($sql_tot_rs);
			$result_GS_total = $this->DB->query($sql_tot_gs);
			$result_tot_bbs_std = $this->DB->query($sql_tot_bbs_std);
			
			if ($result_tot_bbs_std[0]['total_bbs_std'] == 0 ) 
				{ 
					$m3_std_bbs = 0; 
				}
			if ($result_tot_bbs_std[0]['total_bbs_std'] > 0 ) 
				{ 
					$m3_std_bbs = round(($result_MS_total[0]['total']+$result_RS_total[0]['total'])/$result_tot_bbs_std[0]['total_bbs_std'],2); 
				}
			
			echo "  <tr>\n";
			echo "    <td align='right'>".date('d.m.y',strtotime($datum['job_datum']))."</td>\n";
			echo "    <td align='right'>".$result_job_totals[0]['tot_jobs']."</td>\n";
			echo "    <td align='right'>".$result_job_totals[0]['tot_std']."</td>\n";
			echo "    <td align='right'>".$result_job_totals[0]['tot_bw']."</td>\n";
			echo "    <td align='right'>".round($result_MS_total[0]['total'],2)."</td>\n";
			echo "    <td align='right'>".round($result_RS_total[0]['total'],2)."</td>\n";
			echo "    <td align='right' class='total'>".round($result_MS_total[0]['total']+$result_RS_total[0]['total'],2)."</td>\n";
			echo "    <td align='right'>".$m3_std_bbs."</td>\n";
			echo "    <td align='right'>".round($result_GS_total[0]['total'],2)."</td>\n";
			echo "    <td align='right' class='total'>".round($result_MS_total[0]['total']+$result_RS_total[0]['total']+$result_GS_total[0]['total'],2)."</td>\n";
			echo "  </tr>\n";
		}		
		echo "</table>\n";
		echo "<br />\n";
	}
	
	public function monatsTotaleJahr($year)
	{
		//echo "<hr />";
		echo "<h3>Monats Totale ".$year."</h3>\n";
		echo "<table class='grey'>\n";
		echo "  <tr>\n";
		echo "    <th width='75'>Monat</th>\n";
		echo "    <th width='35'>Tot. Jobs</th>\n";
		echo "    <th width='50'>Std.</th>\n";
		echo "    <th width='35'>BW</th>\n";
		echo "    <th width='70'>MS m3</th>\n";
		echo "    <th width='70'>RS m3</th>\n";
		echo "    <th width='70'>Total BBS m3</th>\n";
		echo "    <th width='70'>m3/h BBS</th>\n";
		echo "    <th width='70'>Gatter m3</th>\n";
		echo "    <th width='70'>Tot. BBS & Gatter m3</th>\n";
		echo "  </tr>\n";
		
		$month = array(1 => "Januar", "Februar", "M&auml;rz", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
		// Totals
		$jahr_jobs = 0;
		$jahr_std = 0;
		$jahr_bw = 0;
		$jahr_ms = 0;
		$jahr_rs = 0;
		$jahr_gs = 0;
		
		foreach ($month as $key=>$monatsname)
		{
			$sql_total_jobs = "
				SELECT
				  SUM(esv_jobs.stunden) AS std_total,
				  SUM(esv_jobs.blattwechsel) AS bw_total,
				  COUNT(esv_jobs.id) AS jobs_total
				FROM
				  esv_jobs
				WHERE 
				  YEAR(esv_jobs.job_datum) = '".$year."'
				  AND MONTH(esv_jobs.job_datum) = '".$key."' 			
			";
			$sql_tot_ms = "
				SELECT 
				  SUM(esv_schnitt.m3_total) AS total
				FROM
				  esv_schnitt
				  INNER JOIN esv_jobs ON (esv_schnitt.job_id = esv_jobs.id)
				WHERE
				  esv_schnitt.schnittart = '2'
				  AND YEAR(esv_jobs.job_datum) = '".$year."'
				  AND MONTH(esv_jobs.job_datum) = '".$key."'
			";
			$sql_tot_rs = "
				SELECT 
				  SUM(esv_schnitt.m3_total) AS total
				FROM
				  esv_schnitt
				  INNER JOIN esv_jobs ON (esv_schnitt.job_id = esv_jobs.id)
				WHERE
				  esv_schnitt.schnittart = '1'
				  AND YEAR(esv_jobs.job_datum) = '".$year."'
				  AND MONTH(esv_jobs.job_datum) = '".$key."'
			";
			$sql_tot_gs = "
				SELECT 
				  SUM(esv_schnitt.m3_total) AS total
				FROM
				  esv_schnitt
				  INNER JOIN esv_jobs ON (esv_schnitt.job_id = esv_jobs.id)
				WHERE
				  esv_schnitt.schnittart = '3'
				  AND YEAR(esv_jobs.job_datum) = '".$year."'
				  AND MONTH(esv_jobs.job_datum) = '".$key."'
			";
			
			$sql_tot_bbs_std = "
				SELECT
				  SUM(esv_jobs.stunden) AS total_bbs_std
				FROM
				  esv_jobs
				WHERE
				  YEAR(esv_jobs.job_datum) = '".$year."'
				  AND MONTH(esv_jobs.job_datum) = '".$key."'
				  AND esv_jobs.art = '1'
			";
			
			$result_total_jobs = $this->DB->query($sql_total_jobs);
			$total_ms = $this->DB->query($sql_tot_ms);
			$total_rs = $this->DB->query($sql_tot_rs);
			$total_gs = $this->DB->query($sql_tot_gs);
			$result_tot_bbs_std = $this->DB->query($sql_tot_bbs_std);
			
			if ($result_tot_bbs_std[0]['total_bbs_std'] == 0 ) 
				{ 
					$m3_std_bbs = 0; 
				}
			if ($result_tot_bbs_std[0]['total_bbs_std'] > 0 ) 
				{ 
					$m3_std_bbs = round(($total_ms[0]['total']+$total_rs[0]['total'])/$result_tot_bbs_std[0]['total_bbs_std'],2); 
				}
			
			echo "  <tr onclick=\"toggle(this)\">\n";
			echo "    <td>".$monatsname."</td>\n";
			echo "    <td align='right'>".$result_total_jobs[0]['jobs_total']."</td>\n";
			echo "    <td align='right'>".$result_total_jobs[0]['std_total']."</td>\n";
			echo "    <td align='right'>".$result_total_jobs[0]['bw_total']."</td>\n";
			echo "    <td align='right'>".round($total_ms[0]['total'],2)."</td>\n";
			echo "    <td align='right'>".round($total_rs[0]['total'],2)."</td>\n";
			echo "    <td align='right' class='total'>".round($total_ms[0]['total']+$total_rs[0]['total'],2)."</td>\n";
			echo "    <td align='right'>".$m3_std_bbs."</td>\n";
			echo "    <td align='right'>".round($total_gs[0]['total'],2)."</td>\n";
			echo "    <td align='right' class='total'>".round($total_ms[0]['total']+$total_rs[0]['total']+$total_gs[0]['total'],2)."</td>\n";
			echo "  </tr>\n";
			
			$jahr_jobs = $jahr_jobs+$result_total_jobs[0]['jobs_total'];
			$jahr_std = $jahr_std+$result_total_jobs[0]['std_total'];
			$jahr_bw = $jahr_bw+$result_total_jobs[0]['bw_total'];
			$jahr_ms = $jahr_ms+$total_ms[0]['total'];
			$jahr_rs = $jahr_rs+$total_rs[0]['total'];
			$jahr_gs = $jahr_gs+$total_gs[0]['total'];
		}

		
		// Jahres Total	
		echo "  <tr class='total'>\n";
		echo "    <td><b>Total</b></td>\n";
		echo "    <td align='right'>".$jahr_jobs."</td>\n";
		echo "    <td align='right'>".$jahr_std."</td>\n";
		echo "    <td align='right'>".$jahr_bw."</td>\n";
		echo "    <td align='right'>".round($jahr_ms,2)."</td>\n";
		echo "    <td align='right'>".round($jahr_rs,2)."</td>\n";
		echo "    <td align='right'>".round($jahr_rs+$jahr_ms,2)."</td>\n";
		echo "    <td align='right'></td>\n";
		echo "    <td align='right'>".round($jahr_gs,2)."</td>\n";
		echo "    <td align='right'>".round($jahr_rs+$jahr_ms+$jahr_gs,2)."</td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br />\n";
	}
	
	public function lastJobs($anz)
	{
		$sql = "
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
		ORDER BY
		  esv_jobs.job_datum DESC
		LIMIT ".$anz."			
		";
		
		echo "<h2>Die letzten ".$anz." Jobs.</h2>\n";
		echo "<table class='grey'>\n";
		echo "<tr align='left'>\n";
		echo "<th width='60'>Datum</th>\n";
		echo "<th width='140'>Mitarbeiter</th>\n";
		echo "<th width='50'>Std.</th>\n";
		echo "<th width='35'>BW</th>\n";
		echo "<th width='70'>MS m3</th>\n";
		echo "<th width='70'>RS m3</th>\n";
		echo "<th width='70'>Total BBS m3</th>\n";
		echo "<th width='70'>m3/Std BBS</th>\n";
		echo "<th width='70'>Gatter m3</th>\n";
		echo "<th width='70'>Tot. BBS & Gatter m3</th>\n";
		//echo "<th width='50'>&nbsp;</th>\n";
		echo "</tr>\n";
		
		$result = $this->DB->query($sql);
		foreach ($result as $job)
		{
			$sql_total_MS = "
				SELECT *,SUM(m3_total) AS m3_job_total
				FROM
				  esv_schnitt
				WHERE
				  esv_schnitt.job_id = ".$job['id']." AND 
				  esv_schnitt.schnittart = '2'			
			";			
			$sql_total_RS = "
				SELECT *,SUM(m3_total) AS m3_job_total
				FROM
				  esv_schnitt
				WHERE
				  esv_schnitt.job_id = ".$job['id']." AND 
				  esv_schnitt.schnittart = '1'			
			";	
			$sql_total_GS = "
			SELECT *,SUM(m3_total) AS m3_job_total
			FROM
			  esv_schnitt
			WHERE
			  esv_schnitt.job_id = ".$job['id']." AND
			  esv_schnitt.schnittart = '3'
			"; 	
			$result_MS = $this->DB->query($sql_total_MS);	
			$result_RS = $this->DB->query($sql_total_RS);
			$result_GS = $this->DB->query($sql_total_GS);
				
					
			echo "<tr>\n";
			echo "<td><a href='esv_job_details.php?jobID=".htmlentities($job['id'])."'>".date('d.m.y',strtotime($job['job_datum']))."</a></td>\n";
			echo "<td>".htmlentities($job['name'])." ".htmlentities($job['vorname'])."</td>\n";
			echo "<td align='right'>".htmlentities($job['stunden'])."</td>\n";
			echo "<td align='right'>".htmlentities($job['blattwechsel'])."</td>\n";
			echo "<td align='right'>".round($result_MS[0]['m3_job_total'],2)."</td>\n";		
			echo "<td align='right'>".round($result_RS[0]['m3_job_total'],2)."</td>\n";
			echo "<td align='right' class='total'>".round($result_MS[0]['m3_job_total']+$result_RS[0]['m3_job_total'],2)."</td>\n";
			echo "<td align='right'>";
			if ($job['stunden'] > 0) { echo round(($result_MS[0]['m3_job_total']+$result_RS[0]['m3_job_total'])/$job['stunden'],2); }
			echo "</td>\n";
			echo "<td align='right'>".round($result_GS[0]['m3_job_total'],2)."</td>\n";
			echo "<td align='right' class='total'>".round($result_MS[0]['m3_job_total']+$result_RS[0]['m3_job_total']+$result_GS[0]['m3_job_total'],2)."</td>\n";
			//echo "<td><a href='esv_job_details.php?jobID=".htmlentities($job['id'])."'>Details</a></td>\n";
			echo "</tr>\n";			
		}
		echo "</table>\n";
		echo "<br />";
	}
	
	public function printMAStats($year)
	{
		$sql_ma_stats = "
		SELECT 
		  esv_jobs.ma_id,
		  za_mitarbeiter.name,
		  za_mitarbeiter.vorname,
		  SUM(blattwechsel) AS BW,
		  SUM(stunden) AS std
		FROM
		  esv_jobs
		  INNER JOIN za_mitarbeiter ON (esv_jobs.ma_id = za_mitarbeiter.id)
		WHERE
		  YEAR(esv_jobs.job_datum) = '".$year."'
		GROUP BY
		  esv_jobs.ma_id
		ORDER BY
		  za_mitarbeiter.name
		";
		$result_ma_stats = $this->DB->query($sql_ma_stats);
		if ($result_ma_stats > NULL)
		{
			echo "<h2>Mitarbeiter Leistung ".$year."</h2>\n";
			echo "<table class='grey'>\n";
			echo "<tr>\n";
			echo "<th width='200'>Mitarbeiter</th>\n";
			echo "<th width='50'>Std.</th>\n";
			echo "<th width='35'>BW</th>\n";
			echo "<th width='70'>MS m3</th>\n";
			echo "<th width='70'>RS m3</th>\n";
			echo "<th width='70'>Total BBS</th>\n";
			echo "<th width='70'>m3/h BBS</th>\n";
			echo "<th width='70'>GS m3</th>\n";
			echo "<th width='70'>Tot. BBS & GS m3</th>\n";
			echo "</tr>\n";
			
			$total_all_MS = 0;
			$total_all_RS = 0;
			$total_all_GS = 0;
			$total_all_std = 0;
			$total_all_BW = 0;
			$total_ds_std = 0;
			$total_MA = 0;
			
			foreach ($result_ma_stats as $ma_stats)
			{
				$maID = $ma_stats['ma_id'];
				$sql_ma_stats_wood = "
				SELECT 
				  SUM(IF(esv_schnitt.schnittart = '1', m3_total, 0)) AS m3_RS_jahr,
				  SUM(IF(esv_schnitt.schnittart = '2', m3_total, 0)) AS m3_MS_jahr,
				  SUM(IF(esv_schnitt.schnittart = '3', m3_total, 0)) AS m3_GS_jahr
				FROM
				  esv_schnitt
				  INNER JOIN esv_jobs ON (esv_schnitt.job_id = esv_jobs.id)
				WHERE
				  esv_jobs.ma_id = ".$maID." 
				  AND YEAR(esv_jobs.job_datum) = '".$year."'
				";
				$result_ma_stats_wood = $this->DB->query($sql_ma_stats_wood);
				$total_wood = round($result_ma_stats_wood[0]['m3_MS_jahr'] + $result_ma_stats_wood[0]['m3_RS_jahr'] + $result_ma_stats_wood[0]['m3_GS_jahr'],2);

				// Total Rechnen
				$total_all_MS = round($total_all_MS + $result_ma_stats_wood[0]['m3_MS_jahr'],2);
				$total_all_RS = round($total_all_RS + $result_ma_stats_wood[0]['m3_RS_jahr'],2);
				$total_all_GS = round($total_all_GS + $result_ma_stats_wood[0]['m3_GS_jahr'],2);
				$total_all_std = round($total_all_std + $ma_stats['std'],2);
				$total_all_BW = round($total_all_BW + $ma_stats['BW'],2);
				
				$total_ds_std = $total_ds_std + (round(($result_ma_stats_wood[0]['m3_RS_jahr']+$result_ma_stats_wood[0]['m3_MS_jahr']/$ma_stats['std']),2));
				$total_MA = $total_MA + 1;				
							
				echo "<tr>\n";
				echo "<td><a href='za_ma_stats.php?maID=".$ma_stats['ma_id']."'>".$ma_stats['name']." ".$ma_stats['vorname']."</a></td>\n";
				echo "<td align='right'>".$ma_stats['std']."</td>\n";
				echo "<td align='right'>".$ma_stats['BW']."</td>\n";
				echo "<td align='right'>".round($result_ma_stats_wood[0]['m3_MS_jahr'],2)."</td>\n";
				echo "<td align='right'>".round($result_ma_stats_wood[0]['m3_RS_jahr'],2)."</td>\n";
				echo "<td align='right' class='total'>".round($result_ma_stats_wood[0]['m3_RS_jahr']+$result_ma_stats_wood[0]['m3_MS_jahr'],2)."</td>\n";
				echo "<td align='right'>".round(($result_ma_stats_wood[0]['m3_RS_jahr']+$result_ma_stats_wood[0]['m3_MS_jahr']/$ma_stats['std']),2)."</td>\n";
				echo "<td align='right'>".round($result_ma_stats_wood[0]['m3_GS_jahr'],2)."</td>\n";
				echo "<td align='right' class='total'>".round($result_ma_stats_wood[0]['m3_RS_jahr']+$result_ma_stats_wood[0]['m3_MS_jahr']+$result_ma_stats_wood[0]['m3_GS_jahr'],2)."</td>\n";
				echo "</tr>\n";


			}
			
			echo "<tr class='total'>\n";
			echo "<td><b>TOTAL</b></td>\n";
			echo "<td align='right'>".$total_all_std."</td>\n";
			echo "<td align='right'>".$total_all_BW."</td>\n";
			echo "<td align='right'>".$total_all_MS."</td>\n";
			echo "<td align='right'>".$total_all_RS."</td>\n";
			echo "<td align='right'>".($total_all_MS+$total_all_RS)."</td>\n";
			echo "<td align='right'>&nbsp;</td>\n";
			echo "<td align='right'>".$total_all_GS."</td>\n";
			echo "<td align='right'>".($total_all_MS+$total_all_RS+$total_all_GS)."</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "<br />\n";
		}
	}
	
	public function printHolzStats($year)
	{
		$sql_holzart_jahr = "
		SELECT 
		  za_holzarten.name,
		  SUM(esv_schnitt.m3_total) AS m3_total,
		  SUM(IF(esv_schnitt.schnittart = '1', m3_total,0)) AS m3_RS_jahr,
		  SUM(IF(esv_schnitt.schnittart = '2', m3_total,0)) AS m3_MS_jahr,
		  SUM(IF(esv_schnitt.schnittart = '3', m3_total,0)) AS m3_GS_jahr
		FROM
		  esv_jobs
		  INNER JOIN esv_schnitt ON (esv_jobs.id = esv_schnitt.job_id)
		  INNER JOIN za_holzarten ON (esv_schnitt.holz_id = za_holzarten.id)
		
		WHERE YEAR(esv_jobs.job_datum) = '".$year."' 
		GROUP BY za_holzarten.name
		";
		$result_holzart_stats_ty = $this->DB->query($sql_holzart_jahr);
		if ($result_holzart_stats_ty > NULL)
		{		
			$totalMS = 0;
			$totalRS = 0;
			$totalGS = 0;
			$total = 0;
			
			echo "<h2>Schnittmengen nach Holzart ".$year."</h2>\n";
			echo "<table class='grey'>\n";
			echo "<tr>\n";
			echo "<th width='200'>Holzart</th>\n";
			echo "<th width='75'>MS</th>\n";
			echo "<th width='75'>RS</th>\n";
			echo "<th width='75'>GS</th>\n";
			echo "<th width='100'>Total m3</th>\n";
			echo "</tr>\n";
			
			foreach ($result_holzart_stats_ty as $ha_stats)
			{
				$totalMS = round($totalMS + $ha_stats['m3_MS_jahr'],2);
				$totalRS = round($totalRS + $ha_stats['m3_RS_jahr'],2);
				$totalGS = round($totalGS + $ha_stats['m3_GS_jahr'],2);
				$total = round($total + $ha_stats['m3_total'],2);
								
				echo "<tr>\n";
				echo "<td>".$ha_stats['name']."</td>\n";
				echo "<td align='right'>".round($ha_stats['m3_MS_jahr'],2)."</td>\n";
				echo "<td align='right'>".round($ha_stats['m3_RS_jahr'],2)."</td>\n";
				echo "<td align='right'>".round($ha_stats['m3_GS_jahr'],2)."</td>\n";
				echo "<td class='total' align='right'>".round($ha_stats['m3_total'],2)."</td>\n";
				echo "</tr>\n";	
			
			}
			echo "<tr class=\"total\">\n";
			echo "<td><b>TOTAL</b></td>\n";
			echo "<td align='right'>".$totalMS."</td>\n";
			echo "<td align='right'>".$totalRS."</td>\n";
			echo "<td align='right'>".$totalGS."</td>\n";
			echo "<td align='right'>".$total."</td>\n";
			echo "</tr>\n";
			
			echo "</table>\n";
			echo "<br />\n";
		}
	}
	
} // END Class ESV
?>