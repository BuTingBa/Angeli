<?php
/**
 *   Index.php
 *   名称：公共方法类
 *   作者：不停（BuTing）
 *   联系：my@buting.cc
 *   时间：2020-01-06 19:13
 */

namespace app\common\controller;
use think\Controller;
use think\Db;
use think\facade\Request;

class Index
{

    public static function shopCartIdToGoodsInfo($cartId)
    {
        $idarr=explode('|', $cartId);

        foreach ($idarr as $value){
            $res=Db::table('shoppinp_cart')->where('id',$value)->where('auid',$_SESSION['Auid'])->findOrEmpty();
            $da=array(
                'id'=>$res['id'],
                'goodsId'=>$res['goods_id'],
                'specsId'=>$res['specs_id'],
                'count'=>$res['number'],
                'update_time'=>date('Y-m-d H:i:s',$res['update_time']),
                'goodsInfo'=>self::getGoodsInfo($res['goods_id'],true),
                'goodsSpecs'=>self::getSpecsInfo($res['specs_id'])
            );
            $data[]=$da;
        }
        return $data;

    }



    /**生成商家订单号
     * @return string
     */
    public static function createOrderNo()
    {
        $auid=$_SESSION['Auid'];
        $num=date('YmdHis',time());
        $rand=mt_rand(10,99);
        return 'AGLSHOP'.$num.$rand.$auid;
    }



    /**检测token是否存在
     * @param $token
     * @return bool
     */
    public static function token($token){
        session_id($token);
        session_start();
        if(!$_SESSION['Auid']){
            session_destroy();
            return false;
        }else{
            return true;

        }
    }
    /**
     * 通过商品ID获取规格列表
     * @param $id
     * @return array|false|int|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getGoodsSpecs($id)
    {
        $data=Db::table('specs')->field('id as id,specs_name as name,specs_price as price')->where('goods_id',$id)->select();
        if(count($data)>0){
            return $data;
        }else{
            return 0;
        }
    }

    /**
     * 通过规格ID获取规格信息
     * @param $id
     * @return array|false|int|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getSpecsInfo($id)
    {
        $data=Db::table('specs')->field('id as id,specs_name as name,specs_price as price')->where('id',$id)->findOrEmpty();
        if(count($data)>0){
            return $data;
        }else{
            return 0;
        }
    }

    /**
     * 获取用户信息
     * @param $token
     * @return string
     */
    public static function userInfo($auid)
    {
        if(!$auid){
            return '缺少参数';
        }
        $data = file_get_contents('https://api.angeli.top/user.php?type=getUserInfo&auid='.$auid);
        $data=json_decode($data,true);
        if($data['data']['Auid']!='-1'){
            return $data['data'];
        }else{
            return $data;
        }

    }

    /**
     * 判断是否是VIP
     * @param $auid
     * @return mixed
     */
    public static function isVip($auid)
    {
        $data=self::userInfo($auid);
        return $data['isVip'];

    }

    /**
     * 获取商品信息
     * @param $id
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getGoodsInfo($id,$isarray=false)
    {
        $data=Db::table('goods')->field('id as goodsid,name as title,current_price as price,image as picUrl,goods_number as stock,image_list as lunbo,goods_desc_image_list as info')->where('is_show',1)->where('id',$id)->find();
        if(is_array($data)){
            $specs=self::getGoodsSpecs($id);
            if(is_array($specs)){
                $data['specs']=$specs;
            }else{
                $data['specs']=array(
                    'id'=>1,
                    'name'=>'默认配置',
                    'price'=>$data['pri3ce']
                );
            }

            if($isarray){
                return $data;
            }else{
                $outData=array('code'=>1,'msg'=>'获取成功！','data'=>$data);
                return json_encode($outData,JSON_UNESCAPED_UNICODE);
            }

        }else{
            if($isarray){
                return [];
            }else{
                return '未找到该商品信息或已下架';
            }

        }
    }
}