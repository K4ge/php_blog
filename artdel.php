<?php 

include('./lib/init.php');

$art_id = $_GET['art_id'];

//判断id是否为数字
if(!is_numeric($art_id)) {
	error('id必须为数字');
} 

//判断是否有这篇文章
$sql = "select count(*) from art where art_id=$art_id";
if(mGetOne($sql) == 0) {
	error('没有这篇文章');
}

//删除文章
$sql = "delete from art where art_id=$art_id";
if(mQuery($sql)) {
	succ('删除成功');
} else {
	error('删除失败');
}

?>