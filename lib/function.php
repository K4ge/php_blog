<?php 
include('./init.php');
/**
* 成功的提示信息
*
*
*/
function succ($res="成功") {
	$result = 'succ';
	require(ROOT . '/view/admin/info.html');
	exit();
}

/**
* 失败返回的报错信息
*
*
*/

function error($res) {
	$result = 'fail';
	require(ROOT . '/view/admin/info.html');
	exit();
}

/**
* 获取来访者的真实IP
*
*/
function getRealIp() {
	static $realip = null;
	if($realip !== null) {
		return $realip;
	}

	if(getenv('REMOTE_ADDR')) {
		$realip = getenv('REMOTE_ADDR');
	} else if(getenv('HTTP_CLIENT_IP')) {
		$realip = getenv('HTTP_CLIENT_IP');
	} else if(getenv('HTTP_X_FORWARD_FOR')) {
		$realip = getenv('HTTP_X_FORWARD_FOR');
	}

	return $realip;
}

/**
* 获取分页代码
* @param int $num 总文章数
* @param int $cnt 每页显示文章数
* @param int $curr 当前显示页码数
* @return arr $pages 返回一个页码数=>地址栏值的关联数组
*/

function getPage($num,$cnt,$curr) {
	//计算最大页码数
	$max = ceil($num/$cnt);
	//计算最左面的页码数
	$left = max(1, $curr - 2);
	//计算最右侧的页码数
	$right = min($left+4 , $max);
	//再次计算最左侧页码数
	$left = max(1, $right-4);

	//将获取的5个页码数，放进数组里
	for($i=$left;$i<=$right;$i++) {
		$_GET['page'] = $i;
		$page[$i] = http_build_query($_GET);
	}

	return $page;
}

/**
* 生成随机字符串
* @param int $num 生成的随机字符串的个数
* @return str 生成的随机字符串
*/
function randStr($num=6) {
	$str = str_shuffle('abcdefjhmnpqstuvwxyzABCDEFJHMNPQSTUVWXYZ23456789');
	return substr($str, 0, $num);
}

/**
* 创建目录 ROOT . '/upload/2015/01/25/qwefasd.jpg'
*
*/
function createDir() {
	$path = '/upload/' . date('Y/m/d');
	$fpath = ROOT . $path;
	if(is_dir($fpath) || mkdir($fpath, 0777, true)) {
		return $path;
	} else {
		return false;
	}
}

/**
* 获取文件后缀
* @param str $filename 文件名
* @return str 文件的后缀名，且带.
*/
function getExt($filename) {
	return strchr($filename, '.');
}

/**
* 生成缩略图
* @param str $oimg /upload/2016/01/25/adef.jpg
* @param int $sw 生成缩略图的宽
* @param int $sh 生成缩略图的高
* @return str 生成缩略图的路径 /uplaod/2016/01/25/asdf.jpg
*/

function makeThumb($oimg, $sw=200, $sh=200) {
	//缩略图存放的路径的名称
	$simg = dirname($oimg) . '/' . randStr() . '.png';

	//获取大图和缩略图的绝对路径
	$opath = ROOT . $oimg;//原图的绝对路径
	$spath = ROOT . $simg;//最终生成的小图

	//创建小画布
	$spic = imagecreatetruecolor($sw,$sh);

	//创建白色
	$white = imagecolorallocate($spic, 255, 255, 255);
	imagefill($spic, 0, 0, $white);

	//获取大图信息
	list($bw,$bh,$btype) = getimagesize($opath);

	$map = array(
		1=>'imagecreatefromgif',
		2=>'imagecreatefromjpeg',
		3=>'imagecreatefrompng',
		15=>'imagecreatefromwbmp'	
	);

	if(!isset($map[$btype])) {
		return false;
	}

	$opic = $map[$btype]($opath);

	//计算缩略比
	$rate = min($sw/$bw,$sh/$bh);
	$zw = $bw * $rate;
	$zh = $bh * $rate;

	imagecopyresampled($spic,$opic,($sw-$zw)/2,($sh-$zh)/2,0,0,$zw,$zh,$bw,$bh);

	imagepng($spic,$spath);
	imagedestroy($spic);
	imagedestroy($opic);

	return $simg;
}

/**
* 检测用户是否登录
*
*/

function acc() {
	if(!isset($_COOKIE['name']) || !isset($_COOKIE['ccode'])) {
		return false;
	}
	return $_COOKIE['ccode']===cCode($_COOKIE['name']);
}

/**
* 使用反斜线 转义字符串
* @param arr 待转义的数组
* @return arr 待转义后的数组
*/

function _addslashes($arr) {
	foreach ($arr as $k => $v) {
		if(is_string($v)) {
			$arr[$k] = addslashes($v);
		} else if(is_array($v)) {
			$arr[$k] = _addslashes($v);
		}
	}

	return $arr;
}

/**
* 加密用户名
*
* @param str $name 用户登陆时输入的用户名
* @return str md5(用户名+salt)=>md5码
*/

function cCode($name) {
	$salt = require(ROOT . '/lib/config.php');
	return md5($name . '|' . $salt['salt']);
}
?>