<?php

session_start();

class Announcements{
	const MAX_ANNOUNCEMENTS = 5;

	static public function make_announcement($title, $username, $message){
		if (empty($title)) return Errors::log(Errors::INVALID_FORMAT, 'Announcement must have a title.');
		if (empty($message)) return Errors::log(Errors::INVALID_FORMAT, 'Announcement must have a message.');
		if (empty($username)) return Errors::log(Errors::INVALID_FORMAT, 'User must be logged in to make message.');

		$sql = "INSERT INTO announcements (title, username, message) VALUES (?, ?, ?)";
		$id = MYSQL::insert($sql, 'sss', [htmlspecialchars($title), $username, htmlspecialchars($message)]);

		$sql = "SELECT * FROM announcements WHERE id = ?";
		$announcement = MYSQL::insert($sql, 'i', [$id]);
		if(count($announcement) < 1) return Errors::log(Errors::MYSQL_ERROR, 'Failed to insert announcement.');
		return $announcement;
	}

	static public function recent_announcements($count, $start='') {
		if (empty($start)) {
			$sql = "SELECT * FROM announcements ORDER BY date DESC limit ?";
			return MYSQL::request_info($sql, 'i', [$count]);
		} else {
			$sql = "SELECT * FROM announcements ORDER BY date DESC limit ?, ?";
			return MYSQL::request_info($sql, 'ii', [$start, $count]);
		}
	}

	static public function get_count() {
		$sql = "SELECT COUNT(1) FROM announcements";
		return MYSQL::request_info($sql)[0]['COUNT(1)'];
	}

	static public function paginate_announcements($start_page) {
		$N = self::get_count();

		if ($N == 0) return Errors::log(Errors::RUNTIME_ERROR, 'No announcements found');

		if (empty($start_page) || !is_numeric($start_page) || floor($start_page) != $start_page) $start_page = 0;

		$goback = true;
		$goforward = true;
		if ($start_page <= 0) $goforward = false;
		if (($start_page + 1) * self::MAX_ANNOUNCEMENTS >= $N) $goback = false;

		$start_ann = $start_page * self::MAX_ANNOUNCEMENTS;

		$res = array();
		$res['announcements'] = self::recent_announcements(self::MAX_ANNOUNCEMENTS, $start_ann);
		$res['goback'] = $goback;
		$res['goforward'] = $goforward;
		return $res;
	}

	static public function render_ann($ann, $tab_pre){
		$html = "";
		if ($ann['title'] == 'NULL') $ann['title'] = 'Announcement';

		$paragraphs = explode("\n", $ann['message']);
		$message_html = '';

		$parsedown = new Parsedown();

		$message_html .= $tab_pre."\t<div class=\"ann_msg\">" . PHP_EOL;
		foreach ($paragraphs as $paragraph) {
			$message_html .= $tab_pre.$parsedown->text($paragraph) . PHP_EOL;
		}
		$message_html .= $tab_pre."\t</div>" . PHP_EOL;

		$html .= $tab_pre."<div class=\"announcetag\">" . PHP_EOL;
		if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'delete announcements')) {
			$html .= $tab_pre."\t<div class=\"ann_del\"></div>";
		}
		if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'make announcements')) {
			$html .= $tab_pre."\t<div class=\"ann_edit\"></div>";
		}
		$html .= $tab_pre."\t<h1>".$ann['title']."</h1>" . PHP_EOL;
		$html .= $tab_pre."\t<h2>".$ann['username']." - ".date_time_to_string($ann['date'])."</h2>" . PHP_EOL;
		$html .= $tab_pre.$message_html;
		$html .= $tab_pre."</div>" . PHP_EOL;

		return $html;
	}
}

function GENERATE_ANNOUNCMENTS_TABLE(){
	$sql = "CREATE TABLE IF NOT EXISTS announcements (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT, 
	title VARCHAR(255) DEFAULT NULL, 
	username VARCHAR(75) NOT NULL, 
	date DATETIME DEFAULT CURRENT_TIMESTAMP, 
	message TEXT NOT NULL, 
	PRIMARY KEY (id)
	) ENGINE=InnoDB;";
	MYSQL::create($sql);
}


?>