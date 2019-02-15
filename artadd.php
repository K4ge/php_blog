<?php 
include('./lib/init.php');

if(empty($_POST)) {
	$sql = "select * from cat";
	$cats = mGetAll($sql);
	include(ROOT . '/view/admin/artadd.html');
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

	//插入图片
	if(!($_FILES['pic']['name']=='') && $_FILES['pic']['error']==0) {
		$des =  createDir().'/'.randStr().getExt($_FILES['pic']['name']);
		move_uploaded_file($_FILES['pic']['tmp_name'],ROOT.$des);
	}

	//插入图片信息
	$art['pic'] = $des;
	
	$art['thumb'] = makeThumb($des);
	//插入发布时间
	$art['pubtime'] = time();

	//收集tag
	$art['arttag'] = trim($_POST['tag']);

	//插入内容到art表
	if(!mExec('art', $art)) {
		error('文章发布失败');
	} else {
		//判断是否有tag
		$art['tag'] = trim($_POST['tag']);
		if(empty($_POST[tag])){
			//栏目表num加1
			$sql = "update cat set num=num+1 where cat_id=$art[cat_id]";
			mQuery($sql);
			succ('文章添加成功');
		} else {
			$art_id = getLastId();
			//insert into tag (art_id,tag) values (5,'linux'),(5,'mysql')
			$sql = "insert into tag (art_id,tag) values ";
			$tag = explode(',', $art['tag']);
			foreach ($tag as $v) {
				$sql .= "(" . $art_id . ",'$v'),";
			}
			$sql = rtrim($sql, ',');

			if(mQuery($sql)) {
				//栏目表num+1
				$sql = "update cat set num=num+1 where cat_id=$art[cat_id]";
				mQuery($sql);
				succ('文章添加成功');
			} else {
				//栏目表num-1
				$sql = "update cat set num=num-1 where cat_id=$art[cat_id]";
				mQuery($sql);
				error('标签添加失败');
			}
		}
	}
}



?>