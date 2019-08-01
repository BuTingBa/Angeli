<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">我的好友</block>
		</cu-custom>
		<view class="body">
			<view class="topTitle">
				<text>我的好友</text>
				<text>（互相关注）</text>
			</view>
			<view class="menusolid"></view>
			<view class="friendList">
				<image src="../../static/guanzhuimg.png" mode="scaleToFill"></image>
				<view>不停</view>
				<image src="../../static/chat.png" mode="scaleToFill"></image>
			</view>
			
			<view class="menusolid"></view>
			<view class="friendList">
				<image src="../../static/guanzhuimg.png" mode="scaleToFill"></image>
				<view>不停</view>
				<image src="../../static/chat.png" mode="scaleToFill"></image>
			</view>
			
			<view class="menusolid"></view>
			<view class="friendList">
				<image src="../../static/guanzhuimg.png" mode="scaleToFill"></image>
				<view>不停</view>
				<image src="../../static/chat.png" mode="scaleToFill"></image>
			</view>
			
			<view class="menusolid"></view>
			<view class="friendList">
				<image src="../../static/20.png" mode="scaleToFill"></image>
				<view>不停</view>
				<image src="../../static/chat.png" mode="scaleToFill"></image>
			</view>
		</view>
	</view>
</template>

<script>
</script>

<style>
page{
	background-color: #FFFFFF;
	
}
.friendList{
	height: 100upx;
	padding: 15upx 0upx;
}
.friendList image{
	height: 70upx;
	width: 70upx;
}
.menusolid{
	border-color: #E5E4EA;
}
.topTitle{
	margin-bottom: 15upx;
}
.friendList image:nth-child(1){
	float:left;
	
}
.friendList view:nth-child(2){
	float:left;
	height: 58upx;
	line-height: 58upx;
	font-size: 28upx;
	margin-left: 20upx;
}
.friendList image:nth-child(3){
	float:right;
	width: 54upx;
	height: 54upx;
	margin-top: 8upx;
}

.topTitle text:nth-child(1){
	font-size:32upx;
	font-weight:500;
	color:rgba(54,54,54,1);
}
.topTitle text:nth-child(2){
	font-size:28upx;
	font-weight:400;
	color:rgba(54,54,54,1);
	margin-left: ;
}
.body{
	padding:16upx 38upx;
}
</style>
