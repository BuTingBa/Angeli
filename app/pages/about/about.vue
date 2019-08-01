<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">关于</block>
		</cu-custom>
		<view class="logo">
			<view><image src="../../static/angeli.png" mode="" style="width: 200rpx;height: 132rpx;" ></image></view>
			<view style="margin-top: 27rpx;"><text style="font-size: 28rpx;color: #363636;">Version 1.0.1</text></view>
		</view>
		<view class="menuList">
			
			<view class="menuList-2c" @click="getUpdataLog">
				<view class="menuTiele">更新日志</view>
				<view class="jiantou"></view>
			</view>
			<view class="menusolid nei"></view>
			<view class="menuList-2c">
				<view class="menuTiele">投诉维权</view>
				<view class="jiantou"></view>
			</view>
			<view class="menusolid nei"></view>
			<view class="menuList-2c">
				<view class="menuTiele">公司信息</view>
				<view class="jiantou"></view>
			</view>
		</view>
		<view class="banquan">
			<text >《安个利软件许可及服务协议》和《安个利隐私保护指引》\n\n</text>
			<text style="color: #999999;">鑫峰互联 版权所有\n</text>
			<text style="color: #999999;">Copyright © 2019 Xinfeng.All Rights Reserved.</text>
		</view>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		methods: {
			getUpdataLog:function(){
				uni.navigateTo({
					url:"../edit/edit?type=4"
				})
			}
		}
	}
</script>

<style>
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
