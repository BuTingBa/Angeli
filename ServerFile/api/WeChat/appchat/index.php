<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: text/plain');

require_once "WxPay.Api.php";
require_once "WxPay.Data.php";
require_once '../../angeli.class.php';
require_once('../../../config.php');

/*if(empty($_POST['openid'])){
    $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}*/
if(empty($_POST['fee'])){
    $outmsg = array('code' =>'0','msg'=>'缺少金额参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_POST['number'])){
    $outmsg = array('code' =>'0','msg'=>'缺少数量参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_GET['type'])){
    $outmsg = array('code' =>'0','msg'=>'缺少type参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}
if(empty($_POST['auid'])){
    $outmsg = array('code' =>'0','msg'=>'缺少用户ID参数','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}

$dd=getOrderId($_GET['type'],$_POST['auid']);
if($_GET['type']=='vip'){
    $body='安个利会员'.$_POST['number'].'个月';
    if($_POST['number']=='1'){
        $fee=$_POST['number']*20*100;
    }else{
        $fee=$_POST['number']*20*0.9*100;
    }
    $name=1;
}else{
    $body='安个利充值'.$_POST['number'].'个安个利币';
    $fee=$_POST['number']*100/10;
    $name=2;

}
$app=new angeli($config);
$order=$app->createOrder($_POST['auid'],$name,$fee,$_POST['number'],'oiIia5QlS30j5K7Zd5X6woZ29ObI',$dd);

if($order){
    $unifiedOrder = new WxPayUnifiedOrder();
    $unifiedOrder->SetBody($body);//商品或支付单简要描述
    $unifiedOrder->SetOut_trade_no($dd);
    $unifiedOrder->SetTotal_fee($fee);
    $unifiedOrder->SetTrade_type("APP");
    $result = WxPayApi::unifiedOrder($unifiedOrder);
    if(is_array($result)){
        $outmsg = array('code' =>'1','msg'=>'订单成功！','data'=>$result,'orderId'=>$dd);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
    }else{
        $outmsg = array('code' =>'0','msg'=>'订单失败！','data'=>$result);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
    }
}else{
    $outmsg = array('code' =>'0','msg'=>'创建订单失败！','data'=>$result);
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
?>