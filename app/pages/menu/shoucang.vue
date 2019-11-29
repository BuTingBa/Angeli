<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">我的收藏</block>
		</cu-custom>
		<template  v-if="weikong==false">
		<view class="postList" v-for="(list,index) in postList" :key="index">
			<view class="user">
				<view class="touxiang" :style="{'background-image':'url('+list[0].AuthorInfo.AuthorAvatarUrl+')'}"  @click="getbieren(list[0].AuthorId)"></view>
				<view class="userName">{{list[0].AuthorInfo.AuthorName}}</view>
				<view class="postDate">{{list[0].PsotDate}}</view>
			</view>
			<view class="postText" @tap="getpostinfo(list[0].PostsId)">
				{{list[0].Content}}
			</view>
			<view class="postImage" >
				<image v-for="(img,id) in list[0].PictureId" :key="id" class="postImageItem" :src="img"  @tap="showImage(list[0].PictureId,id)" mode="aspectFill"></image>
			</view>
			
			<view class="postBottom">
				<view class="postClass" @tap="getClass(list[0].Tag.ClassId)">#{{list[0].Tag.ClassName}}</view>
				<view :class="[list[0].Give?'likeing':'like']"  @click="Like(list[0].PostsId,list[0].AuthorId,list[0].Give,index)"></view>
				<view class="postMenu" @click="caidan"><image src="../../static/caidan.png" mode="aspectFit" style="height: 40upx;"></image></view>
			</view>
		</view>
		</template >
		<template v-else>
			<view class="nullBox">
				<image src="../../static/null.png" mode="" class="nullImg"></image>
				<text class="nullText">\n你还没有种草过帖子</text>
			</view>
		</template>
	</view>
</template>

<script>
	import server from '../../server.js';
	//import uniFab from '@/components/uni-fab.vue';
	import uniLoadMore from '@/components/uni-load-more.vue';
	export default {
		data() {
			return {
				TabCur:0,
				postList:[],
				weikong:true
			}
		},
		onLoad:function(e){
			uni.showLoading({
				title: '获取数据中'
			});
			uni.request({
				method:'GET',
				url: 'https://api.angeli.top/post.php?type=getMyGive', //仅为示例，并非真实接口地址。
				data: {
					page:1,
					count:20,
					token:server.token
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
					
				},
				success: (res) => {
					
					if(res.data.code!=="1"){
						uni.showToast({
							title: res.data.msg,
							position:'bottom',
							icon:'none',
							duration:2000,
							mask:true
						});
						this.weikong=true
					}else{
						
						this.postList=res.data.data
						this.weikong=false
					}
					
				},
				complete() {
					uni.hideLoading();
				}
			});
		},
		methods: {
			Like:function(postid,auid,give,index){
				if(give===true){
					var modea='del'
				}else{
					var modea='add'
				}
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=Like", //仅为示例，并非真实接口地址。
					data: {
						fuid: auid,
						postid:postid,
						mode:modea,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						
					},
					success: (res) => {
						if(res.data.code=="1"){
							if(modea=='add'){
								this.postList[index][0].Give=true;
								uni.showToast({
									title: "种草成功！",
									position:'bottom',
									icon:'none'
								});
							}else{
								this.postList[index][0].Give=false;
								uni.showToast({
									title: "取消种草成功！",
									position:'bottom',
									icon:'none'
								});
							}
							
							this.$forceUpdate()
						}else{
							
							uni.showToast({
								title: "种草失败！",
								position:'bottom',
								icon:'none'
							});
						}
					},
					complete() {
						
					}
				});
			},
			getClass:function(id){
				uni.navigateTo({
					url: '../classPost/classPost?id='+id
				})
			},
			showImage(res,c){
				uni.previewImage({
					current:c,
					urls: res,
					longPressActions: {
					itemList: ['发送给朋友', '保存图片', '收藏'],
						success: function (res) {
							
							uni.showToast({
								title: '选中了第' + (res.tapIndex + 1) + '个按钮',
								position:'bottom',
								icon:'none'
							});
							console.log('选中了第' + (res.tapIndex + 1) + '个按钮');
							
						},
						fail: function (res) {
							console.log(res.errMsg);
						}    
					}
				})
			},
			tabSelect(e) {
				this.TabCur = e
	
			},
			getbieren:function(e){
				uni.navigateTo({
					url: '../i/bieren?auid='+e
				})
			},
			getpostinfo:function(id){
				uni.navigateTo({
					url: '../postinfo/postinfo?id='+id
				});
			}
		}
	}
</script>

<style>
.touxiang{
	/* grid-column-start: 1;
	grid-column-end: 3; */
	grid-row-start: 1;
	grid-row-end: 3;
	background-size: 100% auto;
	border-radius:50%;
	height: 90upx;
	width: 90upx;
	background-image: url("https://pic.qqtn.com/up/2017-11/15114902797453337.gif");
}
</style>
