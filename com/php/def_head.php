<?php
if (! isset($rootpath)) $rootpath = "www.welkynd.com/";
if (substr($rootpath, -1) != "/") $rootpath = $rootpath . "/";

if (! isset($compath)) $compath = $rootpath."com/";
if (substr($compath, -1) != "/") $compath = $compath . "/";
if (! isset($respath)) $respath = $rootpath."res/";
if (substr($respath, -1) != "/") $respath = $respath . "/";

//include $compath.'php/mysql_util.php';
include_once $compath.'php/errors.php';
include_once $compath.'php/mysql.php';
include_once $compath.'php/users.php';
include_once $compath.'php/perms.php';
include_once $compath.'php/util.php';
include_once $compath.'php/parsedown.php';

MYSQL::init();
session_start();

$loggedin = false;
$welcometext = '<a class="logintrigger">Login</a> or <a class="logintrigger">Sign up</a>';

if(!empty($_SESSION['loggedin']) && !empty($_SESSION['user'])) {
	$loggedin = true;
	$welcometext = 'Welcome '.$_SESSION['user']->username.' <a onclick="logout(\'./\');">Log out</a>';
} else unset($_SESSION['loggedin']);

if (isset($_POST['clear_session']) && $_POST['clear_session'] == 'clear') {
	session_unset();
	session_destroy();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Welkynd Guild</title>
	<link rel="icon" href="<?= $respath?>pic/welkyndstone.ico"/>
	<link rel="stylesheet" type="text/css" href="<?= $compath?>css/main.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $compath?>css/viewer.css"/>
<?php
if (isset($css_files) && is_array($css_files)) {
	foreach ($css_files as $sheet)
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$sheet\"/>";
}
?>
	<script type="text/javascript" src="<?= $compath?>js/jquery.js"></script>
	<script type="text/javascript" src="<?= $compath?>js/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?= $compath?>js/loggingforms.js"></script>
	<script type="text/javascript" src="<?= $compath?>js/bpulse.js"></script>
	<script type="text/javascript" src="<?= $compath?>js/viewer.js"></script>
	<script type="text/javascript" src="<?= $compath?>js/select_section.js"></script>
<?php
if (is_array($javascripts)) {
	foreach ($javascripts as $script)
		echo "\t<script type=\"text/javascript\" src=\"$script\"></script>";
}
?>
</head>
<body>
	<div id="content">
		<div id="header">
			<span><?= $welcometext?></span>
		</div>
		<div id="title"></div>
		<div id="main">
			<nav id="options">
				<tr>
					<a href="<?= $rootpath?>">Home</a>
					<a>Guild Roster</a>
					<a href="<?= $rootpath?>?sect=gallery">Gallery</a>
					<a>Theater</a>
					<?php if(!$loggedin){ ?><a class="logintrigger">Join the Guild</a><?php };?>
				</tr>
			</nav>
			<table id="panels"><tr>