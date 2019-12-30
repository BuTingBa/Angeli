<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">关于</block>
		</cu-custom>
		<view class="logo">
			<view @tap="num++"><image src="../../static/angeli.png" mode="" style="width: 200rpx;height: 132rpx;" ></image></view>
			<view style="margin-top: 27rpx;"><text style="font-size: 28rpx;color: #363636;">Version {{Version}}</text></view>
		</view>
		<view class="menuList">
			
			<view class="menuList-2c" @click="getpage(1)">
				<view class="menuTiele">更新日志</view>
				<view class="jiantou"></view>
			</view>
			<view class="menusolid nei"></view>
			<view class="menuList-2c" @click="showmail">
				<view class="menuTiele">投诉维权</view>
				<view class="jiantou"></view>
			</view>
			<view class="menusolid nei"></view>
			<view class="menuList-2c" @click="getpage(4)">
				<view class="menuTiele">公司信息</view>
				<view class="jiantou"></view>
			</view>
		</view>
		<view class="banquan">
			<text class="weburl" @click="getpage(2)">《安个利软件许可及服务协议》</text>和<text class="weburl" @click="getpage(3)">《安个利隐私保护指引》\n\n</text>
			<text style="color: #999999;">鑫峰互联 版权所有\n</text>
			<text style="color: #999999;">Copyright © 2019 Xinfeng.All Rights Reserved.</text>
		</view>
		
		<view class="mail shadow-blur" v-if="mail"  @click="hiw">
			<view class="show">请发送邮件至：<text selectable="true">angeli-h@qq.com</text></view>
		</view>
		
		<view class="mail shadow-blur" v-if="num>=3"  @click="hiw">
			<view class="show">
				<text selectable="true">
					Token:{{token}}\n
					openId:{{user.wxOpenId}}
				
				</text>
			</view>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				Version:'1.0.1',
				mail:false,
				num:0,
				user:[],
				token:'asfdasfasrdgrgset3254sdf23werfa'
			}
		},
		onLoad() {
			this.Version=server.Version;
			this.user=server.userinfo;
			this.token=server.token;
		},
		methods: {
			hiw:function(){
				this.mail=false
				this.num=0
			},
			showmail:function(){
				this.mail=true
			},
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
				if(id==4){
					uni.navigateTo({
						url:"../edit/edit?type=7"
					})
				}
				
			}
		}
	}
</script>

<style>
	
.mail{
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	background: rgba(66, 66, 66, 0.26);;
	text-align: center;
}
.show{
	width: 70%;
	margin: 0 auto;
	margin-top: 70%;
	height: 300upx;
	padding: 20upx;
	font-size:32upx ;
	color: #000000;
	border-radius:5px ;
	background-color: #fff;
	padding-top:80upx;
	box-shadow: 0 0 15px #5989B9;
	word-wrap:break-word;
}


.banquan{
	position: absolute;
	bottom: 32rpx;
	width: 100%;
	text-align: center;
	line-height: 1.5em;
}
.menuList{
	margin-top: 100rpx;
	border-top: 1rpx solid #E5E4EA;
	border-bottom: 1rpx solid #E5E4EA;
	margin: 30rpx;
	padding-left: 60rpx;
	padding-right: 40rpx;
}
.nei{
	padding-left: 60rpx;
}
.jiantou{
		width: 20upx;
		height: 20upx;
		border-top: 3px solid #EFEFF4;
		border-right: 3px solid #EFEFF4;
		right: 20upx;
		border-color: #E5E4EA;
		transform: rotate(45deg);
		margin-top:31upx;
		justify-self:end;
	}
.menuTiele{
	height: 87rpx;
	line-height: 87rpx;
	font-size: 28rpx;
}
.logo{
	text-align: center;
	margin-top: 100rpx;
}
page{
	background-color: #FFFFFF;
}
</style>
