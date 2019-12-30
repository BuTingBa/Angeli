<?php
/**
 *   Common.php
 *   名称：
 *   作者：不停（BuTing）
 *   联系：my@buting.cc
 *   时间：2019-12-10 17:06
 */

namespace app\admin\controller;
use think\Controller;
use OSS\Core\OssException;
use OSS\OssClient;
use think\facade\Config;
use think\Image;
class Common extends Controller
{
    public function initialize()
    {
        //判断是否本地环境
        if($_SERVER['SERVER_NAME']=='shop.io'||$_SERVER['SERVER_NAME']=='172.16.10.26'||$_SERVER['SERVER_NAME']=='127.0.0.1'){
            $_SESSION['id']=1;
            $_SESSION['name']='本地测试';
            setcookie("token",session_id(),time()+3600*24*30,'/');
        }else{
            if(empty($_COOKIE['token'])){
                $this->error('你还没有登录呢！','http://admin.angeli.top/');
            }
            session_id($_COOKIE['token']);
            session_start();
            if(!$_SESSION['id']){
                $this->error('你还没有登录呢！','http://admin.angeli.top/');
            }
        }
    }

    /** 上传图片
     * @param $imageUrl 图片路径
     * @return array|string
     */
    public function upImage($imageUrl)
    {

        // 尝试执行
        try {
            $config = Config::pull('ali'); //获取Oss的配置
            //实例化对象 将配置传入
            $ossClient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
            //这里是有sha1加密 生成文件名 之后连接上后缀
            $fileName = 'angeli-shop/' . date('YmdHis'.mt_rand(1,99999), time()) . '.jpg' ;
            //执行阿里云上传
            $result = $ossClient->uploadFile($config['bucket'], $fileName,$imageUrl);
            $url=$result['info']['url'];
        } catch (OssException $e) {
            return $e->getMessage();
        }
        //将结果输出
        return $url;
    }

}