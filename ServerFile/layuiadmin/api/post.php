<?php
require_once '../../config.php';
require_once 'angeli.class.php';
$app=new angeli($config);
if(empty($_GET['type'])){
	die('错误');
}
switch ($_GET['type']){
	case 'getNewPost':
		$out=$app->getPosts();
		$data=array(
			'code'=>0,
			'msg'=>'',
			'count'=>20,
			'data'=>$out
		);
		die(json_encode($data,JSON_UNESCAPED_UNICODE));
		break;
	case 'hot':
		$out=$app->getPosts('hot');
		$data=array(
			'code'=>0,
			'msg'=>'',
			'count'=>20,
			'data'=>$out
		);
		die(json_encode($data,JSON_UNESCAPED_UNICODE));
		break;
		break;
	default:
		
		break;
}

