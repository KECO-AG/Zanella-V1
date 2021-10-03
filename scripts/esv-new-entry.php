<?php
require_once '../common.php';
// Login Check & User Level
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};
//if($_SESSION['level'] >=2){(header("Location: index.php"));}


if(empty($_POST['jobs']) || empty($_POST['holzart']) || empty($_POST['schnittart']) || empty($_POST['stk']) || empty($_POST['laenge']) || empty($_POST['dm']))
{

	(header("Location: ../esv_new_entry.php?message=2&jobID=".$_POST['jobs']."&holzart=".$_POST['holzart']."&schnittart=".$_POST['schnittart'].""));
}
else
{
	$jobID = $DB->escapeString($_POST['jobs']);
	$holzart = $DB->escapeString($_POST['holzart']);
	$schnittart = $DB->escapeString($_POST['schnittart']);
	$stk = $DB->escapeString($_POST['stk']);
	$laenge = $DB->escapeString($_POST['laenge']);
	$dm = $DB->escapeString($_POST['dm']);
	if (isset($_POST['rinde'])) {
		$rinde = $DB->escapeString($_POST['rinde']);
	} else $rinde = 0;

	// $rinde = $DB->escapeString($_POST['rinde']);
	$m3_total = ((pi()*pow(($dm/100)/2, 2)*$laenge)*$stk)*(1-$rinde/100);

	$sql = "INSERT INTO esv_schnitt (job_id,holz_id,schnittart,stk,laenge,dm,m3_total,rinde) VALUES ('".$jobID."','".$holzart."','".$schnittart."','".$stk."','".$laenge."','".$dm."','".$m3_total."','".$rinde."')";
	$DB->query($sql);
	//echo $DB->lastSQLQuery;
	//echo "<br />";
	$sql = "SELECT LAST_INSERT_ID()";
	$id = $DB->query($sql);
	$id = $id[0]['LAST_INSERT_ID()'];

	(header("Location: ../esv_new_entry.php?message=1&jobID=".$_POST['jobs']."&holzart=".$_POST['holzart']."&schnittart=".$_POST['schnittart']."&lastITEM=".$id.""));
}



// TESTINGS
/*
echo "<pre>";
print_r ($_POST);
echo "</pre>";

$stk = htmlentities($_POST['stk']);
$dm = htmlentities($_POST['dm']);
$laenge = htmlentities($_POST['laenge']);

$mm3 = (pi()*pow($dm/2, 2)*$laenge)*$stk;
$m3 = ((pi()*pow($dm/2, 2)*$laenge)/1000000000)*$stk;



echo "<br />";
echo "Radius : ".$_POST['dm']/2;
echo "<br />";
echo "Pi: ".pi();
echo "<br />";
echo "Total mm3: ".$mm3;
echo "<br />";
echo "Total m3: ".$m3;
echo "<br />";
echo "Gerundet: ".round($m3, 4);
echo "<br />";
echo "<a href=\"../esv_new_entry.php?jobID=".$_POST['jobs']."&holzart=".$_POST['holzart']."&schnittart=".$_POST['schnittart']."\">Test Link</a>";
*/
?>