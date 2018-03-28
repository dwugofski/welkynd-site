<?php

function manage_error($reponse, $repopts='html') {
	$func = 'manage_error_'.$repopts;
	if ($reponse['error'] == TRUE) $func($reponse['error']);
	else $func("manage_error detected no error");
}

function manage_error_html($msg) {
	die("<!-- ".htmlspecialchars($msg)." -->");
}

function manage_error_json($msg) {
	die(json_encode($msg));
}

function manage_error_php($msg) {
	error_log($msg, 0);
	die();
}

function date_time_to_string($date) {
	$date = new DateTime($date);
	return $date->format('j M, Y g:i a');
}

?>