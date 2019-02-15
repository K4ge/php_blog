<?php 

include('./lib/init.php');

if(empty($_POST)) {
	include('./view/admin/login.html');
} else {
	$name = trim($_POST['name']);
	$password = trim($_POST['password']);

	if(empty($name)) {
		error('用户名不能为空');
	}

	if(empty($password)) {
		error('密码不能为空');
	}

	$sql = "select * from user where name='$name'";
	$row = mGetRow($sql);
	if(!$row) {
		error('用户名或密码错误');
	} else {
		if(md5($password.$row['salt']) === $row['password']) {
			setcookie('name', $name);
			setcookie('ccode' , cCode($name));
			header('Location: artlist.php');
		} else {
			error('密码错误');
		}
	}
}




?>