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
        if(!$_SESSION['Auid']){
            session_destroy();
            return json_encode(['code'=>0,'msg'=>'用户未登录','data'=>session_id()],JSON_UNESCAPED_UNICODE);
        }

        $user=$this->getUserInfo($_SESSION['Auid']);
        if($user['data']['Auid']!='-1'){
            return json_encode($user,JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'用户不存在','data'=>session_id()],JSON_UNESCAPED_UNICODE);
        }

    }


    /**
     * 删除地址
     * @return false|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delAddress()
    {
        //必须传入token
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'用户未登录！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('id')){
            return json_encode(['code'=>0,'msg'=>'缺少参数！'],JSON_UNESCAPED_UNICODE);
        }
        if(!self::token(Request::param('token'))){
            return json_encode(['code'=>0,'msg'=>'用户未登录或用户不存在'],JSON_UNESCAPED_UNICODE);
        }

        $res=Db::table('address')->where('user_id',$_SESSION['Auid'])->where('address_id',Request::param('id'))->delete();
        if($res>0){
            return json_encode(['code'=>1,'msg'=>'删除成功'],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'删除失败！'],JSON_UNESCAPED_UNICODE);
        }

    }


    /**
     * 新增地址
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addAddress()
    {
        //必须传入token
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'用户未登录！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('name')){
            return json_encode(['code'=>0,'msg'=>'请填写收件人姓名！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('phone')){
            return json_encode(['code'=>0,'msg'=>'请填写收件人手机号码！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('detailed')){
            return json_encode(['code'=>0,'msg'=>'请填写收件人详细地址！'],JSON_UNESCAPED_UNICODE);
        }
        if(!Request::param('region')){
            return json_encode(['code'=>0,'msg'=>'请填写收件人地区！'],JSON_UNESCAPED_UNICODE);
        }
        $isDefault=Request::param('isDefault')?1:0;
        if(!self::token(Request::param('token'))){
            return json_encode(['code'=>0,'msg'=>'用户未登录或用户不存在'],JSON_UNESCAPED_UNICODE);
        }

        //如果传入addressId则说明是修改，那就先闪了之前的那条记录，再重新添加就好了
        //注意！订单表里面的地址，不能用ID索引到此，必须填入收货信息字符串，万一哪天用户删除了，那不就GG 了？

        if(Request::param('addressId') && Request::param('addressId')>0){
            Db::table('address')->where('user_id',$_SESSION['Auid'])->where('address_id',Request::param('addressId'))->delete();
        }

        $add=Db::table('address')->where('user_id',$_SESSION['Auid'])->where('status',1)->findOrEmpty();
        if(empty($add)){
            $isDefault=1;
        }else{
            Db::name('address') ->where('user_id', $_SESSION['Auid'])->update(['status' => 0]);
        }

        $data=[
            'user_id'=>$_SESSION['Auid'],
            'status'=>$isDefault,
            'phone'=>Request::param('phone'),
            'region'=>Request::param('region'),
            'detailed'=>Request::param('detailed'),
            'name'=>Request::param('name')
        ];
        $res=Db::name('address')->insertGetId($data);
        if($res){
            return json_encode(['code'=>1,'msg'=>'添加地址成功！','data'=>$res],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'添加地址失败！','data'=>$res],JSON_UNESCAPED_UNICODE);
        }

    }



    /**
     * 通过token获取到用户收货地址
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAddressList()
    {
        //必须传入token
        if(!Request::param('token')){
            return json_encode(['code'=>0,'msg'=>'用户未登录！'],JSON_UNESCAPED_UNICODE);
        }
        if(!self::token(Request::param('token'))){
            return json_encode(['code'=>0,'msg'=>'用户未登录或用户不存在'],JSON_UNESCAPED_UNICODE);
        }

        $res=Db::table('address')->field('address_id as id,user_id as userId,status as isDefault,phone,region as addressRegion,detailed,name')
            ->where('user_id',$_SESSION['Auid'])
            ->select();

        if(count($res)>0){
            return json_encode(['code'=>1,'msg'=>'获取成功','data'=>$res],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'还没有收货地址'],JSON_UNESCAPED_UNICODE);
        }
    }



    /** 删除购物车
     * @return false|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
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
                Db::table('shoppinp_cart')->where('id',$value)->where('auid',$_SESSION['Auid'])
                                          ->where('data_type','<>',3)->delete();
            }
            $res=Db::table('shoppinp_cart')->where('auid',$_SESSION['Auid'])->where('data_type',1)->select();
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
     * @throws
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
        if(!Request::param('type')){
            $type=1;
        }


        session_id(Request::param('token'));
        session_start();
        if(!$_SESSION['Auid']){
            return json_encode(['code'=>0,'msg'=>'身份失效！'],JSON_UNESCAPED_UNICODE);
        }
        $type=Request::param('type')?Request::param('type'):1;

        if($type==2){
            $cx=Db::table('shoppinp_cart')
                ->where('goods_id',Request::param('goodsId'))
                ->where('specs_id',Request::param('specsId'))
                ->where('data_type',2)
                ->where('auid',$_SESSION['Auid'])->findOrEmpty();
            if(count($cx)>0){
                Db::table('shoppinp_cart')->where('id',$cx['id'])->delete();

                /*if($cx['data_type']==$type){
                    Db::table('shoppinp_cart')->where('id', $cx['id'])->setInc('number', Request::param('count'));
                    return json_encode(['code'=>1,'msg'=>'添加购物车成功！','data'=>$cx['id']],JSON_UNESCAPED_UNICODE);
                }else{
                    return json_encode(['code'=>1,'msg'=>'订单已存在！','data'=>$cx['id']],JSON_UNESCAPED_UNICODE);
                }*/
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
        $res=Db::table('shoppinp_cart')->where('auid',$_SESSION['Auid'])->where('data_type',1)->select();
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
                //Db::table('shoppinp_cart')->where('id',$value)->where('data_type',2)->where('auid',$_SESSION['Auid'])->delete();
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
    public function uidGetAddressList($auid)
    {
        $data=Db::table('address')->field('address_id as id,user_id as userId,status as isDefault,phone,region as addressRegion,detailed,name')->where('user_id',$auid)->select();
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
    public function getUserInfo($token)
    {
        if(!$token){
            return json_encode(['code'=>0,'msg'=>'缺少参数'],JSON_UNESCAPED_UNICODE);
        }
        session_id($token);
        session_start();
        if(!$_SESSION['Auid']){
            session_destroy();
            return json_encode(['code'=>0,'msg'=>'用户未登录','data'=>session_id()],JSON_UNESCAPED_UNICODE);
        }
        $data = file_get_contents('https://api.angeli.top/user.php?type=getUserInfo&auid='.$_SESSION['Auid']);
        $data=json_decode($data,true);
        if($data['data']['Auid']!='-1'){
            return json_encode($data['data'],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['code'=>0,'msg'=>'用户不存在','data'=>session_id()],JSON_UNESCAPED_UNICODE);
        }

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
}
