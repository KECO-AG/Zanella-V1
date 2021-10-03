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

switch ($_GET['action'])
{
	case 'neu':
		break;
	
	case 'delete':
		break;
		
	case 'update':
		break;
		
	default:
		break;
}