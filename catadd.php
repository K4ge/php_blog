<?php 

// error_reporting(0);
include('./lib/init.php');
if(empty($_POST)) {
	include(ROOT . '/view/admin/catadd.html');
} else {

	$cat['catname'] = trim($_POST['catname']);
	//判断栏目名是否为空
	if(empty($cat['catname'])) {
		error('栏目名为空');
		exit();
	}

	//判断栏目是否已存在
	$sql = "select count(*) from cat where catname='$cat[catname]'";
	if(mGetOne($sql)!=0){
		error('栏目已经存在');
		exit();
	}

	//插入栏目
	if(mExec('cat',$cat)) {
		succ('插入成功');
	} else {
		error('插入失败');
	}

}



?>