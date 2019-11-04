<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">发帖</block>
		</cu-custom>
		<view  :style="[{top:CustomBar + 'px'}]">
			
			<view class="textedit">
				<textarea maxlength="1000" :disabled="modalName!=null" @input="textareaAInput" placeholder="在这里输入你想分享的内容，如找不到相应话题可以直接发布在其他闲聊。\n 注意：发帖需要消耗两枚安个利币，每天登陆即可获得一枚，请确认后再编辑内容！（其他获取方式请关注安个利官方通知的信息）"  ></textarea>
			</view>
			
			<sunui-upoos :upImgConfig="upImgOos" @onUpImg="upOosData" @onImgDel="delImgInfo" ref="uImage"></sunui-upoos>
			<!-- <view class="cu-form-group">
				<view class="grid col-4 grid-square flex-sub">
					<view class="bg-img" v-for="(item,index) in imgList" :key="index" @tap="ViewImage" :data-url="imgList[index]">
					 <image :src="imgList[index]" mode="aspectFill"></image>
						<view class="cu-tag bg-red" @tap.stop="DelImg" :data-index="index">
							<text class='cuIcon-close'></text>
						</view>
					</view>
					<view class="solids" @tap="ChooseImage" v-if="imgList.length<5">
						<text class='cuIcon-cameraadd'></text>
					</view>
				</view>
			</view> -->
			<view class="dibu">
				<view class="iconhuati">
					<image src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAEmElEQVRYR62YfWhVZRzHv99zyy1fsFYiCGJZTSxJhfXi7jkLYzSK6IVyMAJRGN5zpgwG5s6ZYZeMnbOKUmQ7Z84M6QWdFIIUZUjmOXcJWZktMFO0KIJFNlByc97zizNc+Ie75xnc88+98Pt9vudznvM893m4xBQvO/JfBTjH001LFd004D+kxdyixXFHZ13Lyck4qgYmffkvu2eO3py5CMGIa5i3qLJ2FOwhsBrgGlfP7SmLjBP2PgzKMRH51jOsGlUZJwq+AVBTRLzidb3lWHlkCsFaCHZD5D3XsFZPQWYUwLSKseKs/Mr1l8ojEwVvANgoAtszzC4Vmc2FXQtiuXpeBL97hjm/FDOlOeNEwScAnowFT3cZ5kEVmfao5wkN2qcCHPJ0s6GMMv55gAsy5D2vZXNnVWScKNgIIBnRba5utpVFJj/YP210+ELy7q+4ulmhIpL0OGGwG8RaUNa5WauvpEz++3dvHb08tjQtXBAvYoxegZyhlmlO6/+/Xox7QNxH4QbJcPBGnLD4q7fCOk87Cs4RuDMtXAAkE0wg4Pg3xUsEIIGJz0kwychdtAvBNsayLC1aBAupcT4E5wD5La0/qQtQQfIRCK4A8vVkjJDDlTMq1ig/ohP6B0E+BY3PubW5AyoyHQN+g8T8DILDrmHWpzHKMnbonyW5EKItco11p9OCk7od+W0E3wKww9XN1jRGSeb6lXQmWzV9PxuLacHXZPoINgtgeboZpDFKMvaAv5wxv4PISdewUlfexE3t0C+QrAXiOldvCcsjE/kvEnwfIntdw2pKC52oO6F/EeTMEe2m29+ubb6QxqmNTBR0EnAEssXTra1poUl9U/TOvAzG/hDIkKdbc1UYVZkDBJ4Rygte1vpIJdgOe+pJ7QsBjni6uVKFUZMJg9Mk7oXGxW5t7pRKsBP6rSC3A+h2dXODCpMqk6ykkX8u/JuEndWrKlRXkhP5AcCcCNZ7htlTFpmOoz0PiKb9IMBPnm4uUQlNepzIPwrQEJGVnmEdUeFSR8YOgyYSH4pgv2eYjSqh4zLXVtIYZe6bWWtIhaNd8NcA2oJJm0UeI1CXTESQSk8IYgZjeUkgl0Et9UQosQx3GeZ22mEwTGJ2mnnKpntDfGKnT8tO6qS2nB2FncviuPjs5ADXk7ijKOjRIH+pBIN8kMnxFDhOkeSoWvJiRjuRbL4l58wq6c/cHY2f7qa0kuww6CbREhOtXVlzR5rMRL2kTEfBXyLCHwGccnVzsWqoE/rJ/HqUGuo7a83DqlxpmchvFHAfgI9d3XxeNdQJ/SGQcxhzXmdd7k9VrqSMHfp5kq8IZKunW1tUQtsGdlVVxlf/hsgl17BmqTBKr8kOg34Sq0hp6sxae1WC2wtBnSb4CiIF17B0FUZNJgoGCdzPOF5a6t+D629oR4FJwIegzzXMdWWRuX4lVd5WNT2/pPGKSrAd+TsIbgDY5uq5bSpM6sg44c5qMP5ZBL94hlmtGmpHwecEHheywcvmDqly4z98kzW3H++dzRE5AZEPPMN6WTXUDv3NJKyKSq06X5Mb3+1Vr9SNUjWoHH3/AfX73DAzBFC+AAAAAElFTkSuQmCC" mode="widthFix" @tap="getClassList"></image>
					
				</view>
				<view style="width: 60%;left: 0upx;" @tap="getClassList">{{huatiname}}</view>
				<view class="send">
					<text style="font-size: 18px;" @tap="sendpost">发布</text>
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
				CustomBar: this.CustomBar,
				modalName: null,
				postcontent:"null",
				huati:"",
				huatiname:"选择话题",
				oosArr: [],
					// 阿里云oos相关配置
				upImgOos: {
					aliConfig: {
						// 阿里云oos上传key_secret(后端传)
						AccessKeySecret: 'Us5V1E9tD2bSYFWYWYsHES4lNdAXRi',
						// 阿里云oos上传key_id(后端传)
						OSSAccessKeyId: 'LTAI9dyLJEiv09Ss',
						// 阿里云oos上传目录(必须存在)
						oosDirectory: 'angeli-image',
						// 阿里云上传url
						url: 'https://sz.oss.data.angeli.top/'
					},
					// 是否开启notli(开启的话就是选择完直接上传，关闭的话当count满足数量时才上传)
					notli: true,
					// 图片数量
					count: 9,
					upTextDesc:"",
					// 上传图片背景修改 
					upBgColor: '#EFEFF4',
					// 上传icon图标颜色修改(仅限于iconfont)
					upIconColor: '#fff',
					// 上传svg图标名称
					upSvgIconName: 'icon-addicon',
					isAddImage:true
				}
				
			}
		},
		onBackPress:function(){
			uni.redirectTo({
				url: '../Home/Home?type=plusPost'
			})
		}
		,
		onShow:function(){
			console.log(server.postClass)
			this.huati=server.postClass.ClassId;
			this.huatiname=server.postClass.ClassName
			
		},
		onLoad: function(e) {

		},
		methods: {
			getClassList:function(){
				uni.navigateTo({
					url: '../huati/huati'
				})
			},
			uImageTap() {
				this.$refs.uImage.uploadimage(this.upImgOos);
			},
			// 删除图片 -2019/05/12(本地图片进行删除)
			async delImgInfo(e) {
				console.log('你删除的图片地址为:', e, this.oosArr.splice(e.index, 1));
			},
			// 阿里云
			async upOosData(e) {
				// 上传图片数组
				let arrImg = [];
				for (let i = 0, len = e.length; i < len; i++) {
					try {
						if (e[i].path_server != "") {
							await arrImg.push(e[i].path_server.split(','));
						}
					} catch (err) {
						console.log('上传失败...');
					}
				}
				// 图片信息保存到data数组
				this.oosArr = arrImg;
			
				// 可以根据长度来判断图片是否上传成功. 2019/4/11新增
				if (arrImg.length == this.upImgOos.count) {
					uni.showToast({
						title: `上传成功`,
						icon: 'none'
					});
				}
			},
			// 获取上传图片阿里云
			
			textareaAInput:function(e){
				this.postcontent = e.target.value 
			},
			sendpost:function(e){
				console.log(this.postcontent);
				if(this.postcontent=="null" ||this.postcontent==""){
					uni.showToast({
						title: "请输入文字内容！",
						position:'bottom',
						icon:'none',
						mask:true
					})
					return;
				}
				if(this.postcontent.length>2000 || this.postcontent.length<10 ){
					uni.showToast({
						title: "内容最少10个字，最多2000个字",
						position:'bottom',
						icon:'none',
						mask:true
					})
					return;
				}
				if(this.huati==null || this.huati==false||this.huati==0||this.huati=='选择话题'){
					uni.showToast({
						title: "你还没有选择话题",
						position:'bottom',
						icon:'none',
						mask:true
					})
					return;
				}
				if(!this.huati){
					this.huati=1
				}
				uni.showLoading({
					title: '发送帖子中..',
					mask:true
				});
				uni.request({
					method:'POST',
					url: 'https://api.angeli.top/post.php?type=addPost',
					data: {
						imageList: this.oosArr.join().split(','),
						txt:this.postcontent,
						huati:this.huati
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						uni.showToast({
							title: res.data.msg,
							position:'bottom',
							icon:'none',
							duration:2000,
							mask:true
						});
						if(res.data.code=="2"){
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none',
								duration:2000,
								mask:true
							});
						}
						if(res.data.code=="1"){
							setTimeout(function () {
								uni.hideLoading()
								uni.redirectTo({
									url: '../Home/Home?type=plusPost'
								})
							}, 2000);
						}
					},
					complete() {

					}
				});
				
			}
		}
	}
	
</script>

<style>
	.textedit{
		top:0upx;
		left: 0upx;
		width: 100%;
		height:400upx;
		background-color: #FFFFFF;
		padding: 10upx 38upx;
		
	}
	textarea{
		top:0upx;
		left: 0upx;
		width: 100%;
		height:400upx;
		background-color: #FFFFFF;
		margin-left: 10upx;
		margin-right: 10upx;
		
	}
	.dibu{
		position: fixed;
		bottom:0upx ;
		height: 40upx;
		width: 100%;
		margin: 40upx;
		display:flex;
		flex-direction:row;
		flex-wrap:nowrap;
		justify-content:space-between;
	}
	.icon-upImg{
		
	}
	.iconhuati{
		width: 40upx;
		height: 100%;
	}
	.send{
		height: 100%;
		margin-right: 80upx;
	}
	page {
		background-color: #FFFFFF;
	}
</style>
