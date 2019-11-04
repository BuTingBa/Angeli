<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once '../../../config.php';
require_once '../../api/angeli.class.php';
$app=new angeli($config);
session_start();

if(!$_SESSION['id']||!$_COOKIE["adminid"]){
	header('location:login.html'); 
}
if($_SESSION['id']!=$_COOKIE["adminid"]){
	die("登录已过期,请重新登录");
	header('location:login.html'); 
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>添加管理员啊</title>
	<style>
		input{
			display: block;
			width: 300px;
			height: 35px;
			font-size: 0.875rem;
			font-weight: 400;
			line-height: 35px;
			font-size:12px ;
			padding: 0 5px;
			background-color: transparent;
			background-clip: padding-box;
			border: 1px solid #cad1d7;
			border-radius: 0.25rem;
			box-shadow: none;
		}
		form{
			font-size:12px;
			color: #808080;
		}
		p{
			font-size:11px;
			color:#808080;
		}
	</style>
</head>
<body>
	<?php
		if(isset($_GET['code'])){
			$type='add';
		}else{
			$type=$_GET['type'];
		}
		switch ($type)
		{
			case 'add':
				$code=$_GET['code'];
				$wxinfo=getWxAccessToken($code);
				$data=$app->getAdminInfo($wxinfo['openid']);
				if(!$data){
					echo "<script language = 'javascript' type = 'text/javascript'>
							top.location.href ='http://admin.angeli.top/assets/html/addadminuser.php?type=addadd&openid=".$wxinfo['openid']."'; 
					</script>"; 
					
				}else{
					header("Refresh:3;url=http://admin.angeli.top");
					die('该微信号已经绑定为管理员了！！');
				}
				break;
			case 'set':
				
			case 'addadd':
				$openid=$_GET['openid'];
				$html=<<<HTML
				<h1>请完成以下资料</h1>
				<form action="http://admin.angeli.top/assets/html/addadminuser.php?type=indata" method="post">
					微信openID：<br><input type="text" name="openid"  value="{$openid}"><br>
					设置姓名：<br><input type="text" name="name"><br>
					手机号：<br><input type="text" name="phone"><br>
					备注：<br><input type="text" name="note"><br><br>
					<input type="submit" value="新增管理员"/><br>
				</form>
				<p>以上信息全部都需要填写</p>
HTML;
				die($html);
				break;
			case 'indata':
				if(empty($_POST['openid'])|empty($_POST['name'])|empty($_POST['phone'])|empty($_POST['note'])){
					die('你必须全部填写才能完成！');
				}
				$data=$app->addadmin($_POST['openid'],$_POST['name'],$_POST['phone'],$_POST['note']);
				if(!$data){
					die('新增失败！');
				}else{
					echo '新增管理员：'.$_POST['name'].'成功！';
					die('<br><br><a href="javascript:window.opener=null;window.close();">关闭本页</a>');
				}
				break;
			default:
				die('非法请求');
				break;
		}
		function getWxAccessToken($code)
		{
			$html = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxf80f27d1e38f8342&secret=2a8daccfdb55c5fdbb87b5b48fca7978&code=$code&grant_type=authorization_code");
			$arr=json_decode($html,true);
			return $arr;
		}
	?>
</body>
</html>

