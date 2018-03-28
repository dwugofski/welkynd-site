<?php

include_once "../../com/php/errors.php";
include_once "../../com/php/mysql.php";
include_once "../../com/php/util.php";
include_once "../../com/php/perms.php";
include_once "../../com/php/parsedown.php";
include_once "announcements.php";

MYSQL::init();

$response = Announcements::paginate_announcements($_POST['start']);
if(isset($response['error'])) Errors::echo_json($response['msg']);

$response['html'] = '';
foreach ($response['announcements'] as $ann) {
	$response['id'] = $ann['id'];
	$response['html'] .= Announcements::render_ann($ann, $_POST['tab_pre']);
}

echo json_encode($response);

/*$dt_start = DateTime::createFromFormat('U.u', microtime(true));

$response = [];
$response['error'] = false;

$MAX_ANNS = 2;

$sql = "
	SELECT COUNT(1)
	FROM announcements";

$n_anns = query_rows($sql, 'json');
if (empty($n_anns)) {
	$response['error'] = 'Error trying to find count';
	manage_error_json($response);
}

$n_anns = $n_anns[0]['COUNT(1)'];
if ($n_anns == 0) {
	$response['error'] = 'No announcements found';
	manage_error_json($response);
}

$start_page = 0;
if (!empty($_POST['start']) && is_numeric($_POST['start']) && floor($_POST['start']) == $_POST['start']) $start_page = $_POST['start'];

$goback = true;
$goforward = true;
if ($start_page <= 0) $goback = false;
if (($start_page + 1) * $MAX_ANNS >= $n_anns) $goforward = false;

$response['goback'] = $goback;
$response['goforward'] = $goforward;

$start_ann = $start_page * $MAX_ANNS;

$sql = "
	SELECT *
	FROM announcements
	ORDER BY date DESC
	LIMIT $start_ann, $MAX_ANNS";

$announcements = query_rows($sql, 'json');
$tab_pre = $_POST['tab_pre'];

$response['html'] = '';
foreach ($announcements as $ann) {
	$response['id'] = $ann['id'];
	$response['html'] .= render_ann($ann, $tab_pre);
}

$response['sql'] = $sql;

echo json_encode($response);*/

?>