<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('api/getGoodsConcise/[:date]/[:token]','api/index/getGoodsList')->allowCrossDomain();
Route::get('api/getGoodsInfo/[:id]/[:token]','api/index/getGoodsInfo')->allowCrossDomain();
Route::get('api/getUserInfo/[:id]/[:token]','api/index/getUserInfo')->allowCrossDomain();
Route::post('api/preOrder','api/index/settleAccounts')->allowCrossDomain();
Route::get('api/[:token]','api/index/index')->allowCrossDomain();
return [

];
