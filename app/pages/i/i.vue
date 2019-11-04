<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">个人中心</block>
		</cu-custom>
		<view  :style="[{top:CustomBar + 'px'}]">
			<view class="top" id="topbox">
				<view class="info">
					<view class="lstouxiang " :style="{'background-image':'url('+name.AvatarUrl+')'}"></view>
					<view class="nameandsex">
						<view class="nameBox">
							<view class="Yname">{{name.UserName}}</view>
							<view :class="name.Gender==2?'Wsex':'Ysex'"></view>
							<view class="dengjia">Lv.{{name.Rank}}</view>
						</view>
						<view class="auid">ID:{{name.Auid}}</view>
					</view>
					<view class="editinfo" >
						<button class="button-edituserinfo" @tap="getEditInfo()">编辑资料</button>
					</view>
					<view class="miaoshu"><text selectable="true">{{name.Synopsis}}</text></view>
				</view>
				<view style="height: 2upx;margin-left:30upx;margin-right:30upx;background-color: #EFEFF4; "><!-- 分割线 --></view>
				<view class="sange">
						<text class="Maxnum">{{name.FollowedCount}}</text>
						<text class="Maxnum">{{name.FollowerCount}}</text>
						<text class="Maxnum">{{name.ZhongcaoCount}}</text>
						<text>关注</text>
						<text>粉丝</text>
						<text>种草</text>
				</view>
			</view>
			<view class="daohang">
				<view class="hang" :class="TabCur==0?'hangIng':''" @tap="tabSelect(0)">我发布的</view>
				<view class="hang" :class="TabCur==1?'hangIng':''" @tap="tabSelect(1)">我的种草</view>
			</view>
			<block v-if="TabCur==0">
				<view class="duoList">
					<view class="items" v-for="(post,index) in postList" :key="index" @tap="getPost(post.PostId)">
						<view class="itemImage" style="background-color: #FFFFFF;" :style="{'background-image':'url('+post.PictureId[0]+')'}"></view>
						<view class="itemText"><text class="text-A">{{post.Content}}</text></view>
						<view class="dibudianzan">
								<image src="../../static/zcxz.png" class="give" mode="aspectFit"></image>
								<text class="giveconut">{{post.Give}}</text>
						</view>
					</view>
				</view>
				
			</block>
			
			<block v-if="TabCur==1">
				<view class="duoList">
					<view class="items" v-for="(post,index) in MyzcList" :key="index" @tap="getPost(post[0].PostsId)">
						<view class="itemImage" style="background-color: #FFFFFF;" :style="{'background-image':'url('+post[0].PictureId[0]+')'}"></view>
						<view class="itemText"><text class="text-A">{{post[0].Content}}</text></view>
						<view class="dibudianzan">
								<image src="../../static/zcxz.png" class="give" mode="aspectFit"></image>
								<text class="giveconut">{{post[0].ZhongcaoCount}}</text>
						</view>
					</view>
				</view>
			</block>
			<uni-load-more :status="status" />
			<!-- <view class="dibutext">没有更多的帖子</view> -->
			
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	import uniLoadMore from '@/components/uni-load-more.vue';
	export default {
		components: {
			uniLoadMore
		},
		data() {
			return {
				name:[],
				sex:'♀',
				TabCur: 0,
				CustomBar: this.CustomBar,
				page:1,
				MyzcList:[],
				postList:[],
				status: 'loading',
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
				}
			}
		},
		onLoad:function(){
			
			this.name=server.userinfo;
			console.log(this.name)
			if(this.name.Gender=="2"){
				this.sex='♀'
			}else{
				this.sex='♂'
			}
			console.log(this.name);
			uni.request({
				method:'GET',
				url: 'https://api.angeli.top/post.php?type=myPostList', //仅为示例，并非真实接口地址。
				data: {
					uid: this.name.Auid,
					count:10,
					page:this.page
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
					'Cookie':server.cookie
				},
				success: (res) => {
					if(res.data.data.length==undefined){
						this.status ="noMore";
					}else{
						this.postList=res.data.data;
						this.status ="more";
						console.log(this.postList);
					}
				}
			});
		},
		methods: {
			getMyzc:function(){
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/post.php?type=getMyGive', //仅为示例，并非真实接口地址。
					data: {
						page:1,
						count:20
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
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
							
							this.MyzcList=res.data.data
							this.weikong=false
						}
						
					},
					complete() {
						uni.hideLoading();
					}
				});
			},
			tabSelect(e) {
				this.TabCur = e
				if(e==1){
					this.getMyzc()
				}
				
			},
			getPost:function(id){
				console.log(id)
				uni.navigateTo({
					url: '../postinfo/postinfo?id='+id
				});
			},
			getEditInfo:function(){
				uni.navigateTo({
					url: '../editinfo/editinfo'
				});
			}
		},
		onReachBottom:function(){
			this.status ="loading";
			this.page++;
			console.log("拉倒低了",this.page)
			uni.request({
				method:'GET',
				url: 'https://api.angeli.top/post.php?type=myPostList', //仅为示例，并非真实接口地址。
				data: {
					uid: this.name.Auid,
					count:10,
					page:this.page
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
					'Cookie':server.cookie
				},
				success: (res) => {
					if(res.data.data.length==undefined){
						this.page--;
						this.status ="noMore";
					}else{
						this.postList=this.postList.concat(res.data.data);
						this.status ="more";
						console.log(this.postList);
					}
					
				}
			});
			
			
			
			
			
		}
	}
