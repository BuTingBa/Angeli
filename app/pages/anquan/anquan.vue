<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">账号安全</block>
		</cu-custom>
		<view class="menusolid"></view>
		<view class="menuList-2c menuList" >
			<view class="menuTiele">我的ID</view>
			<view class="tishi">{{userInfo.Auid?userInfo.Auid:'没有Auid？'}}</view>
		</view>
		<view class="menusolid nei"></view>
		<view class="menuList-2c menuList" @click="getUpdataLog">
			<view class="menuTiele">手机绑定</view>
			<view>
				<text class="tishi">{{userInfo.Phone?userInfo.Phone:'未绑定'}}</text>
				<view class="jiantou"></view>
			</view>
		</view>
		<view class="menusolid nei"></view>
		<view class="menuList-2c menuList" @click="getUpdataLog">
			<view class="menuTiele">微信绑定</view>
			<view>
				<text class="tishi">{{userInfo.wx?'已绑定':'未绑定'}}</text>
				<view class="jiantou"></view>
			</view>
		</view>
		<view class="menusolid nei"></view>
		<view class="menuList-2c menuList" @click="getUpdataLog">
			<view class="menuTiele">QQ绑定</view>
			<view>
				<text class="tishi">{{userInfo.QQ?'已绑定':'未绑定'}}</text>
				<view class="jiantou"></view>
			</view>
		</view>

		<view class="menusolid"></view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				userInfo:[]
			}
		},
		onLoad:function(){
			this.userInfo=server.userinfo;
		},
		methods: {
			
		}
	}
</script>

<style>
.menuTiele{
	font-size: 28upx;
	font-weight: 400;
}
.tishi{
	margin-right: 20upx;
	color: #999999;
}
.menuList{
	background: #FFFFFF;
	height: 88upx;
	padding: 24upx 30upx;
}
.nei{
	margin-left: 60rpx;
}
</style>
