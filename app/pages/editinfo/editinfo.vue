<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">编辑资料</block>
		</cu-custom>
		<view class="touxiangList" @tap="uptouxiang()">
			<view class="editx" :style="{'background-image':'url('+user.AvatarUrl+')'}"></view>
			<view class="title">修改头像</view>
			<view class="jiantou"></view>
		</view>
		
		<view class="menuList" @tap="toPage(1)">
			<view class="menuName">修改名字</view>
			<view class="var">{{user.UserName}}</view>
			<view class="jiantou"></view>
		</view>
		<view class="menuList" @tap="setsex">
			<view class="menuName">修改性别</view>
			<view class="var">{{user.Gender=='1'?'男':'女'}}</view>
			<view class="jiantou"></view>
		</view>
		
		<view class="touxiangList" @tap="toPage(2)">
			<view class="menuName">个性签名</view>
			<view class="var">{{user.Synopsis}}</view>
			<view class="jiantou"></view>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				sex:'男',
				user:[]
			}
		},
		onShow:function(){
			uni.request({
				method:'GET',
				url: 'https://api.angeli.top/user.php?type=getUserInfo', //仅为示例，并非真实接口地址。
				data: {
					auid: server.userinfo.Auid
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
					'Cookie':server.cookie
				},
				success: (res) => {
					if(res.data.code=='1'){
						server.userinfo=res.data.data;
						this.user=res.data.data;
					}
				}
			});
		},
		methods: {
			toPage:function(type){
				uni.navigateTo({
					url: '../edit/edit?type='+type
				});
			},
			setsex:function(){
				uni.showActionSheet({
					itemList: ['男', '女'],
					success: function (res) {
						uni.request({
							method:'GET',
							url: 'https://api.angeli.top/user.php?type=setSex', //仅为示例，并非真实接口地址。
							data: {
								auid: server.userinfo.Auid,
								sex:res.tapIndex + 1
							},
							header: {
								'content-type': 'application/x-www-form-urlencoded',
								'Cookie':server.cookie
							},
							success: (res) => {
								if(res.data.code=='1'){
									uni.showToast({
										title: res.data.msg,
										position:'bottom',
										icon:'none'
									})
								}
							}
						});
					},
					fail: function (res) {
						uni.showToast({
							title: res.errMsg,
							position:'bottom',
							icon:'none'
						})
						console.log(res.errMsg);
					}
				});
			},
			uptouxiang:function(){
				uni.chooseImage({
					count: 1, //默认9
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album','camera'], //从相册选择
				    success: (chooseImageRes) => {
				        const tempFilePaths = chooseImageRes.tempFilePaths;
				        uni.uploadFile({
				        	url: 'https://api.angeli.top/up/index.php', //仅为示例，非真实的接口地址
				        	filePath: tempFilePaths[0],
				        	name: 'file',
				        	formData: {
				        		'type': 'test'
				        	},
				        	header: {
				        		'Cookie':server.cookie
				        	},
				        	success: (uploadFileRes) => {
								console.log(uploadFileRes.data)
				        		this.user.AvatarUrl=uploadFileRes.data;
								
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
