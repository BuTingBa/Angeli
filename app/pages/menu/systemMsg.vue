<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">安个利官方通知</block>
		</cu-custom>
		<view class="cu-chat">
			
			<view v-for="(list,index) in MsgList" :key="index">
				<view class="cu-item">
					<view class="cu-avatar radius yuan" style="background-image: url('https://sz.oss.data.angeli.top/angeli-image/157303749250764.png');" ></view>
					<view class="main">
						<view class="content shadow">
							<view>{{list.msg}}\n<text v-if="list.type==2" @click="getLink(list.typeVal)" style="color: #0081FF;" >点击这里查看详情</text></view>
						</view>
					</view>
					<view class="date ">{{list.time}}</view>
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
			getLink:function(url){
				console.log(url)
				uni.navigateTo({
					url: url
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
