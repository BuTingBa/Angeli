<template>
	<view class="content">	
		<image class="logding" src="../../static/g.png" ></image>
		<view class="anniu">
			<button class="mini-btn" type="default" size="mini" plain="true" @tap="countDown">跳过{{num}}</button>
		</view>
	</view>
	
</template>

<script>
	
	export default {
		data() {
			return {
				timer:"",
				num:5,
			}
		},
		onLoad() {
            //创建并执行定时器
			this.timer = setInterval(() => {
              //当num等于100时清除定时器
				this.num--;
				console.log(this.num);
				if (this.num == 0) {
				    clearInterval(this.timer);
					this.num="";
					uni.redirectTo({
						url: '../Home/Home?id=1'
					});
				}
            }, 1000);
		},
		methods: {
			countDown() {
				clearInterval(this.timer);
				this.num="";
				uni.redirectTo({
					url: '../Home/Home?id=1'
				});
			}
			
		}
	}
</script>

<style>
	.content{
		width: 100%;
		height: 100vh;
	}
	.logding{
		width: 100%;
		height: 100vh;
	}
	.anniu{
		position: absolute;
		  bottom: 30px;
		  right: 10px;
	}
</style>
