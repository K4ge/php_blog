<?php 
include('./lib/init.php');

$sql = "select * from comment";
$comms = mGetAll($sql);

include(ROOT . "/view/admin/commlist.html");


?>