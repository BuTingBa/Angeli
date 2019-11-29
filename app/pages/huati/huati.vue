<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">选择话题</block>
		</cu-custom>
		<view class="huati">
			<view class="radius shadow-blur huatiItem" v-for="(typea,index) in classList" :key="index" :style="{'background-image':'url('+typea.ClassImage+')'}" @tap="getPOST(typea)">
				<view class="text-c">{{typea.ClassName}}</view>
			</view>
			
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				classList:[]
			}
		},
		onLoad:function(){
			uni.request({
				method:'GET',
				url: 'https://api.angeli.top/post.php?type=getClassList', //仅为示例，并非真实接口地址。
				header: {
					'content-type': 'application/x-www-form-urlencoded',
				},
				success: (res) => {
					this.classList=res.data.data;
					console.log(this.classList);
				}
			});
		},
		methods: {
			getPOST:function(e){
				server.postClass=e
				uni.navigateBack({
					
				});
			}
		}
	}
</script>

<style>
	page{
		background-color: #FFFFFF;
	}
	.huati{
		width: 100%;
		height: 100%;
		display: grid;
		padding: 30upx;
		grid-template-columns: 50% 50%;
		justify-items: center;
		align-items: center;
		justify-content: center;
		align-content: center;  
	}
	.huatiItem{
		
		background-size: 100% auto;
		width: 329upx;
		height: 200upx;
		margin: 10upx;
		border-radius:7px
	}
	.text-c{
		text-align: center;
		font-size: 28upx;
		line-height: 200upx;
		padding-top: 35upx;
		color:#FFFFFF;
	}
</style>
