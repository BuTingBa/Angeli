<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">种草排行</block>
		</cu-custom>
		<view class="jifenTop">
			<image :src="userinfo.AvatarUrl" mode="aspectFill" class="jinfenTouxiang"></image>
			<view class="name">{{userinfo.UserName}}</view>
			<view class="jifenMy">
				<view class="titleText">
					<text>{{userinfo.ZhongcaoCount}}\n</text>
					<text>种草总数</text>
				</view>
				<view class="titleText">
					<text>{{topList.index}}\n</text>
					<text>本周排名</text>
				</view>
				<view class="titleText">
					<text>{{topList.count}}\n</text>
					<text>本周种草</text>
				</view>
			</view>
		</view>
		<view class="jifenTiele">
			本周排行榜
		</view>
		<!-- <view class="myJifen">
			<text class="lpaihang">1254</text>
			<image class="listTouxiang" src="" mode=""></image>
			<text class="username">我是你爸爸</text>
			<view class="jifen">132分</view>
		</view> -->
		<view class="jifenListBox">
			<view class="jifenList" v-for="(list,index) in topList.data" :key="index">
				<view class="myJifen" @click="getBire(list.AuId.Auid)">
					<text class="lpaihang">{{index+1}}</text>
					<image class="listTouxiang" :src="list.AuId.AuthorAvatarUrl" mode=""></image>
					<text class="username">{{list.AuId.AuthorName}}</text>
					<view class="jifen">{{list.index}}个种草</view>
				</view>
				<view class="menusolid"></view>
			</view>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				topList:[],
				userinfo:[]
			}
		},
		onLoad:function(){
			this.getTop();
			this.userinfo=server.userinfo;
			
		},
		methods: {
			getBire:function(e){
				uni.navigateTo({
					url: '../i/bieren?auid='+e
				})
			},
			getTop:function(){
				uni.showLoading({
					title: '获取数据中'
				});
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=weekTop", //仅为示例，并非真实接口地址。
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.code=="1"){
							this.topList=res.data.data
						}else{
							uni.showToast({
								title: "获取失败！",
								position:'bottom',
								icon:'none'
							});
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

.jifenListBox{
	margin-top: 9upx;
	
}
.username{
	font-size:28upx;
	font-weight:400;
	line-height:40upx;
	color:rgba(54,54,54,1);
}
.jifen{
	margin-left: auto;
	font-size:28upx;
	font-weight:400;
	line-height:40upx;
	color:rgba(54,54,54,1);
}
.lpaihang{
	height: 102upx;
	font-size:26upx;
	font-weight:500;
}
.listTouxiang{
	height: 70upx;
	width: 70upx;
	background-color: #1CBBB4;
	margin-left: 25upx;
	margin-right: 27upx;
	margin-top: 12upx;
	margin-bottom: 12upx;
	border-radius: 50%;
}
.myJifen{
	height: 102upx;
	padding: 16upx 38upx;
	background-color: #FFFFFF;
	font-size:28upx;
	font-weight:400;
	line-height:102upx;
	color:rgba(54,54,54,1);
	display: flex;
	align-items:center;
	
}
.jifenTiele{
	
	padding: 16upx 38upx;
	background-color: #FFFFFF;
	font-size:32upx;
	font-weight:500;
	line-height:45upx;
	color:rgba(54,54,54,1);
	padding-top: 26upx;
}
.titleText text:nth-child(1){
	font-size:28upx;
	font-weight:500;
	line-height:40upx;
	color:rgba(0,0,0,1);
}
.titleText text:nth-child(2){
	font-size:24upx;
	font-weight:400;
	line-height:33upx;
	color:rgba(153,153,153,1);
}
.jifenMy{
	height: 72upx;
	text-align: center;
	display: flex;
	justify-content:space-between;
	margin-top: 32upx;
	padding: 0upx 100upx;
	
}
.name{
	font-size:28upx;
	font-weight:500;
	line-height:40px;
	color:rgba(54,54,54,1);
}
.jinfenTouxiang{
	height: 128upx;
	width: 128upx;
	margin-top: 49upx;
	border-radius: 50%;
	background-color: #1CBBB4;
}
.jifenTop{
	height:373upx;
	background:rgba(255,255,255,1);
	text-align: center;
	
}
</style>
