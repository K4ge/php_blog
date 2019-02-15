<?php 

include('./lib/init.php');

//查询所有的栏目
$sql = "select * from cat";
$cats = mGetAll($sql);

//判断地址栏是否有cat_id
if(isset($_GET['cat_id'])) {
	$where = " art.cat_id=$_GET[cat_id]";
} else {
	$where = '1';
}

//查询文章数
$sql = "select count(*) from art where $where";
$num = mGetOne($sql);
$curr = isset($_GET['page']) ? $_GET['page'] : 1;
$page = getPage($num,3,$curr);


//查询所有文章
$sql = "select art_id,title,content,pubtime,comm,catname,thumb from art inner join cat on art.cat_id=cat.cat_id where $where limit " . ($curr-1) * 3 . "," . 3;
$arts = mGetAll($sql);


include(ROOT . '/view/front/index.html');

?>