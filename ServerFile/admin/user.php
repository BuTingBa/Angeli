<?php 
session_start();
$_SESSION['menu']=5;
include_once 'header.php';
$type=empty($_GET['type'])?'all':$_GET['type'];
$app=new angeli($config);
$page=empty($_GET['p'])?'1':$_GET['p'];
$page=$page<1?1:$page;
$sort=empty($_GET['sort'])?'1':$_GET['sort'];

switch ($type)
{
	case 'all':
		$userList=$app->getUserList($page,50,$sort);
		break;
	case 'search':
		if(empty($_GET['keyword'])){
			$userList=$app->getUserList($page,50,$sort);
		}else{
			$userList=$app->autoSearchUser($_GET['keyword']);
		}
		break;
	default:
		
		break;
}

?>
	<div class="col-md-12">
	  <div class="card  card-plain">
	    <div class="card-header">
	      <h4 class="card-title">用户列表</h4>
	      <p class="category">显示所有用户，包括已删除、黑名单、禁止登录的用户</p>
	    </div>
		<div class="card-header">
			<form action="user.php" method="get">
			<input type="text" class="hang" placeholder="用户昵称/用户手机号/用户id"  name="keyword">
			<button type="submit" class="btn btn-fill btn-primary" name="type" value="search">搜索用户</button>
			</form>
		</div>
	    <div class="card-body">
	      <div class="table-responsive">
	        <table class="table tablesorter " id="">
	          <thead class=" text-primary">
	            <tr>
	              <th><a class="morenfont" href="user.php?p=<?php echo $page-1; ?>&sort=<?php echo $sort==9?10:9; ?>">
				    AUID↕</a>
	              </th>
	              <th>
	                用户昵称
	              </th>
	              <th class="text-center">
	                电话
	              </th>
	              <th class="text-center">
	                性别
	              </th>
				  <th class="text-center"><a class="morenfont" href="user.php?p=<?php echo $page-1; ?>&sort=<?php echo $sort==7?8:7; ?>">
				    会员↕</a>
				  </th>
				  <th class="text-center"><a class="morenfont" href="user.php?p=<?php echo $page-1; ?>&sort=<?php echo $sort==5?6:5; ?>">
				    总种草数↕</a>
				  </th >
				  <th class="text-center"><a class="morenfont" href="user.php?p=<?php echo $page-1; ?>&sort=<?php echo $sort==3?4:3; ?>">
				    安个利币↕</a>
				  </th>
				  <th class="text-center">
				    状态
				  </th>
				  <th><a class="morenfont" href="user.php?p=<?php echo $page-1; ?>&sort=<?php echo $sort==1?2:1; ?>">
				    创建时间↕</a>
				  </th>
				  <th>
				    操作
				  </th>
	            </tr>
	          </thead>
	          <tbody>
				  <?php
					if(!is_array($userList)){
						$nohave=true;
					}else{
						$nohave=false;
						foreach($userList as $value){
							echo '<tr>';
							echo '<td>'.$value['AuId'].'</td>';
							echo '<td>'.$value['NickName'].'</td>';
							echo '<td class="text-center">'.$value['Phone'].'</td>';
							echo '<td class="text-center">'.$value['Gender'].'</td>';
							echo '<td class="text-center">'.$value['UserType'].'</td>';
							echo '<td class="text-center">'.$value['ZhongcaoCount'].'</td>';
							echo '<td class="text-center">'.$value['Points'].'</td>';
							echo '<td class="text-center">'.$value['Status'].'</td>';
							echo '<td>'.$value['DateCreated'].'</td>';
							echo '<td><a href="javascript:edit('.$value['AuId'].');">编辑</a></td>';
							echo '</tr>';
						}
					}
				  ?> 
	          </tbody>
	        </table>
	      </div>
	    </div>
	  </div>
	  <div class="admin-navtab">
		  <ul class="admin-tabul">
			  <li><a href="user.php?p=<?php echo $page-1; ?>&sort=<?php echo $sort; ?>">上一页</a></li>
			  <li><a href="user.php?p=<?php echo $page+1; ?>&sort=<?php echo $sort; ?>">下一页</a></li>
		  </ul>
	  </div>
	</div>
<script>
	function edit(id){
		//demo.notify('编辑用户资料')
		console.log(id);
		layer.open({
		  type: 2,
		  title:'用户编辑',
		  area: ['700px', '450px'],
		  fixed: false, //不固定
		  maxmin: true,
		  content: 'assets/html/edituser.php?auid='+id
		});
	}
</script>
<?php include_once 'footer.php';?>
<?php 
if($nohave){
	echo '<script>demo.notify("没有查询到用户");</script>';
}
?>

