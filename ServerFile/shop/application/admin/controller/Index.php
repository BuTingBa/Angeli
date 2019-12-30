<?php
/**
 *   Index.php
 *   名称：
 *   作者：不停（BuTing）
 *   联系：my@buting.cc
 *   时间：2019-12-10 16:46
 */
namespace app\admin\controller;
use think\Db;
use think\Request;
class Index extends Common
{
    /**
     * 控制台
     * @return mixed|void
     */
    public function index()
    {
        $this->assign('name', $_SESSION['name']);
        return $this->fetch();
    }

    public function addGoods()
    {
        $this->assign('name', $_SESSION['name']);
        return $this->fetch();
    }

    public function upgoods()
    {

        if (!$this->request->param('goodsName')){
            $this->error('请输入商品名称啊！','addgoods');
        }
        if (!$this->request->param('price')){
            $this->error('请输入商品价格啊！','addgoods');
        }
        $file=$this->request->file('mImage');//商品主图
        $file=$file->getInfo();
        $mImageUrl=$this->upImage($file['tmp_name']);
        $goodsImageList=$this->request->file('imagelist');//轮播图
        $ImageList='';
        foreach($goodsImageList as $imgL){
            $file=$imgL->getInfo();
            $ImageList.=$this->upImage($file['tmp_name']).',';
        }
        $ImageList=rtrim($ImageList, ',');
        $goodsInfo=$this->request->file('goodsinfo');//详细图片
        $goodsInfoList='';
        foreach($goodsInfo as $imgi){
            $file=$imgi->getInfo();
            $goodsInfoList.=$this->upImage($file['tmp_name']).',';
        }
        $goodsInfoList=rtrim($goodsInfoList, ',');

        $data=[
            'name'=>$this->request->param('goodsName'),
            'goods_number'=>$this->request->param('kucun'),
            'current_price'=>$this->request->param('price'),
            'purchase_price'=>$this->request->param('purchase_price'),
            'image'=>$mImageUrl,
            'goods_desc_image_list'=>$goodsInfoList,
            'image_list'=>$ImageList,
            'sale_date'=>$this->request->param('sale_date'),
            'addtime'=>time()
        ];
        $res=Db::name('goods')->data($data)->insert();
        if($res==1){
            $this->success('添加成功！','addgoods');
        }else{
            $this->error('添加失败！','addgoods',$res);
        }
    }

    public function goods($keyword='')
    {
        $data=Db::table('goods')->select();
        $this->assign('name', $_SESSION['name']);
        $this->assign('data',$data);
        return $this->fetch();
    }


}
