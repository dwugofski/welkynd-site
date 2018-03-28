<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php

include_once '../com/php/mysql.php';
include_once '../home/php/announcements.php';

MYSQL::init();
GENERATE_ANNOUNCMENTS_TABLE();

?>

<h1>Hello World!</h1>

</body>
</html>