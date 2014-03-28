<?php
$vDir='';
require './inc/functions.php';
$page = '';
$data='';
/*
if(!isset($_GET['r']))     
{     
echo "<script language=\"JavaScript\">     
<!--      
document.location=\"$PHP_SELF?r=1&width=\"+screen.width+\"&Height=\"+screen.height;     
//-->     
</script>";     
}     
else {         

// Code to be displayed if resolutoin is detected     
     if(isset($_GET['width']) && isset($_GET['Height'])) {     
               // Resolution  detected     
     }     
     else {     
               // Resolution not detected     
     }     
}     
*/

if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'operas':
			$page = 'operas';
			break;
		case 'composers':
			$page = 'composers';
			break;
		case 'add_opera':
			$page = 'add_opera';
			break;
		default:
			$page = 'operas';
			break;
	}
} else {
	$page = 'operas';
}
require $logicDir.$page.'.php';
