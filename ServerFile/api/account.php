<?php
require_once('../config.php');
require_once('angeli.class.php');
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
$app=new angeli($config);
if(empty($_GET['type'])){
    die('缺少请求类型！');
}
session_start();
switch ($_GET['type']){
    case 'queryOrderIdAndVip':
        if(empty($_GET['auid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少必要参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(empty($_GET['orderId'])){
            $outmsg = array('code' =>'0','msg'=>'缺少必要参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $outdata=$app->getOrderAndVip($_GET['orderId'],$_GET['auid']);
        if(!$outdata){
            $outmsg = array('code' =>'0','msg'=>'没有找到订单，有问题请联系客服','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'queryOrderIdAndAngelibi':
        if(empty($_GET['auid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少必要参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(empty($_GET['orderId'])){
            $outmsg = array('code' =>'0','msg'=>'缺少必要参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $outdata=$app->getOrderAndVip($_GET['orderId'],$_GET['auid']);
        if(!$outdata){
            $outmsg = array('code' =>'0','msg'=>'没有找到订单，有问题请联系客服','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'cx':
        if(empty($_GET['auid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少必要参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_GET['auid'];
        $data=$app->getMyBill($auid);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'查询失败','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    default:

        break;
}