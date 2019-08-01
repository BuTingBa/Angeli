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
				<view class="guanzhu">
					+ 关注
				</view>
			</view>
			<view class="plnr">
				<text>我开始盯你了，给我小心点</text>
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
				<view class="guanzhuX">
					已关注
				</view>
			</view>
			<view class="plnr">
				<text>开始关注你了，请注意哦</text>
			</view>
		</view>
	</view>
</template>

<script>
</script>
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
<style>
page{
	background-color: #FFFFFF;
}
.guanzhuX{
	margin-left: auto;
	height:49upx;
	width:116upx;
	border-radius:36upx;
	background:rgba(229,228,234,1);
	box-shadow:0px 1px 1px rgba(229,228,234,0.3);
	text-align: center;
	line-height: 49upx;
	color: #999999;
}
.guanzhu{
	margin-left: auto;
	height:49upx;
	width:116upx;
	border-radius:36upx;
	background:rgba(121,196,152,1);
	box-shadow:0px 1px 1px rgba(121,196,152,0.3);
	text-align: center;
	line-height: 49upx;
	color: #FFFFFF;
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
	height: 151upx;
}
</style>
