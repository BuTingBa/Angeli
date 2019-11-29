<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">用户登录</block>
		</cu-custom>
		<view  :style="[{top:CustomBar + 'px'}]">
			<!--  #ifdef MP -->
				<viee class="logoa">
					<image src="../../static/logo.png" mode="aspectFit" style="width: 200px;height: 200upx;margin-top: 100upx;"></image>
				</viee>
				<view class="padding flex flex-direction" style="margin-top: 60upx;">
					<button open-type="getUserInfo" @getuserinfo="mpGetUserInfo" class="cu-btn bg-red margin-tb-sm lg">微信登录</button>
					<button open-type="getPhoneNumber"  @getphonenumber="phone" class="cu-btn bg-grey lg">已有账号，账号密码登录</button>
				</view>
				<!-- 弹出层- -->
				<view class="cu-modal" :class="modalName=='Image'?'show':''" >
					<view class="cu-dialog" >
						<view class="bg-img Tanceng" >
							<view class="cu-bar justify-end text-white">
								<view class="action" >
									<text class="cuIcon-close "></text>
								</view>
							</view>
							<view style="margin-bottom: 60upx;">授权绑定你的手机号码</view>
						</view>
						<view class="cu-bar">
							<button class="action margin-0 flex-sub  solid-left "  hover-class="none" @click="hid">取消</button>
							<button class="action margin-0 flex-sub  solid-left " open-type="getPhoneNumber"  @getphonenumber="phone">授权</button>
						</view>
					</view>
				</view>
				<!-- 弹出层结束 -->
			<!--  #endif -->
			
			<!--  #ifdef APP-PLUS -->
				<view class="reg">
					<view class="inputbox">
						<view class="from"><input type="number" value="" placeholder="手机号" @input="inputphone"/></view>
						<view class="fromA">
							<input class="yzm" type="number" value="" placeholder="验证码" @input="inputpassword" />
							<view class="getCode" @tap="getTokenCode">{{codeTitle}}</view>
						</view>
					</view>
					<view class="anniu">
						<button class="Angeli" @tap="login">立即登录</button>
						
					</view>
					<view class="lineBox">
						<view class="line"></view>
						<text>其它方式登录</text>
						<view class="line"></view>
					</view>
					<view class="autologin">
						<image class="loginImage" src="../../static/wechat.png" mode="aspectFit" @click="getWechatOauth"></image>
						<!-- <image class="loginImage" src="../../static/qq.png" mode="aspectFit" @click="getQQOauth"></image> -->
					</view>
					</view>
					
				</view>
				<view class="rega">
					登录即视为同意<text @click="getpage(2)" style="color: #1CBBB4;">\n《安个利用户协议》</text>和<text @click="getpage(3)" style="color: #1CBBB4;">《安个利用户隐私协议》</text>
			<!--  #endif -->
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				xcx:true,
				userInfo:[],
				phoneifo:"",
				disabled:true,
				reg:false,
				modalName: null,
				user:"",
				password:"",
				timer:"",
				num:58,
				codeTitle:"获取验证码",
				code:true
				
			}
		},
		onLoad() {
			uni.getSystemInfo({
			    success: function (res) {
					var systemjson={
						phonebrand:res.brand+res.model,
						phonesystem:res.system
					}
					server.system=JSON.stringify(systemjson);
					console.log(server.system)
			    }
			});
		},
		methods: {
			getpage:function(id){
				if(id==1){
					uni.navigateTo({
						url:"../edit/edit?type=4"
					})
				}
				if(id==2){
					uni.navigateTo({
						url:"../edit/edit?type=6"
					})
				}
				if(id==3){
					uni.navigateTo({
						url:"../edit/edit?type=5"
					})
				}
				
			},
			setVal:function(token,user){
				try {
				    uni.setStorageSync('token', token);
				} catch (e) {
				    console.log(e)
				}
				try {
				   uni.setStorageSync('user', user);
				} catch (e) {
				    console.log(e)
				}
			},
			getWechatOauth:function(){
				uni.getProvider({
					service: 'oauth',
					success: function (res) {
						if (~res.provider.indexOf('weixin')) {
							uni.login({
								provider: 'weixin',
								success: function (loginRes) {
									uni.request({
										method:'POST',
										url: 'https://api.angeli.top/reg.php?type=appwxlogin', 
										data: {
											unionid: loginRes.authResult.unionid,
										},
										header: {
											'content-type': 'application/x-www-form-urlencoded'
										},
										success: (res) => {
											console.log(res);
											if(res.data.code=="0"){
												server.usersk=res.data.data.session_key
												uni.showToast({
													title: "你的微信还没有注册，请注册先",
													position:'bottom',
													icon:'none'
												});
												return;
												
											}else if(res.data.code=="1" ||res.data.code=="2"){
												server.userinfo=res.data.data;
												server.token=res.data.token;
												console.log('得到的token：'+res.data.token)
												try {
												    uni.setStorageSync('token',res.data.token);
												} catch (e) {
												    console.log(e)
												}
												try {
												   uni.setStorageSync('user',res.data.data);
												} catch (e) {
												    console.log(e)
												}
												
												if(res.data.code=="2"){
													uni.showToast({
														title: res.data.msg,
														position:'bottom',
														icon:'none'
													})
												}else{
													if(server.userinfo.VIPEndTime>0){
														uni.showToast({
															title: '欢迎VIP：'+server.userinfo.UserName,
															position:'bottom',
															icon:'none'
														})
													}else{
														uni.showToast({
															title: '欢迎你,'+server.userinfo.UserName,
															position:'bottom',
															icon:'none'
														})
													}
													
												}
												
												setTimeout(function () {
													uni.redirectTo({
														url: '../Home/Home'
													})
												}, 2000);
											}
										},
										fail:(src) =>{
											uni.showToast({
												title: "未知原因，登录失败！",
												position:'bottom',
												icon:'none'
											})
										},
									});
								}
							});
						}
					}
				})
			},
			getQQOauth:function(){
				uni.showToast({
					title: '手机QQ暂时停止服务',
					position:'bottom',
					icon:'none',
					position:'center'
				});
				
				
				
				/* uni.getProvider({
					service: 'oauth',
					success: function (res) {
						console.log(res.provider)
						if (~res.provider.indexOf('qq')) {
							uni.login({
								provider: 'qq',
								success: function (loginRes) {
									console.log(JSON.stringify(loginRes));
								}
							});
						}
					}
				}) */
			},
			getTokenCode:function(){
				uni.showLoading({
					title: '获取中'
				});
				if(this.code==true){
					if(!this.user){
						uni.showToast({
							title: '请输入手机号',
							position:'bottom',
							icon:'none',
							position:'center'
						});
						return;
					}
					this.codeTitle="59s"
					this.code=false
					this.timer = setInterval(() => {
					  //当num等于100时清除定时器
						this.num--;
						if (this.num == 0) {
							this.code=true
						    clearInterval(this.timer);
							this.num=58;
							this.codeTitle="获取验证码"
							
						}else{
							this.codeTitle=this.num+"s"
							this.code=false
						}
					}, 1000);
					uni.request({
						method:'POST',
						url: 'https://api.angeli.top/reg.php?type=getCode',//获取验证码
						data: {
							phone:this.user,
						},
						header: {
							'content-type': 'application/x-www-form-urlencoded',
						},
						success: (res) => {
							console.log(res);
							if(res.data.code=="1"){
								//server.cookie=res.header['Set-Cookie'];
								console.log("获取验证码")
								uni.showToast({
									title: '验证码发送成功！',
									position:'bottom',
									icon:'none',
									position:'center'
								});
								server.token=res.data.token;
								uni.setStorageSync('token', server.token);
								console.log('获取验证码cookie',server.cookie);
							}else{
								uni.showToast({
									title: '验证码发送失败！',
									position:'bottom',
									icon:'none',
									position:'center'
								});
								clearInterval(this.timer);
								this.code=true
								clearInterval(this.timer);
								this.num=58;
								this.codeTitle="获取验证码"
							}
							
						},
						fail:function(){
							this.code=true
							clearInterval(this.timer);
							this.num=58;
							this.codeTitle="获取验证码"
							uni.showToast({
								title: '验证码获取失败！请尝试其他方式登录',
								position:'bottom',
								icon:'none',
								position:'center'
							});
						}
					});
				}else{
					console.log("非法获取验证码")
				}
				uni.hideLoading();
			},
			login:function(){
				if(!this.user || !this.password){
					uni.showToast({
						title: '请输入正确的手机号和验证码！',
						position:'bottom',
						icon:'none',
						position:'center'
					});
					return;
				}
				console.log('提交的cookie：',server.cookie);
				uni.request({
					method:'POST',
					url: 'https://api.angeli.top/reg.php?type=codeLogin', //仅为示例，并非真实接口地址。
					data: {
						phone: this.user,
						code:this.password,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						console.log(res);
						if(res.data.code=="1"){//登陆成功
							server.userinfo=res.data.data;
							server.token=res.data.token;
							uni.setStorageSync('token', server.token);
							uni.setStorageSync('user', server.userinfo);
						}
						if(res.data.code=="2"){//新用户注册
							uni.navigateTo({
								url: '../newUser/newUser?phone='+res.data.data
							});
						}else{
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none',
								position:'center'
							});
						}
					}
				});
				
				
				/* uni.request({
					method:'POST',
					url: 'https://api.angeli.top/reg.php?type=login', //仅为示例，并非真实接口地址。
					data: {
						phone: this.user,
						password:this.password
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						console.log(res.data)
						if(res.data.code=="1"){//登陆成功
							server.userinfo=res.data.data;
							server.cookie=res.header['Set-Cookie'];
							uni.setStorage({
								key: 'cookie',
								data: res.header['Set-Cookie'],
								success: function () {
									console.log("储存cookie成功！")
									uni.setStorage({
										key: 'userinfo',
										data: res.data.data,
										success: function () {
											uni.showToast({
												title: res.data.msg,
												position:'bottom',
												icon:'none',
												duration:2000,
												mask:true
											});
											
											if(res.data.code=="1"){
												setTimeout(function () {
													uni.redirectTo({
														url: '../Home/Home'
													})
												}, 1500);
											}
										}
									});
								}
							});
							
							
						}else{
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none',
								position:'center'
							});
						}
					}
				}); */
			},
			appreg:function(){
				uni.showToast({
					title: '内测期间关闭APP注册通道',
					position:'bottom',
					icon:'none'
				});
			},
			inputphone:function(e){
				this.user=e.detail.value
				console.log(this.user);
			},
			inputpassword:function(e){
				this.password=e.detail.value;
				console.log(this.password);
			},
			hid:function(){
				this.modalName="";
				
			},
			phone:function(e){
				uni.request({
					method:'POST',
					url: 'https://api.angeli.top/WeChat/demo.php', //仅为示例，并非真实接口地址。
					data: {
						iv: e.detail.iv,
						data:e.detail.encryptedData,
						sk:server.usersk
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						this.phoneifo = res.data.phoneNumber;
						uni.request({
							method:'POST',
							url: 'https://api.angeli.top/reg.php?type=wxreg', //仅为示例，并非真实接口地址。
							data: {
								unionid: this.userInfo.unionId,
								username:this.userInfo.nickName,
								avatarUrl:this.userInfo.avatarUrl,
								gender:this.userInfo.gender,
								phone:this.phoneifo,
								openid:this.userInfo.openId,
								tuijianId:server.tgid
							},
							header: {
								'content-type': 'application/x-www-form-urlencoded',
								'Cookie':server.cookie
							},
							success: (res) => {
								console.log(res);
								if(res.data.code=="1"){
									uni.showToast({
										title: res.data.msg,
										position:'bottom'
									});
									setTimeout(function () {
										uni.redirectTo({
											url: '../Home/Home'
										})
									}, 2000);
								}else{
									uni.showModal({
										title: '注册失败！',
										content: '错误原因' + res.data,
										showCancel: false
									})
								}
							}
						});
						
					}
				});
			},
			mpGetUserInfo(result) {
				console.log(result)
				if (result.detail.errMsg !== 'getUserInfo:ok') {
					uni.showModal({
						title: '获取用户信息失败',
						content: '错误原因' + result.detail.errMsg,
						showCancel: false
					});
					return;
				}
				uni.request({
					method:'POST',
					url: 'https://api.angeli.top/WeChat/demo.php', //仅为示例，并非真实接口地址。
					data: {
						iv: result.detail.iv,
						data:result.detail.encryptedData,
						sk:server.usersk
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.openId){
							this.reg = true;
							this.disabled = false;
							this.userInfo = res.data;
							console.log('userinfo||', this.userInfo);
							this.modalName = 'Image'
							
						}else{
							uni.showToast({
								title: '错误：'+res.data,
								position:'bottom',
								icon:'none'
							});
						}
					}
				});
				
				
			}
		}
	}
