<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">评论详情</block>
		</cu-custom>
		<view class="plList">
			<view class="pluserinfo">
				<view class="pltouxiang" :style="{'background-image':'url('+plnr.userinfo.AuthorAvatarUrl+')'}"></view>
				<view class="plinfo">
					<text>{{plnr.userinfo.AuthorName}}\n</text>
					<text style="font-size: 22upx;">{{plnr.Time}}</text>
				</view>
			</view>
			<view class="pltxt" @tap="huifua(plnr.userinfo.AuthorName,plnr.CommentsId,plnr.FromUid)">{{plnr.Content}}</view>
			<view style="height: 15upx;"></view>
			
		</view>
		<view class="fenge"></view>
		
		<view class="plList" v-for="(alist,index) in list" :key="index">
			<view class="pluserinfo">
				<view class="pltouxiang" :style="{'background-image':'url('+alist.ReplyUid.AuthorAvatarUrl+')'}"></view>
				<view class="plinfo">
					<text>{{alist.ReplyUid.AuthorName}}\n</text>
					<text style="font-size: 22upx;">{{alist.AddTime}}</text>
				</view>
			</view>
			<view class="pltxt"><text v-if="alist.type==1">回复</text><text class="yanseee" v-if="alist.type==1" @click="getuser(alist.ReplyTo.Auid)">@{{alist.ReplyTo.AuthorName}}</text><text @tap="huifua(alist.ReplyUid.AuthorName,'1',alist.ReplyUid.Auid)"> {{alist.ReplyContent}}</text></view>
			<view style="height: 15upx;"></view>
			<view class="soli"></view>
		</view>
		<view style="margin-top: 50upx;height: 50upx;text-align: center;">没有更多评论了！</view>
		<view style="height: 100upx;width: 100%; bottom: 0px;"></view>
		
		<view class="cu-bar foot input " :style="[{bottom:InputBottom+'px'}]" style="z-index: 777;">
			<input class="solid-bottom" :adjust-position="false" :focus="false" maxlength="300" cursor-spacing="10"
			 @focus="InputFocus" @blur="InputBlur" @input="pinglun" v-model="setvar"></input>
			<button class="cu-btn bg-green shadow" @tap="sendpl">评论</button>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				list:[],
				plnr:'',
				huifuid:0,
				setvar:'',
				pluid:0,
				InputBottom: 0,
				postid:0
			}
		},
		onLoad:function(e){
			console.log(e)
			this.huifuid=e.CommentsId;
			this.postid=e.postId
			this.getpl(e.CommentsId,e.postId)
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
					uni.showLoading({
						title: '正在提交数据'
					});
					uni.request({
						method:'POST',
						url: 'https://api.angeli.top/post.php?type=hfpl', //仅为示例，并非真实接口地址。
						data: {
							id: this.huifuid,
							txt:nr,
							postid:this.postid,
							uid:this.pluid,
							auid:server.userinfo.Auid,
							token:server.token
						},
						header: {
							'content-type': 'application/x-www-form-urlencoded',
							
						},
						success: (res) => {
							if(res.data.code=='1'){
								this.setvar="";
								this.plnr=''
								uni.request({
									method:'GET',
									url: 'https://api.angeli.top/post.php?type=getpl', //仅为示例，并非真实接口地址。
									data: {
										postid: this.postid,
										token:server.token
									},
									header: {
										'content-type': 'application/x-www-form-urlencoded',
										
									},
									success: (res) => {
										console.log("————————————评论详情——————————");
										this.pllist=res.data.data;
										this.plnr==""
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
							
							setTimeout(function () {
								uni.hideLoading()
							}, 2000);
						},
						complete() {
							uni.hideLoading();
						}
					});
					
				}else{
					//评论模式
					uni.showLoading({
						title: '正在提交数据'
					});
					uni.request({
						method:'POST',
						url: 'https://api.angeli.top/post.php?type=pl', //仅为示例，并非真实接口地址。
						data: {
							auid: server.userinfo.Auid,
							txt:this.plnr,
							postid:this.postid,
							uid:this.postInfo.AuthorId,
							token:server.token
						},
						header: {
							'content-type': 'application/x-www-form-urlencoded',
							
						},
						success: (res) => {
							if(res.data.code=='1'){
								this.plnr=''
								this.setvar="";
								uni.request({
									method:'GET',
									url: 'https://api.angeli.top/post.php?type=getpl', //仅为示例，并非真实接口地址。
									data: {
										postid: this.postid,
										token:server.token
									},
									header: {
										'content-type': 'application/x-www-form-urlencoded',
										
									},
									success: (res) => {
										console.log("————————————评论详情——————————");
										this.pllist=res.data.data;
										console.log(this.pllist);
										this.plnr==""
										
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
							setTimeout(function () {
								uni.hideLoading()
							}, 2000);
						},
						complete() {
							uni.hideLoading()
						}
					});
				}
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
			getpl:function(CommentsId,postId){
				uni.showLoading({
					title: '正在请求服务器'
				});
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=getdupl&CommentsId="+CommentsId,
					data: {
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						
						
					},
					success: (res) => {
						if(res.data.code=="1"){
							this.plnr=res.data.data
							this.getList(postId,CommentsId,res.data.data.userinfo.Auid)
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
						uni.hideLoading();
					}
				});
				
			},
			huifua:function(n,plid,uid){
				this.setvar="@"+n+": ";
				this.huifu=true;
				this.pluid=uid;//父级评论id
				console.log("回复",n,uid)
			},
			getList:function(postid,CommentsId,pluid){
				
				uni.showLoading({
					title: '正在请求服务器'
				});
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/post.php?type=gethflist&CommentsId="+CommentsId+"&postid="+postid,
					data: {
						'pluid':pluid,
						token:server.token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						
					},
					success: (res) => {
						if(res.data.code=="1"){
							this.list=res.data.data
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
						uni.hideLoading();
					}
				});
				
			}
		}
	}
	
</script>

<style>
	.plList{
		background: #fff;
	}
	.fenge{
		margin-top: 8upx;
		height: 30upx;
		padding: 0 38upx;
		background: #FFFFFF;
		font-size: 24upx;
		color: #999999;
	}
	.soli{
		margin-left: 124upx;
		margin-right: 38upx;
		height:1px;
		background:rgba(229,228,234,1);
	}
	.pltxt{
		padding-left: 124upx;
		padding-right: 38upx;
		margin-top: 10upx;
		font-size:26upx;
		margin-bottom: 10upx;
	}
	.plinfo{
		margin-left: 16upx;
		font-size:24upx;
		font-weight:400;
		color:rgba(153,153,153,1);
	}
	.pltouxiang{
		width: 70upx;
		height: 70upx;
		border-radius: 50%;
		background-size: auto 100%;
	}
	.pluserinfo{
		padding: 20upx 38upx;
		height: 70upx;
		display: flex;
	}
	.pltitle{
		margin-left: 38upx;
		font-size:32upx;
		font-weight:500;
		line-height:77upx;
	}
	.header{
		background-color: #fff;
		height: 77upx;
		border-bottom: 1upx solid rgba(229,228,234,1);
		border-top:  1upx solid rgba(229,228,234,1);
	}
	.yanseee{
		color: #79C498;
	}
</style>
