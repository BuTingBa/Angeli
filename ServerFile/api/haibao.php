<?php
require_once('../config.php');
require_once('angeli.class.php');
error_reporting(E_ALL^E_NOTICE);

$postid=$_GET['id'];
$app=new angeli($config);
$data=$app->getPostInfo($postid);

$options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type:application/x-www-form-urlencoded;charset=UTF-8',
        'content' => $stringData
    ),
    // 解决SSL证书验证失败的问题
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    )
);
$context = stream_context_create($options);

$postContent=$data['Content'];
$userName=$data['AuthorInfo']['AuthorName'];
$authorAvatarUrl=$data['AuthorInfo']['AuthorAvatarUrl'];
$postTime=$data['PsotDate'];
$pic=$data['PictureId'][0];

$postPic=imagecreatefromstring(file_get_contents($pic));
$maxH=1256;
$maxW=1500;
//求得图片长宽
$oldW = imagesx($postPic);
$oldH = imagesy($postPic);
//判断那个边比较长，利用较长的边求得比例
$newH=floor($maxW*$oldH/$oldW);

// 1.创建画布
$width = 1500;
$height= 1412+$newH;
$image=imagecreatetruecolor($width,$height);

// 2.创建颜色
$red=imagecolorallocate($image,255,0,0);
$blue=imagecolorallocate($image,0,0,255);
$bai=imagecolorallocate($image,255,255,255);
$hei=imagecolorallocate($image,0,0,0);
$angelilv=imagecolorallocatealpha($image,121,196,152,76);
$angelizi=imagecolorallocatealpha($image,121,196,152,10);
// 3.进行绘画


//引入安个利logo
$src = imagecreatefromstring(file_get_contents('logo.png'));

$angeliQR=imagecreatefromstring(getQRimg($postid));

$dibuH=$height-528;
$angeliH=$height-352;

imagefilledrectangle($image, 0, 0, $width, $height, $bai);
imagefilledrectangle($image, 0, $dibuH, 1500, $height, $angelilv);

$touPic=yuan_img($authorAvatarUrl);

//将安个利的标志合并到图片上
imagecopymerge($image, $src, 76,$angeliH, 0, 0, 266, 176, 100);
//将文章图片合并到图片上
/*imagecopymerge($image, $postPic, 76,210, 0, 0, $oldW, $oldH, 100);*/
imagecopyresampled($image,$postPic,76,210,48,0,$maxW,$newH,$oldW,$oldH);



//将头像 合并到图片上
imagecopymerge($image, $touPic, 76,$height-800, 0, 0, 176, 176, 100);
imagettftext($image, 46, 0, 300, $height-730, $hei,'D:\Web\angeli\ServerFile\api\yahei.ttf', $userName);

//二维码
imagecopymerge($image, $angeliQR, 1200,$angeliH, 0, 0, 250, 220, 220);
imagettftext($image, 32, 0, 832, $height-280, $angelizi,'D:\Web\angeli\ServerFile\api\yahei.ttf', "识别小程序码\n查看正文详情");


//遮住右边超出部分
imagefilledrectangle($image, 1424, 210, 1500, $newH+210, $bai);

$txt=autowrap(46,0,'D:\Web\angeli\ServerFile\api\yahei.ttf',$postContent,1348);


imagettftext($image, 46, 0, 76, $newH+300, $hei,'D:\Web\angeli\ServerFile\api\yahei.ttf', $txt);

// 4.输出或保存
//header('content-type:image/jpeg');
imagejpeg($image);

//if(imagejpeg($image,'./1.jpg')) {
//    echo '保存成功';
//} else {
//    echo '保存失败';
//}

// 5.销毁画布
imagedestroy($image);

function autowrap($fontsize, $angle, $fontface, $string, $width) {
// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
    $string = str_replace(array("\r\n", "\r", "\n"), "", $string);
    $content = "";
    $index=0;
    // 将字符串拆分成一个个单字 保存到数组 letter 中
    for ($i=0;$i<mb_strlen($string);$i++) {
        $letter[] = mb_substr($string, $i, 1);
    }
    foreach ($letter as $l) {
        $teststr = $content." ".$l;
        $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
        // 判断拼接后的字符串是否超过预设的宽度
        if (($testbox[2] > $width) && ($content !== "")) {
            $content .= "\n";
            $index++;

        }
        $content .= $l;
        if($index>=3){
            return $content.'...';
        }
    }
    return $content;
}
/*
 * 把头像变成圆形的
 */
function yuan_img($imgpath) {
    $ext     = pathinfo($imgpath);
    $src_img = null;
    global $context;
    switch ($ext['extension']) {
        case 'jpg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'png':
            $src_img = imagecreatefrompng($imgpath);
            break;
    }

    $src_imga=imagecreatefromstring(file_get_contents($imgpath));
    $ow=imagesx($src_imga);
    $oh=imagesy($src_imga);
    $src_img=imagecreatetruecolor($ow*2,$oh*2);

    imagecopyresampled($src_img,$src_imga,0,0,0,0,176,176,$ow,$oh);
    $wh  = getimagesize($imgpath);
    $w   = 176;
    $h   = 176;
    $w   = min($w, $h);
    $h   = $w;
    $img = imagecreatetruecolor($w, $h);
    //这一句一定要有
    imagesavealpha($img, true);
    //拾取一个完全透明的颜色,最后一个参数127为全透明
    $bg = imagecolorallocatealpha($img, 255, 255, 255, 0);
    imagefill($img, 0, 0, $bg);
    $r   = $w / 2; //圆半径

    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgbColor = imagecolorat($src_img, $x, $y);
            if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                imagesetpixel($img, $x, $y, $rgbColor);
            }
        }
    }
    return $img;
}

function getQRimg($postid){
    //header('content-type:image/jpeg');
    global $app;
    $token=$app->getWxAccessToken();
    $url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$token;
    $data=array(
        'id'=>$postid,
        'page'=>"pages/postinfo/postinfo",
        'is_hyaline'=>'true'
    );
    $data=posturl($url,$data);
    die($data);
    return $data;
}
function posturl($url,$data){
    $data  = json_encode($data);
    $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $outData = curl_exec($curl);
    curl_close($curl);
    return $outData;
}