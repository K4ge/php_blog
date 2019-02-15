<?php 

include('./lib/init.php');

//id检验
$art_id = $_GET['art_id'];
if(!is_numeric($art_id)) {
	error('id必须为数字');
}

//取出栏目
$sql = "select * from cat";
$cats = mGetAll($sql);

//取出文章数据
$sql = "select arttag,title,content,art_id,cat_id from art where art_id=$art_id";
$art = mGetRow($sql);

if(!$_POST) {
	include('./view/admin/artedit.html');
} else {
	// 检测标题是否为空
	$art['title'] = trim($_POST['title']);
	if(empty($art['title'])) {
		error('标题不能为空');
	}

	//检测栏目是否合法
	$art['cat_id'] = $_POST['cat_id'];
	if(!is_numeric($art['cat_id'])) {
		error('栏目不合法');
	}

	//检测内容是否为空
	$art['content'] = trim($_POST['content']);
	if(empty($art['content'])) {
		error('内容不能为空');
	}

	//插入发布时间
	$art['pubtime'] = time();

	//收集tag
	$art['arttag'] = trim($_POST['tag']);

	//插入内容到art表
	if(!mExec('art', $art, 'update', "art_id=$art_id")) {
		error('文章修改失败');
	} else {
		//判断是否有tag
		$art['tag'] = trim($_POST['tag']);
		if(empty($_POST[tag])){
			succ('文章修改成功');
		} else {
			//删除tag表中的所有tag
			$sql = "delete from tag where art_id=$art_id";
			if(!mQuery($sql)) {
				error('系统故障');
			}
			//insert into tag (art_id,tag) values (5,'linux'),(5,'mysql')
			$sql = "insert into tag (art_id,tag) values ";
			$tag = explode(',', $art['tag']);
			foreach ($tag as $v) {
				$sql .= "(" . $art_id . ",'$v'),";
			}
			$sql = rtrim($sql, ',');

			if(mQuery($sql)) {
				succ('文章修改成功');
			} else {
				error('标签修改失败');
			}
		}
	}

}





?>