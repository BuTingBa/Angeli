<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">设置</block>
		</cu-custom>
		<view class="menuListBox">
			<view class="menusolid"></view>
			<view class="menuItem" @tap="getInfo">
				<view class="menuIcon aicon-userinfo"></view>
				<view class="menuTiele">个人资料</view>
				<view class="menuRight"></view>
			</view>
			<view class="menusolid"></view>
			<view class="menuItem" @tap="userAnquan">
				<view class="menuIcon aicon-anquan"></view>
				<view class="menuTiele">账号安全</view>
				<view class="menuRight"></view>
			</view>
			<view class="menusolid"></view>
			<view class="menuItem">
				<view class="menuIcon aicon-showAd"></view>
				<view class="menuTiele">显示广告</view>
				<view class="menuRight" @tap="guanbi"><switch @change="SetShadow" :checked="true" :disabled="isShowAD?true:false" :class="shadow?'checked':''" color="#39B54A"></switch></view>
			</view>
			<view class="menusolid"></view>
		</view>
		<view class="menuClass"><text>通用设置</text></view>
		<view class="menuListBox">
			<view class="menusolid"></view>
			<view class="menuItem" @click="getAbout">
				<view class="menuIcon aicon-about"></view>
				<view class="menuTiele">关于安个利</view>
				<view class="menuRight"></view>
			</view>
			<view class="menusolid"></view>
			<view class="menuItem">
				<view class="menuIcon aicon-help"></view>
				<view class="menuTiele"><button class="kefuanniu" open-type="contact">帮助与客服</button></view>
				<view class="menuRight"></view>
			</view>
			<view class="menusolid"></view>
			<view class="menuItem" @click="getyinsi">
				<view class="menuIcon aicon-yinsi"></view>
				<view class="menuTiele">隐私政策</view>
				<view class="menuRight"></view>
			</view>
			<view class="menusolid"></view>
			<view class="menuItem" @tap="logout">
				<view class="menuIcon aicon-logout"></view>
				<view class="menuTiele">退出登录</view>
				<view class="menuRight"></view>
			</view>
			<view class="menusolid"></view>
		</view>
		<!-- 弹出层 -->
		<view class="cu-modal" :class="modalName=='Image'?'show':''" >
			<view class="cu-dialog" >
				<view class="bg-img Tanceng" >
					<view class="cu-bar justify-end text-white">
						<view class="action" @click="modalName=''">
							<text class="cuIcon-close "></text>
						</view>
					</view>
					<view style="margin-bottom: 60upx;">开通VIP，即刻免广告！</view>
				</view>
				<view class="cu-bar">
					<button class="action margin-0 flex-sub  solid-left" @click="kaitong">开通VIP</button>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				shadow:true,
				isShowAD:true,
				modalName:''
			}
		},
		onLoad:function(){
			uni.getStorage({
			    key: 'showAD',
			    success: (res) => {
					console.log(res.data)
			        if(res.data=='true'){
						this.isShowAD=true 
						this.shadow = true
					}else{
						this.isShowAD=false
						this.shadow = false
					}
			    }
			});
			if(server.userinfo.VIPEndTime>0){
				this.isShowAD=false;
			}else{
				this.isShowAD=true;
				this.shadow = false
			}	
		},
		methods: {
			getyinsi:function(){
				uni.navigateTo({
					url: '../edit/edit?type=5'
				})
			},
			kaitong:function(){
				this.modalName=''
				uni.navigateTo({
					url: '../vip/vip'
				})
			},
			userAnquan:function(){
				uni.navigateTo({
					url: '../anquan/anquan'
				});
			},
			logout:function(){
				uni.clearStorage();
				uni.showToast({
					title: "即将退出登录",
					position:'bottom',
					icon:'none',
					position:'center'
				})
				server.userinfo=[];
				server.cookie="";
				setTimeout(function () {
					uni.redirectTo({
						url: '../Home/Home'
					})
				}, 2000);
			},
			SetShadow(e) {
				this.shadow = e.detail.value
				if(this.shadow==false){
					console.log("先冲会员")
					if(server.userinfo.VIPEndTime>0){
						uni.setStorage({
						    key: 'showAD',
						    data: 'false',
						    success: function () {
						        console.log('success');
						    }
						});
					}else{
						this.modalName = 'Image'
						uni.setStorage({
						    key: 'showAD',
						    data: 'true',
						    success: function () {
						        console.log('success');
						    }
						});
					}
				}else{
					uni.setStorage({
					    key: 'showAD',
					    data: 'true',
					    success: function () {
					        console.log('success');
					    }
					});
				}
				
				console.log(this.shadow)
			},
			guanbi:function(){
				if(server.userinfo.VIPEndTime<=0){
					this.modalName = 'Image'	
				}
			},
			getInfo:function(){
				uni.navigateTo({
					url: '../editinfo/editinfo'
				});
			},
			getAbout:function(){
				uni.navigateTo({
					url:'../about/about'
				})
			}
		}
	}
</script>

<style>
page{
	background-color: #F7F8FA;
}
.menuClass{
	font-size: 32rpx;
	margin: 15rpx 37rpx;
}
.kefuanniu{
	margin: 0upx;
	padding: 0upx;
	line-height: normal;
	background-color: transparent;
	outline: none;
	font-size: 28upx;
	left: 0upx;
	text-align: left;
}
.kefuanniu::after{
	border:none;
}
input{
	outline:none;
	border:none;
	list-style: none;
}
</style>
