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
					<view class="xinxi">
						<text selectable="true" style="font-size: 40upx;padding-bottom: 10upx;">{{name.UserName}}</text>
						<view style="vertical-align: top;color: #0081FF;display: inline-block;font-weight:bold;font-size: 30upx;" :style="{color:(name.Gender==2?'#79C498':'#79C498')}">{{sex}}</view>
						<view class='cu-tag line-green dengji' :style="{color:(name.Gender==2?'#FF1493':'#4169E1')}">Lv.{{name.Rank}}</view>
						<text selectable="true" >\nID:{{name.Auid}}</text>
					</view>
					<view class="editinfo" >
						<button class="button-sixin" @tap="getChat(auid)"></button>
						<button class="button-edituserinfo" @tap="getGuanzhu(auid)" :class="gz=='取消关注'?'button-edituserinfoXZ':'button-edituserinfo'">{{gz}}</button>
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
				<view class="hang" :class="TabCur==0?'hangIng':''" @tap="tabSelect(0)">发布记录</view>
				<view class="hang" :class="TabCur==1?'hangIng':''" @tap="tabSelect(1)">悬赏记录</view>
			</view>
			<block v-if="TabCur==0">
				<template v-if="weikong==false">
					<view class="duoList">
						<view class="items" v-for="(post,index) in postList" :key="index" @tap="getPost(post.PostId)">
							<view class="itemImage" style="background-color: #FFFFFF;" :style="{'background-image':'url('+post.PictureId[0]+')'}"></view>
							<view class="itemText">{{post.Content}}</view>
							<view class="dibudianzan">
								<view class="text-gray text-sm">
									<text class="cuIcon-attentionfill margin-lr-xs"></text> 10
									<text class="cuIcon-appreciatefill margin-lr-xs"></text> 20
									<text class="cuIcon-messagefill margin-lr-xs"></text> 30
								</view>
							</view>
						</view>
					</view>
				</template>
				<template v-else>
					<view class="nullBox">
						<image src="../../static/null.png" mode="" class="nullImg"></image>
						<text class="nullText">\nTA还没有发过帖子</text>
					</view>
				</template>
			</block>
			
			<block v-if="TabCur==1">
				<template v-if="weikong==false">
					<view class="duoList">
						<view class="items" v-for="(post,index) in postList" :key="index">
							<view class="itemImage" style="background-color: #FFFFFF;" :style="{'background-image':'url('+post.PictureId[0]+')'}"></view>
							<view class="itemText">{{post.Content}}</view>
							<view class="dibudianzan">
								<view class="text-gray text-sm">
									<text class="cuIcon-attentionfill margin-lr-xs"></text> 10
									<text class="cuIcon-appreciatefill margin-lr-xs"></text> 20
									<text class="cuIcon-messagefill margin-lr-xs"></text> 30
								</view>
							</view>
						</view>
					</view>
				</template>
				<template v-else>
					<view class="nullBox">
						<image src="../../static/null.png" mode="" class="nullImg"></image>
						<text class="nullText">\nTA还没有悬赏记录</text>
					</view>
				</template>
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
				gz:'+关注',
				TabCur: 0,
				CustomBar: this.CustomBar,
				page:1,
				weikong:true,
				postList:[],
				auid:0,
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
		onLoad:function(e){
			console.log(e.auid)
			this.auid=e.auid;
			uni.request({
				method:'GET',
				url: 'https://api.angeli.top/user.php?type=getUserInfo', //仅为示例，并非真实接口地址。
				data: {
					auid: e.auid
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
					'Cookie':server.cookie
				},
				success: (res) => {
					console.log(res)
					this.name=res.data.data
					this.getPostList(this.name.Auid)
					if(res.data.data.guanzhu==true){
						this.gz="取消关注";
					}
					
				}
			});
			if(this.name.Gender=="2"){
				this.sex='♀'
			}else{
				this.sex='♂'
			}
		},
		methods: {
			getChat:function(id){
				let go=parseInt(id)+parseInt(server.userinfo.Auid);
				uni.navigateTo({
					url: '../menu/chat?id='+go+'&toid='+this.auid
				});
			},
			tabSelect(e) {
				this.TabCur = e
			},
			getGuanzhu:function(uid){
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/user.php?type=gzORungz', //仅为示例，并非真实接口地址。
					data: {
						uid: uid,
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						uni.showToast({
							title: res.data.msg,
							position:'bottom',
							icon:'none'
						});
						if(res.data.msg=='关注成功'){
							this.gz="取消关注";
						}else{
							this.gz="+关注";
						}
					}
				}); 
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
			},
			getPostList(auid){
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/post.php?type=myPostList', //仅为示例，并非真实接口地址。
					data: {
						uid: auid,
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
							this.weikong=true;
						}else{
							this.postList=res.data.data;
							this.status ="more";
							this.weikong=false;
							console.log(this.postList);
						}
					}
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
		height: 76upx;
		overflow:hidden;
		text-overflow:ellipsis;
		display:-webkit-box;
		-webkit-box-orient:vertical;
		-webkit-line-clamp:2; 
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
		border-bottom:2px solid #79C498;
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
		height: 49upx;
		width: 116upx;
		font-size: 24upx;
		vertical-align:auto;
		line-height: 49upx;
		color: #FFFFFF;
		border-radius: 25upx;
		display: inline-block;
	}
	.button-edituserinfoXZ{
		background-color: #E5E4EA;
		height: 49upx;
		width: 116upx;
		font-size: 24upx;
		vertical-align:auto;
		line-height: 49upx;
		color: #999999;
		border-radius: 25upx;
		display: inline-block;
	}
	.button-sixin{
		width: 49upx;
		background-color: #79C498;
		height: 49upx;
		border-radius: 50%;
		display: inline-block;
		margin-right: 6upx;
		background-image: url('../../static/sixin.png');
		background-size: 100% 100%;
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
		grid-template-columns: 25% 45% 30%;
		grid-template-rows: 50% 50%;
		flex-wrap: nowrap;
		align-items: center;
	}
	.lstouxiang{
		width: 132upx;
		height: 132upx;
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
