<?php
require_once 'WeChatPay.class.php';
require_once '../angeli.class.php';
require_once('../../config.php');

if(empty($_POST['openid'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_POST['fee'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_POST['number'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_GET['type'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_POST['auid'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
$dd=getOrderId($_GET['type'],$_POST['auid']);
if($_GET['type']=='vip'){
    $body='安个利会员'.$_POST['number'].'个月';
    $name=1;
}else{
    $body='安个利充值积分'.$_POST['number'];
    $name=2;
}

$fee=$_POST['fee'];

$app=new angeli($config);
$order=$app->createOrder($_POST['auid'],$name,$fee,$_POST['number'],$_POST['openid'],$dd);
if($order){
    $pay=new WeChatPay($_POST['openid'],$dd,$body,$fee);
    $data=$pay->unifiedorder();
    if($data['return_code']=='SUCCESS'){
        $outmsg = array('code' =>'1','msg'=>'订单成功！','data'=>$data,'orderId'=>$dd);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
    }else{
        $outmsg = array('code' =>'0','msg'=>'订单失败！','data'=>$data);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
    }
}else{
    $outmsg = array('code' =>'0','msg'=>'创建订单失败！','data'=>$data);
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}

function getOrderId($type,$auid){
    $time=getMillisecond();
    switch ($type){
        case '1':
            return 'LHSD1'.$auid.$time.mt_rand(1000,9999);
            break;
        case '2':
            return 'LHSD2'.$auid.$time.mt_rand(1000,9999);
            break;
        default:
            return 'LHSD9'.$auid.$time.mt_rand(1000,9999);
            break;
    }
}


function getMillisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}