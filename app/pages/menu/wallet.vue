<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">我的钱包</block>
		</cu-custom>
	
		<view class="vipCardBox">
			<view class="vipCard">
				<view class="vipCardName">
					累计收益 (安个利币)
				</view>
				<view class="vipCardTitle">{{endVipTime}}</view>
			</view>
			<view style="padding: 0 38upx;padding-top: 40upx;padding-bottom: 16upx;" @tap="showKaitong"><button class="Angeli" :disabled="ann">充值</button></view>
		</view>
		<view class="fenge">
			<view :class="xz==1?'feniteamX':'feniteam'" @click="setxz(1)">提现方式</view>
			<view :class="xz==2?'feniteamX':'feniteam'" @click="setxz(2)">账单记录</view>
		</view>
		
		<view v-if="xz==1">
			<view class="tequanList" @click="get(1)">
				<image src="../../static/WeChatpayicon.png" mode="" class="tu"></image>
				<view><text style="font-size:  28upx;color: #000000;font-weight: 500;">提现到微信\n</text><text style="font-size: 24upx;color: #999999;font-weight: 400;">活动繁忙期间到账可以会延迟</text></view>
			</view>
			<view class="menusolid"></view>
			<view class="tequanList" @click="get(2)">
				<image src="../../static/alipayicon.png" mode="" class="tu"></image>
				<view><text style="font-size: 28upx;color: #000000;font-weight: 500;">提现到支付宝 \n</text><text style="font-size: 24upx;color: #999999;font-weight: 400;">活动繁忙期间到账可以会延迟</text></view>
			</view>
		</view>
		<view v-if="xz==2">
			<view class="flexList" v-for="(list,index) in billList" :key="index">
				<view>
					<text style="font-size: 28upx;">{{list.note}}\n</text>
					<text style="font-size: 24upx;color:rgba(153,153,153,1);opacity:1;">{{list.time}}</text>
				</view>
				<view style="text-align: right;">
					<text style="font-size: 28upx;color:rgba(121,196,152,1);">{{list.type+list.number}}\n</text>
					<text style="font-size: 24upx;">成功</text>
				</view>
			</view>
			
		</view>
		
		<view :class="showVip?'mask':''" :style="{background:yanse}" @click="hideVip"></view>
		<view class="pay" :style="{ bottom:gaodu}">
			<text class="titlea">充值安个利币</text>
			<view class="vipBox">
				<view :class="xzId==1?'vipItemXZ':'vipItem'" @click="xuanze(1)">
					<view class="vipTitle">100安个利币</view>
					<text class="paynumber">￥10</text>
				</view>
				<view :class="xzId==2?'vipItemXZ':'vipItem'" @click="xuanze(2)">
					<view class="vipTitle">500安个利币</view>
					<text class="paynumber">￥50</text>
				</view>
				<view :class="xzId==3?'vipItemXZ':'vipItem'" @click="xuanze(3)">
					<view class="vipTitle">1000安个利币</view>
					<text class="paynumber">￥100</text>
				</view>
				<view :class="xzId==4?'vipItemXZ':'vipItem'" @click="xuanze(4)">
					<view class="vipTitle">{{angelibi}}</view>
					<input type="text" value="" placeholder="输入人民币" @input="inputVip"/>
				</view>
			</view>
			<button class="Angeli" style="margin-top: 72upx;margin-bottom: 16upx;" @click="getVip">立即支付</button>
			<view style="width: 100%;text-align: center;margin-bottom: 82upx;"><text style="font-size: 22upx;">购买即视为同意《安个利用户充值协议》</text></view>
		</view> 
		
		
	</view>
</template>

