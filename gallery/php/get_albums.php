
<?php

include_once '../../com/php/errors.php';
include_once '../../com/php/mysql.php';
include_once 'images.php';

MYSQL::init();
include_once 'images.php';

$img_albums = Images::load_albums();
$albums = [];
foreach ($img_albums as $album) {
	$albums[] = ['name' => $album, 'sname' => urlencode($album)];
}

echo json_encode($albums);

?>