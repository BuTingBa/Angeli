<template>
	<view>
		<view class="content">
			<view class="row">
				<view class="nominal">
					收件人
				</view>
				<view class="input">
					<input placeholder="请输入收件人姓名" type="text" v-model="name" />
				</view>
			</view>
			<view class="row">
				<view class="nominal">
					电话号码
				</view>
				<view class="input">
					<input placeholder="请输入收件人电话号码" type="text" v-model="tel" />
				</view>
			</view>
			<view class="row">
				<view class="nominal">
					所在地区
				</view>
				<view class="input" @tap="chooseCity">
					{{region.label}}
				</view>
				
			</view>
			<view class="row">
				<view class="nominal">
					详细地址
				</view>
				<view class="input">
					<textarea v-model="detailed" auto-height="true" placeholder="输入详细地址"></textarea>
				</view>
			</view>
			<view class="row">
				<view class="nominal">
					设置默认地址
				</view>
				<view class="input switch">
					<switch color="#f06c7a" :checked="isDefault" @change="isDefaultChange" />
				</view>
			</view>
			<view class="row" v-if="editType=='edit'" @tap="del">
				<view class="del">
					删除收货地址
				</view>
			</view>
		</view>
		<view class="save" @tap="save">
			<view class="btn">
				保存地址
			</view>
		</view>
		<mpvue-city-picker :themeColor="themeColor" ref="mpvueCityPicker" :pickerValueDefault="cityPickerValue" @onCancel="onCancel" @onConfirm="onConfirm"></mpvue-city-picker>
	</view>
</template>

<script>
	import server from '../../server.js';
	import mpvueCityPicker from '@/components/mpvue-citypicker/mpvueCityPicker.vue'
	export default {
		components: {
			mpvueCityPicker
		},
		data() {
			return {
				editType:'edit',
				id:'',
				name:'',
				tel:'',
				detailed:'',
				isDefault:false,
				cityPickerValue: [0, 0, 1],
				themeColor: '#007AFF',
				region:''
			};
		},
		methods: {
			onCancel(e) {
				console.log(e)
			},
			chooseCity() {
				this.$refs.mpvueCityPicker.show()
			},
			onConfirm(e) {
				this.region = e;
				this.cityPickerValue = e.value;
				console.log(e)
			},
			isDefaultChange(e){
				
				this.isDefault = e.detail.value;
			},
			del(){
				if(this.isDefault){
					uni.showToast({
						title: '默认收货地址无法删除，请设置其他为默认收货地址，再来删除',
						position:'bottom',
						icon:'none'
					});
					return;
					
				}
				
				uni.showModal({
					title: '删除提示',
					content: '你将删除这个收货地址',
					success: (res)=>{
						if (res.confirm) {
							this.delAddress()
						}
					}
				});
				
			},
			delAddress(){
				uni.request({
					method:'POST',
					url: server.requestUrl+'delAddress', 
					data:{
						id:this.id,
						token:server.Token
					},
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						if(res.data.code=='1'){
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							});
							
							uni.navigateBack();
						}else{
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							});
						}
						console.log(res);
					},
					complete() {
						uni.hideLoading();
					}
				});
			},
			save(){
				let data=[];
				
				if(!this.name){
					uni.showToast({title:'请输入收件人姓名',icon:'none'});
					return ;
				}
				if(!this.tel){
					uni.showToast({title:'请输入收件人电话号码',icon:'none'});
					return ;
				}
				if(!this.detailed){
					uni.showToast({title:'请输入收件人详细地址',icon:'none'});
					return ;
				}
				if(!this.region.label){
					uni.showToast({title:'请选择收件地址',icon:'none'});
					return ;
				}
				if(this.region.value.length==0){
					uni.showToast({title:'请选择收件地址',icon:'none'});
					return ;
				}
				
				if(this.editType=='edit'){
					data={
						name:this.name,
						phone:this.tel,
						detailed:this.detailed,
						region:this.region.label,
						isDefault:this.isDefault?1:0,
						token:server.Token,
						addressId:this.id
					}
					console.log('编辑')
				}else{
					data={
						name:this.name,
						phone:this.tel,
						detailed:this.detailed,
						region:this.region.label,
						isDefault:this.isDefault?1:0,
						token:server.Token
					}
					console.log('新建')
				}
				console.log(data)
				uni.showLoading({
					title:'正在提交'
				})
				//提交地址到服务器
				uni.request({
					method:'POST',
					url: server.requestUrl+'addAddress', 
					data:data,
					header: {
						'content-type': 'application/x-www-form-urlencoded',
					},
					success: (res) => {
						if(res.data.code=='1'){
							this.addressList=res.data.data
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							});
						}else{
							uni.showToast({
								title: res.data.msg,
								position:'bottom',
								icon:'none'
							});
						}
						console.log(res);
					},
					complete() {
						uni.hideLoading();
					}
				});
				
				
				
				
				
			}
		},
		onLoad(e) {
			//获取传递过来的参数
			
			this.editType = e.type;
			if(e.type=='edit'){
				uni.getStorage({
					key:'address',
					success: (e) => {
						this.id = e.data.id;
						this.name = e.data.name;
						this.tel = e.data.phone;
						this.detailed = e.data.detailed;
						this.isDefault = e.data.isDefault;
					}
				})
			}
			
		},
		onBackPress() {
			if (this.$refs.mpvueCityPicker.showPicker) {
				this.$refs.mpvueCityPicker.pickerCancel();
				return true;
			}
		},
		onUnload() {
			if (this.$refs.mpvueCityPicker.showPicker) {
				this.$refs.mpvueCityPicker.pickerCancel()
			}
		}
	};
</script>
<style lang="scss">

.save{
		view{
			display: flex;
		}
		position: fixed;
		bottom: 0;
		width: 100%;
		height: 120upx;
		display: flex;
		justify-content: center;
		align-items: center;
		.btn{
			box-shadow: 0upx 5upx 10upx rgba(0,0,0,0.4);
			width: 70%;
			height: 80upx;
			border-radius: 80upx;
			background-color: #f06c7a;
			color: #fff;
			justify-content: center;
			align-items: center;
			.icon{
				height: 80upx;
				color: #fff;
				font-size: 30upx;
				justify-content: center;
				align-items: center;
			}
			font-size: 30upx;
		}
	}
	.content{
		margin-top:40upx ;
		display: flex;
		flex-wrap: wrap;
		view{
			display: flex;
		}
		.row{
			width: 94%;
			
			margin: 0 3%;
			border-top: solid 1upx #eee;
			.nominal{
				width: 30%;
				height: 120upx;
				font-weight: 200;
				font-size: 30upx;
				align-items: center;
			}
			.input{
				width: 70%;
				padding: 20upx 0;
				align-items: center;
				font-size: 30upx;
				&.switch{
					justify-content: flex-end;
				}
				.textarea{
					margin: 20upx 0;
					min-height: 120upx;
				}
			}
			.del{
				width: 100%;
				height: 100upx;
				justify-content: center;
				align-items: center;
				font-size: 36upx;
				color: #f06c7a;
				background-color: rgba(255,0,0,0.05);
				border-bottom: solid 1upx #eee;
			}
		}
	}
	
</style>
