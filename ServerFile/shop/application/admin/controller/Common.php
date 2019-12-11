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
}