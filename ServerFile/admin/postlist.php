<?php 
session_start();
$_SESSION['menu']=4;
include_once 'header.php';
$app=new angeli($config);
$type=empty($_GET['type'])?'new':$_GET['type'];
$tag=empty($_GET['tag'])?'1':$_GET['tag'];
$app=new angeli($config);
$page=empty($_GET['p'])?'1':$_GET['p'];
$page=$page<1?1:$page;
$sort=empty($_GET['sort'])?'1':$_GET['sort'];
$dataList=$app->getPosts($type,20,$page,$tag);

?>
	<div class="col-md-12">
	  <div class="card  card-plain">
	    <div class="card-header">
	      <h4 class="card-title">帖子列表</h4>
	      <p class="category">显示所有帖子，包括已经被锁定、删除、屏蔽的帖子。</p>
	    </div>
		<div class="card-header">
			<button type="submit" class="btn btn-fill btn-primary">全部帖子</button>
			<button type="submit" class="btn btn-fill btn-primary">待审核帖子</button>
			<button type="submit" class="btn btn-fill btn-primary">异常帖子</button>
			<input type="text" class="hang" placeholder="搜索帖子" value="" name="keyword">
		</div>
	    <div class="card-body">
	      <div class="table-responsive">
	        <table class="table tablesorter " id="">
	          <thead class=" text-primary">
	            <tr>
	              <th>
	                帖子ID
	              </th>
	              <th>
	                发帖人
	              </th>
	              <th>
	                帖子内容
	              </th>
	              <th class="text-center">
	                缩略图
	              </th>
				  <th class="text-center">
				    话题
				  </th>
				  <th class="text-center">
				    总种草数
				  </th >
				  <th class="text-center">
				    状态
				  </th>
				  <th class="text-center">
				    审核
				  </th>
				  <th>
				    发布时间
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
				  		echo '<td>'.$value['PostsId'].'</td>';
				  		echo '<td>'.$value['AuthorInfo']['AuthorName'].'</td>';
				  		echo '<td style="max-width: 600px;">'.$value['Content'].'</td>';
				  		echo '<td class="text-center"><img src="'.$value['PictureId'][0].'" height="100" width="100"></td>';
				  		echo '<td class="text-center">'.$value['Tag']['ClassName'].'</td>';
				  		echo '<td class="text-center">'.$value['ZhongcaoCount'].'</td>';
				  		echo '<td class="text-center">'.$value['IsLock'].'</td>';
				  		echo '<td class="text-center">'.$value['Review'].'</td>';
				  		echo '<td>'.$value['PsotDate'].'</td>';
				  		echo '<td><a href="javascript:edit('.$value['PostsId'].');">编辑</a></td>';
				  		echo '</tr>';
				  	}
				  ?> 
	          </tbody>
	        </table>
	      </div>
	    </div>
	  </div>
	  <div class="admin-navtab">
	  		  <ul class="admin-tabul">
	  			  <li><a href="postlist.php">上一页</a></li>
	  			  <li><a href="postlist.php?p=<?php echo $page+1; ?>&sort=<?php echo $sort; ?>">下一页</a></li>
	  		  </ul>
	  </div>
	</div>
	
<script>
	function edit(id){
		console.log(id);
		layer.open({
		  type: 2,
		  title:'编辑帖子',
		  area: ['700px', '450px'],
		  fixed: false, //不固定
		  maxmin: true,
		  content: 'assets/html/editpost.php?postid='+id
		});
	}
</script>

<?php include_once 'footer.php'; ?>

