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
use think\facade\Log;

require_once Env::get('ROOT_PATH').'extend/lib/wxpay/WeChatPay.class.php';


error_reporting(E_ERROR | E_WARNING | E_PARSE);


class Order
{
    //测试支付
    public function test($id)
    {
        $orderNo=gg::createOrderNo();
        $wxpay=new \WeChatPay($orderNo,'测试','1');
        return json_encode($wxpay->unifiedorder(),JSON_UNESCAPED_UNICODE);

    }

    /**
     * 获取微信支付回调
     */
    public function wxNotify()
    {
        $postXml = file_get_contents("php://input"); //接收微信参数
        $data=gg::xmlToArray($postXml);
        log::write($data);
        if($data['result_code']=='SUCCESS') {
            $res=Db::table('order')->where('order_no',$data['out_trade_no'])->find();
            log::write('查询结果');
            log::write($res);
            if(is_array($res)){
                if($res['price']==$data['total_fee']){
                    log::write('价格相等！');
                    $upda=array(
                        'order_status' => 1,
                        'payment_price'=>$data['total_fee'],
                        'pay_order_no'=>$data['transaction_id'],
                        'completion_time'=>time()
                    );
                    $ddd=Db::table('order')->where('order_no',$data['out_trade_no'])->update($upda);
                    if($ddd<1){
                        log::write('更新订单失败！');
                    }
                    $idarr=explode('|', $res['shop_cart_id']);
                    foreach ($idarr as $value){
                        Db::table('shoppinp_cart')->where('id',$value)->update(['data_type'=>3]);
                    }
                }else{
                    log::write('价格不一样！');
                    $upda=array(
                        'mark' =>'支付失败:金额不匹配',
                    );
                    Db::table('order')->where('order_no',$data['out_trade_no'])->update($upda);
                }
            }else{
                log::write('没有找到订单！');
            }
        }else{
            log::write('支付失败！');

        }
        exit;


    }

    /**
     * 查询未付款订单
     * @param $token
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function queryArrearage($type,$token)
    {
        if(!$token){
            return json_encode(['code'=>0,'msg'=>'用户未登录！'],JSON_UNESCAPED_UNICODE);
        }
        if(!gg::token($token)){
            return json_encode(['code'=>0,'msg'=>'用户未登录或用户不存在'],JSON_UNESCAPED_UNICODE);
        }

        if($type==0){
            $res=Db::table('order')->where('user_id',$_SESSION['Auid'])->select();
        }
        if($type==1){
            $res=Db::table('order')->where('user_id',$_SESSION['Auid'])->where('order_status',0)->select();
        }
        if($type==2){
            $res=Db::table('order')->where('user_id',$_SESSION['Auid'])->where('order_status',1)->select();
        }
        if($type==3){
            $res=Db::table('order')->where('user_id',$_SESSION['Auid'])->where('order_status',2)->select();
        }
        if($type==4){
            $res=Db::table('order')->where('user_id',$_SESSION['Auid'])->where('order_status',4)->select();
        }

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
        $title='安个利';
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
            $price=$price+$da['count']*$da['goodsSpecs']['price']*100;
            $title=$da['goodsInfo']['title'];
        }
        if($count>1){
            $title=$da['goodsInfo']['title'].'等'.$count.'件商品';
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
        $orderNo=gg::createOrderNo($_SESSION['Auid']);
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
            $wxpay=new \WeChatPay($orderNo,$title,$vipPrice);
            $payData=$wxpay->unifiedorder();
            if($payData['result_code']=='SUCCESS'){
                return json_encode(['code'=>1,'msg'=>$payData['return_msg'],'data'=>$payData['mweb_url']],JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(['code'=>0,'msg'=>$payData['return_msg']],JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode(['code'=>0,'msg'=>'添加订单失败'],JSON_UNESCAPED_UNICODE);
        }

    }









}