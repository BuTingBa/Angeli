<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">帖子详情</block>
		</cu-custom>
		<view  :style="[{top:CustomBar + 'px'}]" class="postBox">
			<view class="postList">
				<view class="usera" @click="getbieren(postInfo.AuthorId)">
					<view class="touxiang" :style="{'background-image':'url('+postInfo.AuthorInfo.AuthorAvatarUrl+')'}" style="background-image:url(https://ossweb-img.qq.com/images/lol/web201310/skin/big25002.jpg);"></view>
					<view class="userName">{{postInfo.AuthorInfo.AuthorName}}</view>
					<view class="postDate">{{postInfo.PsotDate}}</view>
				</view>
				<view class="postText"><text decode="false" selectable="true" space="nbsp" >
					{{postInfo.Content}}</text>
				</view>
				<view class="postImage">
					<image class="postImageItem"  v-for="(img,id) in postInfo.PictureId" :key="id" :src="img"  @tap="showImage(postInfo.PictureId,id)" mode="aspectFill"></image>
				</view>
				<view class="postBottom">
					<view class="postClass">{{postInfo.Tag.ClassName}}</view>
					<view>
						<view :class="[postInfo.Give?'likeing':'like']"  @click="Like(postInfo.PostsId,postInfo.AuthorId,postInfo.Give,postInfo.ZhongcaoCount)"></view><view class="postviewcount" v-if="postInfo.ZhongcaoCount>0" @click="Like(postInfo.PostsId,postInfo.AuthorId,postInfo.Give,postInfo.ZhongcaoCount)">{{postInfo.ZhongcaoCount}}</view>
						
					</view>
					<view class="postMenu" @click="caidan"><image src="../../static/caidan.png" mode="aspectFit" style="height: 40upx;"></image></view>
				</view>
			</view>
			
		</view>
		<view class="zan">
			<view class="zananiu">
				 <button class="cu-btn round bg-red button-hover" @tap="showKaitong">赞赏</button>
			</view>
			<view class="zanlist" v-if="dslist==0">
				还没有人给过钱，快来做第一个给钱的吧！
			</view>
			<view class="dashangList" @click="getList" v-if="dslist.count>0">
				<view class="jiaozheng">
					<image v-for="(ds,id) in dslist.data" :key="id" :src="ds.fromAuid.AuthorAvatarUrl" mode="" class="dslist" >{{ds.fromAuid.AuthorAvatarUrl}}</image>
					
				</view>
				
			</view>
		</view>
		<view :class="showVip?'mask':''" :style="{background:yanse}" @click="hideVip"></view>
		<view class="pay" :style="{ bottom:gaodu}">
			<text class="titlea">打赏安个利币</text>
			<view class="vipBox">
				<view :class="xzId==1?'vipItemXZ':'vipItem'" @click="xuanze(1)">
					<text class="paynumber">1 安个利币</text>
				</view>
				<view :class="xzId==2?'vipItemXZ':'vipItem'" @click="xuanze(2)">
					<text class="paynumber">5 安个利币</text>
				</view>
				<view :class="xzId==3?'vipItemXZ':'vipItem'" @click="xuanze(3)">
					<text class="paynumber">10 安个利币</text>
				</view>
				<view :class="xzId==4?'vipItemXZ':'vipItem'" @click="xuanze(4)">
					<text class="paynumber">20 安个利币</text>
				</view>
			</view>
			<button class="Angeli" style="margin-top: 72upx;margin-bottom: 16upx;" @tap="getDashang">立即打赏</button>
			<view style="width: 100%;text-align: center;margin-bottom: 82upx;"><!-- <text style="font-size: 22upx;">购买即视为同意《安个利会员用户协议》</text> --></view>
		</view> 
		<!-- 评论列表 -->
		<view class="cu-list menu-avatar comment solids-top" >
			<view class="cu-item" v-for="(pl,index) in pllist" :key="index"  style="margin-top: -4upx;">
				<view class="cu-avatar round" :style="{'background-image':'url('+pl.userinfo.AuthorAvatarUrl+')'}" style="background-image:url(https://ossweb-img.qq.com/images/lol/img/champion/Morgana.png);"></view>
				<view class="content">
					<view class="text-grey">{{pl.userinfo.AuthorName}}</view>
					<view class=" text-content text-df">
						{{pl.Content}}
					</view>
					<view class="bg-grey padding-sm radius margin-top-sm  text-sm" v-for="(hf,xuhao) in pl.replyList" :key="xuhao">
						<view class="flex">
							<view>{{hf.ReplyUid.AuthorName}}: </view>
							<view class="flex-sub">{{hf.ReplyContent}}</view>
						</view>
					</view>
					<view class="margin-top-sm flex justify-between">
						<view class="text-gray text-df">{{pl.Time}}</view>
						<view>
							<text class="cuIcon-appreciatefill  text-red" @tap="dianzan(pl.CommentsId,index)" v-model="Give">{{pl.Give}}</text>
							<text class="cuIcon-messagefill text-gray margin-left-sm" @tap="huifua(pl.CommentsId,pl.userinfo.AuthorName,pl.CommentsId,pl.FromUid)"></text>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view style="margin-top: 50upx;height: 50upx;text-align: center;">没有更多评论了！</view>
		<!-- 撰写评论 -->
		<view class="cu-bar foot input " :style="[{bottom:InputBottom+'px'}]" style="z-index: 777;">
			<!-- <view class="action">
				<text class="cuIcon-sound text-grey"></text>
			</view> -->
			<input class="solid-bottom" :adjust-position="false" :focus="false" maxlength="300" cursor-spacing="10"
			 @focus="InputFocus" @blur="InputBlur" @input="pinglun" v-model="setvar"></input>
			<!-- <view class="action">
				<text class="cuIcon-emojifill text-grey"></text>
			</view> -->
			<button class="cu-btn bg-green shadow" @tap="sendpl">评论</button>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				postInfo:[],
				pllist:[],
				dslist:[],
				showVip:false,
				InputBottom: 0,
				plnr:"",
				xzId:1,
				postid:"",
				setvar:"",
				Give:"0",
				huifu:false,
				gaodu:'-710px',
				yanse:'rgba(0,0,0,0)',
				pluid:"",
				monnumber:1
			}
		},
		onShareAppMessage(res) {
			if (res.from === 'button') {// 来自页面内分享按钮
			  console.log(res.target)
			}
			return {
			  title: this.postInfo.Content,
			  path:'/pages/postinfo/postinfo?id='+this.postid,
			  desc:this.postInfo.Content,
			  imageUrl:this.postInfo.PictureId[0]
			}
		},
		onLoad: function (option) { //option为object类型，会序列化上个页面传递的参数
			console.log(option.id); //打印出上个页面传递的参数。
			this.postid=option.id;
			this.getdsList(this.postid);
			uni.showLoading({
				title: '加载中'
			});
			uni.request({
				method:'GET',
				url: 'https://api.angeli.top/post.php?type=outPostInfo', 
				data: {
					id: option.id
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
					'Cookie':server.cookie
				},
				success: (res) => {
					console.log("————————————帖子详情——————————");
					this.postInfo=res.data.data;
					console.log(this.postInfo);
					uni.request({
						method:'GET',
						url: 'https://api.angeli.top/post.php?type=getpl', 
						data: {
							postid: option.id
						},
						header: {
							'content-type': 'application/x-www-form-urlencoded',
							'Cookie':server.cookie
						},
						success: (res) => {
							console.log("————————————评论详情——————————");
							this.pllist=res.data.data;
							console.log(this.pllist);
							
						},
						complete() {
							uni.hideLoading();
						}
					});
				},
				complete() {
					
				}
			});
			
			console.log(this.dslist)
		},
		
		methods: {
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
			getdsList:function(postid){
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=getDashangList&postId="+postid, //仅为示例，并非真实接口地址。
					data: {
					
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.code=="1"){
							this.dslist=res.data.data
							console.log("打赏输出")
							console.log(this.dslist);
							this.$forceUpdate()
						}else{
							this.dslist=0;
						}
					},
					complete() {
						
					}
				});
			},
			getList:function(){
				uni.navigateTo({
					url:"dashang?id="+this.postid
				})
			},
			getDashang:function(){
				console.log(this.monnumber);
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=dashang", //仅为示例，并非真实接口地址。
					data: {
						toid: this.postInfo.AuthorId,
						postid:this.postid,
						number:this.monnumber
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.code=="1"){
							this.showVip=false;
							this.gaodu='-710px';
							this.yanse='rgba(0,0,0,0)'
							uni.showToast({
								title: "打赏成功！",
								position:'bottom',
								icon:'none'
							});
							
							this.$forceUpdate()
						}else{
							
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							});
						}
					},
					complete() {
						
					}
				});
				
			},
			hideVip:function(){
				this.showVip=false;
				this.gaodu='-710px';
				this.yanse='rgba(0,0,0,0)'
			},
			xuanze:function(e){
				switch (e){
					case 1:
						this.monnumber=1
						break;
					case 2:
						this.monnumber=5
						break;
					case 3:
						this.monnumber=10
						break;
					case 4:
						this.monnumber=20
						break;
					default:
						this.monnumber=1
						break;
				}
				this.xzId=e
				/* 新思路,点赞改变列表状态可以使用该方法,:class="当前ID是否等于列子ID，如果是那么就点赞，如果不是就没有点赞，"
				但是,这样只能存在一个点赞状态,其他的状态就会消失,带思考 */
			},
			showKaitong:function(){
				this.showVip=true
				this.gaodu='0px'
				this.yanse='rgba(0,0,0,0.4)'
			},
			Like:function(postid,auid,give,zc){
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
								this.postInfo.Give=true;
								this.postInfo.ZhongcaoCount=Number(zc)+1;
								uni.showToast({
									title: "种草成功！",
									position:'bottom',
									icon:'none'
								});
							}else{
								this.postInfo.Give=false;
								this.postInfo.ZhongcaoCount=Number(zc)-1;
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
			InputFocus(e) {
				this.InputBottom = e.detail.height
			},
			InputBlur(e) {
				this.InputBottom = 0
			},
			pinglun:function(e){
				this.plnr=e.target.value;
				if(this.plnr.indexOf('@')==0){
					this.huifu=true;
				}else{
					this.huifu=false;
				}
			},
			onShareAppMessage(res) {
				if (res.from === 'button') {// 来自页面内分享按钮
				  console.log(res.target)
				}
				return {
				  title: this.postInfo.Content,
				  path:'/pages/postinfo/postinfo?id='+this.postid,
				  desc:this.postInfo.Content,
				  imageUrl:this.postInfo.PictureId[0]
				}
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
			sendpl:function(e){
				console.log(this.plnr);
				if(this.plnr=="null" ||this.plnr==""){
					uni.showToast({
						title: "请输入评论内容！",
						position:'bottom',
						icon:'none',
						mask:true
					})
					return;
				}
				if(this.plnr.length>300 || this.plnr.length<1 ){
					uni.showToast({
						title: "内容最少1个字，最多300字",
						position:'bottom',
						icon:'none',
						mask:true
					})
					return;
				}
				
				if(this.huifuid && this.huifu==true){
					//回复评论模式
					//console.log("回复评论",this.plnr)
					let qian=this.plnr.substring(0,this.plnr.indexOf(':')+1);
					let nr=this.plnr.replace(qian,"");
					uni.request({
						method:'POST',
						url: 'https://api.angeli.top/post.php?type=hfpl', //仅为示例，并非真实接口地址。
						data: {
							id: this.huifuid,
							txt:nr,
							postid:this.postid,
							uid:this.pluid,
							auid:server.userinfo.Auid
						},
						header: {
							'content-type': 'application/x-www-form-urlencoded',
							'Cookie':server.cookie
						},
						success: (res) => {
							if(res.data.code=='1'){
								this.setvar="";
								uni.request({
									method:'GET',
									url: 'https://api.angeli.top/post.php?type=getpl', //仅为示例，并非真实接口地址。
									data: {
										postid: this.postid
									},
									header: {
										'content-type': 'application/x-www-form-urlencoded',
										'Cookie':server.cookie
									},
									success: (res) => {
										console.log("————————————评论详情——————————");
										this.pllist=res.data.data;
										console.log(this.pllist);
										
									}
								});
							}
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none',
								duration:2000,
								mask:true
							});
						}
					});
					
				}else{
					//评论模式
					uni.request({
						method:'POST',
						url: 'https://api.angeli.top/post.php?type=pl', //仅为示例，并非真实接口地址。
						data: {
							auid: server.userinfo.Auid,
							txt:this.plnr,
							postid:this.postid,
							uid:this.postInfo.AuthorId
						},
						header: {
							'content-type': 'application/x-www-form-urlencoded',
							'Cookie':server.cookie
						},
						success: (res) => {
							if(res.data.code=='1'){
								this.setvar="";
								uni.request({
									method:'GET',
									url: 'https://api.angeli.top/post.php?type=getpl', //仅为示例，并非真实接口地址。
									data: {
										postid: this.postid
									},
									header: {
										'content-type': 'application/x-www-form-urlencoded',
										'Cookie':server.cookie
									},
									success: (res) => {
										console.log("————————————评论详情——————————");
										this.pllist=res.data.data;
										console.log(this.pllist);
										
									}
								});
							}
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none',
								duration:2000,
								mask:true
							});
						}
					});
				}
			},
			dianzan:function(plid,index){
				console.log("点赞")
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/post.php?type=dianzan', //仅为示例，并非真实接口地址。
					data: {
						postid: plid
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.code=="1"){
							var give=this.pllist[index].Give;
							console.log(give);
							give++;
							console.log(give);
							this.$set(this.pllist[index],`Give`,give);
						}
						uni.showToast({
							title: res.data.msg,
							position:'bottom',
							icon:'none',
							duration:2000
						});
					}
				});
			},
			huifua:function(e,n,plid,uid){
				this.huifuid=e;
				this.setvar="@"+n+": ";
				this.huifu=true;
				this.pluid=uid;
				console.log("回复",e,n)

			}
		}
	}
