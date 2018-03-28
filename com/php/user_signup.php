<?php

include_once 'errors.php';
include_once 'mysql.php';
include_once 'users.php';

session_start();

MYSQL::init();

$user_resp = User::create_new_user($_POST['username'], $_POST['password'], $_POST['email'], FALSE);
if (isset($user_resp['error'])) {
	Errors::echo_json($user_resp['msg']);
}

$_SESSION['user'] = $user_resp['user'];
$_SESSION['loggedin'] = TRUE;
echo json_encode(['success' => TRUE]), PHP_EOL;

?>