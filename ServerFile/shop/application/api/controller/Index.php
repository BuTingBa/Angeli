<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
error_reporting(E_ERROR | E_WARNING | E_PARSE);
class Index extends Controller
{
    public function index()
    {
        return 'SESSIONID:'.session_id();
    }

    public function getGoodsList($date)
    {
        $data=Db::table('goods')->where('sale_date',$date)->select();
        $outData=array('code'=>1,'msg'=>'获取成功！','data'=>$data);
        return json_encode($outData,JSON_UNESCAPED_UNICODE);
    }
    public function getUserInfo($token)
    {
        session_id($token);
        session_start();
        $data='用户名：'.$_SESSION['UserName'].'|Auid:'.$_SESSION['Auid'].'|用户名:'.$_SESSION['name'];
        return $data;
    }

}
