<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">会员中心</block>
		</cu-custom>
		<view style="text-align: center;width: 100%;margin-top: 37upx;"><view class="touxiang"></view></view>
		<view class="vipCardBox">
			<view class="vipCard">
				<view class="vipCardName">
					{{userInfo.UserName?userInfo.UserName:'未知用户'}}
				</view>
				<view class="vipCardTitle">开通安个利VIP,畅享高级功能</view>
			</view>
			<view style="padding: 72upx 0upx;" @tap="showKaitong"><button class="Angeli">立即开通</button></view>
		</view>
		<view class="fenge">会员特权</view>
		<view class="tequanList">
			<image src="../../static/vipname.png" mode="" class="tu"></image>
			<view><text style="font-size: 28upx;color: #000000;font-weight: 500;">会员昵称\n</text><text style="font-size: 24upx;color: #999999;font-weight: 400;">会员拥有尊贵VIP标识，红色昵称</text></view>
		</view>
		<view class="menusolid"></view>
		
		<view class="tequanList">
			<image src="../../static/sbjifen.png" mode="" class="tu"></image>
			<view><text style="font-size: 28upx;color: #000000;font-weight: 500;">双倍积分 \n</text><text style="font-size: 24upx;color: #999999;font-weight: 400;">会员签到积分双倍计算</text></view>
		</view>
		<view class="menusolid"></view>
		
		<view class="tequanList">
			<image src="../../static/fatiejianban.png" mode="" class="tu"></image>
			<view><text style="font-size: 28upx;color: #000000;font-weight: 500;">发帖减半 \n</text><text style="font-size: 24upx;color: #999999;font-weight: 400;">会员发布帖子所需积分减半</text></view>
		</view>
		<view class="menusolid"></view>
		
		<view class="tequanList">
			<image src="../../static/hiad.png" mode="" class="tu"></image>
			<view><text style="font-size: 28upx;color: #000000;font-weight: 500;">广告隐藏 \n</text><text style="font-size: 24upx;color: #999999;font-weight: 400;">会员具有开关广告权限</text></view>
		</view>
		<view class="menusolid"></view>
		<view class="tequanList">
			<image src="../../static/editid.png" mode="" class="tu"></image>
			<view><text style="font-size: 28upx;color: #000000;font-weight: 500;">修改昵称 \n</text><text style="font-size: 24upx;color: #999999;font-weight: 400;">会员拥有每月一次昵称修改机会</text></view>
		</view>
		<view class="menusolid"></view>
		<view class="tequanList">
			<image src="../../static/gengduo.png" mode="" class="tu"></image>
			<view><text style="font-size: 28upx;color: #000000;font-weight: 500;">后续特权 \n</text><text style="font-size: 24upx;color: #999999;font-weight: 400;">会员后期享有积分商城折扣</text></view>
		</view>
		
		
		
		<view :class="showVip?'mask':''" :style="{background:yanse}" @click="hideVip"></view>
		<view class="pay" :style="{ bottom:gaodu}">
			<text class="titlea">充值会员</text>
			<view class="vipBox">
				<view :class="xzId==1?'vipItemXZ':'vipItem'" @click="xuanze(1)">
					<view class="vipTitle">一个月</view>
					<text class="paynumber">￥1</text>
				</view>
				<view :class="xzId==2?'vipItemXZ':'vipItem'" @click="xuanze(2)">
					<view class="vipTitle">三个月</view>
					<text class="paynumber">￥48</text>
				</view>
				<view :class="xzId==3?'vipItemXZ':'vipItem'" @click="xuanze(3)">
					<view class="vipTitle">一整年</view>
					<text class="paynumber">￥192</text>
				</view>
				<view :class="xzId==4?'vipItemXZ':'vipItem'" @click="xuanze(4)">
					<view class="vipTitle">自定义</view>
					<input type="text" value="" placeholder="输入月数" @input="inputVip"/>
				</view>
			</view>
			<button class="Angeli" style="margin-top: 72upx;margin-bottom: 16upx;" @tap="getVip">立即支付</button>
			<view style="width: 100%;text-align: center;margin-bottom: 82upx;"><text style="font-size: 22upx;">购买即视为同意《安个利会员用户协议》</text></view>
		</view> 
		
		
		<!-- 以前的版本会员中心<view class="vipNote">
			<text class="title">四大权益</text>
			<view class="tequan">
				<view class="titem">
					<view class="yuan"></view>
					<text>会员尊贵标志</text>
				</view>
				<view class="titem">
					<view class="yuan"></view>
					<text>会员尊贵标志</text>
				</view>
				<view class="titem">
					<view class="yuan"></view>
					<text>会员尊贵标志</text>
				</view>
				<view class="titem">
					<view class="yuan"></view>
					<text>会员尊贵标志</text>
				</view>
			</view>
		</view>
		<view class="line"></view>
		<view class="pay">
			<text class="titlea">充值会员</text>
			<view class="vipBox">
				<view :class="xzId==1?'vipItemXZ':'vipItem'" @click="xuanze(1)">
					<view class="vipTitle">一个月</view>
					<text class="paynumber">￥1</text>
				</view>
				<view :class="xzId==2?'vipItemXZ':'vipItem'" @click="xuanze(2)">
					<view class="vipTitle">三个月</view>
					<text class="paynumber">￥29</text>
				</view>
				<view :class="xzId==3?'vipItemXZ':'vipItem'" @click="xuanze(3)">
					<view class="vipTitle">一整年</view>
					<text class="paynumber">￥58</text>
				</view>
				<view :class="xzId==4?'vipItemXZ':'vipItem'" @click="xuanze(4)">
					<view class="vipTitle">自定义</view>
					<text class="paynumber">￥1</text>
				</view>
			</view>
		</view> -->
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
				monnumber:1,
				money:0
			}
		},
		onLoad:function(){
			this.userInfo=server.userinfo
		},
		methods: {
			getVip:function(){
				if(this.monnumber>=1){
					if(this.monnumber==1){
						this.money=1
					}else{
						this.money=this.monnumber*20-this.monnumber*4
					}
					console.log('月份：'+this.monnumber,'金额：'+this.money)
					let sjstr=md5(String(Date.now()));
					let time=String(Date.now())
					let signTemp="appId=wxb2418420ae2cf37c&nonceStr="+sjstr+"&package=prepay_id=wx021047283782973608a1c5e11751141900&signType=MD5&timeStamp="+time+"&key=xinfenghuliankejiyouxiangongsi12"
					let sign=md5(signTemp)
					uni.requestPayment({
						provider: 'wxpay',
						timeStamp: time,
						nonceStr: sjstr,
						package: 'prepay_id=wx021047283782973608a1c5e11751141900',
						signType: 'MD5',
						paySign: sign,
						success: function (res) {
							console.log('success:' + JSON.stringify(res));
						},
						fail: function (err) {
							console.log('fail:' + JSON.stringify(err));
						}
					});
				}else{
					uni.showToast({
						title: "必须选择月份",
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
			inputVip:function(e){
				this.monnumber=e.target.value
			},
			hideVip:function(){
				this.showVip=false
				this.gaodu='-710px';
				this.yanse='rgba(0,0,0,0)'
				
			},
			xuanze:function(e){
				switch (e){
					case 1:
						this.monnumber=1
						break;
					case 2:
						this.monnumber=3
						break;
					case 3:
						this.monnumber=12
						break;
					case 4:
						break;
					default:
						this.monnumber=0
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
	height: 556upx;
	padding-top: 79upx;
	padding-bottom: 44upx;
	padding-left: 38upx;
	padding-right: 38upx;
	text-align: center;
}
.tu{
	width: 100upx;
	height: 100upx;
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
	height: 77upx;
	padding-left: 38upx;
	font-size: 32upx;
	font-weight:500;
	background-color: #F7F8FA;
	line-height: 77upx;
	
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
	font-size: 28upx;
	color:rgba(121,196,152,1);
}
.vipCardName{
	font-size: 32upx;
	font-weight: 400;
	color: #FFFFFF;
	padding-top: 81upx;
}
.vipCard{
	height: 298upx;
	width: 674upx;
	background-color: #fff;
	background-image: url('~@/static/VIPcard.png');
	background-size: 100% 100%;
	
	box-shadow:0px 3px 6px rgba(0,0,0,0.3);
	border-radius:16px;
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
