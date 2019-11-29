<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">完善信息</block>
		</cu-custom>
		<view class="sexBox">
			<image class="iamgea" src="../../static/10.png"  :src="imgurl.man" mode="" style="margin-right: 62rpx;" @click="sex(1)"></image>
			<image class="iamgea" src="../../static/20.png"  :src="imgurl.woman" mode="" style="margin-left: 62rpx;" @click="sex(2)"></image>
			<text style="margin-right: 62rpx;">男</text>
			<text style="margin-left: 62rpx;">女</text>
		</view>
		<view class="input">
			<input type="text" value="" maxlength="12" placeholder="取一个响亮的名字" placeholder-style="color:#999;" class="inputtxt" @input="inpuing"/>
			<view style="margin-top: 30rpx;font-size:24rpx;color: #999;">
				{{tishi}}
			</view>
		</view>
		<view class="anniu">
			<button class="Angeli" @tap="login">保存</button>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				imgurl:{
					man:"../../static/11.png",
					woman:"../../static/21.png"
				},
				dataSex:1,
				name:"安个利用户",
				tishi:"用户名能修改的次数很少，谨慎填写",
				zhuce:false,
				phone:''
			}
		},
		onLoad:function(e){
			console.log(e.phone)
			this.phone=e.phone
		},
		methods: {
			login:function(){
				uni.showLoading({
					title: '正在注册'
				});
				console.log(this.name,this.dataSex,this.phone)
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/reg.php?type=reg', //仅为示例，并非真实接口地址。
					data: {
						username:this.name,
						gender:this.dataSex,
						phone:this.phone,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						
					},
					success: (res) => {
						console.log(res)
						uni.hideLoading();
						if(res.data.code=="1"){
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none',
								duration:2000,
								mask:true
							});
							server.userinfo=res.data.data;
							server.cookie=res.header['Set-Cookie'];
							setTimeout(function () {
								uni.redirectTo({
									url: '../Home/Home'
								})
							}, 1500);
						}
					},
					fail:function(){
						console.log('失败')
						uni.hideLoading();
						uni.showToast({
							title: "未知原因注册失败",
							position:'bottom',
							icon:'none',
							duration:2000,
							mask:true
						});
					}
				});
			},
			sex:function(e){
				this.dataSex=e
				
				if(e=="1"){
					this.imgurl.man="../../static/10.png"
					this.imgurl.woman="../../static/21.png"
				}else{
					this.imgurl.man="../../static/11.png"
					this.imgurl.woman="../../static/20.png"
				}
			},
			inpuing:function(e){
				this.name=e.detail.value;
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/reg.php?type=checkName&name='+this.name, //仅为示例，并非真实接口地址。
					data: {
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						
					},
					success: (res) => {
						if(res.data.code=="0"){
							this.tishi=res.data.msg
							this.zhuce=false
						}else{
							this.tishi=res.data.msg
							this.zhuce=true
						}
						console.log(this.zhuce)
					}
				});
				
			}
		}
	}
</script>

<style>
.input{
	height: 60rpx;
	margin: 80rpx 40rpx;
	border-bottom: 1px solid #EFEFF4;
	text-align: center;
}
.anniu{
	margin: 100rpx 40rpx;
}
.inputtxt{
	line-height: 60rpx;
	font-size: 28rpx;
	text-align: center;
}
.sexBox{
	display: grid;
	grid-template-columns: 50% 50%;
	margin-top: 145rpx;
	margin-left: 180rpx;
	margin-right: 180rpx;
	justify-items:center;
	align-items: start;
}
.iamgea{
	width: 128rpx;
	height: 128rpx;
}
page{
	background-color: #FFFFFF;
}
text{
	margin-top: 16rpx;
}
</style>
