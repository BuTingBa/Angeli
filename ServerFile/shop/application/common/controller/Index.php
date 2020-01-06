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
}