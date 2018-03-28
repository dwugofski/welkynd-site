<?php

include_once '../../com/php/errors.php';
include_once '../../com/php/mysql.php';
include_once '../../com/php/perms.php';
include_once 'images.php';

MYSQL::init();

$subbed = ($_POST['subbed'] == 'true');

$resp = '';
if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'delete images')){
	if ($subbed){
		$form = $_POST['form'];
		$count = $form['count'];
		$out = [];
		for ($i=0; $i<$count; $i+=1){
			$pic = [];
			$id = $form[$i."_id"];
			$pic['id'] = $id;
			$pic['name'] = $form["img_".$id."_name"];
			$pic['desc'] = $form["img_".$id."_desc"];
			$name = $pic['name'];
			$desc = $pic['desc'];
			Images::edit_image($id, $name, $desc);
		}

		$resp = json_encode($out);
	} else {
		$pic_ids = $_POST['ids'];
		$count = 0;
		$album = '';
		foreach ($pic_ids as $i => $id) {
			$count += 1;
			$pic = Images::get_image($id);
			$album = $pic['album'];
			$resp .= Images::img_item($pic['name'], $pic['image'], $pic['description'], $pic['id'], $i);
		}
		if ($count > 0) {
			$resp .= "<input name=\"count\" type=\"hidden\" value=\"$count\" \\>";
			$resp .= "<input name=\"album\" type=\"hidden\" value=\"".urlencode($album)."\" \\>";
		}
	}
} else {
	$resp = 'You do not have sufficient permission to access this feature.';
}

echo $resp;

?>