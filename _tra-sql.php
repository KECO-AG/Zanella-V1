<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

require_once 'common.php';

$sql	=	"SELECT * FROM tra_items";
$result 	= $DB->query($sql);

foreach ($result as $item) {
	$sqlitem	=	" UPDATE tra_items SET auftrag = '".$item['kunde']." - ".$item['auftragnr']."' WHERE id = '".$item['id']."'";
	$DB->query($sqlitem);
}
?>