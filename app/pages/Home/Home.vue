<template>
	<view>
		<scroll-view :scroll-top="scrollTop" scroll-y class="DrawerPage" :class="modalName=='viewModal'?'show':''" enable-back-to-top="true" @scrolltolower="EndLoding" @scroll="cs">
			<cu-custom bgColor="bg-white" :isBack="false">
				<block slot="content">安个利</block>
			</cu-custom>
			<!-- 搜索框 -->
			<view class="cu-bar bg-white search fixed" :style="[{top:CustomBar + 'px'}]" id="topbox" style="box-shadow:0 0rpx 0rpx rgba(0, 0, 0, 0.1);">
				<view class="search-form round">
					<text class="cuIcon-search"></text>
					<input type="text" disabled="true" placeholder="搜索热门话题" confirm-type="search" @click="sousuo"></input>
				</view>
				<view class="action" style="position: relative;">
					<image src="../../static/caidana.png" @tap="showModal" data-target="viewModal" mode="aspectFit" style="width: 45upx; height: 42upx;"></image>
					<image src="../../static/hongdain.png" v-if="msgNumber>0" mode="aspectFit" class="hongdiana"></image>
				</view>
			</view>
		
			<!-- 搜索框结束 -->
			<!-- 导航栏 -->
	<view :style="[{height:gaodu+ 'px'}]"></view>
	<view class="daohangBox">
			<view class="daohang" >
				<view class="hang" :class="TabCur==0?'hangIng':''" @tap="tabSelect(0)">最新</view>
				<view class="hang" :class="TabCur==1?'hangIng':''" @tap="tabSelect(1)">最热</view>
				<view class="hang" :class="TabCur==2?'hangIng':''" @tap="tabSelect(2)">关注</view>
			</view>
	</view><view class="daohang" >占位元素</view>
	
			<block v-if="TabCur==0">
				<view class="postList" v-for="(list,index) in postList" :key="index">
					<view class="user">
						<view class="touxiang" :style="{'background-image':'url('+list.AuthorInfo.AuthorAvatarUrl+')'}" @click="getbieren(list.AuthorId)"><view class="vipLogo" v-if="list.AuthorInfo.VIPEndTime>0"></view></view>
						<view :class="list.AuthorInfo.VIPEndTime>0?'vipUserName':'userName'">{{list.AuthorInfo.AuthorName}}</view>
						<view class="postDate">{{list.PsotDate}}</view>
					</view>
					<view class="postText" @tap="getpostinfo(list.PostsId)"><text decode="false" selectable="true" space="nbsp" class="text-c">{{list.Content}}</text></view>
					<block v-if="list.PictureId[0].length>5">
						<view class="postImage" >
							<image v-for="(img,id) in list.PictureId" :key="id" class="postImageItem" :src="img"  @tap="showImage(list.PictureId,id)" mode="aspectFill"></image>
						</view>
					</block>
					
					<view class="postBottom">
						<view class="postClass" @tap="getClass(list.Tag.ClassId)">#{{list.Tag.ClassName}}</view>
						<view>
							<view :class="[list.Give?'likeing':'like']"  @click="Like(list.PostsId,list.AuthorId,list.Give,index,list.ZhongcaoCount)"></view><view class="postviewcount" v-if="list.ZhongcaoCount>0" @click="Like(list.PostsId,list.AuthorId,list.Give,index,list.ZhongcaoCount)">{{list.ZhongcaoCount}}</view>
						</view>
						<view class="postMenu" @click="caidan(list)"><image src="../../static/caidan.png" mode="aspectFit" style="height: 40upx;"></image></view>
					</view>
				</view>
				<uni-load-more :status="status" />
			</block>
			<block v-if="TabCur==1">
				<view class="postList" v-for="(list,index) in postList" :key="index">
					<view class="user">
						<view class="touxiang" :style="{'background-image':'url('+list.AuthorInfo.AuthorAvatarUrl+')'}"  @click="getbieren(list.AuthorId)"><view class="vipLogo" v-if="list.AuthorInfo.VIPEndTime>0"></view></view>
						<view :class="list.AuthorInfo.VIPEndTime>0?'vipUserName':'userName'">{{list.AuthorInfo.AuthorName}}</view>
						<view class="postDate">{{list.PsotDate}}</view>
					</view>
					<view class="postText" @tap="getpostinfo(list.PostsId)"><text decode="false" selectable="true" space="nbsp" class="text-c">{{list.Content}}</text></view>
					<block v-if="list.PictureId[0].length>5">
						<view class="postImage" >
							<image v-for="(img,id) in list.PictureId" :key="id" class="postImageItem" :src="img"  @tap="showImage(list.PictureId,id)" mode="aspectFill"></image>
						</view>
					</block>
					
					<view class="postBottom">
						<view class="postClass" @tap="getClass(list.Tag.ClassId)">#{{list.Tag.ClassName}}</view>
						<view>
							<view :class="[list.Give?'likeing':'like']"  @click="Like(list.PostsId,list.AuthorId,list.Give,index,list.ZhongcaoCount)"></view><view class="postviewcount" v-if="list.ZhongcaoCount>0" @click="Like(list.PostsId,list.AuthorId,list.Give,index,list.ZhongcaoCount)">{{list.ZhongcaoCount}}</view>
						</view>
						<view class="postMenu" @click="caidan(list)"><image src="../../static/caidan.png" mode="aspectFit" style="height: 40upx;"></image>
						</view>
					</view>
				</view>
				<uni-load-more :status="status" />
			</block>
			<block v-if="TabCur==2">
				<template  v-if="weikong==false">
				<view class="postList" v-for="(list,index) in postList" :key="index">
					<view class="user">
						<view class="touxiang" :style="{'background-image':'url('+list.AuthorInfo.AuthorAvatarUrl+')'}"  @click="getbieren(list.AuthorId)"><view class="vipLogo" v-if="list.AuthorInfo.VIPEndTime>0"></view></view>
						<view :class="list.AuthorInfo.VIPEndTime>0?'vipUserName':'userName'">{{list.AuthorInfo.AuthorName}}</view>
						<view class="postDate">{{list.PsotDate}}</view>
					</view>
					<view class="postText" @tap="getpostinfo(list.PostsId)"><text decode="false" selectable="true" space="nbsp" class="text-c">{{list.Content}}</text></view>
					<block v-if="list.PictureId[0].length>5">
						<view class="postImage" >
							<image v-for="(img,id) in list.PictureId" :key="id" class="postImageItem" :src="img"  @tap="showImage(list.PictureId,id)" mode="aspectFill"></image>
						</view>
					</block>
					<view class="postBottom">
						<view class="postClass" @tap="getClass(list.Tag.ClassId)">#{{list.Tag.ClassName}}</view>
						<view>
							<view :class="[list.Give?'likeing':'like']"  @click="Like(list.PostsId,list.AuthorId,list.Give,index,list.ZhongcaoCount)"></view><view class="postviewcount" v-if="list.ZhongcaoCount>0" @click="Like(list.PostsId,list.AuthorId,list.Give,index,list.ZhongcaoCount)">{{list.ZhongcaoCount}}</view>
						</view>
						<view class="postMenu" @click="caidan(list)"><image src="../../static/caidan.png" mode="aspectFit" style="height: 40upx;"></image></view>
					</view>
				</view>
				<uni-load-more :status="status" />
				</template >
				<template v-else>
					<view class="nullBox">
						<image src="../../static/null.png" mode="" class="nullImg"></image>
						<text class="nullText">\n你还没有关注用户哦</text>
					</view>
				</template>
			</block>
			<template >
				<view></view>
			</template>
			<!-- 只有是iPhone，iOS系统才显示该按钮 -->
			<template v-if="shebei=='ios'||shebei=='devtools'||shebei=='android'">
			<!-- 超级菜单 -->
			<view class="plusmenu" style="width: 100upx;height: 100upx;bottom:180upx;right: 30upx;z-index: 30;">
				<button class="cu-btn cuIcon " style="width: 100upx;height: 100upx;background-color: #79C498;box-shadow:0 0 20upx 0 #555555;" @tap="plusbutton">
					<text class="cuIcon-more" style="font-size: 60upx;color: #fff;"></text>
				</button>
				<image src="../../static/hongdain.png" v-if="msgNumber>0" mode="aspectFit" class="hongdiana2"></image>
			</view>
			<view class="plusmenubihe"  :class="openmenu?'plusmenuopen2':'plusmenubihe'">
				<button class="cu-btn cuIcon " style="width: 100upx;height: 100upx;background-color: #79C498;" @tap="sousuo">
					<text class="cuIcon-search" style="font-size: 50upx;color: #fff;"></text>
				</button>
			</view>
			<view class="plusmenubihe"  :class="openmenu?'plusmenuopen':'plusmenubihe'">
				<button class="cu-btn cuIcon " style="width: 100upx;height: 100upx;background-color: #79C498;" @tap="showModal" data-target="viewModal">
					<text class="cuIcon-sort" style="font-size: 50upx;color: #fff;"></text>
				</button>
				<image src="../../static/hongdain.png" v-if="msgNumber>0" mode="aspectFit" class="hongdiana2"></image>
			</view>
			</template>
			<!-- 发送帖子 -->
			<view class="plus" style="width: 100upx;height: 100upx;">
				<button class="cu-btn cuIcon " style="width: 100upx;height: 100upx;background-color: #79C498;box-shadow:0 0 20upx 0 #555555" @tap="pluspost">
					<text class="cuIcon-add" style="font-size: 70upx;color: #fff;"></text>
				</button>
			</view>
		</scroll-view>	
		
		<view class="cu-modal" :class="modalName=='DialogModal2'?'show':''">
			<view class="cu-dialog">
				<view class="cu-bar bg-white justify-end">
					<view class="content">关于安个利测试通知</view>
					<view class="action" @tap="hideModal">
						<text class="cuIcon-close text-red"></text>
					</view>
				</view>
				<view class="padding-xl">
					{{systemConfig}}
				</view>
				<view class="cu-bar bg-white">
					<view class="action margin-0 flex-sub  solid-left" @tap="hideModal">确定</view>
				</view>
			</view>
		</view>
		<!-- 右侧菜单栏 -->
		<view class="DrawerClose" :class="modalName=='viewModal'?'show':''" @tap="hideModal">
			<text class="cuIcon-pullright"></text>
		</view>
		<view class="DrawerWindow " :class="modalName=='viewModal'?'show':''">
			<view class='Luserinfo' >
				<view class="infoo" @tap="reguser">
					<view class="lstouxiang" style="background-image: url(http://img1.doubanio.com/view/group_topic/raw/public/p158668038.jpg);" :style="{'background-image':'url('+AvatarUrl+')'}"><view class="vipLogo listvip" v-if="userInfo.VIPEndTime>0"></view></view>
					<view class="xinxi">
						<text class="listnamea">{{username}}</text>
						<text>\nID:{{userid}}</text>
						<text>\n等级：{{dengji}}</text>
					</view>
				</view>
				<view class="sange">
						<text class="Maxnum" @click="guanzhua">{{guanzhu}}</text>
						<text class="Maxnum" @click="fensia">{{fensi}}</text>
						<text class="Maxnum" >{{zhongcao}}</text>
						<text @click="guanzhua">关注</text>
						<text @click="fensia">粉丝</text>
						<text >种草</text>
				</view>
			</view>
			<view style="background-color: #FFFFFF;width: 100%;top: 65%;height: 100%;" >
				<view class="cu-list menu card-menu margin-top-xl margin-bottom-xl shadow-lg" >
					<view class="menuListBox">
						<view class="menuItem" @tap="reguser">
							<view class="menuIcon aicon-userList" ></view>
							<view class="menuTiele">个人中心</view>
							<view class="menuRight"></view>
						</view>
						<view class="menusolid"></view>
						<view class="menuItem" @tap="getMessage">
							<view class="menuIcon aicon-Msg" ></view>
							<view class="menuTiele">消息通知</view>
							<view class="menuRight">{{msgNumber<=0?'':'+'+msgNumber}}</view>
						</view>
						<view class="menusolid"></view>
						<view class="menuItem" @tap="getFriend">
							<view class="menuIcon aicon-friend" ></view>
							<view class="menuTiele">我的老铁</view>
							<view class="menuRight"></view>
						</view>
						<!-- <view class="menusolid"></view>
						<view class="menuItem" @tap="getShoucang">
							<view class="menuIcon aicon-keep"></view>
							<view class="menuTiele">我的种草</view>
							<view class="menuRight"></view>
						</view> -->
					</view>
					<view class="solidMax"></view>
					<view class="menuListBox">
						<template v-if="shebei=='devtools'||shebei=='android' ||iosapy=='yes'">
						<view class="menuItem" @tap="wallet">
							<view class="menuIcon aicon-qianbao"></view>
							<view class="menuTiele">我的钱包</view>
							<view class="menuRight"></view>
						</view>
						<view class="menusolid"></view>
						<view class="menuItem" @click="getVip">
							<view class="menuIcon aicon-vip"></view>
							<view class="menuTiele">会员中心</view>
							<view class="menuRight"></view>
						</view>
						<view class="menusolid"></view>
						</template>
						<view class="menuItem" @click="getJifen">
							<view class="menuIcon aicon-jifen"></view>
							<view class="menuTiele">种草排行</view>
							<view class="menuRight"></view>
						</view>
						<!-- <view class="menusolid"></view>
						<view class="menuItem" @tap="getshop">
							<view class="menuIcon aicon-shop"></view>
							<view class="menuTiele">Angeli Shop</view>
							<view class="menuRight"></view>
						</view> -->
						<view class="menusolid"></view>
						<view class="menuItem" @tap="set">
							<view class="menuIcon aicon-set"></view>
							<view class="menuTiele">设置</view>
							<view class="menuRight"></view>
						</view>
						<!-- <view class="menusolid"></view>
						<view class="menuItem" @tap="logout">
							<view class="menuIcon aicon-set"></view>
							<view class="menuTiele">退出登录</view>
							<view class="menuRight"></view>
						</view> -->
					</view>
				</view>
			</view>
		</view>
		
		<!-- 举报理由框框 -->
		<view class="show-jubao shadow shadow-lg bg-blue" v-if="showJubao">
			<textarea maxlength="300"  @input="jubaoliyouinput" placeholder="请输入举报理由" class="jubao-input"></textarea>
			<text class="jubao-anniu2" @click="showJubao=false"> 取消</text>
			<text class="jubao-anniu" @click="sendjubao"> 提交</text>
		</view>
		
		<!-- 分享弹出框 -->
		<view class="bt-fenxiang" v-if="showAppFenxiang">
			<text class="fx-title">选择分享到\n</text>
			<image src="../../static/WeChatpayicon.png" mode="aspectFit" @click="appFenxiang(2,0)"></image>
			<image src="../../static/pyq.png" mode="aspectFit" @click="appFenxiang(3,0)"></image>
			<image src="../../static/qq.png" mode="aspectFit" @click="appFenxiang(4,0)"></image>
			<image src="../../static/link.png" mode="aspectFit" @click="appFenxiang(1,0)"></image>
			<text class="fx-guanbi" @click="appFenxiang(0,0)">关闭</text>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	//import uniFab from '@/components/uni-fab.vue';
	import uniLoadMore from '@/components/uni-load-more.vue';
	export default {
		components: {  
			uniLoadMore,
		},
		data() {
			return {
				Dindex:[],
				
				CustomBar: this.CustomBar,
				modalName: null,
				AvatarUrl:"",
				TabCur: 0,
				showAppFenxiang:false,
				gaodu:0,
				msgNumber:0,
				weikong:true,
				username:"游客",
				page:1,
				postList:[],
				scrollTop: 0,
				old: {
					scrollTop: 0
				},
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
				},
				zhongcao:"0",
				guanzhu:"0",
				userid:"0",
				fensi:"0",
				dengji:"0",
				tuijianren:0,
				systemConfig:'',
				index:false,
				userInfo:[],
				postype:'new',
				menuList:['分享给朋友', '生成海报', '举报'],
				openmenu:false,
				shebei:'',
				iosapy:'no',
				jubao:{
					postid:0,
					authorid:0
				},
				jubaoliyou:'',
				showJubao:false
			}
		},
		onShow:function(){
			// #ifdef APP-PLUS

			try {
			    server.token = uni.getStorageSync('token');
/* 				console.log('赋值后的Token：'+server.token,server.token)
				console.log('直接输出token：'+ uni.getStorageSync('token'), uni.getStorageSync('token')) */
			    if (server.token) {
			       console.log('赋值后的Token：'+server.token,server.token)
			    }
			} catch (e) {
			    uni.showToast({
			    	title: '身份已失效，请重新登录',
			    	position:'bottom',
			    	icon:'none'
			    })
				console.log(e)
			}
			try {
			    server.userinfo = uni.getStorageSync('user');
				/* console.log('赋值后的Token：'+server.token,server.token) */
			    if ( server.userinfo) {
			       console.log('赋值后的userinfo：'+server.userinfo,server.userinfo)
			    }
			} catch (e) {
			    uni.showToast({
			    	title: '身份已失效，请重新登录',
			    	position:'bottom',
			    	icon:'none'
			    })
				console.log(e)
			}

			// #endif
			uni.request({
				method:'GET',
				url: "https://api.angeli.top/user.php?type=getMyNoRead",
				data: {
					auid:this.userid,
					token:server.token
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
				},
				success: (res) => {
					this.iosapy=res.data.data.pay
					if(res.data.code=="1"){
						this.msgNumber=res.data.data.count
					}else{
			
					}
				}
			});
			// #ifdef APP-PLUS
			//获取个人信息
			uni.request({
				method:'GET',
				url: "https://api.angeli.top/user.php?type=getMyinfo",
				data: {
					auid:this.userid,
					token:server.token
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
				},
				success: (res) => {
					if(res.data.code=='1'){
						this.AvatarUrl=res.data.data.AvatarUrl;
						this.username=res.data.data.UserName;
						this.zhongcao=res.data.data.ZhongcaoCount;
						this.guanzhu=res.data.data.FollowedCount;
						this.fensi=res.data.data.FollowerCount;
						this.dengji=res.data.data.Rank;
						this.userid=res.data.data.Auid;
						this.userInfo=res.data.data;
						server.userinfo=res.data.data;
					}else{
						this.AvatarUrl='https://c-ssl.duitang.com/uploads/item/201807/01/20180701122340_uxlwc.thumb.700_0.jpeg';
						this.username='未登录';
						this.zhongcao=0;
						this.guanzhu=0;
						this.fensi=0;
						this.dengji=0;
						this.userid=0;
						this.userInfo=null;
					}
				}
			});
			// #endif
		},
		onLoad:function(e){
			// #ifdef APP-PLUS
			
			try {
				server.token = uni.getStorageSync('token');
				/* 	console.log('赋值后的Token：'+server.token,server.token)
				console.log('直接输出token：'+ uni.getStorageSync('token'), uni.getStorageSync('token')) */
				if (server.token) {
				   console.log('赋值后的Token：'+server.token,server.token)
				}
			} catch (e) {
				uni.showToast({
					title: '身份已失效，请重新登录',
					position:'bottom',
					icon:'none'
				})
				console.log(e)
			}
			try {
				server.userinfo = uni.getStorageSync('user');
				/* console.log('赋值后的Token：'+server.token,server.token) */
				if ( server.userinfo) {
				   console.log('赋值后的userinfo：'+server.userinfo,server.userinfo)
				}
			} catch (e) {
				uni.showToast({
					title: '身份已失效，请重新登录',
					position:'bottom',
					icon:'none'
				})
				console.log(e)
			}
			
			//获取最新的版本号
			
			if(!server.update){
				setTimeout(this.getVersion,4000)
			}

			// #endif
			
			//setTimeout(this.getVersion(), 3000)
			this.shebei=uni.getSystemInfoSync().platform;
			uni.getSystemInfo({
			    success: function (res) {
					var systemjson={
						phonebrand:res.brand+res.model,
						phonesystem:res.system
					}
					server.system=JSON.stringify(systemjson);
					console.log(server.system)
			    }
			});
			this.tuijianren=e.tuijianid;
			server.tgid=this.tuijianren
			this.postList=[];
			console.log("推荐人ID",this.tuijianren)
			
			// #ifdef  APP-PLUS
			this.getPostData('new',0);
			// #endif
			//上面只在APP，下面仅在微信小程序
			// #ifdef MP-WEIXIN
			if(server.token==""||server.token==null){
				uni.login({
				    provider: 'weixin',
				    success: (res) => {
						console.log(res);
						uni.request({
							method:'POST',
							url: 'https://api.angeli.top/reg.php?type=wxlogin', //仅为示例，并非真实接口地址。
							data: {
								code: res.code,
								tuijianId:this.tuijianren
							},
							header: {
								'content-type': 'application/x-www-form-urlencoded',
								'system':server.system
							},
							success: (res) => {
								console.log(res);
								if(res.data.code=="0"){
									server.usersk=res.data.data.session_key
									uni.showToast({
										title: "欢迎你，游客",
										position:'bottom',
										icon:'none'
									});
									this.AvatarUrl="https://sz.oss.data.angeli.top/angeli-image/1562320238188110.png";
								}else if(res.data.code=="1" ||res.data.code=="2"){
									this.AvatarUrl=res.data.data.AvatarUrl;
									this.username=res.data.data.UserName;
									this.zhongcao=res.data.data.ZhongcaoCount;
									this.guanzhu=res.data.data.FollowedCount;
									this.fensi=res.data.data.FollowerCount;
									this.dengji=res.data.data.Rank;
									this.userid=res.data.data.Auid;
									this.userInfo=res.data.data;
									server.token=res.data.token;
									server.userinfo=res.data.data;
									
									if(res.data.code=="2"){
										uni.showToast({
											title: res.data.msg,
											position:'bottom',
											icon:'none'
										})
									}else{
										if(this.userInfo.VIPEndTime>0){
											uni.showToast({
												title: '欢迎VIP：'+this.username,
												position:'bottom',
												icon:'none'
											})
										}else{
											uni.showToast({
												title: '欢迎你,'+this.username,
												position:'bottom',
												icon:'none'
											})
											this.getPostData('new',0);
										}
									}
									
								}
							},
							complete: () => {
								this.getPostData('new',0);
							},
							fail:(src) =>{
								uni.showToast({
									title: "未知原因，登录失败！",
									position:'bottom',
									icon:'none'
								})
								this.getPostData('new',0);
							},
						});
				    },
				    fail: (err) => {
						uni.showToast({
							title: "登录失败呢！关闭之后重新打开吧！",
							position:'bottom'
						}),
				        console.error('授权登录失败：' + JSON.stringify(err));
						this.getPostData('new',0);
				    }
				})
			}else{
				if(server.userinfo.VIPEndTime>0){
					uni.showToast({
						title: '欢迎VIP：'+server.userinfo.username,
						position:'bottom',
						icon:'none'
					})
				}else{
					uni.showToast({
						title: '欢迎你,'+server.userinfo.username,
						position:'bottom',
						icon:'none'
					})
					
				}
				this.getPostData('new',0);
			}
		
			// #endif
			this.getSysConfig('home_txt');
			//this.getPostData('new',0);
			
			this.shebei=uni.getSystemInfoSync().platform;
			console.log(this.shebei);
		},
		onReady: function() {
			this.getHei()
			
			
		},
		methods: {
			appFenxiang:function(id,type){
				switch (id){
					case 0:
						this.showAppFenxiang=false
						break;
					case 1:
						uni.setClipboardData({
							data: 'http://share.angeli.top/?postId='+this.postid,
							success: function () {
								console.log('success');
							}
						});
						console.log('复制链接')
						break;
					case 2:
						console.log(this.Dindex.PictureId[0])
						console.log(this.Dindex.Content)
						console.log('http://share.angeli.top/?postId='+this.Dindex.PostsId)
						uni.share({
						    provider: 'weixin',
						    scene: "WXSceneSession",
						    type: 5,
						    imageUrl: this.Dindex.PictureId[0],
						    title: this.Dindex.Content,
						    miniProgram: {
						        id: 'gh_a38adc10b952',
						        path: 'pages/postinfo/postinfo?id='+this.Dindex.PostsId,
						        type: 0,
						        webUrl: 'http://share.angeli.top/?postId='+this.Dindex.PostsId
						    },
						    success: ret => {
						        console.log(JSON.stringify(ret));
						    },
							fail: function (err) {
							    console.log("fail:" + JSON.stringify(err));
							}
						});
						break;
					case 3:
						uni.share({
						    provider: "weixin",
						    scene: "WXSenceTimeline",
						    type: 0,
						    href: 'http://share.angeli.top/?postId='+this.Dindex.PostsId,
						    title: this.Dindex.Content,
						    summary: this.Dindex.Content,
						    imageUrl: this.Dindex.PictureId[0],
						    success: function (res) {
						        console.log("success:" + JSON.stringify(res));
						    },
						    fail: function (err) {
						        console.log("fail:" + JSON.stringify(err));
						    }
						});
						break;
					case 4:
						uni.share({
						    provider: "qq",
						    type: 1,
							href:'http://share.angeli.top/?postId='+this.Dindex.PostsId,
						    summary: this.Dindex.Content,
							title:this.Dindex.Content,
							imageUrl: this.Dindex.PictureId[0],
						    success: function (res) {
						        console.log("success:" + JSON.stringify(res));
						    }, 
						    fail: function (err) {
						        console.log("fail:" + JSON.stringify(err));
						    }
						});
						break;
					default:
						
				}
			},
			getVersion:function(){
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=getVersion", 
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							if(server.Version<res.data.data.version){
								console.log(res.data.data)
								uni.showModal({
								    title: '应用更新',
								    content: '发现安个利新版本，是否安装升级？',
								    success: (ok) => {
								        if (ok.confirm) {
											console.log(res.data.data.downloadUrl)
								            plus.runtime.openURL(res.data.data.downloadUrl)
											server.update=true
								        }
								    }
								});
							}
						}
					},
				});
			},
			sendjubao:function(){
				
				this.$jubao(this.jubao.postid,server.userinfo.Auid,this.jubao.authorid,this.jubaoliyou);
				this.showJubao=false;
				uni.showToast({
					title:'举报成功',
					position:'bottom',
					icon:'none'
				});
			},
			plusbutton:function(){
				this.openmenu=this.openmenu?false:true;
			},
			guanzhua:function(){
				uni.navigateTo({
					url: '../menu/guanzhu'
				})
			},
			jubaoliyouinput:function(res){
				this.jubaoliyou=res.target.value 
			},
			fensia:function(){
				uni.navigateTo({
					url: '../menu/newFans'
				})
			},
			aotuloding:function(){
				uni.showToast({
					title: "刚刚出现问题，已经为你修复",
					position:'bottom',
					icon:'none',
					position:'center',
				})
				this.getPostData('new',0);
			},
			getshop:function(){
				this.systemConfig="商城正在建设中"
				this.modalName = 'DialogModal2'
			},
			getSysConfig:function(name){
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=getSysConfig", //仅为示例，并非真实接口地址。
					data: {
						configName:name,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							this.systemConfig=res.data.data;
							if(!res.data.data){
								this.modalName = ''
							}else{
								this.modalName = 'DialogModal2'
							}
						}
						console.log(this.msgNumber)
					},
					complete() {
						
					}
				});
			},
			getFriend:function(){
				uni.navigateTo({
					url: '../menu/friend'
				})
			},
			getbieren:function(e){
				if(e==server.userinfo.Auid){
					uni.navigateTo({
						url: '../i/i'
					})
				}else{
					uni.navigateTo({
						url: '../i/bieren?auid='+e
					})
				}
			},
			getShoucang:function(){
				uni.navigateTo({
					url: '../menu/shoucang'
				})
			},
			getJifen:function(){
				uni.navigateTo({
					url: '../menu/jifen'
				})
			},
			getMessage:function(){
				uni.navigateTo({
					url: '../menu/Message'
				})
			},
			wallet:function(){
				uni.navigateTo({
					url: '../menu/wallet'
				})
			},
			set:function(){
				uni.navigateTo({
					url: '../set/set'
				})
			},
			logout:function(){
				uni.clearStorage();
				uni.showToast({
					title: "已退出登录",
					position:'bottom',
					icon:'none',
					position:'center',
				})
				server.userinfo=[];
				server.cookie="";
				this.modalName = null

			},
			cs:function(e){
				this.old.scrollTop = e.detail.scrollTop
			},
			sousuo:function(){
				uni.navigateTo({
					url: '../sousuo/sousuo'
				})
			},
			getClass:function(id){
				uni.navigateTo({
					url: '../classPost/classPost?id='+id
				})
			},
			showModal(e) {
				this.modalName = e.currentTarget.dataset.target
				this.AvatarUrl=server.userinfo.AvatarUrl;
				this.username=server.userinfo.UserName;
				this.zhongcao=server.userinfo.ZhongcaoCount;
				this.guanzhu=server.userinfo.FollowedCount;
				this.fensi=server.userinfo.FollowerCount;
				this.dengji=server.userinfo.Rank;
				this.userid=server.userinfo.Auid;
				console.log("测试！：",this.username)
				//请求 https://api.angeli.top/user.php?type=getMyNoRead
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=getMyNoRead", 
					data: {
						auid:this.userid,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							this.msgNumber=res.data.data.count
						}else{

						}
						console.log(this.msgNumber)
					},
					complete() {
						
					}
				});
			},
			hideModal(e) {
				this.modalName = null
			},
			tabSelect(e) {
				this.TabCur = e
				this.scrollTop = this.old.scrollTop
				this.$nextTick(function() {
					this.scrollTop = 0
				});
				console.log(e)
				if(e==0){ //最新
					this.getPostData('new',0);
					this.postype='new'
				}else if(e==1){ //最热
					this.postype='hot'
					this.getPostData('hot',0);
					
				}else if(e==2){ //关注
					this.postype='guanzhu'
					this.getPostData('guanzhu',0);
				}
				
			},
			Like:function(postid,auid,give,index,zc){
				if(server.token==""||server.token==null){
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
					return;
				}
				
				if(auid==server.userinfo.Auid){
					uni.showToast({
						title: "不能给自己种草",
						position:'bottom',
						icon:'none'
					});
					return;
				}
				
				if(give===true){
					var modea='del'
				}else{
					var modea='add'
				}
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=Like", 
					data: {
						fuid: auid,
						postid:postid,
						mode:modea,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'system':server.system
					},
					success: (res) => {
						if(res.data.code=="1"){
							if(modea=='add'){
								this.postList[index].ZhongcaoCount=Number(zc)+1;
								this.postList[index].Give=true;
								uni.showToast({
									title: "种草成功！",
									position:'bottom',
									icon:'none'
								});
							}else{
								this.postList[index].ZhongcaoCount=Number(zc)-1;
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
			caidan:function(resa){
				console.log(resa)
				this.Dindex=resa;
				if(resa.AuthorId==server.userinfo.Auid){
					this.menuList=['分享', '举报','删除帖子']
				}else{
					this.menuList=['分享', '举报']
				}
				uni.showActionSheet({
					itemList:this.menuList,
					success: (res) =>{
						switch(res.tapIndex){
							case 0:
								this.showAppFenxiang=true
								break;
							case 1:
								this.showJubao=true;
								this.jubao.postid=resa.PostsId;
								this.jubao.authorid=resa.AuthorId
								
								
								break;
							case 2:
								this.$delPost(resa.PostsId);
								break;
							default:
								
						}
					},
					fail: function (res) {
						console.log(res.errMsg);
					}
				});
			},
			getHei(){
				
				let view = uni.createSelectorQuery().select("#topbox");
				view.fields({
						  size: true,
						  scrollOffset: true
						}, data => {
						  this.gaodu=data.height-5;
						  console.log("总高度：" + this.gaodu);
						}).exec();
			},
			showImage(res,c){
				var imageurl=res[c];
				uni.previewImage({
					current:c,
					urls: res,
					longPressActions: {
					itemList: ['保存图片'],
						success: (resa) => {
							
							uni.downloadFile({
							    url: imageurl, 
							    success: (res) => {
									console.log(res)
							        if (res.statusCode === 200) {
							           uni.saveImageToPhotosAlbum({
										   filePath: res.tempFilePath,
										   success: function () {
											  uni.showToast({
											  	title: '已保存',
											  	position:'bottom',
											  	icon:'none'
											  });
										   }
									   });
							        }else{
										uni.showToast({
											title: '保存失败',
											position:'bottom',
											icon:'none'
										});
									}
							    }
							});
							
						},
						fail: function (res) {
							console.log(res.errMsg);
						}    
					}
				})
			},
			getPostData(type,classId){
				console.log(type)
				this.page=1
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/post.php?type=outPostList',
					data: {
						page: 1,
						postType:type,
						count:10,
						classId:classId,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						
						this.postList=[]
						console.log(res.data.data);
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
					complete: () => {
						this.$forceUpdate();
					}
				});
				
			},
			reguser:function(){
				if(server.token==""||server.token==null){
					uni.navigateTo({
						url: '../reg/reg'
					})
				}else{
					uni.navigateTo({
						url: '../i/i'
					})
				}
			},
			getVip:function(){
				uni.navigateTo({
					url: '../vip/vip'
				})
			},
			pluspost:function(){
				console.log("发帖：",server.userinfo.Auid)
				if(server.token==""||server.token==null){
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
					uni.navigateTo({
						url: '../post/post'
					})
				}
				
			},
			EndLoding:function(e){
				this.page++;
				console.log(this.scrollTop)
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/post.php?type=outPostList', //仅为示例，并非真实接口地址。
					data: {
						page: this.page,
						postType:this.postype,
						sort:'PsotDate',
						count:10,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						console.log("————————————帖子列表——————————"+this.page);
						if(res.data.data.length==undefined){
							this.page--;
							this.status ="noMore";
						}else{
							this.postList=this.postList.concat(res.data.data);
							this.status ="loading";
							console.log(this.postList);
						}
						
						
					}
				});
			},
			getpostinfo:function(id){
				uni.navigateTo({
					url: '../postinfo/postinfo?id='+id
				});
			}
		},
		onShareAppMessage(res) {
			if (res.from === 'button') {// 来自页面内分享按钮
			  console.log(res.target)
			} 
			return {
			  title: "超级无敌安个利",
			  path:'/pages/Home/Home',
			  desc:"超级无敌安个利！"
			}
		}
	}
	