</script>

<style>
	.dslist{
		width: 50upx;
		height: 50upx;
		border-radius: 50%;
		margin: 0 4upx;
		
	}
	.jiaozheng{
		text-align: center;
	}
	
	
	.dashangList{
		
		padding-top: 20upx;
		position: relative;
		height: 60upx;
		text-align: center;
	}
	.mask{
		position: fixed;
		top: 0upx;
		left: 0upx;
		width: 100%;
		height: 800upx;
		background: rgba(0,0,0,0.4);
		opacity:1;
		transition: all 0.5s 0.2s;
		z-index: 999;
	}
	.vipItem{
		width: 332upx;
		height: 169upx;
		background-color: #F7F8FA;
		color: #000000;
		font-size: 28upx;
		text-align: center;
		margin-top: 10upx;
		padding-top: 65upx;
		
	}
	.paynumber{
		
		font-size: 28upx;
		font-weight: 500;
	}
	.vipTitle{
		font-size: 24upx;
		margin-top: 16upx;
		margin-bottom: 16upx;
		color: #999999;
	}
	.vipItemXZ{
		width: 332upx;
		font-weight: 400;
		height: 169upx;
		background:rgba(121,196,152,0.3);
		color: #79C498;
		font-size: 28upx;
		text-align: center;
		margin-top: 10upx;
		padding-top: 65upx;
	}
	.vipBox{
		display: flex;
		justify-content:space-between;
		flex-wrap:wrap;
		padding-top: 20upx;
	}
	.titlea{
		margin: 16upx 0upx;
		font-size: 32upx;
		font-weight: Medium;
		
	}
	.pay{
		width: 100%;
		padding: 38upx;
		position: fixed;
		height: 706upx;
		bottom: -710upx;
		background-color: #FFFFFF;
		transition: all 0.4s;
		z-index: 1000;
	}
	.zan{
		margin-top: 1upx;
		padding: 20px;
		width: 100%;
		
		background-color: #FFFFFF;
		text-align: center;
	}
	.zanlist{
		margin-top: 20upx;
		
	}
	.usera{
		display: grid;
		grid-template-columns: 20% 80%;
		grid-template-rows: 50% 50%;
		align-items:center;
	}
	
	.pinglun{
		margin-top: 1upx;
		width: 100%;
		background-color: #FFFFFF;
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
	}
	.postBox{
		width: 100%;
		background-color: #FFFFFF;
	}
	.listtype{
		margin-left: 30upx;
		color: #555555;
		
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
		padding-left: 30upx;
		padding-bottom: 25upx;
		padding-right:30upx ;
	}
	.info{
		margin-left:15px;
		top:5px;
		width: 100%;
		float:right;
		
	}
	.touxiang{
		width: 90upx;
		height: 90upx;
		margin-top:5px;
		margin-bottom:15px;
		margin-right:15px;
		
	}
	.user{
	  height: 200px;
	}
	.userinfo{
		top: 0upx;
		height: 250upx;
		margin-top:30upx;
		margin-bottom:20px;
		margin-right:5%;
		margin-left:5%;
		color: #FFFFFF;
	}
</style>
