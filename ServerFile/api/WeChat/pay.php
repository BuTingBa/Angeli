<?php
require_once 'WeChatPay.class.php';
require_once '../angeli.class.php';
require_once('../../config.php');

/*
    这里有一个大坑！客户端传过来的参数，里面有数量和和金额，
    服务器端并没有对这个进行判断，也就是说，客户端传多少钱和多少数量都是可以的。
    也就是说，如果客户端被人请求，5毛钱也可以充值5000万个安个利币或者N个月会员都是没有问题的！
    这个问题需要解决
*/
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
    if($_POST['number']=='1'){
        $fee=$_POST['number']*100*20;
    }else{
        $fee=$_POST['number']*100*20*0.9;
    }

    $name=1;

}else{
    $body='安个利充值'.$_POST['number'].'个安个利币';
    $fee=$_POST['number']*100/10;
    $name=2;

}

//$fee=$_POST['fee']*100; //上线需要乘以100




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
        case 'vip':
            return 'LHSD1'.$auid.$time.mt_rand(1000,9999);
            break;
        case 'angelibi':
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