</script>

<style>
	page {
		background-image: url("https://sz.oss.data.angeli.top/angeli-image/156231483329545.png");
		width: 100vw;
		overflow: hidden;
	}
	.Maxnum{
		font-size: 28upx;
		font-weight: 700;

	}
	.sange{
		padding-top: 40upx;
		margin: 0upx;
		font-size: 24upx;
		width: 550upx;
		bottom: 20upx;
		display: grid;
		grid-template-columns: 33.33% 33.33% 33.33%;
		justify-items: center;
	}
	.infoo{
		display: flex;
		padding: 30upx;
		border-bottom:1px #F7F8FA solid;
	}
	.xinxi{
		margin-top: 10upx;
		margin-left: 30upx;
		line-height:40upx;
	}
	.listvip{
		width: 35upx;
		height: 35upx;
	}
	.lstouxiang{
		width: 132upx;
		height: 132upx;
		background-size:100% 100%;
		border-radius:50%;
		position: relative;
	}
	.hongdiana2{
		width: 16upx;
		height:16upx;
		position: absolute;
		right: 5upx;
		top: 5upx;
	}
	.plusmenubihe{
		position: fixed;
		bottom:180upx;
		right: 30upx;
		width: 100upx;
		height: 100upx;
		bottom:180upx;
	}
	.plusmenuopen{
		position: fixed;
		bottom:180upx;
		right: 140upx;
		width: 100upx;
		height: 100upx;
		bottom:180upx;
	}
	.plusmenuopen2{
		position: fixed;
		bottom:180upx;
		right: 250upx;
		width: 100upx;
		height: 100upx;
		bottom:180upx;
	}
	.plus{
		position: fixed;
		bottom:60upx;
		right: 30upx;
	}
	.plusmenu{
		position: fixed;
		bottom:180upx;
		right: 30upx;
	}
	
	.listtype{
		margin-left: 45upx;
		color: #555555;
		
	}
	.image{
		display: flex;
		flex-wrap: wrap;
		margin-bottom: 30upx;
		width: 100%;
		margin-left: 40upx;/* 
		margin-right: 30upx;
		margin-top: 30upx;
		margin-bottom: 10upx;
		height: 332upx; */
	}
	.listnamea{
		font-size: 40upx;
		padding-bottom: 10upx;
	}
	.daohangBox{
		width: 100%;
		position:fixed;
		z-index: 1000;
	}
	.lieimage{
		width: 30%;
		margin-top: 5upx;
		height: 220upx;
		padding-right: 5upx;
	}
	.listName{
		float:right;
		margin-top:10px;
		margin-bottom:10px;
	}
	.Listinfo{
		height: 100upx;
		display:flex;
	}
	.angelibox{
		width: 100%;
		background-color: #FFFFFF;
		margin-bottom:5px;
		padding-bottom: 15upx;
	}
	.txt{
		width: 100%;
		padding-top: 15upx;
		padding-left: 45upx;
		padding-bottom: 25upx;
		padding-right:40upx ;
	}
	.info{
		margin-left:15px;
		top:5px;
		width: 100%;
		float:right;
		
	}
