<?php 
error_reporting(0);
include('./lib/init.php');

$cat_id = $_GET['cat_id'];

//判断id是否为数字
if(!is_numeric($cat_id)) {
	error('id必须为数字');
	exit();
}

//判断栏目是否存在
$sql = "select count(*) from cat where cat_id = $cat_id";
if(mGetOne($sql) == 0) {
	error('栏目不存在');
	exit();
}

if(empty($_POST)) {
	$sql = "select catname from cat where cat_id=$cat_id";
	$cat['catname'] = mGetRow($sql)['catname'];
	include(ROOT . '/view/admin/catedit.html');
} else {
	$cat['catname'] = trim($_POST['catname']);
	if(mExec('cat',$cat,'update',"cat_id=$cat_id")) {
		succ('修改成功');
	} else {
		error('修改失败');
	}
}
?>