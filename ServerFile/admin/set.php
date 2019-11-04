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
                    <div class="col-md-5 pr-md-1">
                      <div class="form-group">
                        <label>Company (disabled)</label>
                        <input type="text" class="form-control" disabled="" placeholder="Company" value="Creative Code Inc.">
                      </div>
                    </div>
                    <div class="col-md-3 px-md-1">
                      <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" placeholder="Username" value="michael23">
                      </div>
                    </div>
                    <div class="col-md-4 pl-md-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" placeholder="mike@email.com">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" placeholder="Company" value="Mike">
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" placeholder="Last Name" value="Andrew">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" placeholder="Home Address" value="Bld Mihail Kogalniceanu, nr. 8 Bl 1, Sc 1, Ap 09">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>City</label>
                        <input type="text" class="form-control" placeholder="City" value="Mike">
                      </div>
                    </div>
                    <div class="col-md-4 px-md-1">
                      <div class="form-group">
                        <label>Country</label>
                        <input type="text" class="form-control" placeholder="Country" value="Andrew">
                      </div>
                    </div>
                    <div class="col-md-4 pl-md-1">
                      <div class="form-group">
                        <label>Postal Code</label>
                        <input type="number" class="form-control" placeholder="ZIP Code">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
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