<script>
	import server from '../../server.js';
	import md5 from '../../static/md5.js';
	export default {
		data() {
			return {
				xzId:1,
				showVip:false,
				gaodu:'-710px',
				yanse:'rgba(0,0,0,0)',
				userInfo:[],
				monnumber:10,
				money:0,
				endVipTime:'1,234.32',
				ann:false,
				xz:1,
				angelibi:"自定义",
				billList:[]
			}
		},
		onLoad:function(){
			this.userInfo=server.userinfo;
			this.getjifen();
		},
		methods: {
			get:function(e){
				if(e==1){
					//微信
					uni.navigateTo({
						url: 'tixian?type=wx'
					});
				}else{
					//支付宝
					uni.navigateTo({
						url: 'tixian?type=alipay'
					});
				}
			},
			getjifen:function(){
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=cxjf", //仅为示例，并非真实接口地址。
					data: {
						auid:server.userinfo.Auid
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							this.endVipTime=res.data.data.Points
						}else{
							
						}
						console.log(this.msgNumber)
					},
					complete() {
						
					}
				});
			},
			getVip:function(){
				if(this.monnumber>=10){
					this.money=this.monnumber/10
					console.log('安个利币：'+this.monnumber,'金额：'+this.money)
					uni.showLoading({
						title: '加载中'
					});
					let wxkey=md5('不停'+String(Date.now()));
					uni.request({
						method:'POST',
						url: "https://api.angeli.top/WeChat/pay.php?type=angelibi", //仅为示例，并非真实接口地址。
						data: {
							openid:server.userinfo.wxOpenId,
							fee:this.money,
							number:this.monnumber,
							key:wxkey,
							auid:server.userinfo.Auid
						},
						header: {
							'content-type': 'application/x-www-form-urlencoded',
							'Cookie':server.cookie
						},
						success: (res) => {
							console.log(res)
							let dd=res.data.orderId;
							if(res.data.code=="1"){
								let sjstr=md5(String(Date.now()));
								let time=String(Date.now())
								let signTemp="appId=wxb2418420ae2cf37c&nonceStr="+sjstr+"&package=prepay_id="+res.data.data.prepay_id+"&signType=MD5&timeStamp="+time+"&key=xinfenghuliankejiyouxiangongsi12"
								let sign=md5(signTemp)
								uni.requestPayment({
									provider: 'wxpay',
									timeStamp: time,
									nonceStr: sjstr,
									package: 'prepay_id='+res.data.data.prepay_id,
									signType: 'MD5',
									paySign: sign,
									success: (res) => {
										uni.showLoading({
											title: '正在查询中...'
										});
										uni.request({
											method:'GET',
											url: 'https://api.angeli.top/account.php?type=queryOrderIdAndVip', //仅为示例，并非真实接口地址。
											data:{
												auid:server.userinfo.Auid,
												orderId:dd
											},
											header: {
												'content-type': 'application/x-www-form-urlencoded',
												'Cookie':server.cookie
											},
											success: (res) => {
												if(res.data.code==1){
													if(res.data.data.payStatus=='已支付' && res.data.data.payStatus=='OK'){
														console.log('已支付',this.endVipTime)
														uni.showToast({
															title: "已充值",
															position:'bottom',
															icon:'none',
															position:'center'
														})
														uni.navigateBack()
													}else{
														uni.showToast({
															title: "支付失败！请联系客服",
															position:'bottom',
															icon:'none',
															position:'center'
														})
													}
												}else{
													uni.showToast({
														title: "支付失败！请联系客服",
														position:'bottom',
														icon:'none',
														position:'center'
													})
												}
											},
											complete: () => {
												uni.hideLoading();
											}
										});
									},
									fail: function (err) {
										uni.showToast({
											title: "支付失败！请联系客服",
											position:'bottom',
											icon:'none',
											position:'center'
										})
									}
								});
								
							}else{
					
							}
							
						},
						complete() {
							uni.hideLoading();
						}
					});
					
				}else{
					uni.showToast({
						title: "必须选择充值数量",
						position:'bottom',
						icon:'none',
						position:'center'
					})
				}
			},
			showKaitong:function(){
				this.showVip=true
				this.gaodu='0px'
				this.yanse='rgba(0,0,0,0.4)'
			},
			setxz:function(e){
				this.xz=e
				if(e==2){
					this.getBill();
				}
			},
			getBill:function(){
				uni.showLoading({
					title: '正在查询中...'
				});
				uni.request({
					method:'GET',
					url: 'https://api.angeli.top/account.php?type=cx', //仅为示例，并非真实接口地址。
					data:{
						auid:server.userinfo.Auid,
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie
					},
					success: (res) => {
						if(res.data.code=="1"){
							this.billList=res.data.data
						}else{
							uni.showToast({
								title: "获取账单失败！",
								position:'bottom',
								icon:'none'
							});
						}	
					},
					complete: () => {
						uni.hideLoading();
					}
				});
			},
			inputVip:function(e){
				this.monnumber=e.target.value
				this.angelibi=e.target.value*10+"安个利币"
				if(e.target.value==""||e.target.value==null){
					this.angelibi="自定义"
				}
			},
			hideVip:function(){
				this.showVip=false
				this.gaodu='-710px';
				this.yanse='rgba(0,0,0,0)'
			},
			xuanze:function(e){
				switch (e){
					case 1:
						this.monnumber=100
						break;
					case 2:
						this.monnumber=500
						break;
					case 3:
						this.monnumber=1000
						break;
					case 4:
						this.monnumber=100
						break;
					default:
						this.monnumber=100
						break;
				}
				this.xzId=e
				/* 新思路,点赞改变列表状态可以使用该方法,:class="当前ID是否等于列子ID，如果是那么就点赞，如果不是就没有点赞，"
				但是,这样只能存在一个点赞状态,其他的状态就会消失,带思考 */
			}
		}
	}
