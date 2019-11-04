<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">{{title}}</block>
		</cu-custom>
		<view class="body">
			<view v-if="type=='1'" class="upname">
				<text style="color: #888888;"> 更改昵称，普通用户一年只能修改两次，你还能修改{{upNameNumber}}次</text>
				<input type="text" value="" maxlength="7" focus="true" @input="inputing" class="in" />
				<view class="dibu">
					<button class="Angeli" v-if="upNameNumber>0" @click="setName">确定修改</button>
				</view>
			</view>
			<view class="upname" v-if="type=='2'" >
				 <textarea placeholder-style="color:#888888" placeholder="用一句牛逼的话来描述自己,最多输入60个字" @input="inputing" maxlength="60"/>
				 <view class="dibu">
				 	<button class="Angeli" @click="setms">确定修改</button>
				 </view>
			</view>
			<view class="upname" v-if="type=='3'" >
				 
			</view>
			<view class="upname" v-if="type=='4'" >
				 <web-view src="https://api.angeli.top/html/updata.html"></web-view>
			</view>
			<view class="upname" v-if="type=='5'" >
				 <web-view src="https://api.angeli.top/html/ys.html"></web-view>
			</view>
			<view class="upname" v-if="type=='6'" >
				 <web-view src="https://api.angeli.top/html/user.html"></web-view>
			</view>
			
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				type:1,
				title:"安个利",
				upNameNumber:0,
				newName:''
			}
		},
		onLoad:function(val) {
			console.log(val.type)
			this.type=val.type;
			if(val.type=='1'){
				this.title="修改名字"
				this.getNumber();
			}
			if(val.type=='2'){
				this.title="个性签名"
			}
			if(val.type=='3'){
				this.title="等级特权"
			}
			if(val.type=='4'){
				this.title="关于安个利"
			}
			if(val.type=='5'){
				this.title="安个利隐私政策"
			}
			if(val.type=='6'){
				this.title="安个利用户协议"
			}
		},
		methods: {
			inputing:function(e){
				this.newName=e.detail.value;
				console.log(e.detail)
			},
			setName:function(){
				uni.showLoading({
					title: '正在提交数据'
				});
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/user.php?type=setName', //仅为示例，并非真实接口地址。
					data: {
						auid: server.userinfo.Auid,
						newName:this.newName
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						uni.showToast({
							title: res.data.msg,
							position:'bottom',
							icon:'none'
						})
						if(res.data.code=="1"){
							setTimeout(function () {
								uni.hideLoading()
								uni.navigateBack({
								    delta: 1
								});
							}, 1500);
						}
						
					},
					complete() {
						uni.hideLoading();
					}
				});
			},
			getNumber:function(){
				
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/user.php?type=getNameCount', //仅为示例，并非真实接口地址。
					data: {
						auid: server.userinfo.Auid
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.code=='1'){
							this.upNameNumber=res.data.data;
							console.log(this.upNameNumber,res.data.data)
						}
					}
				});
			},
			setms:function(){
				uni.showLoading({
					title: '正在提交数据'
				});
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/user.php?type=setms', //仅为示例，并非真实接口地址。
					data: {
						auid: server.userinfo.Auid,
						ms:this.newName
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						uni.showToast({
							title: res.data.msg,
							position:'bottom',
							icon:'none'
						})
						if(res.data.code=="1"){
							setTimeout(function () {
								uni.hideLoading()
								uni.navigateBack({
								    delta: 1
								});
							}, 1500);
						}
					},
					complete() {
						uni.hideLoading();
					}
				});
			}
		}
	}
</script>

<style>
	.textarea{
		width: 100%;
	}
.body{
	background-color: #FFFFFF;
}
.an{
	width: 100%;
}
.dibu{
	position: absolute;
	width: 90%;
	bottom: 50upx;
	margin: 0upx 20upx;
}
.in{
	color: #000000;
	font-size: 28upx;
	height: 32upx;
	line-height: 32upx;
	margin-top: 10upx;
}
.upname{
	padding: 20upx 20upx;
	font-size: 26upx;
	
}
</style>
