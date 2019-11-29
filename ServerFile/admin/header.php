<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once '../config.php';
require_once 'api/angeli.class.php';
session_start();
if($_SERVER['SERVER_NAME']=='admin.io'||$_SERVER['SERVER_NAME']=='172.16.10.26'){
	//header("refresh:3;url='http://admin.angeli.top'");
	$app=new angeli($config);
	$data=$app->getAdminInfo('oYlOMs5bN-3Yhj0vOfcImu_yknS8');
	if(!$data){
		die('你没有权限登录安个利的后台！！');
	}else{
		//var_dump($data);
		$_SESSION['id']=$data['id'];
		$_SESSION['name']=$data['name'];
		$_SESSION['wxopenid']=$data['wxopenid'];
		$_SESSION['phone']=$data['phone'];
		$_SESSION['note']=$data['note'];
		setcookie("adminid",$data['id'],time()+3600*24*30,'/');
	}
}

if(!$_SESSION['id']||!$_COOKIE["adminid"]){
	header('location:login.html'); 
}
if($_SESSION['id']!=$_COOKIE["adminid"]){
	die("登录已过期,请重新登录");
	header('location:login.html'); 
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   安个利后台管理
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="assets/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="assets/demo/demo.css" rel="stylesheet" />
  <link href="assets/admin.css" rel="stylesheet" />

</head>
<body class="">
  <div class="wrapper">
    <div class="sidebar">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
    -->
      <div class="sidebar-wrapper">
        <div class="logo">
          <a href="javascript:void(0)" class="simple-text logo-mini">
            Ali
          </a>
          <a href="javascript:void(0)" class="simple-text logo-normal">
            安个利后台管理
          </a>
        </div>
        <ul class="nav">
          <li <?php echo $_SESSION['menu']==0?'class="active"':'';?>>
            <a href="index.php">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>首页仪表盘</p>
            </a>
          </li>
          <li <?php echo $_SESSION['menu']==1?'class="active"':'';?>>
            <a href="set.php">
              <i class="tim-icons icon-settings"></i>
              <p>基础设置</p>
            </a>
          </li>
          <li <?php echo $_SESSION['menu']==2?'class="active"':'';?>>
            <a href="setclass.php">
              <i class="tim-icons icon-bullet-list-67"></i>
              <p>分类管理</p>
            </a>
          </li>
          <li <?php echo $_SESSION['menu']==3?'class="active"':'';?>>
            <a href="message.php">
              <i class="tim-icons icon-bell-55"></i>
              <p>消息管理</p>
            </a>
          </li>
          <li <?php echo $_SESSION['menu']==4?'class="active"':'';?>>
            <a href="postlist.php">
              <i class="tim-icons icon-paper"></i>
              <p>帖子管理</p>
            </a>
          </li>
		  <li <?php echo $_SESSION['menu']==10?'class="active"':'';?>>
		    <a href="paihangbang.php">
		      <i class="tim-icons icon-chart-bar-32"></i>
		      <p>种草排行</p>
		    </a>
		  </li>
		  <li <?php echo $_SESSION['menu']==9?'class="active"':'';?>>
		    <a href="jubao.php">
		      <i class="tim-icons icon-alert-circle-exc"></i>
		      <p>举报管理</p>
		    </a>
		  </li>
          <li <?php echo $_SESSION['menu']==5?'class="active"':'';?>>
            <a href="user.php">
              <i class="tim-icons icon-single-02"></i>
              <p>用户管理</p>
            </a>
          </li>
          <li <?php echo $_SESSION['menu']==6?'class="active"':'';?>>
            <a href="moneyout.php">
              <i class="tim-icons icon-coins"></i>
              <p>提现管理</p>
            </a>
          </li>
          <li <?php echo $_SESSION['menu']==7?'class="active"':'';?>>
            <a href="bill.php">
              <i class="tim-icons icon-cart"></i>
              <p>支付订单</p>
            </a>
          </li>
          <li <?php echo $_SESSION['menu']==8?'class="active"':'';?>>
            <a href="adminuser.php">
              <i class="tim-icons icon-badge"></i>
              <p>管理员设置</p>
            </a>
          </li>
		  <li >
		    <a href="api/login.php?type=logout">
		      <i class="tim-icons icon-simple-remove"></i>
		      <p>退出</p>
		    </a>
		  </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:void(0)" id="chartLinePurple">ANGELI</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
              <li class="search-bar input-group">
                <button class="btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal"><i class="tim-icons icon-zoom-split" ></i>
                  <span class="d-lg-none d-md-block">输入关键字搜索</span>
                </button>
              </li>
<!--              <li class="dropdown nav-item">
                <a href="javascript:void(0)" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="notification d-none d-lg-block d-xl-block"></div>
                  <i class="tim-icons icon-sound-wave"></i>
                  <p class="d-lg-none">
                    Notifications
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                  <li class="nav-link"><a href="#" class="nav-item dropdown-item">Mike John responded to your email</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">You have 5 more tasks</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Your friend Michael is in town</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another notification</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another one</a></li>
                </ul>
              </li> -->
              <li class="dropdown nav-item">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="photo">
                    <img src="../assets/img/anime3.png" alt="Profile Photo">
                  </div>
                  <b class="caret d-none d-lg-block d-xl-block"></b>
                  <p class="d-lg-none">
                    <?php echo $_SESSION['name']; ?>
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-navbar">
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">管理员组</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">个人信息</a></li>
                  <li class="dropdown-divider"></li>
                  <li class="nav-link"><a href="api/login.php?type=logout" class="nav-item dropdown-item">退出登录</a></li>
                </ul>
              </li>
              <li class="separator d-lg-none"></li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="这个搜索暂时并无卵用">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="tim-icons icon-simple-remove"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
<div class="content">
<div class="row">	  