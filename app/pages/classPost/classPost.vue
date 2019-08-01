<template>
	<view>
		<view class="head" :style="{'background-image':'url('+'https://sz.oss.data.angeli.top/system/1%20%289%29.jpg'+')'}">
			<view class="titleBox">
				<view class="title">美食探店</view>
				<view class="fabu">立即参与</view>
			</view>
			<view class="info">
				34人参与·15.1万次浏览
			</view>
		</view>
		<view class="daohang" >
			<view class="hang" :class="TabCur==0?'hangIng':''" @tap="tabSelect(0)">最新</view>
			<view class="hang" :class="TabCur==1?'hangIng':''" @tap="tabSelect(1)">最热</view>
		</view>
		<view class="postList">
			<view class="user">
				<view class="touxiang" :style="{'background-image':'url('+'https://wx.qlogo.cn/mmopen/vi_32/tfV7uwJicyIgrk25qvTbYqWtRoZYGpN7icqV4AGTyhDSiaWH97SoAcXia2gxcp7tPWfswAsa5Z6EQzj0yZa0Y16aiaQ/132'+')'}" ></view>
				<view class="userName">不停</view>
				<view class="postDate">6月17日</view>
			</view>
			<view class="postText">
				测试的一条内容aasfasddf as测试的一条内容aasfasddf as测试的一条内容aasfasddf as测试的一条内容aasfasddf as
			</view>
			<view class="postImage">
				<image class="postImageItem" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1562157158231&di=67244750931cf11fc83dc043d75355c4&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201805%2F29%2F20180529172546_Jfl2h.png" mode="aspectFill"></image>
				<image class="postImageItem" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1562157176515&di=42a36737694d135e5dcfe5e0255ad8c2&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201406%2F12%2F20140612202753_u4nG5.jpeg" mode="aspectFill"></image>
				<image class="postImageItem" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1562157193599&di=821dd2abcfd3b09bc7396a3d4fb973d1&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201804%2F17%2F20180417084703_V48Nf.jpeg" mode="aspectFill"></image>	
			</view>
			<view class="postBottom">
				<view class="postClass">#宠物心得</view>
				<view class="like"  @click="zan"></view>
				<view class="postMenu" @click="caidan"><image src="../../static/caidan.png" mode="aspectFit" style="height: 40upx;"></image></view>
			</view>
		</view>
		<view class="postList">
			<view class="user">
				<view class="touxiang" :style="{'background-image':'url('+'https://wx.qlogo.cn/mmopen/vi_32/tfV7uwJicyIgrk25qvTbYqWtRoZYGpN7icqV4AGTyhDSiaWH97SoAcXia2gxcp7tPWfswAsa5Z6EQzj0yZa0Y16aiaQ/132'+')'}" ></view>
				<view class="userName">不停</view>
				<view class="postDate">6月17日</view>
			</view>
			<view class="postText">
				测试的一条内容aasfasddf as测试的一条内容aasfasddf as测试的一条内容aasfasddf as测试的一条内容aasfasddf as
			</view>
			<view class="postImage">
				<image class="postImageItem" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1562157158231&di=67244750931cf11fc83dc043d75355c4&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201805%2F29%2F20180529172546_Jfl2h.png" mode="aspectFill"></image>
				<image class="postImageItem" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1562157176515&di=42a36737694d135e5dcfe5e0255ad8c2&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201406%2F12%2F20140612202753_u4nG5.jpeg" mode="aspectFill"></image>
				<image class="postImageItem" src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1562157193599&di=821dd2abcfd3b09bc7396a3d4fb973d1&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201804%2F17%2F20180417084703_V48Nf.jpeg" mode="aspectFill"></image>	
			</view>
			<view class="postBottom">
				<view class="postClass">#宠物心得</view>
				<view class="like"  @click="zan"></view>
				<view class="postMenu" @click="caidan"><image src="../../static/caidan.png" mode="aspectFit" style="height: 40upx;"></image></view>
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
.head{
	height: 300upx;
	background-color: #1CBBB4;
	background-size: 100% 100%;
	
}
.title{
	padding-top: 6upx;
	font-size: 32upx;
}
.titleBox{
	margin-left: 100upx;
	display: flex;
	justify-content:space-between;
	color:#FFFFFF;
	margin-right: 38upx;
	padding-top: 184upx;
}
.fabu{
	height: 49upx;
	width: 128upx;
	background: #79C498;
	border-radius: 25upx;
	text-align: center;
	line-height: 49upx;
	font-size: 24upx;
}
.info{
	padding-top: 21upx;
	color: #fff;
	padding-left: 38upx;
	font-size: 28upx;
}
.touxiang{
	/* grid-column-start: 1;
	grid-column-end: 3; */
	grid-row-start: 1;
	grid-row-end: 3;
	background-size: 100% auto;
	border-radius:50%;
	height: 90upx;
	width: 90upx;
	background-image: url("https://pic.qqtn.com/up/2017-11/15114902797453337.gif");
}
</style>
