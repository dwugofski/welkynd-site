<?php

include_once '../../com/php/errors.php';
include_once '../../com/php/mysql.php';
include_once '../../com/php/perms.php';
include_once 'images.php';

session_start();
MYSQL::init();

if (!isset($_SESSION['user']) || !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
	Errors::echo_json('Must be logged in to make images');
}

if (!Permissions::check_user_permission($_SESSION['user'], 'post images')) {
	Errors::log(Errors::PERMISSIONS_ERROR, 'User attempted to post images without sufficient permissions.');
	Errors::echo_json('You do not have sufficient permissions to do this.');
}

$response['error'] = FALSE;
$response['html'] = '';
$response['ids'] = [];

$count = $_POST['upl_cont_count'];
if ($count <= 0) {
	Errors::echo_json('No items uploaded');
}

$album = rtrim($_POST['album']);
if (empty($album) == TRUE){
	Errors::echo_json('No album given');
}
$response['html'] .= '<h2>Additions to '.$album.'</h2>';

$conn = new mysqli("localhost", 'welkynd_guest', 'stone', "welkynd");
if ($conn->connect_error) {
	$reponse['error'] = "Connection failed: ". $conn->connect_error;
	manage_error($response, 'json');
}
for ($i=0; $i<$count; $i+=1){
	$name = $_POST['upl_cont_'.$i.'_name'];
	$img_id = Images::create_image($name, $_POST['user'], $album);
	$response['ids'][] = $img_id;
	$response['html'] .= Images::img_item("", $name, '', $img_id, $i);
}

$response['html'] .= "<input name=\"count\" type=\"hidden\" value=\"$count\" \\>";
$response['html'] .= "<input name=\"album\" type=\"hidden\" value=\"".urlencode($album)."\" \\>";

echo json_encode($response);

?>
