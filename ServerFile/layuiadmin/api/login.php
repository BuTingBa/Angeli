<?php
require_once '../../config.php';
require_once 'angeli.class.php';
$app=new angeli($config);

$type=empty($_GET['code'])?'user':'weixin';
switch ($type){
	case 'user':
		die('账号密码错误！');
		break;
	case 'weixin':
		$code=$_GET['code'];
		$wxinfo=getWxAccessToken($code);
		$data=$app->getAdminInfo($wxinfo['openid']);
		if(!$data){
			header("Refresh:3;url=http://admin.angeli.top/login.html");
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
					top.location.href ='http://admin.angeli.top'; 
			</script>"; 
			
			die('欢迎你，'.$_SESSION['name']);
		}
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


