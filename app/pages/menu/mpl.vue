<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">我的好友</block>
		</cu-custom>
		<view class="listBox">
			<view class="userBox">
				<view class="touxiang"></view>
				<view class="info">
					<text>粽子\n</text>
					<text>2小时前</text>
				</view>
			</view>
			<view class="plnr">
				<text>这个超级好用，谁用谁知道</text>
			</view>
			<view class="mynr">
				<text>我的评论：</text><text>这个好用么？求知情大佬告知。</text>
			</view>
		</view>
		<view class="menusolid"></view>
		<view class="listBox">
			<view class="userBox">
				<view class="touxiang"></view>
				<view class="info">
					<text>粽子\n</text>
					<text>2小时前</text>
				</view>
			</view>
			<view class="plnr">
				<text>这个超级好用，谁用谁知道</text>
			</view>
			<view class="mynr">
				<text>我的评论：</text><text>这个好用么？求知情大佬告知。</text>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				TabCur:0
			}
		},
		methods: {
			tabSelect(e) {
				this.TabCur = e
	
			}
		}
	}
</script>

<style>
page{
	background-color: #FFFFFF;
}
.mynr{
	padding: 8upx;
	height: 49upx;
	margin-left: 86upx;
	background:rgba(247,248,250,1);
	font-size:24upx;
	font-weight:400;
	color:rgba(54,54,54,1);
}
.mynr text:nth-child(1){
	color:#79C498;
}
.plnr{
	margin-top: 5upx;
	margin-left: 86upx;
	margin-bottom: 8upx;
	font-size:28upx;
	font-weight:400;
	color:rgba(54,54,54,1);
}
.info text:nth-child(2){
	font-size:22upx;
	font-weight:400;
	color:rgba(0,0,0,0.60);
	opacity:1;
}
.info text:nth-child(1){
	font-size:24upx;
	font-weight:400;
	color:rgba(0,0,0,1);
}
.touxiang{
	height: 70upx;
	width: 70upx;
	border-radius: 50%;
	background-image: url('http://img1.imgtn.bdimg.com/it/u=2317060666,1193629305&fm=26&gp=0.jpg');
	background-size: 100% 100%;
	margin-right: 16upx;
	line-height: 38upx;
}
.userBox{
	display: flex;
	
}
.listBox{
	padding: 16upx 38upx;
	height: 208upx;
}
</style>
