<template>
	<view>
		<view class="head" :style="{'background-image':'url('+classinfo.ClassImageMax+')'}">
			<view class="titleBox">
				<view class="title">{{classinfo.ClassName}}</view>
				<view class="fabu" @click="getPushPost">立即参与</view>
			</view>
			<view class="info">
				{{classinfo.canyu}}人参与·{{classinfo.ViewCount}}次浏览
			</view>
		</view>
		<view class="daohang" >
			<view class="hang" :class="TabCur==0?'hangIng':''" @tap="tabSelect(0)">最新</view>
			<view class="hang" :class="TabCur==1?'hangIng':''" @tap="tabSelect(1)">最热</view>
		</view>
		<template v-if="TabCur==0">
		    <view class="postList" v-for="(list,index) in postList" :key="index">
		    	<view class="user">
		    		<view class="touxiang" :style="{'background-image':'url('+list.AuthorInfo.AuthorAvatarUrl+')'}" @click="getbieren(list.AuthorId)"><view class="vipLogo" v-if="list.AuthorInfo.VIPEndTime>0" ></view></view>
					<view :class="list.AuthorInfo.VIPEndTime>0?'vipUserName':'userName'">{{list.AuthorInfo.AuthorName}}</view>
		    		<view class="postDate">{{list.PsotDate}}</view>
		    	</view>
		    	<view class="postText" @tap="getpostinfo(list.PostsId)">
					<text decode="false" selectable="true" space="nbsp" class="text-c">{{list.Content}}</text>
		    	</view>
				<block v-if="list.PictureId[0].length>5">
					<view class="postImage" >
						<image v-for="(img,id) in list.PictureId" :key="id" class="postImageItem" :src="img"  @tap="showImage(list.PictureId,id)" mode="aspectFill"></image>
					</view>
				</block>
		    	
		    	<view class="postBottom">
		    		<view class="postClass" >#{{list.Tag.ClassName}}</view>
		    		<view :class="[list.Give?'likeing':'like']"  @click="Like(list.PostsId,list.AuthorId,list.Give,index)"></view>
		    		<view class="postMenu" @click="caidan(list)"><image src="../../static/caidan.png" mode="aspectFit" style="height: 40upx;"></image></view>
		    	</view>
		    </view>
		</template>
		<template v-if="TabCur==1">
		
		</template>	
		<uni-load-more :status="status" />
	</view>
</template>

<script>
	import uniLoadMore from '@/components/uni-load-more.vue';
	import server from '../../server.js';
	export default {
		components: {
			uniLoadMore,
		},
		data() {
			return {
				TabCur:0,
				postList:[],
				status: 'loading',
				type:"new",
				classinfo:[],
				statusTypes: [{
					value: 'more',
					text: '加载前',
					checked: true
				}, {
					value: 'loading',
					text: '加载中',
					checked: false
				}, {
					value: 'noMore',
					text: '我是有底线的',
					checked: false
				}],
				contentText: {
					contentdown: '查看更多',
					contentrefresh: '加载中',
					contentnomore: '我是有底线的'
				},
			}
		},
		onLoad:function(e){
			this.classId=e.id;
			this.getClassInfo(this.classId);
			this.getPostList(this.classId);
		},
		methods: {
			getPushPost:function(){
				if(server.userinfo.Auid==""||server.userinfo.Auid==null){
					uni.showToast({
						title: "你还没有登录，请登录后再来吧",
						position:'bottom',
						icon:'none'
					});
					setTimeout(function () {
						uni.navigateTo({
							url: '../reg/reg'
						})
					}, 1200);
				}else{
					server.postClass=this.classinfo;
					uni.navigateTo({
						url: '../post/post'
					})
				}
			},
			getClassInfo:function(e){
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/post.php?type=getClassInfo', //仅为示例，并非真实接口地址。
					data: {
						classId:e,
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						uni.hideLoading();
						this.classinfo=[]
						this.classinfo=res.data.data;
						console.log(this.classinfo);
						this.weikong=false
						if(res.data.code!=="1"){
							uni.showToast({
								title: '获取话题信息失败',
								position:'bottom',
								icon:'none'
							});
						}
						this.$forceUpdate();
					}
				});
			},
			getpostinfo:function(id){
				uni.navigateTo({
					url: '../postinfo/postinfo?id='+id
				});
			},
			getbieren:function(e){
				uni.navigateTo({
					url: '../i/bieren?auid='+e
				})
			},
			getPostList:function(classId){
				uni.showLoading({
					title: '获取数据中'
				});
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/post.php?type=getClassPostList', //仅为示例，并非真实接口地址。
					data: {
						page: 1,
						count:20,
						classId:classId,
						postType:this.type
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						uni.hideLoading();
						this.postList=[]
						console.log("————————————帖子列表——————————");
						this.postList=res.data.data;
						console.log(this.postList);
						this.weikong=false
						if(res.data.code!=="1"){
							uni.showToast({
								title: '获取帖子失败，建议重启',
								position:'bottom',
								icon:'none'
							});
						}
						if(res.data.data==false){
							this.weikong=true
						}
						//this.$forceUpdate();
					},
					complete:function(){
						uni.hideLoading();
					},
					
				});
			},
			showImage:function(res,c){
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
			caidan:function(res){
				console.log(res)
				this.Dindex=res;
				if(res.AuthorId==server.userinfo.Auid){
					this.menuList=['分享给朋友', '生成海报', '举报','删除帖子']
				}else{
					this.menuList=['分享给朋友', '生成海报', '举报']
				}
				uni.showActionSheet({
					itemList:this.menuList,
					success: function (res) {
						switch(res.tapIndex){
							case 0:
								uni.showToast({
									title: "分享"+res.Content,
									position:'bottom',
									icon:'none'
								});
								break;
							case 1:
								
								break;
							case 2:
								
								break;
							case 3:
							
								break;
							default:
								
						}
					},
					fail: function (res) {
						console.log(res.errMsg);
					}
				});
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
						mode:modea
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.code=="1"){
							if(modea=='add'){
								this.postList[index].Give=true;
								uni.showToast({
									title: "种草成功！",
									position:'bottom',
									icon:'none'
								});
							}else{
								this.postList[index].Give=false;
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
			tabSelect(e) {
				this.TabCur = e
				if(e==0){
					this.type="new";
				}else{
					this.type="hot";
				}
				
			}
		}
	}
</script>

<style>
.head{
	height: 300upx;
	background-color: #1CBBB4;
	background-size: 100% 100%;
	
}
.title{
	padding-top: 6upx;
	font-size: 32upx;
}
.titleBox{
	margin-left: 100upx;
	display: flex;
	justify-content:space-between;
	color:#FFFFFF;
	margin-right: 38upx;
	padding-top: 184upx;
}
.fabu{
	height: 49upx;
	width: 128upx;
	background: #79C498;
	border-radius: 25upx;
	text-align: center;
	line-height: 49upx;
	font-size: 24upx;
}
.info{
	padding-top: 21upx;
	color: #fff;
	padding-left: 38upx;
	font-size: 28upx;
}
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
	position: relative;
}
</style>
