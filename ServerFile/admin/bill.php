<?php 
session_start();
$_SESSION['menu']=4;
include_once 'header.php';
$app=new angeli($config);
$type=empty($_GET['type'])?'ok':$_GET['type'];
$tag=empty($_GET['tag'])?'1':$_GET['tag'];
$app=new angeli($config);
$page=empty($_GET['p'])?'1':$_GET['p'];
$page=$page<1?1:$page;
$sort=empty($_GET['sort'])?'all':$_GET['sort'];
$dataList=$app->getPayList($sort,20,$page,$type);

?>

<div class="col-md-12">
	  <div class="card  card-plain">
	    <div class="card-header">
	      <h4 class="card-title">支付订单</h4>
	      <p class="category">显示小程序充值、VIP等充值订单</p>
	    </div>
		<div class="card-header">
			<a href="bill.php?sort=ok">
			<button type="button" class="btn btn-fill btn-primary">已支付订单</button></a>
			<a href="bill.php?sort=wei"><button type="button" class="btn btn-fill btn-primary">未支付订单</button></a>
			<button type="button" class="btn btn-fill btn-primary">支付排行榜</button>
			<a href="bill.php?sort=<?php echo $sort;?>&type=del"><button type="button" class="btn btn-fill btn-primary">清空一天前无效订单</button></a>
			<!-- <input type="text" class="hang" placeholder="搜索订单" value="" name="keyword"> -->
			
		</div>
	    <div class="card-body">
	      <div class="table-responsive">
	        <table class="table tablesorter " id="">
	          <thead class=" text-primary">
	            <tr>
					<th>
					  充值用户
					</th>
	              <th>
	                订单号
	              </th>
	              <!-- <th>
	                openID
	              </th> -->
	              <th>
	                微信订单号
	              </th>
	              <th>
	                支付商品
	              </th>
				  <th class="text-center">
				    数量
				  </th>
				  <th class="text-center">
				    充值金额
				  </th >
				  <th class="text-center">
					累计充值
				  </th >
				  <th class="text-center">
				    订单时间
				  </th>
				  <th>
				    支付状态
				  </th>
				  <!-- <th>
				    操作
				  </th> -->
	            </tr>
	          </thead>
	          <tbody>
				  <?php
					if(!is_array($dataList)){
						$nohave=true;
					}else{
						foreach($dataList as $value){
							echo '<tr>';
							echo '<td>'.$value['auid']['AuthorName'].'</td>';
							echo '<td>'.$value['orderId'].'</td>';
							//echo '<td>'.$value['wxOpenId'].'</td>';
							echo '<td style="max-width: 600px;">'.$value['wxOrderId'].'</td>';
							echo '<td>'.$value['name'].'</td>';
							echo '<td class="text-center">'.$value['number'].'</td>';
							echo '<td class="text-center">'.$value['payFee'].'</td>';
							echo '<td class="text-center">'.$value['leiji'].'</td>';
							echo '<td class="text-center">'.$value['orderTime'].'</td>';
							echo '<td>'.$value['payStatus'].'</td>';
							//echo '<td><a href="javascript:edit('.$value['PostsId'].');">编辑</a></td>';
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
	  			  <li><a href="bill.php?p=<?php echo $page-1; ?>&sort=<?php echo $sort; ?>">上一页</a></li>
	  			  <li><a href="bill.php?p=<?php echo $page+1; ?>&sort=<?php echo $sort; ?>">下一页</a></li>
	  		  </ul>
	  </div>
	</div>
	
<script>
	function edit(id){
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
<?php include_once 'footer.php'; 
if($type=='del'){
	show("已经清空之前无效的订单");
}
?>
