<?php 
session_start();
$_SESSION['menu']=11;
include_once 'header.php';
$app=new angeli($config);

if(isset($_GET['access']) && isset($_GET['type']) && isset($_GET['id']) && $_GET['access']=='del'){
	$nohave=true;
	$d=$app->delCommentsAndReply($_GET['type'],$_GET['id']);
	if($d){
		$text='已删除成功';
	}else{
		$text='删除失败！';
	}

}else{
	$dataList=$app->getAllPlList();
}


?>
<div class="col-md-12">
	  <div class="card  card-plain">
	    <div class="card-header">
	      <h4 class="card-title">评论列表</h4>
	      <p class="category">评论以及回复的内容均在此处</p>
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
	                ID
	              </th>
	              <th>
	                评论用户(昵称/AUID)
	              </th>
	              <th >
	                评论内容
	              </th>
				  <th class="text-center">
				    所在帖子ID
				  </th>
	              <th class="text-center">
	                评论时间
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
				  		echo '<td>'.$value['user']['AuthorName'].'/'.$value['user']['Auid'].'</td>';
				  		echo '<td>'.$value['content'].'</td>';
				  		echo '<td class="text-center">'.$value['postId'].'</td>';
				  		echo '<td>'.$value['time'].'</td>';
				  		echo '<td><a href="pl.php?access=del&type='.$value["commentsType"].'&id='.$value["id"].'">删除评论</a></td>';
				  		echo '</tr>';
				  	}
				  ?> 
	          </tbody>
	        </table>
	      </div>
	    </div>
	  </div>
	</div>

<?php 

include_once 'footer.php';
 if(isset($text)){
	echo "<script>function tiaozhuan (url) {window.location.href ='pl.php';}</script>" ;
 	echo '<script>demo.notify("'.$text.'");</script>';
	echo "<script>";
	echo "setTimeout(tiaozhuan,800);";
	echo "</script>";
 }
 
 ?>
