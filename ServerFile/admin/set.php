<?php 
session_start();
$_SESSION['menu']=1;
include_once 'header.php';
$app=new angeli($config);
?>
<div class="col-md-9">
            <div class="card">
              <div class="card-header">
                <h5 class="title">系统基础设置</h5>
              </div>
			  <form action="api/admin.php" method="post">
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-9 pr-md-1">
                      <div class="form-group">
                        <label>小程序首页启动图</label>
                        <input type="text" class="form-control"  placeholder="Company" value="Creative Code Inc.">
                      </div>
                    </div>
                    <div class="col-md-3 px-md-1">
                      <div class="form-group">
                        <label>_</label>
                        <input type="button" class="form-control" placeholder="Username" value="上传图片">
                      </div>
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-md-2 pr-md-1">
                      <div class="form-group">
                        <label>当前版本号</label>
                        <input type="text" class="form-control"  placeholder="Company" value="1.22">
                      </div>
                    </div>
                    <div class="col-md-10 px-md-1">
                      <div class="form-group">
                        <label>下载地址</label>
                        <input type="text" class="form-control" placeholder="Username" value="上传图片">
                      </div>
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>应用启动弹窗文字通知</label>
                        <textarea rows="4" cols="80" class="form-control" placeholder="填写的话,所有用户每次启动应用都会强制弹窗提示该内容.如果不设置则不显示." name="hometxt" ></textarea>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-footer">
					<button type="submit" class="btn btn-fill btn-primary">保存设置</button>
              </div>
			  </form>
            </div>
          </div>
 
	<div class="col-md-3">
        <div class="card card-user">
			<div class="card-header">
			  <h5 class="title">应用启动图</h5>
			</div>
			<img src="https://sz.oss.data.angeli.top/system/20190822.jpg" alt="" class="homeimage">
			
        </div>
    </div>


<?php include_once 'footer.php'; ?>