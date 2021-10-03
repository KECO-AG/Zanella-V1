<?php
/*
   * TODO:
   *
   *
   *
*/

require_once '../common.php';
// Login check
if($LOGIN->checkLoginStatus()==FALSE){(header("Location: login.php"));};

//User lvl check muss in jedem case-statement eingebaut werden.
//if($_SESSION['level'] >=2){(header("Location: index.php"));}

// Berechnung des Montags der Woche
$heute			=	new DateTime();
$startday		=	clone $heute;

if ($heute->format('D') == 'Sun') {
	$modify 	=	6;
}
else {
	$modify		=	$startday->format('w') - 1;
}

$startday		=	$startday->modify('-'.$modify.' day');
if(isset($_GET['wback'])) {
	$days = $_GET['wback']*7;
	$startday = $startday->modify('-'.$days.' day');
}
// input parameter is element to delete (suppress errors by adding a @ sign)
$p = @$_REQUEST['p'];

// explode input parameters:
// 0 - $sub_id - subject id
// 1 - $tbl1   - target table index
// 2 - $row1   - target row
// 3 - $col1   - target column
// 4 - $tbl0   - source table index
// 5 - $row0   - source row
// 6 - $col0   - source column
list($sub_id, $tbl1, $row1, $col1, $tbl0, $row0, $col0) = explode('_', $p);

if ($tbl1 == 1)
{
	$date_calc		=	((($row1+1)/3)-1)*7+$col1;
	$datum	=	$startday->modify('+'.$date_calc.' day')->format('Y-m-d');

	$werte = $p;
	$sql = "UPDATE auf_items SET auf_items.datum = '".$datum."' WHERE auf_items.id = '".$sub_id."'";
	$DB->query($sql);
}
if ($tbl1 == 0 && $col1 == 2)
{
	$werte = $p;
	$timestamp = new DateTime();
	$sql = "UPDATE auf_items SET auf_items.erledigt = '".$timestamp->format('Y-m-d G:i:s')."' WHERE auf_items.id = '".$sub_id."'";
	$DB->query($sql);
}


// no cache
header('Pragma: no-cache');
// HTTP/1.1
header('Cache-Control: no-cache, must-revalidate');
// date in the past
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
?>