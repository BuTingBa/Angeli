<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">{{title}}</block>
		</cu-custom>
		<view class="body">
			<view v-if="type=='1'" class="upname">
				<text style="color: #888888;"> 更改昵称，普通用户一年只能修改两次</text>
				<input type="text" value="" maxlength="24" focus="true" @input="inputing" class="in" />
				<view class="dibu">
					<button class="Angeli">确定修改</button>
				</view>
			</view>
			<view class="upname" v-if="type=='2'" >
				 <textarea placeholder-style="color:#888888" placeholder="用一句牛逼的话来描述自己,最多输入60个字" maxlength="60"/>
				 <view class="dibu">
				 	<button class="Angeli">确定修改</button>
				 </view>
			</view>
			<view class="upname" v-if="type=='3'" >
				 
			</view>
			<view class="upname" v-if="type=='4'" >
				 <web-view src="https://api.angeli.top/html/updata.html"></web-view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				type:1,
				title:"安个利"
			}
		},
		onLoad:function(val) {
			console.log(val.type)
			this.type=val.type;
			if(val.type=='1'){
				this.title="修改名字"
			}
			if(val.type=='2'){
				this.title="个性签名"
			}
			if(val.type=='3'){
				this.title="等级特权"
			}
			if(val.type=='4'){
				this.title="关于安个利"
			}
		},
		methods: {
			inputing:function(e){
				console.log(e.detail)
			}
		}
	}
</script>

<style>
	.textarea{
		width: 100%;
	}
.body{
	background-color: #FFFFFF;
}
.an{
	width: 100%;
}
.dibu{
	position: absolute;
	width: 90%;
	bottom: 10upx;
	margin: 0upx 20upx;
}
.in{
	color: #000000;
	font-size: 28upx;
	height: 32upx;
	line-height: 32upx;
	margin-top: 10upx;
}
.upname{
	padding: 20upx 20upx;
	font-size: 26upx;
	
}
</style>