.daohang{
	margin-top: 5upx;
	padding: 10upx;
	height: 70upx;
	display: flex;
	justify-content: space-around;
	background-color: #FFFFFF;
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
	
	.Luserinfo{
		top: 0upx;
		height: 300upx;
		margin-top:30upx;
		margin-bottom:20px;
		margin-right:5%;
		margin-left:5%;
		color: #FFFFFF;
	}
	.DrawerPage {
		position: fixed;
		height: 100vh;
		left: 0vw;
		background-color: #f1f1f1;
		transition: all 0.4s;
		
	}
	.heie{
		height: 500upx;
	}
	.DrawerPage.show {
		transform: scale(0.9, 0.9);
		left: 85vw;
		box-shadow: 0 0 60upx rgba(0, 0, 0, 0.2);
		transform-origin: 0;
	}
	
	.DrawerWindow {
		position: absolute;
		width: 85vw;
		height: 100vh;
		left: 0;
		top: 0;
		transform: scale(0.9, 0.9) translateX(-100%);
		opacity: 0;
		pointer-events: none;
		transition: all 0.4s;
		padding: 100upx 0;
	}
	
	.DrawerWindow.show {
		transform: scale(1, 1) translateX(0%);
		opacity: 1;
		pointer-events: all;
	}
	
	.DrawerClose {
		position: absolute;
		width: 40vw;
		height: 100vh;
		right: 0;
		top: 0;
		color: transparent;
		padding-bottom: 30upx;
		display: flex;
		align-items: flex-end;
		justify-content: center;
		background-image: linear-gradient(90deg, rgba(0, 0, 0, 0.01), rgba(0, 0, 0, 0.6));
		letter-spacing: 5px;
		font-size: 50upx;
		opacity: 0;
		pointer-events: none;
		transition: all 0.4s;
	}
	
	.DrawerClose.show {
		opacity: 1;
		pointer-events: all;
		width: 15vw;
		color: #fff;
	}
	
	.DrawerPage .cu-bar.tabbar .action button.cuIcon {
		width: 45upx;
		height: 42upx;
		margin: 0;
		display: inline-block;
		
	}
	.hongdiana{
		width: 16upx;
		height:16upx;
		position: absolute;
		right: -8upx;
		top: -8upx;
	}
	
	.DrawerPage .cu-bar.tabbar .action .cu-avatar {
		margin: 0;
	}
	
	.DrawerPage .nav {
		flex: 1;
	}
	
	.DrawerPage .nav .cu-item.cur {
		border-bottom: 0;
		position: relative;
	}
	
	.DrawerPage .nav .cu-item.cur::after {
		content: "";
		width: 10upx;
		height: 10upx;
		background-color: currentColor;
		position: absolute;
		bottom: 10upx;
		border-radius: 10upx;
		left: 0;
		right: 0;
		margin: auto;
	}
	
	.DrawerPage .cu-bar.tabbar .action {
		flex: initial;
	}
</style>
