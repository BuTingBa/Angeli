<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">安个利官方通知</block>
		</cu-custom>
		<view class="cu-chat">
			
			<view v-for="(list,index) in MsgList" :key="index">
				<view class="cu-item">
					<view class="cu-avatar radius yuan" style="background-image: url('../../static/systemMsg.png');" @click="getbie"></view>
					<view class="main">
						<view class="content shadow">
							<text>{{list.msg}}</text>
						</view>
					</view>
					<view class="date ">{{list.time}}</view>
				</view>
			</view>
			
			
			<!--
			
			中间系统消息 
			<view class="cu-info round">对方撤回一条消息!</view>
			
			带图标的中间消息
			<view class="cu-info">
				<text class="cuIcon-roundclosefill text-red "></text> 对方拒绝了你的消息
			</view>
			 超长文本的中间消息，还有链接地址 
			<view class="cu-info">
				对方开启了好友验证，你还不是他(她)的好友。请先发送好友验证请求，对方验证通过后，才能聊天。
				<text class="text-blue">发送好友验证</text>
			</view>
			
			  图片消息
			<view class="cu-item self">
				<view class="main">
					<image src="https://ossweb-img.qq.com/images/lol/web201310/skin/big10006.jpg" class="radius" mode="widthFix"></image>
				</view>
				<view class="cu-avatar radius yuan" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big107000.jpg);"></view>
				<view class="date"> 13:23</view>
			</view>
			-->
			
		</view>
		
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				MsgList:[],
				auid:0,
				val:'',
				value:""
			}
		},
		onLoad:function(e){

			this.getMyMsg()
			
		},
		onShow:function(){
			this.markmsg();
		},
		methods: {
			input:function(e){
				this.val=e.target.value 
			},
			getme:function(){
				uni.navigateTo({
					url: '../i/i'
				})
			},
			markmsg:function(){
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=mark&class=system", //请求标记已读消息
					data: {
						'msgId':this.msgid
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							console.log('已将信息标记为已读')
						}else{
							console.log('标记已读失败！')
						}
						
					},
					complete() {
						
					}
				});
			},
			InputFocus(e) {
				this.InputBottom = e.detail.height
			},
			InputBlur(e) {
				this.InputBottom = 0
			},
			getMyMsg:function(){
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=getSystemMsg", //仅为示例，并非真实接口地址。
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
						console.log(this.MsgList)
					}
				});
			},
		}
	}
</script>

<style>

</style>
