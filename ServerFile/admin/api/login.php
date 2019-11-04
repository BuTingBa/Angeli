<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once '../../config.php';
require_once 'angeli.class.php';
$app=new angeli($config);

$type=$_GET['type'];
if(isset($_GET['code'])){
	$type='weixin';
}

switch ($type){
	case 'user':
		die('账号密码错误！');
		break;
	case 'weixin':
		$code=$_GET['code'];
		$wxinfo=getWxAccessToken($code);
		$data=$app->getAdminInfo($wxinfo['openid']);
		if(!$data){
			header("Refresh:3;url=../index.php");
			die('你没有权限登录安个利的后台！！');
		}else{
			//var_dump($data);
			session_start();
			$_SESSION['id']=$data['id'];
			$_SESSION['name']=$data['name'];
			$_SESSION['wxopenid']=$data['wxopenid'];
			$_SESSION['phone']=$data['phone'];
			$_SESSION['note']=$data['note'];
			setcookie("adminid",$data['id'],time()+3600*24*30,'/');
			echo "<script language = 'javascript' type = 'text/javascript'>
					top.location.href ='../index.php'; 
			</script>"; 
			
			die('欢迎你，'.$_SESSION['name']);
		}
		break;
	case 'logout':
		session_start();
		unset($_SESSION['id']);
		unset($_SESSION['name']);
		unset($_SESSION['wxopenid']);
		unset($_SESSION['phone']);
		unset($_SESSION['note']);
		$html='<div style="width:100%;text-align:center;margin-top:80px;"><img src="http://img.zcool.cn/community/0168b75b4da4b3a80121ade03ff4d3.gif" alt=""><h1>正在退出中</h1></div>';
		echo $html;
		setcookie("adminid",'',time()-3600,'/');
		session_destroy();
		header("Refresh:3;url=../login.html");
		break;
	default:
		die('非法请求');
		break;	
}


/**
 * 获取微信token
 */
function getWxAccessToken($code)
{
	$html = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxf80f27d1e38f8342&secret=2a8daccfdb55c5fdbb87b5b48fca7978&code=$code&grant_type=authorization_code");
	$arr=json_decode($html,true);
	return $arr;
}


