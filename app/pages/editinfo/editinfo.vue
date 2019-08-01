<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">编辑资料</block>
		</cu-custom>
		<view class="touxiangList" @tap="uptouxiang()">
			<view class="editx" :style="{'background-image':'url('+'http://5b0988e595225.cdn.sohucs.com/images/20170827/dab634088d034e54bb658f0ef192389a.jpeg'+')'}"></view>
			<view class="title">修改头像</view>
			<view class="jiantou"></view>
		</view>
		
		<view class="menuList" @tap="toPage(1)">
			<view class="menuName">修改名字</view>
			<view class="var">不停</view>
			<view class="jiantou"></view>
		</view>
		<view class="menuList" @tap="setsex">
			<view class="menuName">修改性别</view>
			<view class="var">男</view>
			<view class="jiantou"></view>
		</view>
		
		<view class="touxiangList" @tap="toPage(2)">
			<view class="menuName">个性签名</view>
			<view class="var">暂无个性签名</view>
			<view class="jiantou"></view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				sex:'男',
			}
		},
		methods: {
			toPage:function(type){
				uni.navigateTo({
					url: '../edit/edit?type='+type
				});
			},
			setsex:function(){
				uni.showActionSheet({
					itemList: ['男', '女', '第三种性别','保密'],
					success: function (res) {
						console.log('选中了第' + (res.tapIndex + 1) + '个按钮');
					},
					fail: function (res) {
						console.log(res.errMsg);
					}
				});
			},
			uptouxiang:function(){
				uni.chooseImage({
					count: 1, //默认9
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album','camera'], //从相册选择
					success: function (res) {
						const tempFilePaths = JSON.stringify(res.tempFilePaths);
						uni.uploadFile({
							url: 'https://www.example.com/upload?type=updata', //仅为示例，非真实的接口地址
							filePath: tempFilePaths,
							name: 'file',
							formData: {
								'type': 'test'
							},
							success: (uploadFileRes) => {
								console.log(uploadFileRes.data);
							},
							complete:function(){
								console.log("cuowu");
							}
						});
					},
					fail:function(){
						uni.showToast({
							title: "选择头像失败！",
							position:'bottom',
							icon:'none'
						});
					}
				});
			}
		}
	}
</script>

<style>
.editx{
	height: 128upx;
	width: 128upx;
	border-radius: 50%;
	background-size:auto 100%;
}
.touxiangList{
	height: 176upx;
	padding: 24upx 30upx;
	margin-top: 1upx;
	background-color: #FFFFFF;
	display: grid;
	grid-template-columns: 20% 60% 20%;
	justify-items: start;
    align-items: center;
	margin-bottom: 9upx;
}
.menuList{
	height: 87upx;
	padding: 24upx 30upx;
	margin-top: 1upx;
	background-color: #FFFFFF;
	display: grid;
	grid-template-columns: 20% 60% 20%;
	justify-items: start;
    align-items: center;
}
.menuName{
	color: #999999;
	font-size: 28upx;
}
.title{
	font-size: 28upx;
	color: #999999;
	margin-left: 21upx;
}
.var{
	font-size: 28upx;
	color: #363636;
}


</style>
