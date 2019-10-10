
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>首页</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="../layui/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="../layui/style/admin.css" media="all">
</head>
<body>
  
  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md8">
        <div class="layui-row layui-col-space15">
          <div class="layui-col-md6">
            <div class="layui-card">
              <div class="layui-card-header">快捷方式</div>
              <div class="layui-card-body">
                
                <div class="layui-carousel layadmin-carousel layadmin-shortcut">
                  <div carousel-item>
                    <ul class="layui-row layui-col-space10">
                      <li class="layui-col-xs3">
                        <a lay-href="home/homepage1.html">
                          <i class="layui-icon layui-icon-console"></i>
                          <cite>小程序后台</cite>
                        </a>
                      </li>
                      <li class="layui-col-xs3">
                        <a lay-href="home/homepage2.html">
                          <i class="layui-icon layui-icon-chart"></i>
                          <cite>数据监控</cite>
                        </a>
                      </li>
                      <li class="layui-col-xs3">
                        <a lay-href="component/layer/list.html">
                          <i class="layui-icon layui-icon-template-1"></i>
                          <cite>用户列表</cite>
                        </a>
                      </li>
                      <li class="layui-col-xs3">
                        <a layadmin-event="im">
                          <i class="layui-icon layui-icon-chat"></i>
                          <cite>帖子列表</cite>
                        </a>
                      </li>
                      <li class="layui-col-xs3">
                        <a lay-href="component/progress/index.html">
                          <i class="layui-icon layui-icon-find-fill"></i>
                          <cite>提现申请</cite>
                        </a>
                      </li>
                      <li class="layui-col-xs3">
                        <a lay-href="app/workorder/list.html">
                          <i class="layui-icon layui-icon-survey"></i>
                          <cite>积分动态</cite>
                        </a>
                      </li>
                      <li class="layui-col-xs3">
                        <a lay-href="user/user/list.html">
                          <i class="layui-icon layui-icon-user"></i>
                          <cite>转账记录</cite>
                        </a>
                      </li>
                      <li class="layui-col-xs3">
                        <a lay-href="set/system/website.html">
                          <i class="layui-icon layui-icon-set"></i>
                          <cite>帖子数据</cite>
                        </a>
                      </li>
                    </ul>
                    <ul class="layui-row layui-col-space10">
                      <li class="layui-col-xs3">
                        <a lay-href="set/user/info.html">
                          <i class="layui-icon layui-icon-set"></i>
                          <cite>操作日志</cite>
                        </a>
                      </li>
                      <li class="layui-col-xs3">
                        <a lay-href="set/user/info.html">
                          <i class="layui-icon layui-icon-set"></i>
                          <cite>更新日志</cite>
                        </a>
                      </li>
                      
                    </ul>
                    
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="layui-col-md6">
            <div class="layui-card">
              <div class="layui-card-header">待办事项</div>
              <div class="layui-card-body">

                <div class="layui-carousel layadmin-carousel layadmin-backlog">
                  <div carousel-item>
                    <ul class="layui-row layui-col-space10">
                      <li class="layui-col-xs6">
                        <a lay-href="app/content/comment.html" class="layadmin-backlog-body">
                          <h3>待审帖子</h3>
                          <p><cite>66</cite></p>
                        </a>
                      </li>
                      <li class="layui-col-xs6">
                        <a lay-href="app/forum/list.html" class="layadmin-backlog-body">
                          <h3>提现申请</h3>
                          <p><cite>12</cite></p>
                        </a>
                      </li>
                      <li class="layui-col-xs6">
                        <a lay-href="template/goodslist.html" class="layadmin-backlog-body">
                          <h3>举报信息</h3>
                          <p><cite>99</cite></p>
                        </a>
                      </li>
                      <li class="layui-col-xs6">
                        <a href="javascript:;" onclick="layer.tips('不跳转', this, {tips: 3});" class="layadmin-backlog-body">
                          <h3>新增用户</h3>
                          <p><cite>20</cite></p>
                        </a>
                      </li>
                    </ul>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="layui-col-md12">
            <!-- <div class="layui-card">
              <div class="layui-card-header">数据概览</div>
              <div class="layui-card-body">
                
                <div class="layui-carousel layadmin-carousel layadmin-dataview" data-anim="fade" lay-filter="LAY-index-dataview">
                  <div carousel-item id="LAY-index-dataview">
                    <div><i class="layui-icon layui-icon-loading1 layadmin-loading"></i></div>
                    <div></div>
                    <div></div>
                  </div>
                </div>
                
              </div>
            </div> -->
            <div class="layui-card">
              <div class="layui-tab layui-tab-brief layadmin-latestData">
                <ul class="layui-tab-title">
                  <li class="layui-this">最新帖子</li>
                  <li>提现申请</li>
                </ul>
                <div class="layui-tab-content">
                  <div class="layui-tab-item layui-show">
                    <table id="LAY-index-topSearch"></table>
                  </div>
                  <div class="layui-tab-item">
                    <table id="LAY-index-topCard"></table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="layui-col-md4">
		  <div class="layui-card">
		    <div class="layui-card-header">备注留言板</div>
		    <div class="layui-card-body">
		      <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea>
		    </div>
		  </div>
        <div class="layui-card">
          <div class="layui-card-header">基础信息</div>
          <div class="layui-card-body layui-text">
            <table class="layui-table">
              <colgroup>
                <col width="100">
                <col>
              </colgroup>
              <tbody>
                <tr>
                  <td>服务器</td>
                  <td>
                    <script type="text/html" template>
                      Windows server2012 R2 data center 64bit
                    </script>
                  </td>
                </tr>
                <tr>
                  <td>运行环境</td>
                  <td>
                    <script type="text/html" template>
						PHP/<?php echo PHP_VERSION;?> <?php echo $_SERVER["SERVER_SOFTWARE"];?>
                    </script>
                 </td>
                </tr>
                <tr>
                  <td>IP/CPU/RAM</td>
                  <td>47.107.64.83 &nbsp;/&nbsp; 2 vCPU 2 GiB</td>
                </tr>
               
              </tbody>
            </table>
          </div>
        </div>
        
		
		<div class="layui-card">
		  <div class="layui-card-header">实时监控</div>
		  <div class="layui-card-body layadmin-takerates">
		    <div class="layui-progress" lay-showPercent="yes">
		      <h3>CPU使用率</h3>
		      <div class="layui-progress-bar" lay-percent="50%" id="CPU"></div>
		    </div>
		    <div class="layui-progress" lay-showPercent="yes">
		      <h3>内存占用率</h3>
		      <div class="layui-progress-bar" lay-percent="50%" id="RAM"></div>
		    </div>
		  </div>
		</div>
        <div class="layui-card">
          <div class="layui-card-header">服务器</div>
          <div class="layui-card-body layadmin-takerates">
            <div class="layui-progress" lay-showPercent="yes">
              <h3>服务器磁盘</h3>
			  <?php  
					$max=disk_total_space(dirname(__FILE__));
					$keyong=disk_free_space(dirname(__FILE__));
					$bi=$keyong/$max;
					$val=100*sprintf("%.2f", 1-$bi);
					//$val=82;
			   ?>
              <div class="layui-progress-bar <?php echo $val>80?'layui-bg-red':'' ?>" lay-percent="<?php echo $val.'%'; ?>"></div>
            </div>
            <div class="layui-progress" lay-showPercent="yes">
              <h3>数据库大小</h3>
              <div class="layui-progress-bar" lay-percent="90%"></div>
            </div>
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
  }).use(['index', 'console','element'],function(){
	  var $ = layui.jquery;
	  var element = layui.element;
	  
	  
	  //轮询服务器状态信息
	  setInterval(function(){
		$.get("../api/systemInfo.php",function(data,status){
			console.log(data);
			data=JSON.parse(data);
		    $("#CPU").attr("lay-percent",data.CPU+"%");
			$("#RAM").attr("lay-percent",data.RAM+"%");
			if(data.CPU>90){
				$("#CPU").addClass("layui-bg-red");
			}else{
				$("#CPU").removeClass("layui-bg-red");
			}
			if(data.RAM>90){
				$("#RAM").addClass("layui-bg-red");
			}else{
				$("#RAM").removeClass("layui-bg-red");
			}
			element.render('progress');
		});
	  }, 1000);
  });
  
  
  </script>
</body>
</html>
<?php

function outNote($txt){
	$filename="static/homeNote.txt";
	$handle=fopen($filename,"a+");
	$str=fwrite($handle,$txt.PHP_EOL);
	fclose($handle);
}



?>
