<?php 
include('./lib/init.php');

if(!acc()) {
	header('Location: login.php');
}

$sql = "select art_id,art.cat_id,title,pubtime,comm,catname from art left join cat on art.cat_id=cat.cat_id";
$arts = mGetAll($sql);

include('./view/admin/artlist.html');




?>