<?php

include_once '../../com/php/errors.php';
include_once '../../com/php/mysql.php';
include_once '../../com/php/perms.php';
include_once 'images.php';

session_start();
MYSQL::init();

if (!isset($_SESSION['user']) || !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
	Errors::echo_json('Must be logged in to delete images');
}

if (!Permissions::check_user_permission($_SESSION['user'], 'delete images')) {
	Errors::log(Errors::PERMISSIONS_ERROR, 'User attempted to delete images without sufficient permissions.');
	Errors::echo_json('You do not have sufficient permissions to do this.');
}

$response['error'] = FALSE;
$response['html'] = '';

$ids = [];
if (array_key_exists('ids', $_POST)) $ids = $_POST['ids'];
$albums = [];
if (array_key_exists('albums', $_POST)) $albums = $_POST['albums'];

if (is_array($albums) && !empty($albums)) Images::delete_albums($albums);

if (is_array($ids) && !empty($ids)) Images::delete_images($ids);


?>