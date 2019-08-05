<?php
require_once '../angeli.class.php';
require_once('../../config.php');
$postXml = file_get_contents("php://input"); //接收微信参数
// 接受不到参数可以使用 file_get_contents("php://input"); PHP 高版本中$GLOBALS 好像已经被废弃了
var_dump($postXml);
if (empty($postXml)) {
    return false;
}

//将 xml 格式转换成数组
function xmlToArray($xml) {
    //禁止引用外部 xml 实体
    libxml_disable_entity_loader(true);
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $val = json_decode(json_encode($xmlstring), true);
    return $val;
}

$attr = xmlToArray($postXml);

$total_fee = $attr['total_fee'];//支付的金额,单位为分
$open_id = $attr['openid'];//支付的微信openID
$out_trade_no = $attr['out_trade_no'];//自己定义的订单号
$time = $attr['time_end'];//完成支付时间
$wxTeadeNo=$attr['transaction_id'];//微信订单号
$status=$attr['result_code'];//支付结果，	SUCCESS/FAIL
$time=strtotime($time);

if($status=="SUCCESS"){
    //支付成功
    $app=new angeli($config);
    $yOrder=$app->getOrder($open_id,$out_trade_no);
    if(!$yOrder){

        testLog("查询订单时失败！");
        exit;
    }
    testLog(json_encode($attr,JSON_UNESCAPED_UNICODE));
    if($yOrder['payFee']!==$total_fee){
        testLog("订单金额和支付金额不一致！");
        exit;
    }
    $paysta=$app->upOrder($wxTeadeNo,$time,$open_id,$out_trade_no);
    if(!$paysta){
        testLog("更新订单时 失败！");
        exit;
    }
    if($yOrder['name']==1){
        $s=60*60*24*$yOrder['number']*30;
        $endtime=time()+$s;
        $app->setVIP('auid',$yOrder['auid'],$endtime);
    }else{

    }




}else{
    testLog("支付失败！");
}

function testLog($str)
{
    $path = "wxpay.txt";
    $handle = fopen($path,"a+");
    fwrite($handle,'['.date('Y-m-d H:i:s').']'.$str.PHP_EOL);
    fclose($handle);
}
