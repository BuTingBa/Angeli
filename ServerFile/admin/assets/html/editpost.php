<?php 
error_reporting(E_ALL ^ E_NOTICE);

if(empty($_GET['postid'])){
	die('<h1>没有传入帖子ID，编辑个锤子哦？？</h1>');
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
	if($_GET['status']=='0'){
		$app->setPostInfo($_GET['postid'],'IsLock','0');
	}
	if($_GET['status']=='2'){
		$app->setPostInfo($_GET['postid'],'IsLock','2');
	}
}

$postInfo=$app->getPostInfo($_GET['postid']);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<style>
			.red{
				color: red;
			}
		</style>
	</head>
	<body>
		<h1>编辑帖子:<?php echo $postInfo['UserName']; ?></h1>
		<hr >
		<p>当前帖子状态：<a href="editpost.php?postid=<?php echo $_GET['postid']; ?>&status=<?php echo $postInfo['IsLock']=='0'?'2':'0';	
		?>"><?php 
			
			if($postInfo['IsLock']==0){
				echo '正常';
			}else{
				echo '<span class="red">不被显示(软删除/封贴/未审核)</span>';
			}
		?></a></p>
		<p>浏览次数：<?php echo $postInfo['ViewCount'];   ?> 次</p>
		<h1>帖子内容:</h1>
		<hr >
		<p><?php echo $postInfo['Content']; ?></p>
		<?php 
			foreach($postInfo['PictureId'] as $img){
				echo '<img src="'.$img.'" alt=""  height="150px" style="margin: 1px;">';
			}
		?>
	</body>
	
	
	<script>
		//parent.layer.close(1);
	</script>
</html>
