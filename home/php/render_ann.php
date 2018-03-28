<?php

function render_ann($ann, $tab_pre){
	$html = "";
	if ($ann['title'] == 'NULL') $ann['title'] = 'Announcement';

	$paragraphs = explode("\n", $ann['message']);
	$message_html = '';

	foreach ($paragraphs as $paragraph) {
		$message_html .= $tab_pre."\t<p>" . PHP_EOL;
		$message_html .= $tab_pre."\t\t".$paragraph . PHP_EOL;
		$message_html .= $tab_pre."\t</p>" . PHP_EOL;
	}

	$html .= $tab_pre."<div class=\"announcetag\">" . PHP_EOL;
	$html .= $tab_pre."\t<h1>".$ann['title']."</h1>" . PHP_EOL;
	$html .= $tab_pre."\t<h2>".$ann['username']." - ".date_time_to_string($ann['date'])."</h2>" . PHP_EOL;
	$html .= $tab_pre.$message_html;
	$html .= $tab_pre."</div>" . PHP_EOL;

	return $html;
}

?>