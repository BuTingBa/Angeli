<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\facade\Request;
error_reporting(E_ERROR | E_WARNING | E_PARSE);
class Index extends Controller
{
    /**
     * 获取用户信息
     * @param $token
     */
    public function index($token)
    {
        //return date('H-m-d H:i:s',time());
        if(!$token){
            return json_encode(['code'=>0,'msg'=>'缺少参数'],JSON_UNESCAPED_UNICODE);
        }
        session_id($token);
        session_start();
        $user=$this->getUserInfo($_SESSION['Auid']);
        if(is_array($user)){
            return json_encode($user,JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'失败','data'=>session_id()],JSON_UNESCAPED_UNICODE);
        }

    }


    public function settleAccounts()
    {
        //需要 商品ID、token、规格id
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'身份失效！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('goods')){
            return json_encode(['code'=>0,'msg'=>'缺少参数！'],JSON_UNESCAPED_UNICODE);
        }
        session_id(Request::param('token'));
        session_start();
        if(!$_SESSION['Auid']){
            return json_encode(['code'=>0,'msg'=>'用户未登录或查找用户失败！'],JSON_UNESCAPED_UNICODE);
        }else{

            $add=Db::table('address')->field('address_id as id,user_id as auid,status,phone,address,name')->where('user_id',$_SESSION['Auid'])->find();
            $goods=Db::table('goods')->field('id as goodsid,name as title,current_price as price,image as picUrl,goods_number as stock,image_list as lunbo,goods_desc_image_list as info')->where('is_show',1)->where('id',Request::param('goods'))->find();
            $specs=Db::table('specs')->field('id,specs_name as name,specs_price as price')->where('id',Request::param('specs'))->find();
            return json_encode(['code'=>1,'msg'=>'成功','address'=>$add,'goods'=>$goods,'specs'=>$specs],JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 获取商品列表
     * @param string $date
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGoodsList($date='')
    {
        $xxDate=date('Ymd',time());
        $date=is_numeric($date)?$date:$xxDate;
        $data=Db::table('goods')->field('id as goodsid,name as title,current_price as price,image as picUrl,goods_number as stock')->where("sale_date=$date or status=2")->select();
        if(count($data)>0){
            $outData=array('code'=>1,'msg'=>'获取成功！','data'=>$data);
            return json_encode($outData,JSON_UNESCAPED_UNICODE);
        }else{
            $outData=array('code'=>0,'msg'=>'该日没有出售商品！','data'=>$data);
            return json_encode($outData,JSON_UNESCAPED_UNICODE);
        }
    }


    public function getAddressList($auid)
    {
        $data=Db::table('address')->field('address_id as id,user_id as auid,status,phone,address,name')->where('user_id',$auid)->select();
        if(count($data)>0){
            $outData=array('code'=>1,'msg'=>'获取成功！','data'=>$data);
            return json_encode($outData,JSON_UNESCAPED_UNICODE);
        }else{
            $outData=array('code'=>0,'msg'=>'该日没有出售商品！','data'=>$data);
            return json_encode($outData,JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * 获取用户信息
     * @param $token
     * @return string
     */
    public function getUserInfo($id)
    {
        /*session_id($token);
        session_start();
        $data='用户名：'.$_SESSION['UserName'].'|Auid:'.$_SESSION['Auid'].'|用户名:'.$_SESSION['name'];*/
        if(!$id){
            return '用户未登录';
        }
        $data = file_get_contents('https://api.angeli.top/user.php?type=getUserInfo&auid='.$id);
        $data=json_decode($data,true);
        if($data['data']['Auid']!='-1'){
            return $data['data'];
        }
        return '无此用户';
    }

    /**
     * 获取商品信息
     * @param $id
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGoodsInfo($id)
    {
        $data=Db::table('goods')->field('id as goodsid,name as title,current_price as price,image as picUrl,goods_number as stock,image_list as lunbo,goods_desc_image_list as info')->where('is_show',1)->where('id',$id)->find();
        if(count($data)>0){
            $specs=$this->getGoodsSpecs($id);
            if(is_array($specs)){
                $data['specs']=$specs;
            }else{
                $data['specs']=array(
                    'id'=>1,
                    'name'=>'默认配置',
                    'price'=>$data['pri3ce']
                );
            }

            $outData=array('code'=>1,'msg'=>'获取成功！','data'=>$data);
            return json_encode($outData,JSON_UNESCAPED_UNICODE);
        }else{
            $outData=array('code'=>0,'msg'=>'未找到该商品信息或已下架','data'=>$data);
            return json_encode($outData,JSON_UNESCAPED_UNICODE);
        }
    }
    public function getGoodsSpecs($id)
    {
        $data=Db::table('specs')->field('id as id,specs_name as name,specs_price as price')->where('goods_id',$id)->select();
        if(count($data)>0){
            return $data;
        }else{
            return 0;
        }
    }


}
