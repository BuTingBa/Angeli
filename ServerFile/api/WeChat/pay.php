<?php
require_once 'WeChatPay.class.php';
if(empty($_POST['openid'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_POST['fee'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_POST['moon'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_GET['type'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}


$dd=getOrder();

if($_GET['type']=='vip'){
    $body='安个利会员'.$_POST['moon'].'个月';
}else{
    $body='安个利充值积分'.$_POST['moon'];
}
$pay=new WeChatPay($_POST['openid'],$dd,$body,$_POST['fee']);
$data=$pay->unifiedorder();
if($data['return_code']=='SUCCESS'){
    $outmsg = array('code' =>'1','msg'=>'订单成功！','data'=>$data);
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}else{
    $outmsg = array('code' =>'1','msg'=>'订单失败！','data'=>$data);
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}

function getOrder(){
    return 'Lvhuanshabi'.time();
}