</script>

<style>
	.dibudianzan{
		float: right;
	}
	.giveconut{
		font-size: 32upx;
		font-weight: 500;
		
	}
	
	.text-A{
		overflow:hidden;
		text-overflow: ellipsis;
		display:-webkit-box;
		-webkit-line-clamp:2;
		-webkit-box-orient:vertical;
	}
	.infoa{
		display: flex;
		align-items:center;
		margin-top: 68upx;
		margin-left: 38upx;
		margin-right: 38upx;
		position: relative;
	}
	.nameBox{
		height: 45upx;
		line-height: 45upx;
		font-size:32upx;
		font-weight: 500;
		vertical-align: middle;
	}
	.dengjia{
		margin-left: 18upx;
		height: 30upx;
		vertical-align: middle;
		padding: 0upx 16upx;
		background:rgba(121,196,152,0.3);
		border-radius: 15upx;
		font-size:22upx;
		color:rgba(121,196,152,1);
		line-height: 30upx;
	}
	.nameBox view{
		display: inline-block;
	}
	.Ysex{
		margin-left: 18upx;
		vertical-align: middle;
		height: 30upx;
		width: 30upx;
		background-image: url('../../static/nan.png');
		background-size:100%;
	}
	.Wsex{
		margin-left: 18upx;
		vertical-align: middle;
		height: 30upx;
		width: 30upx;
		background-image: url('../../static/nv.png');
		background-size:100%;
	}
	.nameandsex{
		
		height: 110upx;
	}
	.give{
		width: 32upx;
		height: 32upx;
		
	}
	.top{
		background-color: #FFFFFF;
		padding-bottom: 30upx;
	}
	.itemImage{
		width: 100%;
		height: 75%;
		background-size: auto 100%; 
	}
	.itemText{
		width: 100%;
		padding: 5upx;
		font-size: 28upx;
		line-height: 28upx;
		
		overflow:hidden;
		text-overflow:ellipsis;
		display:-webkit-box;
		box-orient:vertical;
		line-clamp:2; 
	}
	.miaoshu{
		grid-column-start: 2;
		grid-column-end: 4;
		justify-self:start;
		align-self:start;
		vertical-align:text-top;
	}
	.hang{
		margin-left: 20upx;
		height: 48upx;
		padding-bottom: 5upx;
	}
	.dibutext{
		text-align: center;
		margin: 35upx;
	}
	.hangIng{
		font-weight: 600;
		color: #79C498;
		border-bottom:2upx solid #79C498;
	}
	.daohang{
		margin-top: 5upx;
		padding: 10upx;
		height: 50upx;
		display: flex;
		justify-content: space-around;
		background-color: #FFFFFF;
		font-size: 28upx;
	}
	.Maxnum{
		font-size: 28upx;
		font-weight:700;
		color: #000000;
	}
	.items{
		margin-top: 10upx;
		height: 450upx;
		width: 100%;
		margin-left: 10upx;
		background-color: #F7F8FA;
	}
	.duoList{
		padding-left: 25upx;
		padding-right: 35upx;
		padding-top: 20upx;
		padding-bottom: 20upx;
		background-color: #FFFFFF;
		display: grid;
		grid-template-columns: 50% 50%;
		grid-column-gap: 8upx;
		justify-items:center;
		align-items:center;
	}
	.sange{
		padding-top: 30upx;
		padding-left: 50upx;
		padding-right: 50upx;
		font-size: 24upx;
		color: #999999;
		height: 120upx;
		bottom: 20upx;
		display: grid;
		grid-template-columns: 33.33% 33.33% 33.33%;
		
		justify-items: center;
	}
	.editinfo{
		padding-right: 0upx;
		
		padding-top: 20upx;
	}
	.button-edituserinfo{
		background-color: #79C498;
		height: 60upx;
		font-size: 25upx;
		vertical-align:auto;
		color: #FFFFFF;
		border-radius: 25upx;
	}
	.dengji{
		font-size:20upx;
		height: 30upx;
		border-radius:25upx !important;
		vertical-align: top;
	}
	.info{
		padding: 60upx;
		display: grid;
		grid-template-columns: 25% 50% 25%;
		grid-template-rows: 50% 50%;
		flex-wrap: nowrap;
		align-items: center;
	}
	.lstouxiang{
		width: 110upx;
		height: 110upx;
		background-size:100% 100%;
		border-radius:50%;
	}
	.xinxi{
		margin-top: 10upx;
		line-height:40upx;
		vertical-align:top;
		line-height: 50upx;
	}
</style>
