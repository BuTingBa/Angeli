<?php
/**
 *   Order.php
 *   名称：
 *   作者：不停（BuTing）
 *   联系：my@buting.cc
 *   时间：2020-01-06 16:05
 */

namespace app\api\controller;
use think\Controller;
use think\Db;
use think\facade\Request;
use app\common\controller\Index as gg;

error_reporting(E_ERROR | E_WARNING | E_PARSE);

class Order
{
    public function test($id)
    {
        $data=gg::getGoodsSpecs($id);
        return json_encode(['code'=>0,'msg'=>'成功！','data'=>$data],JSON_UNESCAPED_UNICODE);
    }

    public function newOrder()
    {
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'用户未登录！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('data')){
            return json_encode(['code'=>0,'msg'=>'缺少商品信息参数'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('address')){
            return json_encode(['code'=>0,'msg'=>'没有收货地址怎么可能寄的出去呢？'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('payMode')){
            return json_encode(['code'=>0,'msg'=>'请选择一个有效的支付方式。'],JSON_UNESCAPED_UNICODE);
        }

        $token=Request::param('token');
        $goodsData=Request::param('data');
        $address=Request::param('address');
        $address=json_decode($address,true);
        $payMode=Request::param('payMode');


        if(!gg::token($token)){
            return json_encode(['code'=>0,'msg'=>'用户未登录或用户不存在'],JSON_UNESCAPED_UNICODE);
        }

        //获取订单购物车ID
        $idarr=explode('|', $goodsData);
        //遍历购物车ID，获取商品信息
        foreach ($idarr as $value){
            $res=Db::table('shoppinp_cart')->where('id',$value)->where('auid',$_SESSION['Auid'])->select();
            $da=array(
                'id'=>$res['id'],
                'goodsId'=>$res['goods_id'],
                'specsId'=>$res['specs_id'],
                'count'=>$res['number'],
                'update_time'=>date('Y-m-d H:i:s',$res['update_time']),
                'goodsInfo'=>$this->getGoodsInfo($res['goods_id'],true),
                'goodsSpecs'=>$this->getSpecsInfo($res['specs_id'])
            );
        }

    }









}