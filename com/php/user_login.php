<?php

include_once 'errors.php';
include_once 'mysql.php';
include_once 'users.php';

session_start();

MYSQL::init();

if (!isset($_POST['username']) || !isset($_POST['password'])) {
	Errors::echo_json('Username or password not set');
}

$username = $_POST['username'];
$password = $_POST['password'];

$user_resp = User::login_user($username, $password, FALSE);
if (isset($user_resp['error'])) {
	Errors::echo_json($user_resp['msg']);
}

$_SESSION['user'] = $user_resp['user'];
$_SESSION['loggedin'] = TRUE;

echo json_encode(['success' => TRUE]), PHP_EOL;

?>