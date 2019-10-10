<?php 
require_once '../config.php';
require_once 'api/angeli.class.php';

if($_SERVER['SERVER_NAME']=='admin.io'||$_SERVER['SERVER_NAME']=='172.16.10.31'){
	$app=new angeli($config);
	$data=$app->getAdminInfo('oYlOMs5bN-3Yhj0vOfcImu_yknS8');
	if(!$data){
		die('你没有权限登录安个利的后台！！');
	}else{
		//var_dump($data);
		session_start();
		$_SESSION['id']=$data['id'];
		$_SESSION['name']=$data['name'];
		$_SESSION['wxopenid']=$data['wxopenid'];
		$_SESSION['phone']=$data['phone'];
		$_SESSION['note']=$data['note'];
		setcookie("adminid",$data['id'],time()+3600*24*30,'/');
	}
}

session_start();
if(!$_SESSION['id']||!$_COOKIE["adminid"]){
	header('location:http://admin.angeli.top/login.html'); 
}
if($_SESSION['id']!=$_COOKIE["adminid"]){
	die("登录已过期,请重新登录");
	header('location:http://admin.angeli.top/login.html'); 
}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>安个利管理后台</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="layui/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="layui/style/admin.css" media="all">
</head>
<body class="layui-layout-body">
  
  <div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
      <div class="layui-header">
        <!-- 头部区域 -->
        <ul class="layui-nav layui-layout-left">
          <li class="layui-nav-item layadmin-flexible" lay-unselect>
            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
              <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="http://www.layui.com/admin/" target="_blank" title="前台">
              <i class="layui-icon layui-icon-website"></i>
            </a>
          </li>
          <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;" layadmin-event="refresh" title="刷新">
              <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search" layadmin-event="serach" lay-action="template/search.html?keywords="> 
          </li>
        </ul>
        <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
          
          <li class="layui-nav-item" lay-unselect>
            <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">
              <i class="layui-icon layui-icon-notice"></i>  
              
              <!-- 如果有新消息，则显示小圆点 -->
              <span class="layui-badge-dot"></span>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="theme">
              <i class="layui-icon layui-icon-theme"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="note">
              <i class="layui-icon layui-icon-note"></i>
            </a>
          </li>
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="fullscreen">
              <i class="layui-icon layui-icon-screen-full"></i>
            </a>
          </li>
          <li class="layui-nav-item" lay-unselect>
            <a href="javascript:;">
              <cite><?php echo $_SESSION['name']; ?></cite>
            </a>
            <dl class="layui-nav-child">
              <dd><a lay-href="set/user/info.html">基本资料</a></dd>
              <dd><a lay-href="set/user/password.html">修改绑定微信</a></dd>
              <hr>
              <dd layadmin-event="logout" style="text-align: center;"><a>退出</a></dd>
            </dl>
          </li>
          
          <li class="layui-nav-item layui-hide-xs" lay-unselect>
            <a href="javascript:;" layadmin-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a>
          </li>
          <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
            <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
          </li>
        </ul>
      </div>
      
      <!-- 侧边菜单 -->
      <div class="layui-side layui-side-menu">
        <div class="layui-side-scroll">
          <div class="layui-logo" lay-href="html/console.php">
            <img src="static/image/logo.png" class="angeli-logo">
			
          </div>
          
          <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
            <li data-name="home" class="layui-nav-item layui-nav-itemed">
                <a lay-href="html/console.php">
					<i class="layui-icon layui-icon-home"></i>
					<cite>仪表盘</cite>
				</a>
            </li>
			<li data-name="appset" class="layui-nav-item">
			    <a href="javascript:;" lay-tips="组件" lay-direction="2">
			      <i class="layui-icon layui-icon-app"></i>
			      <cite>应用管理</cite>
			    </a>
				<dl class="layui-nav-child">
					<dd data-name="button">
					  <a lay-href="component/button/index.html">基本配置</a>
					</dd>
					<dd data-name="button">
					  <a lay-href="html/button/index.html">分类管理</a>
					</dd>
					<dd data-name="button">
					  <a lay-href="html/button/index.html">消息管理</a>
					</dd>
					<dd data-name="button">
					  <a lay-href="html/button/index.html">更新日志</a>
					</dd>
					<dd data-name="button">
					  <a lay-href="https://mp.weixin.qq.com/">小程序后台</a>
					</dd>
				</dl>
			</li>
            <li data-name="component" class="layui-nav-item">
              <a href="javascript:;" lay-tips="组件" lay-direction="2">
                <i class="layui-icon layui-icon-component"></i>
                <cite>内容管理</cite>
              </a>
              <dl class="layui-nav-child">
              	<dd data-name="button">
              	  <a lay-href="component/button/index.html">待审核的帖子</a>
              	</dd>
              	<dd data-name="button">
              	  <a lay-href="html/button/index.html">帖子数据</a>
              	</dd>
              	<dd data-name="button">
              	  <a lay-href="html/button/index.html">全部帖子</a>
              	</dd>
              </dl>
            </li>
			<li data-name="user" class="layui-nav-item">
			  <a href="javascript:;" lay-tips="用户" lay-direction="2">
			    <i class="layui-icon layui-icon-rmb"></i>
			    <cite>资金管理</cite>
			  </a>
			  <dl class="layui-nav-child">
			    <dd>
			      <a lay-href="user/user/list.html">支付订单</a>
			    </dd>
			    <dd>
			      <a lay-href="user/administrators/list.html">提现申请</a>
			    </dd>
				<dd>
				  <a lay-href="user/administrators/list.html">提现记录</a>
				</dd>
				<dd>
				  <a lay-href="user/administrators/list.html">积分动态</a>
				</dd>
			  </dl>
			</li>
            <li data-name="user" class="layui-nav-item">
              <a href="javascript:;" lay-tips="用户" lay-direction="2">
                <i class="layui-icon layui-icon-user"></i>
                <cite>用户管理</cite>
              </a>
              <dl class="layui-nav-child">
                <dd>
                  <a lay-href="html/userList.php">用户列表</a>
                </dd>
                <dd>
                  <a lay-href="user/administrators/list.html">黑名单列表</a>
                </dd>
              </dl>
            </li>
            <li data-name="set" class="layui-nav-item">
              <a href="javascript:;" lay-tips="设置" lay-direction="2">
                <i class="layui-icon layui-icon-set"></i>
                <cite>系统设置</cite>
              </a>
              <dl class="layui-nav-child">
                <dd>
                  <a lay-href="user/administrators/list.html">管理员列表</a>
                </dd>
                <dd>
                  <a lay-href="user/administrators/list.html">改绑微信</a>
                </dd>
				<dd>
				  <a lay-href="user/administrators/list.html">操作日志</a>
				</dd>
              </dl>
            </li>
            <li data-name="get" class="layui-nav-item">
              <a href="javascript:;" lay-href="//www.layui.com/admin/#get" lay-tips="授权" lay-direction="2">
                <i class="layui-icon layui-icon-auz"></i>
                <cite>授权</cite>
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- 页面标签 -->
      <div class="layadmin-pagetabs" id="LAY_app_tabs">
        <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-down">
          <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
            <li class="layui-nav-item" lay-unselect>
              <a href="javascript:;"></a>
              <dl class="layui-nav-child layui-anim-fadein">
                <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
              </dl>
            </li>
          </ul>
        </div>
        <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
          <ul class="layui-tab-title" id="LAY_app_tabsheader">
            <li lay-id="home/console.html" lay-attr="home/console.html" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
          </ul>
        </div>
      </div>
      
      
      <!-- 主体内容 -->
      <div class="layui-body" id="LAY_app_body">
        <div class="layadmin-tabsbody-item layui-show">
          <iframe src="html/console.php" frameborder="0" class="layadmin-iframe"></iframe>
        </div>
      </div>
      
      <!-- 辅助元素，一般用于移动设备下遮罩 -->
      <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
  </div>

  <script src="layui/layui/layui.js"></script>
  <script>
  layui.config({
    base: 'layui/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use('index');
  </script>
</body>
</html>


