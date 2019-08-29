<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">消息通知</block>
		</cu-custom>
		<view class="mtop">
			<view><image src="../../static/dianzanimg.png" mode="scaleToFill" @click="getPage(1)"></image>
			<view v-if="msgNumber.Give>0" class="tip">{{msgNumber.Give}}</view></view>
			<view><image src="../../static/pinglunimg.png" mode="scaleToFill" @click="getPage(2)"></image>
			<view v-if="msgNumber.pinglun>0" class="tip">{{msgNumber.pinglun}}</view></view>
			<view><image src="../../static/guanzhuimg.png" mode="scaleToFill" @click="getPage(3)"></image>
			<view v-if="msgNumber.Guanzhu>0" class="tip">{{msgNumber.Guanzhu}}</view></view>
			
			<text>点赞打赏</text>
			<text>评论和@</text>
			<text>新增粉丝</text>
		</view>
		<view class="messageListBox">
			<!-- 系统通知 -->
			<view class="messageList" @click="getChat(list.ToId.Auid,list.FromId.Auid)">
				<image :src="list.ToId.Auid==auid?list.FromId.AuthorAvatarUrl:list.ToId.AuthorAvatarUrl" mode="" class="touxiang"></image>
				<text>安个利官方通知</text>
				<text>刚刚</text>
				<text>安个利活动开启了</text>
				<text :class="list.MsgStatus=='0'?'hongdian':''"></text>
			</view>
			<view class="menusolid"></view>
			<view v-for="(list,index) in MsgList" :key="index">
				<view class="messageList" @click="getChat(list.ToId.Auid,list.FromId.Auid)">
					<image :src="list.ToId.Auid==auid?list.FromId.AuthorAvatarUrl:list.ToId.AuthorAvatarUrl" mode="" class="touxiang"></image>
					<text>{{list.ToId.Auid==auid?list.FromId.AuthorName:list.ToId.AuthorName}}</text>
					<text>{{list.MsgSendTime}}</text>
					<text>{{list.Msg}}</text>
					<text :class="list.MsgStatus=='0'?'hongdian':''"></text>
				</view>
				<view class="menusolid"></view>
			</view>
			<uni-load-more :status="status" />
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	import uniLoadMore from '@/components/uni-load-more.vue';
	export default {
		components: {
			uniLoadMore
		},
		data() {
			return {
				msgNumber:[],
				MsgList:[],
				status: 'loading',
				statusTypes: [{
					value: 'more',
					text: '加载前',
					checked: true
				}, {
					value: 'loading',
					text: '加载中',
					checked: false
				}, {
					value: 'noMore',
					text: '我是有底线的',
					checked: false
				}],
				contentText: {
					contentdown: '查看更多',
					contentrefresh: '加载中',
					contentnomore: '我是有底线的'
				},
				auid:0
			}
		},
		onShow:function(){
			this.getNoReadMsgNumber();
			this.getMsgList();
		},
		onLoad:function(){
			//this.getNoReadMsgNumber();
			this.auid=server.userinfo.Auid
		},
		methods: {
			getChat:function(id,id2){
				let go=parseInt(id)+parseInt(id2)
				uni.navigateTo({
					url: 'chat?id='+go
				});
			},
			getPage:function(e){
				if(e==1){
					uni.navigateTo({
						url: 'zan'
					})
				}
				if(e==2){
					uni.navigateTo({
						url: 'mpl'
					})
				}
				if(e==3){
					uni.navigateTo({
						url: 'newFans'
					})
				}
			},
			getNoReadMsgNumber:function(){
				uni.showLoading({
					title: '加载中'
				});
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=getMyNoRead", //仅为示例，并非真实接口地址。
					data: {
						
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							this.msgNumber=res.data.data
						}
						
						
						console.log(this.msgNumber)
					},
					complete() {
						uni.hideLoading();
					}
				});
			},
			getMsgList:function(){
				this.status ="more";
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=getMyMsgList", //仅为示例，并非真实接口地址。
					data: {
						
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							this.MsgList=res.data.data
						}
						if(res.data.code=="0" ||res.data.data.length<50 ){
							this.status ="noMore";
						}
						console.log(this.MsgList)
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
.mtop{
	background-color: #FFFFFF;
	height: 210upx;
	padding: 46upx 100upx;
	display: grid;
	grid-template-columns: 33.33% 33.33% 33.33%;
	grid-template-rows: 85upx 37upx;
	justify-items: center;
	align-items: center;
}
.tip{
	display: inline-block;
	width: 36upx;
	height: 36upx;
	background-color: red;
	position: absolute;
	text-align: center;
	line-height: 36upx;
	border-radius: 50%;
	font-size:26upx;
	margin-left: -18upx;
	margin-top: -18upx;
	color:#FFFFFF;
}
.touxiang{
	height: 100upx;
	width: 100upx;
	border-radius: 50%;
	grid-column-start: 1;
	grid-column-end: 2;
	grid-row-start: 1;
	grid-row-end: 3;
}
.messageList{
	padding: 18upx 38upx;
	display: grid;
	grid-template-columns: 20% 50% 30%;
	grid-template-rows: 50% 50%;
	justify-items: start ;
	align-items: center;
	position: relative;
}
.messageList text:nth-child(2){
	font-weight:500;
	font-size: 28upx;
}
.messageList text:nth-child(3){
	font-size:22upx;
	font-weight:400;
	color:rgba(153,153,153,1);
	justify-self:end;
}
.messageList text:nth-child(4){
	font-size:24upx;
	font-weight:400;
	color:rgba(153,153,153,1);
	grid-column-start: 2;
	grid-column-end: 4;
}
.hongdian{
	background-color: red;
	width: 11upx;
	height: 11upx;
	position: absolute;
	border-radius: 50%;
	right: 38upx;
	top: 98upx;
}
.mtop view {
	position: relative;
	vertical-align:top;
}
.mtop view image{
	width: 85upx;
	height: 85upx;
	margin-bottom: 8upx;
	
}
.mtop text{
	font-size:26upx;
	font-weight:400;
	line-height:37px;
	color:rgba(54,54,54,1);
}
.messageListBox{
	margin-top: 8upx;
	background-color: #FFFFFF;
	
}
</style>
