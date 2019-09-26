<?php
	require_once '../../config.php';
	require_once '../api/angeli.class.php';
	$app=new angeli($config);
	$userList=$app->getUserList(1,50);
	//var_dump($userList);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>用户列表</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="../layui/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="../layui/style/admin.css" media="all">
</head>
<body>

  <div class="layui-card layadmin-header">
    <div class="layui-breadcrumb" lay-filter="breadcrumb">
      <a lay-href="">主页</a>
      <a><cite>组件</cite></a>
      <a><cite>数据表格</cite></a>
      <a><cite>开启头部工具栏</cite></a>
    </div>
  </div>
  
  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">
				<select name="quiz2">
				  <option value="">请选择市</option>
				  <option value="杭州">杭州</option>
				  <option value="宁波" disabled="">宁波</option>
				  <option value="温州">温州</option>
				  <option value="温州">台州</option>
				  <option value="温州">绍兴</option>
				</select>
		  </div>
          <div class="layui-card-body">
			<table class="layui-table" lay-skin="line">
			  <colgroup>
			    <col width="150">
			    <col width="150">
			    <col width="200">
			    <col>
			  </colgroup>
			  <thead>
			    <tr>
			      <th>ID</th>
			      <th>昵称</th>
			      <th>电话</th>
			      <th>性别</th>
				  <th>会员</th>
				  <th>种草数量</th>
				  <th>安个利币</th>
				  <th>充值金额</th>
				  <th>创建时间</th>
				  <th>操作</th>
			    </tr> 
			  </thead>
			  <tbody>
				<?php 
					foreach($userList as $value){
						echo '<tr>';
						echo '<td>'.$value['AuId'].'</td>';
						echo '<td>'.$value['NickName'].'</td>';
						echo '<td>'.$value['Phone'].'</td>';
						echo '<td>'.$value['Gender'].'</td>';
						echo '<td>'.$value['UserType'].'</td>';
						echo '<td>'.$value['ZhongcaoCount'].'</td>';
						echo '<td>'.$value['Points'].'</td>';
						echo '<td>'.$value['AuId'].'</td>';
						echo '<td>'.$value['DateCreated'].'</td>';
						echo '<td><a id="edit">编辑</a></td>';
						echo '</tr>';
					}
				?>  
			  </tbody>
			</table>   
			
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../layui/layui/layui.js?t=1"></script>
  <script>
  layui.config({
    base: '../layui/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use('layer', function(){ //独立版的layer无需执行这一句
	  var $ = layui.jquery; //独立版的layer无需执行这一句
	  $("#edit").click(function(){
	      layer.tab({
	        area: ['600px', '300px'],
	        tab: [{
	          title: 'TAB1', 
	          content: '内容1'
	        }, {
	          title: 'TAB2', 
	          content: '内容2'
	        }, {
	          title: 'TAB3', 
	          content: '内容3'
	        }]
	      });
	  });
	  
	});

  </script>
</body>
</html>