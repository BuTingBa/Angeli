<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">帮助与客服</block>
		</cu-custom>
		<view class="box">
			<text>写下你的问题，客服随后联系你</text>
			<textarea name="" id="" cols="30" rows="10" adjust-position="false" placeholder="[必填]输入你的问题" v-model="text"></textarea>
			<view class="inputBox">
				<input type="text" class="dan" placeholder="怎么称呼？" v-model="name">
				<input type="text" class="dan" placeholder="QQ/微信/邮箱" v-model="phone">
			</view>
			<button class="Angeli send" @click="go">与客服建立连接</button>
		</view>
		<view class="boxc">
			<text selectable="true">客服邮箱：angeli-h@qq.com\n</text>
			<text selectable="true">客服微信：HWG5428</text>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				name:'',
				phone:'',
				text:''
			}
		},
		onLoad:function(e){
			
		},
		methods: {
			go:function(){
				if(!this.text){
					uni.showToast({
						title: "必须输入遇到的问题",
						position:'bottom',
						icon:'none',
						position:'center'
					});
					return;
				}
				uni.request({
					method:'POST',
					url: 'https://api.angeli.top/user.php?type=upHelp', 
					data: {
						token:server.token,
						name:this.name,
						phone:this.phone,
						text:this.text
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						if(res.data.code=='1'){
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none',
								position:'center'
							});
							setTimeout(function () {
								uni.redirectTo({
									url: '../Home/Home'
								})
							}, 1500);
						}else{
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none',
								position:'center'
							});
						}
					}
				});
				
			}
		}
	}
</script>

<style>
page{
	width: 100%;
	background-color: #fff;
}
.boxc text{
	margin: 10upx;
}
.boxc{
	width: 50%;
	margin: 0 auto;
	font-size:28upx;
	color:#808080;
	
}
.send::after {
  border: none;
}
.send{
	width: 100%;
	margin:100upx 0;
	height: 80upx;
	border-radius: 15upx;
	border: 1px solid #ccc;
	line-height: 80upx;
	font-size:28upx;
}
textarea{
	width: 100%;
	margin: 40upx 0;
	height: 200upx;
	font-size:28upx;
	border-radius: 15upx;
	border: 1px solid #ccc;
	padding:10upx 10upx ;
}
.inputBox{
	display: flex;
	justify-content: space-between;
}
.box{
	width: 100%;
	background-color: #FFFFFF;
	padding: 40upx 38upx;
}
.box text{
	margin: 40upx 0;
	font-size:30upx ;
}
.dan{
	width: 48%;
	height: 80upx;
	font-size:28upx;
	border-radius: 15upx;
	border: 1px solid #ccc;
	padding:0 10upx ;
}
</style>
