<?php 
session_start();
$_SESSION['menu']=6;
include_once 'header.php';
$app=new angeli($config);
$dataList=$app->getTixian();
?>
<div class="col-md-12">
	  <div class="card  card-plain">
	    <div class="card-header">
	      <h4 class="card-title">提现申请列表</h4>
	      <p class="category">申请提现的人列表将显示在此。仅显示可以提现的用户，已经扣除了服务费用。</p>
	    </div>
		<div class="card-header">
			<button type="submit" class="btn btn-fill btn-primary">手动提现</button>
		</div>
	    <div class="card-body">
	      <div class="table-responsive">
	        <table class="table tablesorter " id="">
	          <thead class=" text-primary">
	            <tr>
	              <th>
	                TXID
	              </th>
	              <th>
	                提现用户(昵称/AUID)
	              </th>
	              <th class="text-center">
	                提现安个利币
	              </th>
	              <th class="text-center">
	                提现金额
	              </th>
				  <th>
				    提现到
				  </th>
				  <th>
				    账户
				  </th >
				  <th class="text-center">
				    提现状态
				  </th>
				  <th>
				    操作员
				  </th>
				  <th>
				    申请时间
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
				  		echo '<td>'.$value['auid']['AuthorName'].'/'.$value['auid']['Auid'].'</td>';
				  		echo '<td class="text-center">'.$value['jifen'].'</td>';
				  		echo '<td class="text-center">'.$value['fee'].'</td>';
				  		echo '<td>'.$value['tixianto'].'</td>';
				  		echo '<td>'.$value['openid'].'</td>';
				  		echo '<td class="text-center">'.$value['status'].'</td>';
				  		echo '<td >'.$value['operator'].'</td>';
				  		echo '<td>'.$value['addtime'].'</td>';
				  		echo '<td><a href="javascript:edit('.$value['AuId'].');">提现操作</a></td>';
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