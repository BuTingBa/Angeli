<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">我的老铁</block>
		</cu-custom>
		<view class="body">
			<view class="topTitle">
				<text>我的老铁</text>
				<text>（互相关注）</text>
			</view>
			<view v-for="(list,index) in dataList" :key="index">
				<view class="menusolid"></view>
				<view class="friendList"  @click="getChat(list.notMe.Auid)"> 
					<image src="../../static/guanzhuimg.png" :src="list.notMe.AuthorAvatarUrl" mode="scaleToFill"></image>
					<view >{{list.notMe.AuthorName}}</view>
					<image src="../../static/chat.png" mode="scaleToFill"></image>
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
				dataList:[]
			}
		},
		onLoad:function(){
			this.getMypengyou();
		},
		methods: {
			getbieren:function(e){
				if(e==server.userinfo.Auid){
					uni.navigateTo({
						url: '../i/i'
					})
				}else{
					uni.navigateTo({
						url: '../i/bieren?auid='+e
					})
				}
			},
			getChat:function(id){
				let go=parseInt(id)+parseInt(server.userinfo.Auid);
				uni.navigateTo({
					url: '../menu/chat?id='+go+'&toid='+id
				});
			},
			getMypengyou:function(){
				uni.showLoading({
					title: '加载中'
				});
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=getMyhaoyou", //请求标记已读消息
					data: {
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							this.dataList=res.data.data;
						}else{
							
							
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