</script>

<style>
.tequanList{
	line-height: 44upx;
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
.vipCardBox{
	
	text-align: center;
}
.tu{
	width: 56upx;
	height: 56upx;
	margin-right: 14upx;
	
}
.tequanList{
	height: 136upx;
	padding: 18upx 38upx;
	display: flex;
	justify-content: flex-start;
	align-items:center;
}
.fenge{
	height: 94upx;
	padding:0 70upx;
	font-weight:500;
	background-color: #fff;
	line-height: 77upx;
	display: flex;
	justify-content:space-around;
	align-items:center;
}
.feniteam{
	height: 94upx;
	font-size: 28upx;
	font-weight: 400upx;
	line-height: 94upx;
	opacity:0.9;
}
.feniteamX{
	height: 94upx;
	font-size: 28upx;
	font-weight: 500upx;
	line-height: 94upx;
	opacity:1;
	border-bottom: 3upx solid #79C498;
}
.touxiang{
	width: 128upx;
	height: 128upx;
	border-radius: 50%;
	position: absolute;
	background-image: url('../../static/11.png');
	background-size: 100% 100%;
	margin-left: 50%;
	transform: translateX(-50%);
}
.vipCardTitle{
	padding-top: 42upx;
	font-size: 72upx;
	font-weight: 400;
	color: #FFFFFF;
}
.flexList{
	display: flex;
	justify-content:space-between;
	align-items: center;
	padding: 24upx 38upx;
}
.flexList{
	line-height: 44upx;
}
.vipCardName{
	
	padding-top: 81upx;
	font-size: 28upx;
	color:rgba(121,196,152,1);
}
.vipCard{
	height: 336upx;

	background-color: #fff;
	background-image: url('https://sz.oss.data.angeli.top/system/walletbg.png');
	background-size: 100% 100%;

	text-align: center;
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
page{
	background-color: #FFFFFF;
}
.paynumber{
	font-size: 28upx;
	font-weight: 700;
}
.vipTitle{
	font-size: 24upx;
	margin-top: 16upx;
	margin-bottom: 16upx;
	color: #999999;
}
.vipItemXZ{
	width: 332upx;
	height: 169upx;
	background:rgba(121,196,152,0.3);
	color: #79C498;
	font-size: 28upx;
	text-align: center;
	margin-top: 10upx;
}
.vipItem{
	width: 332upx;
	height: 169upx;
	background-color: #F7F8FA;
	color: #000000;
	font-size: 28upx;
	text-align: center;
	margin-top: 10upx;
	
}
.vipBox{
	display: flex;
	justify-content:space-between;
	flex-wrap:wrap;
	padding-top: 20upx;
}
.line{
	height: 8upx;
	background: #EFEFF4;
}

.title{
	margin: 16upx 38upx;
	font-size: 32upx;
	font-weight: Medium;
	
}
.titlea{
	margin: 16upx 0upx;
	font-size: 32upx;
	font-weight: Medium;
	
}
.titem{
	font-size: 28upx;
	width: 250upx;
	margin-top: 30upx;
}
.yuan{
	background-color: #79C498;
	border-radius: 50%;
	width: 10upx;
	height: 10upx;
	display: inline-block;
	margin-right: 10upx;
}
.tequan{
	margin: 0 70upx;
	display: flex;
	justify-content:space-between;
	flex-wrap:wrap;
	line-height: 30upx;
}
.vipNote{
	/* padding: 21upx; */
	background-color: #FFFFFF;
	height: 235upx;
	
}
</style>
