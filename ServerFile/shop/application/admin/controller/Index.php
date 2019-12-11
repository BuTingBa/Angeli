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
        $request = Request::instance();
        var_dump($request->file('file'));
        exit();
    }
}
