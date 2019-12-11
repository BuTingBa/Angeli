<?php 
session_start();
$_SESSION['menu']=10;
include_once 'header.php';
$app=new angeli($config);

$sdefaultDate = date("Y-m-d");
$first=1;
$w=date('w',strtotime($sdefaultDate));
$beginThisweek=strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days');
$endtemp=date('Y-m-d',$beginThisweek);
$endThisweek=strtotime("$endtemp +7 days");
if(empty($_GET['stime']) || empty($_GET['etime'])){
	$dataList=$app->getWeekTop();
}else{
	$stime=strtotime($_GET['stime']);
	$etime=strtotime($_GET['etime']);
	$dataList=$app->getWeekTop($stime,$etime);
	$beginThisweek=$stime;
	$endThisweek=$etime;
}
?>
<div class="col-md-12">
	  <div class="card  card-plain">
	    <div class="card-header">
	      <h4 class="card-title">种草排行榜TOP100</h4>
	      <p class="category">当前种草前一百排行榜</p>
	    </div>
		<div class="card-header">
			<form action="paihangbang.php" method="get">
			<span>查询历史排行榜</span>
			<input type="text" class="hang" placeholder="开始时间" value="<?php echo date('YmdHis',$beginThisweek);?>" name="stime">
			<input type="text" class="hang" placeholder="结束时间" value="<?php echo date('YmdHis',$endThisweek);?>" name="etime">
			<button type="submit" class="btn btn-fill btn-primary">查询</button>
			<form>
		</div>
	    <div class="card-body">
	      <div class="table-responsive">
	        <table class="table tablesorter " id="">
	          <thead class=" text-primary">
	            <tr>
	              <th>
	                排名
	              </th>
	              <th>
	                用户名称
	              </th>
				  <th>
				    用户AUID
				  </th>
	              <th class="text-center">
	                种草数
	              </th>
	            </tr>
	          </thead>
	          <tbody>
				  <?php
				  	foreach($dataList as $value){
						$index++;
				  		echo '<tr>';
				  		echo '<td>'.$index.'</td>';
				  		echo '<td>'.$value['AuId']['AuthorName'].'</td>';
						echo '<td>'.$value['AuId']['Auid'].'</td>';
				  		echo '<td class="text-center">'.$value['api'].'</td>';
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
		  content: 'assets/html/editpost.php?postid='+id
		});
	}
</script>
<?php include_once 'footer.php'; ?>