<?php

include_once "wxBizDataCrypt.php";


$appid = 'wxb2418420ae2cf37c';
$sessionKey =$_REQUEST['sk'];
$encryptedData=$_REQUEST['data'];
$iv =$_REQUEST['iv'];

$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data );

if ($errCode == 0) {
    print($data . "\n");
} else {
    print($errCode . "\n");
}
