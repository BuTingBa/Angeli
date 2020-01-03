<template>
	<view>
		<scroll-view scroll-x="true" class="scroll-x" scroll-with-animation="true" show-scrollbar="false">
			<view v-for="(data,index) in timeList" :key="index" @click="tabXZ(index)" :class="index==thisIndex?'this itam':'itam'" :id="'id'+index">
				<text>{{data.time}}\n</text>
				<text>{{data.title}}</text>
			</view>
		</scroll-view>
		<view class="ad">
			<image src="https://cdn.cnbj1.fds.api.mi-img.com/mi-mall/b8bdd93c2aad57ede093b09d40000a48.jpg?thumb=1&w=1226&h=120&f=webp&q=90" mode="aspectFill"></image>
		</view>
		<view class="body">
			<view class="iteamList" v-for="(goods,id) in goodsList" :key="id" @click="go(goods.goodsid)">
				<image :src="goods.picUrl" mode="aspectFill"></image>
				<view class="goodTitle">{{goods.title}}</view>
				<view class="jiage">
					<text class="an-price">￥ {{goods.price}} <text class="min-price">会员: {{Math.round(goods.price*0.9)}}</text> </text>
					<text class="kucun">库存:{{goods.stock}}</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import server from '../../server.js';
	export default {
		data() {
			return {
				thisIndex: 0,
				timeList:[
					{time:'12-06',title:'正在发售'},
					{time:'12-07',title:'正在发售'},
					{time:'12-08',title:'正在发售'},
					{time:'12-09',title:'正在发售'},
					{time:'12-10',title:'正在发售'},
					{time:'12-11',title:'正在发售'}
				],
				goodsList:[]
			}
		},
		onLoad(option) {
			console.log(option)
			if(option.token){
				server.Token=option.token;
				this.login(option.token);
				
			}else{
				this.login(server.Token);
			}
			this.getGoods('')
		},
		methods: {
			go:function(id){
				uni.navigateTo({
					url:'goods?goodsId='+id
				})
				console.log(id)
			},
			tabXZ:function(index){
				console.log(index)
				this.thisIndex=index;
			},
			login:function(token){
				uni.request({
					method:'GET',
					url: server.requestUrl+token, 
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						console.log(res)
						if(res.data.code=='0'){
							uni.showToast({
								title: '登录错误：'+res.data.msg,
								position:'bottom',
								icon:'none'
							});
						}else{
							uni.showToast({
								title: '欢迎你，'+res.data.UserName,
								position:'bottom',
								icon:'none'
							});
							server.UserInfo=res.data;
							
						}
						console.log(res.data);
					}
				});
			},
			getGoods:function(date){
				uni.request({
					method:'GET',
					url: server.requestUrl+'getGoodsConcise/'+date, 
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						if(res.data.code=='1'){
							this.goodsList=res.data.data;
						}else{
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							});
						}
						console.log(res);
					}
				});
			}
		}
	}
</script>

<style>

.kucun{
	font-size:28upx;
	color: #ccc;
}
.min-price{
	font-size: 22upx;
	margin-left: 10upx;

}
.an-price{
	color: #f47925;
}
.jiage{
	display: flex;
	margin: 5upx 20upx;
	position: absolute;
	bottom: 15upx;
	width: 95%;
	justify-content:space-between;
	align-items:center;
}

.goodinfo{
	display: flex;
	margin: 10upx;
}
.goodTitle{
	margin: 10upx 20upx;
	font-size:36upx;
	overflow: hidden;
	text-overflow: ellipsis;
	/*设置成弹性盒子 */
	display: -webkit-box;
	/*显示的个数 */
	-webkit-line-clamp: 2;
	/* 属性规定框的子元素应该被水平或垂直排列。 */
	-webkit-box-orient: vertical;
}
.iteamList{
	height: 450upx;
	background-color: #fff;
	border-radius: 20upx;
	box-shadow:#555555 0px 0px 5px;
	margin: 40upx 0;
	overflow: hidden;
	position: relative;
}
.iteamList image{
	height: 65%;
	width: 100%;
}
.ad{
	height: 200upx;
	background-color: #79C498;
	text-align: center;
	line-height: 200upx;
	color: #FFFFFF;
	margin: 10upx 0;
}
.ad image{
	height: 100%;
	width: 100%;
}
.itam{
	text-align: center;
	padding: 30upx 60upx;
	height: 100%;
	font-size: 28upx;
	display: inline-block;
}
.this{
	color: #79C498;
}
.body{
	margin-top: 8upx;
	padding: 0 28upx;
}
.scroll-x{
	position: relative;
	height: 130upx;
	width: 100%;
	background: #fff;
	top: 0px;
	left: 0px;
	white-space: nowrap;
	overflow: hidden;
}
page{
	background: rgb(248,248,248);
}
</style>
