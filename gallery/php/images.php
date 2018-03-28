<?php

include_once "../../com/php/mysql.php";
include_once "../../com/php/errors.php";

session_start();

class Images {
	static public function load_from_album($album_name) {
		$sql ="SELECT * FROM images WHERE album = ? ORDER BY date DESC";
		$images = MYSQL::request_info($sql, 's', [$album_name]);
		if (count($images) < 1) Errors::log(Errors::IMAGES_ERROR, "No entries found for album of name '".$album_name."'");
		return $images;
	}

	static public function load_albums() {
		$sql ="SELECT DISTINCT album FROM images ORDER BY album ASC";
		$sql_albums = MYSQL::request_info($sql);
		$albums = [];
		foreach ($sql_albums as $album) {
			$albums[] = $album['album'];
		}
		return $albums;
	}

	static public function create_image($image, $user, $album) {
		$sql ="INSERT INTO images (image, user, album) VALUES (?, ?, ?)";
		return MYSQL::insert($sql, 'sss', [$image, $user, $album]);
	}

	static public function edit_image($id, $name, $desc) {
		$sql = "UPDATE images SET name = ?, description = ? WHERE id = ?";
		MYSQL::update($sql, 'ssi', [$name, $desc, $id]);
	}

	static public function get_image($id) {
		$sql = "SELECT * FROM images WHERE id = ?";
		$images = MYSQL::request_info($sql, 'i', [$id]);
		if (count($images) < 1) Errors::log(Errors::IMAGES_ERROR, "No entries found for image of id '".$id."'");
		return $images[0];
	}

	static public function delete_images($ids) {
		$sql = "DELETE FROM images WHERE id = ?";
		$resp = MYSQL::delete_array($sql, 'i', $ids);
		return $resp;
	}

	static public function delete_albums($albums) {
		$sql = "DELETE FROM images WHERE album = ?";
		$resp = MYSQL::delete_array($sql, 's', $albums);
		return $resp;
	}

	static public function img_item($name, $image, $description, $id, $i) {
		$dom = new DOMDocument('1.0', 'utf-8');

		$item = $dom->createElement('div');
		$item->setAttribute('class', 'img_item');

		$img_holder = $dom->createElement('div');
		$img_holder->setAttribute('class', 'img_holder');
		$img = $dom->createElement('img');
		$img->setAttribute('id', 'img_'.$id.'_img');
		$img->setAttribute('src', 'gallery/php/uploads/'.$image);
		$img_holder->appendChild($img);

		$inp_holder = $dom->createElement('div');
		$inp_holder->setAttribute('class', 'inp_holder');
		$inp_id = $dom->createElement('input');
		$inp_id->setAttribute('name', $i.'_id');
		$inp_id->setAttribute('type', 'hidden');
		$inp_id->setAttribute('value', $id);
		$inp = $dom->createElement('input');
		$inp->setAttribute('name', 'img_'.$id.'_name');
		$inp->setAttribute('type', 'text');
		$inp->setAttribute('placeholder', 'Image name');
		$inp->setAttribute('class', 'spacer');
		$inp->setAttribute('maxlength', '100');
		$inp->setAttribute('autocomplete', 'off');
		$inp->setAttribute('value', $name);
		$desc = $dom->createElement('textarea');
		$desc->setAttribute('name', 'img_'.$id.'_desc');
		$desc->setAttribute('placeholder', 'Image description');
		$desc->setAttribute('rows', 10);
		$desc->setAttribute('cols', 30);
		$desc->setAttribute('maxlength', 1000);
		$desc->appendChild($dom->createTextNode(''.$description));
		$inp_holder->appendChild($inp_id);
		$inp_holder->appendChild($inp);
		$inp_holder->appendChild($desc);

		$clearer = $dom->createElement('div');
		$clearer->setAttribute('class', 'clearer');

		$item->appendChild($img_holder);
		$item->appendChild($inp_holder);
		$item->appendChild($clearer);

		$dom->appendChild($item);

		return $dom->saveHTML($item);
	}
}

function GENERATE_IMAGES_TABLE() {
	$sql = "CREATE TABLE images (
		id INT UNSIGNED NOT NULL AUTO_INCREMENT, 
		image VARCHAR(100) CHARACTER SET 'utf8' COLLATE utf8_general_ci NOT NULL, 
		user VARCHAR(75) CHARACTER SET 'utf8' COLLATE utf8_general_ci NOT NULL, 
		name VARCHAR(255) CHARACTER SET 'utf8' COLLATE utf8_general_ci NOT NULL, 
		date DATETIME DEFAULT CURRENT_TIMESTAMP, 
		album VARCHAR(50) CHARACTER SET 'utf8' COLLATE utf8_general_ci DEFAULT NULL, 
		description TEXT CHARACTER SET 'utf8' COLLATE utf8_general_ci, 
		PRIMARY KEY (id),
		INDEX album (album))
		ENGINE = InnoDB;";
	MYSQL::create($sql);
}


?>