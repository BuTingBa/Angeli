<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">分享得会员</block>
		</cu-custom>
		<view class="body">
			<view class="tou">
				<view class="wurentou" :style="{'background-image':'url('+MsgList[0].ToTG.AuthorAvatarUrl+')'}"></view>
				<view class="wurentou" :style="{'background-image':'url('+MsgList[1].ToTG.AuthorAvatarUrl+')'}"></view>
				<view class="wurentou" :style="{'background-image':'url('+MsgList[2].ToTG.AuthorAvatarUrl+')'}"></view>
			</view>
			<button class="fenx" open-type="share">立即分享</button>
			
			
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
					url: "https://api.angeli.top/user.php?type=getMyTG", 
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
			}
		},
		onShareAppMessage(res) {
		   if (res.from === 'button') {// 来自页面内分享按钮
		      console.log(res.target)
		    }
			
		    return {
		      title: '给你安利一个好东西',
		      path: '/pages/Home/Home?tuijianid='+server.userinfo.Auid
		    }
		}
	}
</script>

<style>
.tou{
	display: flex;
	position: absolute;
	bottom: 712upx;
	justify-content:center;
	width: 750upx;
}

.wurentou{
	width: 100upx;
	height: 100upx;
	border:2upx solid rgba(121,196,152,1);
	border-radius:50%;
	margin:0upx 10upx;
	background-image: url('../../static/tttt.png');
	background-size:100% auto;
}
*{
	margin:0;
	padding:0;
}
.fenx{
	position: absolute;
	bottom: 112upx;
	margin: 0upx 38upx;
	width:674upx;
	height:81upx;
	background:rgba(238,92,75,1);
	border:2upx solid rgba(251,166,13,1);
	box-shadow:0upx 3upx 6upx rgba(92,171,129,1);
	opacity:1;
	border-radius:41upx;
	color: #FFFFFF;
	font-size:32upx;
	line-height: 81upx;
	text-align: center;
}
.body{
	height:1615upx;width:100%; 
	background-image: url('https://sz.oss.data.angeli.top/system/tuiguanghuodong.png');
	background-size: 100% auto;
	position: relative;
}
</style>
