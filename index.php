<?php

$rootpath = "./";
$compath = 'com/';
$respath = 'res/';

$sect = 'home';

if(!isset($_GET['sect'])) $_GET['sect'] = 'home';

switch ($_GET['sect']) {
	case 'home':
	case 'gallery':
		$sect = $_GET['sect'];
		break;
	default:
		$sect = 'home';
		break;
}

include $sect.'/'.$sect.'.php';

?>