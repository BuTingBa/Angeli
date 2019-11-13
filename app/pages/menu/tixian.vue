<template>
	<view>
		<cu-custom bgColor="bg-white" :isBack="true">
			<block slot="backText">返回</block>
			<block slot="content">提现</block>
		</cu-custom>
		<view class="inputBox" v-if="alipay==true">
			<text>支付宝账号</text>
			<input type="digit" value="" placeholder="请填写支付宝账号" placeholder-class="ssssss alipayinput" @input="alipayuser"/>
		</view>
		<view class="inputBox" >
			<text>金额（￥）</text>
			<input type="text" value="" placeholder="请填写提现金额" placeholder-class="ssssss" @input="feeing" />
		</view>
		<view class="txt">
			<text>·提现手续费：每次提现按提现金额的8%进行收取\n·到账金额=提现金额-提现手续费，到账金额指实际提现至指定账户中的金额\n·提现汇率：10安个利币=1人民币\n·单笔提现金额最低为100元，会员80元\n人工汇款时间为每个月底\n
</text>
		</view>
		<view style="margin: 0 38upx;">
			<button class="Angeli" style="margin-top: 72upx;margin-bottom: 16upx;" @click="tixian">提现</button>
		</view>
		
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				alipay:false,
				aliuser:'',
				fee:0,
				openid:''
			}
		},
		onLoad:function(e){
			console.log(e.type)
			if(e.type=="alipay"){
				this.alipay=true;
			}
		},
		methods: {
			alipayuser:function(e){
				this.aliuser=e.detail.value;
			},
			feeing:function(e){
				this.fee=e.detail.value;
			},
			tixian:function(){
				if(this.alipay){
					this.openid=this.aliuser
				}else{
					this.openid='wxpay'
				}
				uni.showLoading({
					title: '正在请求'
				});
				uni.request({
					method:'GET',
					url: "https://api.angeli.top/user.php?type=tixian", //请求标记已读消息
					data: {
						'openid':server.userinfo.wxOpenId,
						'fee':this.fee
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
						'Cookie':server.cookie,
						'system':server.system
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=="1"){
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							})
							setTimeout(function () {
								uni.hideLoading()
								uni.navigateBack({});
							}, 2000);
						}else{
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							})
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
.inputBox{
	height: 136upx;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 48upx 38upx;
	background-color: #FFFFFF;
	font-size: 28upx;
}
.inputBox input{
	text-align: right;
	font-size: 28upx;
	color:#000;
	width: 60%;
}
.txt{
	margin: 24upx 38upx;
	color:rgba(153,153,153,1);
	font-size: 26upx;
	margin-bottom: 95upx;
}
.ssssss{
	color:rgba(153,153,153,1);
}
.alipayinput{
	text-align: right;
}
</style>
