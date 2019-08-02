<?php
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
$total_fee = $attr['total_fee'];//支付的金额
$open_id = $attr['openid'];//支付的微信openID
$out_trade_no = $attr['out_trade_no'];
$time = $attr['time_end'];



testLog($open_id);
testLog($total_fee);
testLog($out_trade_no);
function testLog($str)
{
    $path = "wxpay.txt";
    $handle = fopen($path,"a+");
    fwrite($handle,'['.date('Y-m-d H:i:s').']'.$str.PHP_EOL);
    fclose($handle);
}