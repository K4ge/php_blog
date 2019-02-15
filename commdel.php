<?php 

include('./lib/init.php');

$comment_id = $_GET['comment_id'];

if(!is_numeric($comment_id)) {
	error('id必须为数字');
}

// 获取art_id
$sql = "select art_id from comment where comment_id=$comment_id";
$art_id = mGetOne($sql);

//更改评论数
$sql = "update art set comm=comm-1 where art_id=$art_id";
if(!mQuery($sql)) {
	error('系统故障');
}

$sql = "delete from comment where comment_id=$comment_id";

if(mQuery($sql)) {
	succ('评论删除成功');
} else {
	error('评论删除失败');
}



?>