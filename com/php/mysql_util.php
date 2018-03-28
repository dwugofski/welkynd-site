<?php

include 'util.php';

$mysql_guest_conn = new mysqli("127.0.0.1", "welkynd_guest", "stone", "welkynd");
$mysql_admin_conn = new mysqli("127.0.0.1", "welkynd_admin", "stone", "welkynd");

function general_query($sql, $username, $password) {
	$response = ['error'=>FALSE, 'result'=>FALSE];

	/*$conn = new mysqli("localhost", $username, $password, "welkynd");
	if ($conn->connect_error) {
		$reponse['error'] = "Connection failed: ". $conn->connect_error;
		return $response;
	}

	$response['result'] = $conn->query($sql);*/
	global $mysql_admin_conn;
	$response['result'] = $mysql_admin_conn->query($sql);
	if ($mysql_admin_conn->error == TRUE) $response['error'] = "Error running query <<".$sql.">>: ".$mysql_admin_conn->error;

	return $response;
}

function guest_query($sql) {
	return general_query($sql, "welkynd_guest", "stone");
}

function admin_query($sql, $password) {
	return general_query($sql, "welkynd_admin", $password);
}

function query_rows($sql, $repopts='html') {
	$rows = [];
	$sql_response = guest_query($sql);
	if ($sql_response['error'] == TRUE) manage_error($sql_response, $repopts);
	else while ($row = $sql_response['result']->fetch_assoc())
		$rows[] = $row;

	return $rows;
}

?>