</script>

<style>
page{
	background-color: #FFFFFF;
	text-align: center;
}
.loginImage{
	height: 87upx;
	width: 87upx;
}
.autologin{
	margin: 0upx 240upx;
	display: flex;
	justify-content:center;
	align-items:center;
	
}
.line{
	height: 2upx;
	width: 90upx;
	background-color: #999999;
	
}
.lineBox{
	margin-top: 300upx;
	margin-right: 165upx;
	margin-left:165upx;
	margin-bottom: 56upx;
	display: flex;
	justify-content:space-between;
	align-items:center;
}
.yzm{
	width: 70%;
}
.logoa{
	width: 100%;
	text-align: center;
	margin-top: 200upx;
}
.anniu{
	margin: 100rpx 40rpx;
}
.inputbox{
	margin: 60rpx 40rpx;
	text-align:left;
}
.from{
	margin-top: 20rpx;
	height: 80rpx;
	border-bottom: 1px solid #EFEFF4;
}
.fromA{
	margin-top: 20rpx;
	height: 80rpx;
	border-bottom: 1px solid #EFEFF4;
	display: flex;
	justify-content:space-between;
}
.reg{
	margin: 30upx;
}
.rega{
	position: absolute;
	width: 100%;
	
	bottom: 30upx;
	margin: 20rpx auto;
	font-size: 26upx;
	color: #C0C0C0;
}
.getCode{
	width: 160rpx;
	height: 50rpx;
	background:rgba(121,196,152,0.3);
	text-align: center;
	line-height: 50rpx;
	font-size:24rpx;
	border-radius: 25rpx;
	align-items:center;
	margin-top: 20rpx;
}
input{
	/* border: none; */
	width: 100%;
	height: 80rpx;
	
}
button::after {
	  border: none;
	  outline: none; 
	  margin: 0upx;
	  padding: 0upx;
	}
</style>
