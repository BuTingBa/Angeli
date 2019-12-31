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


    public function delShopCart()
    {
        //需要 购物车ID、token
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'身份失效！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('shopCartId')){
            return json_encode(['code'=>0,'msg'=>'缺少参数！'],JSON_UNESCAPED_UNICODE);
        }
        session_id(Request::param('token'));
        session_start();

        if(!$_SESSION['Auid']){
            return json_encode(['code'=>0,'msg'=>'用户未登录或查找用户失败！'],JSON_UNESCAPED_UNICODE);
        }else{
            $idarr=explode('|', Request::param('shopCartId'));
            foreach ($idarr as $value){
                Db::table('shoppinp_cart')->where('id',$value)->where('auid',$_SESSION['Auid'])->delete();
            }
            $res=Db::table('shoppinp_cart')->where('auid',$_SESSION['Auid'])->select();
            if(count($res)>0){
                foreach ($res as $val){
                    $data=array(
                        'id'=>$val['id'],
                        'goodsId'=>$val['goods_id'],
                        'specsId'=>$val['specs_id'],
                        'count'=>$val['number'],
                        'update_time'=>date('Y-m-d H:i:s',$val['update_time']),
                        'goodsInfo'=>$this->getGoodsInfo($val['goods_id'],true),
                        'goodsSpecs'=>$this->getSpecsInfo($val['specs_id']),
                        'selected'=>false
                    );
                    $outData[]=$data;
                }
                return json_encode(['code'=>1,'msg'=>'删除成功！','data'=>$outData],JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(['code'=>0,'msg'=>'购物车内没有任何东西！'],JSON_UNESCAPED_UNICODE);
            }
        }
    }



    /**
     * 添加购物车
     * @return false|string
     */
    public function addShopCart()
    {
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'身份失效！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('goodsId')){
            return json_encode(['code'=>0,'msg'=>'缺少参数！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('specsId')){
            return json_encode(['code'=>0,'msg'=>'缺少参数！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('count')){
            return json_encode(['code'=>0,'msg'=>'缺少参数！'],JSON_UNESCAPED_UNICODE);
        }

        session_id(Request::param('token'));
        session_start();
        if(!$_SESSION['Auid']){
            return json_encode(['code'=>0,'msg'=>'身份失效！'],JSON_UNESCAPED_UNICODE);
        }
        $type=Request::param('type')?Request::param('type'):1;

        $cx=Db::table('shoppinp_cart')
            ->where('goods_id',Request::param('goodsId'))
            ->where('specs_id',Request::param('specsId'))
            ->where('auid',$_SESSION['Auid'])->findOrEmpty();

        if(count($cx)>0){
            if($cx['data_type']==$type){
                Db::table('shoppinp_cart')->where('id', $cx['id'])->setInc('number', Request::param('count'));
                return json_encode(['code'=>1,'msg'=>'添加购物车成功！','data'=>$cx['id']],JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(['code'=>1,'msg'=>'订单已存在！','data'=>$cx['id']],JSON_UNESCAPED_UNICODE);
            }
        }

        $data=[
            'auid'=>$_SESSION['Auid'],
            'goods_id'=>Request::param('goodsId'),
            'specs_id'=>Request::param('specsId'),
            'number'=>Request::param('count'),
            'update_time'=>time(),
            'data_type'=>$type

        ];
        $res=Db::name('shoppinp_cart')->insertGetId($data);
        if($res){
            return json_encode(['code'=>1,'msg'=>'添加购物车成功！','data'=>$res],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'添加购物车失败！','data'=>$res],JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 获取购物车列表
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getShopCart()
    {
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'身份失效！'],JSON_UNESCAPED_UNICODE);
        }
        session_id(Request::param('token'));
        session_start();
        if(!$_SESSION['Auid']){
            return json_encode(['code'=>0,'msg'=>'身份失效！'],JSON_UNESCAPED_UNICODE);
        }
        $res=Db::table('shoppinp_cart')->where('auid',$_SESSION['Auid'])->select();
        if(count($res)>0){
            foreach ($res as $val){
                $data=array(
                    'id'=>$val['id'],
                    'goodsId'=>$val['goods_id'],
                    'specsId'=>$val['specs_id'],
                    'count'=>$val['number'],
                    'update_time'=>date('Y-m-d H:i:s',$val['update_time']),
                    'goodsInfo'=>$this->getGoodsInfo($val['goods_id'],true),
                    'goodsSpecs'=>$this->getSpecsInfo($val['specs_id']),
                    'selected'=>false
                );
                $outData[]=$data;
            }
            return json_encode(['code'=>1,'msg'=>'获取成功！','data'=>$outData],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'购物车内没有任何东西！'],JSON_UNESCAPED_UNICODE);
        }

    }


    /**
     * 通过传入的购物车ID，返回商品列表
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function settleAccounts()
    {
        //需要 购物车ID、token
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'身份失效！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('shopCartId')){
            return json_encode(['code'=>0,'msg'=>'缺少参数！'],JSON_UNESCAPED_UNICODE);
        }
        session_id(Request::param('token'));
        session_start();

        if(!$_SESSION['Auid']){
            return json_encode(['code'=>0,'msg'=>'用户未登录或查找用户失败！'],JSON_UNESCAPED_UNICODE);
        }else{
            $idarr=explode('|', Request::param('shopCartId'));
            foreach ($idarr as $value){
                $res=Db::table('shoppinp_cart')->where('id',$value)->where('auid',$_SESSION['Auid'])->select();
                foreach ($res as $val){
                    $da=array(
                        'id'=>$val['id'],
                        'goodsId'=>$val['goods_id'],
                        'specsId'=>$val['specs_id'],
                        'count'=>$val['number'],
                        'update_time'=>date('Y-m-d H:i:s',$val['update_time']),
                        'goodsInfo'=>$this->getGoodsInfo($val['goods_id'],true),
                        'goodsSpecs'=>$this->getSpecsInfo($val['specs_id'])
                    );
                    $data[]=$da;
                }

                //添加到订单即删除购物车(早就看不惯，商品一直待着购物车里了，早删早清净)
                Db::table('shoppinp_cart')->where('id',$value)->where('auid',$_SESSION['Auid'])->delete();

            }
            if(!is_array($data)){
                $outData=array('code'=>0,'msg'=>'错误！','data'=>$data);
                return json_encode($outData,JSON_UNESCAPED_UNICODE);
            }

            if(count($data)>=1){
                $address=Db::table('address')->where('user_id',$_SESSION['Auid'])->where('status',1)->findOrEmpty();
                $outData=array('code'=>1,'msg'=>'获取成功！','data'=>$data,'address'=>$address);
                return json_encode($outData,JSON_UNESCAPED_UNICODE);
            }else{
                $outData=array('code'=>0,'msg'=>'未知错误！','data'=>$data);
                return json_encode($outData,JSON_UNESCAPED_UNICODE);
            }
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

    /**
     * 通过用户ID获取收货地址列表
     * @param $auid
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
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
    public function getGoodsInfo($id,$isarray=false)
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
                $outData=array('code'=>0,'msg'=>'未找到该商品信息或已下架','data'=>$data);
                return json_encode($outData,JSON_UNESCAPED_UNICODE);
            }

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
    public function getGoodsSpecs($id)
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
    public function getSpecsInfo($id)
    {
        $data=Db::table('specs')->field('id as id,specs_name as name,specs_price as price')->where('id',$id)->findOrEmpty();
        if(count($data)>0){
            return $data;
        }else{
            return 0;
        }
    }

}
