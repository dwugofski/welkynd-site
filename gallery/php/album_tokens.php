<?php

include_once '../../com/php/mysql.php';
include_once "images.php";

MYSQL::init();

$response = [];
$response['error'] = false;

$albums = Images::load_albums();

$substr = $_GET['val'];
$matches = [];

foreach ($albums as $album) {
	if (stripos($album, $substr) === 0) $matches[] = $album;
}
$response['matches'] = $matches;
$response['id'] = $_GET['id'];

echo json_encode($response);

?>