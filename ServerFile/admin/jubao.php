<?php 
session_start();
$_SESSION['menu']=9;
include_once 'header.php';
$app=new angeli($config);
$dataList=$app->getJubaoList();



?>
<div class="col-md-12">
	  <div class="card  card-plain">
	    <div class="card-header">
	      <h4 class="card-title">举报列表</h4>
	      <p class="category">举报信息将保留于此</p>
	    </div>
		<!-- <div class="card-header">
			<button type="submit" class="btn btn-fill btn-primary">手动提现</button>
		</div> -->
	    <div class="card-body">
	      <div class="table-responsive">
	        <table class="table tablesorter " id="">
	          <thead class=" text-primary">
	            <tr>
	              <th>
	                举报ID
	              </th>
	              <th>
	                举报用户(昵称/AUID)
	              </th>
	              <th >
	                被举报用户(昵称/AUID)
	              </th>
	              <th class="text-center">
	                举报帖子ID
	              </th>
				  <th>
				    举报理由
				  </th>
				  <th>
				    举报时间
				  </th >
                <th>
                    当前状态
                </th >
				  <th>
				    操作
				  </th>
	            </tr>
	          </thead>
	          <tbody>
				  <?php
				  	foreach($dataList as $value){
				  	    $s=$value['stastu']=='2'?'已封贴':'正常显示';
				  		echo '<tr>';
				  		echo '<td>'.$value['id'].'</td>';
				  		echo '<td>'.$value['jubaoid']['AuthorName'].'/'.$value['jubaoid']['Auid'].'</td>';
				  		echo '<td>'.$value['beijubaoid']['AuthorName'].'/'.$value['beijubaoid']['Auid'].'</td>';
				  		echo '<td class="text-center">'.$value['postid'].'</td>';
				  		echo '<td>'.$value['reason'].'</td>';
                        echo '<td>'.$value['addtime'].'</td>';
                        echo '<td>'.$s.'</td>';
				  		echo '<td><a href="javascript:edit('.$value['postid'].');">操作帖子</a></td>';
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