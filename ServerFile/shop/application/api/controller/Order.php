<?php
/**
 *   Order.php
 *   名称：
 *   作者：不停（BuTing）
 *   联系：my@buting.cc
 *   时间：2020-01-06 16:05
 */

namespace app\api\controller;
use app\index\controller\WebDav;
use think\Controller;
use think\Db;
use think\facade\Request;
use app\common\controller\Index as gg;
use think\facade\Env;

require_once Env::get('ROOT_PATH').'extend/lib/wxpay/WeChatPay.class.php';


error_reporting(E_ERROR | E_WARNING | E_PARSE);


class Order
{
    //测试支付
    public function test($id)
    {
        $orderNo=gg::createOrderNo();
        $wxpay=new \WeChatPay('11',$orderNo,'测试','1');
        var_dump($wxpay->unifiedorder());
    }

    /**
     * 查询未付款订单
     * @param $token
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function queryArrearage($token)
    {
        if(!$token){
            return json_encode(['code'=>0,'msg'=>'用户未登录！'],JSON_UNESCAPED_UNICODE);
        }
        if(!gg::token($token)){
            return json_encode(['code'=>0,'msg'=>'用户未登录或用户不存在'],JSON_UNESCAPED_UNICODE);
        }
        $res=Db::table('order')->where('user_id',$_SESSION['Auid'])->where('order_status',0)->select();
        if(count($res)>0){
            foreach ($res as $val){
                $d=array(
                    'id'=>$val['id'],
                    'orderNo'=>$val['order_no'],
                    'goodsList'=>gg::shopCartIdToGoodsInfo($val['shop_cart_id']),
                    'orderPrice'=>$val['order_price'],
                    'price'=>$val['price'],
                    'address'=>$val['address'],
                    'phone'=>$val['phone'],
                    'orderTitle'=>$val['order_title'],
                    'addressee'=>$val['addressee'],
                    'orderStatus'=>$val['order_status'],
                    'express'=>$val['express'],
                    'expressNumber'=>$val['tracking_number'],
                    'createTime'=>$val['create_time'],
                    'completionTime'=>$val['completion_time'],
                    'mark'=>$val['mark']
                );
                $data[]=$d;
            }
            return json_encode(['code'=>1,'msg'=>'获取成功','data'=>$data],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'没有该类型订单'],JSON_UNESCAPED_UNICODE);
        }
    }




    /**
     * 新增订单
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
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
        $note=Request::param('note');
        if(!gg::token($token)){
            return json_encode(['code'=>0,'msg'=>'用户未登录或用户不存在'],JSON_UNESCAPED_UNICODE);
        }
        //获取订单购物车ID
        $idarr=explode('|', $goodsData);
        //遍历购物车ID，获取商品信息
        $count=0;
        $price=0;

        foreach ($idarr as $value){
            $res=Db::table('shoppinp_cart')->where('id',$value)->where('auid',$_SESSION['Auid'])->findOrEmpty();
            $da=array(
                'id'=>$res['id'],
                'goodsId'=>$res['goods_id'],
                'specsId'=>$res['specs_id'],
                'count'=>$res['number'],
                'update_time'=>date('Y-m-d H:i:s',$res['update_time']),
                'goodsInfo'=>gg::getGoodsInfo($res['goods_id'],true),
                'goodsSpecs'=>gg::getSpecsInfo($res['specs_id'])
            );
            $count++;
            $price=$price+$da['count']*$da['goodsSpecs']['price'];
        }
        if($count>1){
            $title=$da['goodsInfo']['name'].'等'.$count.'件商品';
        }else{
            $title=$da['goodsInfo']['name'];
        }
        if(gg::isVip($_SESSION['Auid'])){
            $vipPrice=$price*0.9;
        }else{
            $vipPrice=$price;
        }

        //  $price          计算商品总共的金额
        //  $vipPrice       会员打折价格(实际付款金额)
        //  $title          订单名称
        //  $address
        //  订单编号
        $orderNo=gg::createOrderNo();

        $orderData=array(
            'order_no'=>$orderNo,
            'user_id'=>$_SESSION['Auid'],
            'shop_cart_id'=>$goodsData,
            'order_price'=>$price,
            'price'=>$vipPrice,
            'address'=>$address['region'].$address['detailed'],
            'phone'=>$address['phone'],
            'addressee'=>$address['name'],
            'order_status'=>0,
            'order_title'=>$title,
            'pay_mode'=>$payMode,
            'create_time'=>time(),
            'mark'=>$note
        );

        $addOrder = Db::name('order')->insertGetId($orderData);
        if($addOrder>0){

        }

    }









}