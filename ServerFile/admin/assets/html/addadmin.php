<?php 
error_reporting(E_ALL ^ E_NOTICE);
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
<html>
	<head>
		<meta charset="utf-8">
		<title>添加管理员</title>
		<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"  charset="utf-8"></script>
		<style>
			.center{
				text-align: center;
			}
		</style>
	</head>
	<body class="center">
		<h2>请新管理员扫描二维码</h2>
		<div id="qrcode">
			
		</div>
	</body>
	<script type="text/javascript">
		var obj = new WxLogin({
			 self_redirect:true,
			 id:"qrcode", 
			 appid: "wxf80f27d1e38f8342", 
			 scope: "snsapi_login", 
			 redirect_uri: "http%3a%2f%2fadmin.angeli.top%2fassets%2fhtml%2faddadminuser.php",
			 state: "woshinibaba",
			 style: "",
			 href: ""
		 });
		 
		 if (window != top){
		     top.location.href = location.origin+location.pathname;
		 } 
	</script>
</html>
