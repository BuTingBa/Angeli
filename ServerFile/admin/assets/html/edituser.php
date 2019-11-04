<?php 
error_reporting(E_ALL ^ E_NOTICE);

if(empty($_GET['auid'])){
	die('<h1>没有用户id编辑个啥子哦？</h1>');
}
require_once '../../../config.php';
require_once '../../api/angeli.class.php';
session_start();
$app=new angeli($config);
if($_SERVER['SERVER_NAME']=='admin.io'||$_SERVER['SERVER_NAME']=='172.16.10.26'){
	
	$data=$app->getAdminInfo('oYlOMs5bN-3Yhj0vOfcImu_yknS8');
	if(!$data){
		die('你没有权限登录安个利的后台！！');
	}else{
		//var_dump($data);
		$_SESSION['id']=$data['id'];
		$_SESSION['name']=$data['name'];
		$_SESSION['wxopenid']=$data['wxopenid'];
		$_SESSION['phone']=$data['phone'];
		$_SESSION['note']=$data['note'];
		setcookie("adminid",$data['id'],time()+3600*24*30,'/');
	}
}

if(!$_SESSION['id']||!$_COOKIE["adminid"]){
	header('location:login.html'); 
}
if($_SESSION['id']!=$_COOKIE["adminid"]){
	die("登录已过期,请重新登录");
	header('location:login.html'); 
}

if(isset($_GET['status'])){
	if($_GET['status']=='fenghao'){
		$app->setUserInfo($_GET['auid'],'Status','2');
	}
	if($_GET['status']=='jiefeng'){
		$app->setUserInfo($_GET['auid'],'Status','1');
	}
}


$userInfo=$app->getUserInfo('auid',$_GET['auid']);


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<h1>用户编辑:<?php echo $userInfo['UserName']; ?></h1>
		<hr>
		<p>当前用户状态：<a href="edituser.php?auid=<?php echo $_GET['auid']; ?>&status=<?php echo $userInfo['Status']=='1'?'fenghao':'jiefeng'; ?>"><?php echo $userInfo['Status']=='1'?'正常使用':'封号状态';?></a> </p>
		<p>用户累计充值：<?php echo $app->getLeijichongzhi($_GET['auid']);   ?>元</p>
	</body>
	
	
	<script>
		//parent.layer.close(1);
	</script>
</html>
