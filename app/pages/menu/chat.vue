<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">{{MsgList[0].ToId.Auid==auid?MsgList[0].FromId.AuthorName:MsgList[0].ToId.AuthorName}}</block>
		</cu-custom>
		<view class="cu-chat">
			
			<view v-for="(list,index) in MsgList" :key="index">
				 <template v-if="list.type=='he'">
				        <!-- 对方消息 -->
				        <view class="cu-item">
				        	<view class="cu-avatar radius yuan" :style="{'background-image':'url('+list.ff.AuthorAvatarUrl+')'}" @click="getbie"></view>
				        	<view class="main">
				        		<view class="content shadow">
				        			<text>{{list.Msg}}</text>
				        		</view>
				        	</view>
				        	<view class="date ">{{list.MsgSendTime}}</view>
				        </view>
				 </template>
				 <template v-if="list.type=='me'">
				 	<!-- 我方聊天 -->
				 	<view class="cu-item self">
				 		<view class="main">
				 			<view class="content bg-green shadow">
				 				<text>{{list.Msg}}</text>
				 			</view>
				 		</view>
				 		<view class="cu-avatar radius yuan" :style="{'background-image':'url('+list.mm.AuthorAvatarUrl+')'}" @click="getme"></view>
				 		<view class="date">{{list.MsgSendTime}}</view>
				 	</view>
				 </template>
				
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
		<view style="height: 100upx;"></view>
		<view class="cu-bar foot input" :style="[{bottom:InputBottom+'px'}]">
			<input class="solid-bottom " :adjust-position="false" :focus="false" maxlength="300" cursor-spacing="10"
			 @focus="InputFocus" @blur="InputBlur" @input="input" v-model="value"></input>
			<button class="cu-btn bg-green shadow" @click="upmsg">发送</button>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				InputBottom: 0,
				msgid:0,
				MsgList:[],
				auid:0,
				val:'',
				toid:0,
				value:""
			}
		},
		onLoad:function(e){
			this.auid=server.userinfo.Auid
			console.log(e.id,e.toid)
			this.msgid=e.id
			this.toid=e.toid
			this.getMyMsg()
			
		},
		onShow:function(){
			this.markmsg();
		},
		methods: {
			input:function(e){
				this.val=e.target.value 
			},
			getbie:function(){
				uni.navigateTo({
					url: '../i/bieren?auid='+this.toid
				})
			},
			getme:function(){
				uni.navigateTo({
					url: '../i/i'
				})
			},
			upmsg:function(){
				uni.showLoading({
					title: '发送中..',
					mask:true
				});
				uni.request({
					method:'POST',
					url: "https://api.angeli.top/user.php?type=upmsg", //仅为示例，并非真实接口地址。
					data: {
						'msg':this.val,
						'toid':this.toid
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							this.value="";
							this.getMyMsg()
							
						}
						
						if(res.data.code=="0" ||res.data.code==null||res.data.code==false ){
							uni.showToast({
								title: "发送失败，请稍后再试",
								position:'bottom',
								icon:'none'
							})
						}
						console.log(this.MsgList)
					},
					complete() {
						uni.hideLoading();
					}
				});
			},
			markmsg:function(){
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=mark&class=msg", //请求标记已读消息
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
					url: "https://api.angeli.top/user.php?type=getMyMsg", //仅为示例，并非真实接口地址。
					data: {
						'msgid':this.msgid
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							this.MsgList=res.data.data
							this.toid=this.MsgList[0].ff.Auid
							setTimeout(function () {
								uni.pageScrollTo({
								    scrollTop: 30000,
								    duration: 300
								});
								uni.pageScrollTo({
								    scrollTop: 30100,
								    duration: 300
								});
							}, 200);
						}
						if(res.data.code=="0" ||res.data.data.length<50 ){
							
						}
						console.log(this.MsgList)
					},
					complete() {
						uni.hideLoading();
						
						
					}
				});
			},
		}
	}
</script>

<style>

</style>
