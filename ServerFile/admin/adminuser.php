<?php 
session_start();
$_SESSION['menu']=8;
include_once 'header.php';
$app=new angeli($config);
$dataList=$app->getAdminList();
$type=false;
if(isset($_GET['code'])){
	$type=true;
}


?>
<div class="col-md-12">
	  <div class="card  card-plain">
	    <div class="card-header">
	      <h4 class="card-title">管理员列表</h4>
	      <p class="category">所有的管理员均在此处</p>
	    </div>
		<div class="card-header">
			<a href="assets/html/addadmin.php" target="_blank">
			<button type="submit" class="btn btn-fill btn-primary">添加后台管理员</button>
			</a>
		</div>
	    <div class="card-body">
	      <div class="table-responsive">
	        <table class="table tablesorter " id="">
	          <thead class=" text-primary">
	            <tr>
	              <th>
	                ADMINID
	              </th>
	              <th>
	                管理员名称
	              </th>
	              <th>
	                绑定微信
	              </th>
	              <th class="text-center">
	                手机号码
	              </th>
				  <th>
				    备注
				  </th>
				  <th>
				    操作
				  </th>
	            </tr>
	          </thead>
	          <tbody>
				  <?php
				  	foreach($dataList as $value){
				  		echo '<tr>';
				  		echo '<td>'.$value['id'].'</td>';
				  		echo '<td>'.$value['name'].'</td>';
				  		echo '<td>'.$value['wxopenid'].'</td>';
				  		echo '<td class="text-center">'.$value['phone'].'</td>';
				  		echo '<td>'.$value['note'].'</td>';
				  		echo '<td><a href="javascript:edit('.$value['AuId'].');">编辑资料</a></td>';
				  		echo '</tr>';
				  	}
				  ?> 
	          </tbody>
	        </table>
	      </div>
	    </div>
	  </div>
	</div>
<script>
	function edit(id){
		console.log(id);
		layer.open({
		  type: 2,
		  title:'操作提现',
		  area: ['700px', '450px'],
		  fixed: false, //不固定
		  maxmin: true,
		  content: 'assets/html/edituser.php?auid='+id
		});
	}
</script>
<?php include_once 'footer.php'; ?>
<script>
	<?php 
	
		if($type){
			echo 'showadd("'.$_GET['code'].'");';
		}
	?>
	function showadd(){
		layer.open({
			type: 2,
			title:'操作提现',0.0
			area: ['450px', '700px'],
			fixed: true, //不固定
			maxmin: true,
			content: 'assets/html/addadmin.php'
		});
	}
	
	
</script>