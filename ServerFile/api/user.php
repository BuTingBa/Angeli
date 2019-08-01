<?php
require_once('../config.php');
require_once('angeli.class.php');
//require_once('sendsms.class.php');
session_start();
error_reporting(E_ALL^E_NOTICE);
$allow_origin = array(
    'https://api.angeli.top',
    'http://www.angeli.top',
    'http://angeli.top'
);
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';  //跨域访问的时候才会存在此字段
if (in_array($origin, $allow_origin)) {
    header('Access-Control-Allow-Origin:' . $origin);
}
$user=new angeli($config);
if(empty($_GET['type']))
{
    die("请输出请求类型参数！");
}



switch ($_GET['type']){
    case 'getUserInfo':
        $info=$user->getUserInfo('auid',$_GET['auid']);
        if($info){
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>$info);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>'没有找到该用户','data'=>$info);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'search':
        if(empty($_GET['keyword'])){
            $outmsg = array('code' =>'0','msg'=>'请输入搜索关键词！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $userinfo=$user->searchUser('name',$_GET['keyword']);
        if(!$userinfo){
            $outmsg = array('code' =>'0','msg'=>'没有搜索到用户','data'=>$info);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>$userinfo);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getMyNoRead':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        //$auid=6666;
        $app=$user->getMyNoreadNumber($auid);
        $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$app);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));

        break;
    case 'getMyZan':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(!$app=$user->getMyZan($auid)){
            $outmsg = array('code' =>'0','msg'=>'查询失败！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$app);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'mark':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(!$app=$user->markNoRead($auid)){
            $outmsg = array('code' =>'0','msg'=>'标记失败！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$app);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        break;
    case 'getMypl':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        //$auid=6666;
        if(!$app=$user->getMyPinglun($auid)){
            $outmsg = array('code' =>'0','msg'=>'查询失败！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$app);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    default:
        $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
}












?>