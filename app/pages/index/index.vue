<template>
	<view class="content">	
		<image class="logding" src="../../static/g.png" :src="image"></image>
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
				image:'../../static/g.png'
			}
		},
		onLoad(){
			uni.request({
				method:'GET',
				url: "https://api.angeli.top/user.php?type=getSysConfig", //仅为示例，并非真实接口地址。
				data: {
					configName:'home_imageurl'
				},
				header: {
					'content-type': 'application/x-www-form-urlencoded',
				},
				success: (res) => {
					console.log(res)
					if(res.data.code=="1"){
						this.systemConfig=res.data.data;
						if(!res.data.data){
							this.image = ''
						}else{
							this.image = res.data.data
						}
					}
					console.log(this.image)
				},
				complete() {
					
				}
			});
			
		},
		onReady() {
            //创建并执行定时器
			uni.getStorage({
			    key: 'showAD',
			    success: (res) => {
					console.log(res.data)
			        if(res.data=='true'){
						
					}else{
						this.countDown()
					}
			    }
			});
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
