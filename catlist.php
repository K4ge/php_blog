<?php 
error_reporting(0);
include('./lib/init.php');

//取出数据到数组中
$sql = "select * from cat";
$cat = mGetAll($sql);


include(ROOT . '/view/admin/catlist.html');


?>