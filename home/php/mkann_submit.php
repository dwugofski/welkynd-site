<?php

include_once '../../com/php/errors.php';
include_once '../../com/php/users.php';
include_once '../../com/php/perms.php';
include_once 'announcements.php';

session_start();
MYSQL::init();

if (!isset($_SESSION['user']) || !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
	Errors::echo_json('Must be logged in to make announcements');
}

if (!Permissions::check_user_permission($_SESSION['user'], 'make announcements')) {
	Errors::log(Errors::PERMISSIONS_ERROR, 'User attempted to make announcement without sufficient permissions.');
	Errors::echo_json('You do not have sufficient permissions to access this.');
}

echo json_encode(Announcements::make_announcement($_POST['title'], $_SESSION['user']->username, $_POST['message']));

?>