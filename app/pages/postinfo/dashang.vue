<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">打赏列表</block>
		</cu-custom>
		<view class="body">
			
			
			<view class="friendList" v-for="(list,index) in list" :key="index">
				<image :src="list.fromAuid.AuthorAvatarUrl" mode="scaleToFill"></image>
				<view>{{list.fromAuid.AuthorName}}</view>
				<view>+ 关注</view>
			</view>
			
			
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				list:[]
			}
		},
		onLoad:function(e){
			console.log(e.id)
			this.getList(e.id);
		},
		methods: {
			getList:function(postid){
				
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=getDashangList&postId="+postid, //仅为示例，并非真实接口地址。
					data: {
					
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.code=="1"){
							this.list=res.data.data
							this.$forceUpdate()
						}else{
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							});
						}
					},
					complete() {
						
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
	height: 70upx;
	line-height: 70upx;
	font-size: 28upx;
	margin-left: 20upx;
}
.friendList view:nth-child(3){
	float:right;
	background:rgba(121,196,152,1);
	box-shadow:0px 1px 1px rgba(121,196,152,0.3);
	border-radius: 25upx;
	text-align: center;
	width: 116upx;
	height: 49upx;
	margin-top: 8upx;
	line-height: 49upx;
	font-size: 24upx;
	color: #FFFFFF;
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
