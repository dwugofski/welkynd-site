
<?php

$sql = "SELECT * FROM images ORDER BY date DESC LIMIT 4";
$gallery_preview = MYSQL::request_info($sql);

?>