<?php
require_once 'vendor/autoload.php';
require_once '../angeli.class.php';
require_once '../../config.php';

use OSS\OssClient;
use OSS\Core\OssException;
session_start();
error_reporting(E_ALL^E_NOTICE);
// 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
$accessKeyId = "LTAI4FxmXhgN5esyQL9yV9A1";
$accessKeySecret = "aYOvY6YtX9c4FZlAVk32GlIzlyqgip";
// Endpoint以杭州为例，其它Region请按实际情况填写。
$endpoint = "http://oss-cn-shenzhen.aliyuncs.com";
// 存储空间名称

$bucket= "angeli-image";

$imageUrl="https://sz.oss.data.angeli.top/";

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);        // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/jpg")
        || ($_FILES["file"]["type"] == "image/pjpeg")
        || ($_FILES["file"]["type"] == "image/x-png")
        || ($_FILES["file"]["type"] == "image/png"))
    && ($_FILES["file"]["size"] < 1048576)    // 小于 1mb
    && in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
        $outmsg = array('code' =>'0','msg'=>$_FILES["file"]["error"],'data'=>'');
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));

    }
    else
    {
        // 文件名称
        $object = "angeli/".time().mt_rand(1000,9999).".png";
        // <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt
        $filePath = $_FILES["file"]["tmp_name"];
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->uploadFile($bucket, $object, $filePath);
            if(!$_SESSION['Auid'])
            {
                $outmsg = array('code' =>'0','msg'=>'没有登录就想评论？','data'=>"");
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
            $auid=$_SESSION['Auid'];
            //$auid=6666;
            if(checkImage($imageUrl.$object)==2){
                $outmsg = array('code' =>'2','msg'=>'图片包含敏感内容，请更换其他','data'=>"");
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }

            $app=new angeli($config);


            $data=$app->setAvatar('auid',$auid,$imageUrl.$object);
            if($data==true){

                $outmsg = array('code' =>'1','msg'=>'修改成功！','data'=>$imageUrl.$object);
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));

            }else{
                $outmsg = array('code' =>'0','msg'=>'修改失败！','data'=>$data);
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            exit;
        }
    }


}
else
{
    $outmsg = array('code' =>'0','msg'=>'图片大小超出限制','data'=>'');
    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
}

function checkImage($url){
    $html = file_get_contents('https://api.angeli.top/contentCensor.php?type=image&imageUrl='.$url);
    $html=json_decode($html,true);
    return $html['conclusionType'];

    if($html['conclusion']=='合规'){
        return true;
    }else{
        return false;
    }
}




