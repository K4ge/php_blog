<?php 

include('./lib/init.php');

$art_id = $_GET['art_id'];
$sql = "select pic,title,content,pubtime,catname,comm from art left join cat on art.cat_id=cat.cat_id where art_id=".$art_id;
$art = mGetRow($sql);

if(empty($art)) {
	header('Location:index.php');
	exit();
}

$sql = 'select * from cat';
$cat = mGetAll($sql);

//如果post非空 则由评论
if(!empty($_POST)) {
	$comm = array();
	$comm['art_id'] = $art_id;
	$comm['nick'] = $_POST['nick'];
	$comm['email'] = $_POST['email'];
	$comm['content'] = $_POST['content'];
	$comm['pubtime'] = time();
	$comm['ip'] = sprintf('%u', ip2long(getRealIp()));
	//插入的评论返回结果 如果返回false 则发布评论失败
	$rs = mExec('comment', $comm);
	//评论发布成功 将art表的comm+1
	$sql = "update art set comm=comm+1 where art_id=$art_id";
	mQuery($sql);

	//跳转到上一页
	$ref = $_SERVER['HTTP_REFERER'];
	header("Location: $ref");
}

//取出所有评论
$sql = "select * from comment where art_id=$art_id";
$comms = mGetAll($sql);

include(ROOT . '/view/front/art.html');

?>