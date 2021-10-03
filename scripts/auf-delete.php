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

// input parameter is element to delete (suppress errors by adding a @ sign)
$p = @$_REQUEST['p'];

// explode input parameteres: id, row and column
list($sub_id, $row, $col) = explode('_', $p);

// if row and col are numeric then delete from database (but only one row - limit 1)
if (is_numeric($row) && is_numeric($col)) {
	// delete element from database (only one row)
	$sql	=	"DELETE from auf_items WHERE id='".$sub_id."' limit 1";
	$DB->query($sql);
}

// no cache
header('Pragma: no-cache');
// HTTP/1.1
header('Cache-Control: no-cache, must-revalidate');
// date in the past
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
?>