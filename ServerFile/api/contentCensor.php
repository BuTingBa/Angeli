<?php
require_once 'Baidu/AipImageCensor.php';
const APP_ID = '16781515';
const API_KEY = 'FcufqO4LNNB3tig2fBdikXfL';
const SECRET_KEY = '6lKHpLEkoZ27dqOSXqc3yuXpUrbEu9TS';
$client = new AipImageCensor(APP_ID, API_KEY, SECRET_KEY);

if(empty($_GET['type'])){
    die("请传入内容类别");
}

//0表示非违禁，1表示违禁，2表示建议人工复审
switch ($_GET['type']){
    case 'text':
        if(empty($_GET['text'])){
            die("请传入文本内容");
        }
        $demo = $client->antiSpam($_GET['text']);
        die(json_encode($demo, JSON_UNESCAPED_UNICODE));
        break;
    case 'image':
        if(empty($_GET['imageUrl'])){
            die("请传入图片链接");
        }
        $result = $client->imageCensorUserDefined($_GET['imageUrl']);
        die(json_encode($demo, JSON_UNESCAPED_UNICODE));
        break;
}






