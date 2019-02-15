<?php 
error_reporting(0);
include('./lib/init.php');
//cat_id
$cat_id = $_GET['cat_id'];


//检测id是否为数字
if(!is_numeric($cat_id)) {
	error('id必须为数字');
	exit();
}

//检测栏目是否存在
$sql = "select count(*) from cat where cat_id=$cat_id";
if(mGetOne($sql) == 0) {
	error('栏目不存在');
	exit();
}

//检测栏目下是否有文章
$sql = "select count(*) from art where cat_id=$cat_id";
if(mGetOne($sql) != 0) {
	error('栏目下有文章，不能删除');
	exit();
}

//删除文章
$sql = "delete from cat where cat_id=$cat_id";
if(mQuery($sql)) {
	succ('栏目删除成功');
} else {
	error('栏目删除失败');
}
?>