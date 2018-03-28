
<?php

include "../../com/php/mysql.php";
include "images.php";

MYSQL::init();

echo json_encode(Images::load_from_album($_POST['album']));

?>