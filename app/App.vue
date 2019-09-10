<script>
	import Vue from 'vue'
	export default {
		onLaunch: function() {
		    uni.getSystemInfo({
		        success: function(e) {
		            // #ifndef MP
		            Vue.prototype.StatusBar = e.statusBarHeight;
		            if (e.platform == 'android') {
		                Vue.prototype.CustomBar = e.statusBarHeight + 50;
		            } else {
		                Vue.prototype.CustomBar = e.statusBarHeight + 45;
		            };
		            // #endif
		            // #ifdef MP-WEIXIN
		            Vue.prototype.StatusBar = e.statusBarHeight;
		            let custom = wx.getMenuButtonBoundingClientRect();
		            Vue.prototype.Custom = custom;
		            Vue.prototype.CustomBar = custom.bottom + custom.top - e.statusBarHeight;
		            // #endif       
		            // #ifdef MP-ALIPAY
		            Vue.prototype.StatusBar = e.statusBarHeight;
		            Vue.prototype.CustomBar = e.statusBarHeight + e.titleBarHeight;
		            // #endif
		        }
		    })
		},
	}
</script>

<style>
	/*每个页面公共css */
	@import "components/colorui/main.css";
	@import "components/colorui/icon.css";
	.mianbg{
		width: 100%;
		height: 100vh;
	}
	.menuList-2c{
		display: flex;
		justify-content:space-between;
	}
	.jiantou{
		width: 20upx;
		height: 20upx;
		border-top: 3px solid #EFEFF4;
		border-right: 3px solid #EFEFF4;
		right: 20upx;
		display: inline-block;
		border-color: #E5E4EA;
		transform: rotate(45deg);
		margin-top:10upx;
		justify-self:end;
	}
	.postBox{
		margin-bottom: 10upx;
		background-color: #F7F8FA;
	}
	.menuIcon{
		width: 40upx;
		height: 40upx;
		background-size: 100% 100%;
	}
	.menuListBox{
		background-color: #FFFFFF;
		margin-bottom: 8upx;
	}
	.menuItem{
		display: grid;
		grid-template-columns: 10% 70% 20%;
		padding: 24upx 37upx;
		font-size: 28upx;
		align-items:center;
	}
	.nullImg{
		width: 303upx;
		height: 321upx;
	}
	.yuan{
		border-radius: 50%;
	}
	.nullBox{
		text-align: center;
		margin-top: 132upx;
	}
	.nullText{
		font-size:24upx;
		font-weight:400;
		color:rgba(153,153,153,1);
	}
	.menusolid{
		border-width: 0 0 1upx 0;
		border-style:solid;
		border-color:#F7F8FA;
	}
	.solidMax{
		height: 8upx;
		background-color: #F7F8FA;
	}
	.Angeli{
		width: 100%;
		background-color: #79C498;
		height: 81upx;
		border-radius: 41upx;
		color: #FFFFFF;
		box-shadow:0px 3px 6px rgba(121,196,152,0.3);
		
	}
	.menuRight{
		color: #79C498;
		font-size: 28upx;
		justify-self:end;
	}
	.user{
		display: grid;
		grid-template-columns: 20% 80%;
		grid-template-rows: 50% 50%;
		align-items:center;
	}
	.postList{
		margin: 8upx 0upx;
		background-color: #FFFFFF;
		padding-top: 20upx;
		padding-left: 35upx;
		padding-right: 35upx;
	}
	.touxiang{
		height: 200upx;
		width: 200upx;
		border-radius: 50%;
	}
	.userName{
		font-weight: 600;
		font-size: 28upx;
	}
	.vipUserName{
		font-weight: 600;
		font-size: 28upx;
		color: #F8C259;
	}
	.vipLogo{
		position: absolute;
		width: 30upx;
		height: 30upx;
		background-image: url('static/VIPLogo2.png');
		background-size: 100% 100%;
		right: -5upx;
		bottom: 0upx;
	}
	.postDate{
		opacity: 0.4;
		font-size: 24upx;
	}
	.postText{
		font-size: 28upx;
		margin-top: 10upx;
		padding-bottom: 10upx;
		letter-spacing:3upx;
		line-height: 40upx;
	}
	.text-c{
		overflow:hidden;
		text-overflow: ellipsis;
		display:-webkit-box;
		-webkit-line-clamp:4;
		-webkit-box-orient:vertical;
	}
	.hangIng{
		font-weight: 600;
		color: #443259;
		border-bottom:2px solid #79C498;
	}
	.daohang{
			margin-top: 5upx;
			padding: 10upx;
			height: 60upx;
			display: flex;
			justify-content: space-around;
			background-color: #FFFFFF;
			font-size: 28upx;
	}
	.daohangBox{
		width: 100%;
		position:fixed;
		z-index: 1000;
	}
	.hang{
			margin-left: 20upx;
			text-align: center;
			height: 48upx;
			width:120upx;
			padding-bottom: 5upx;
		}
	.postImage{
		display: grid;
		grid-template-columns: repeat(auto-fill, 33%);
		margin: 10upx 0upx;
		grid-row-gap: 5upx;
		grid-column-gap: 0.5%;
		justify-content:space-between;
	}
	.postImageItem{
		width: 222upx;
		height: 222upx;
		
		overflow:hidden;
	}
	.postBottom{
		padding: 5px 0upx;
		display: grid;
		padding-bottom: 15upx;
		grid-template-columns: 10% 75% 15%;
		justify-items: end;
	}
	.postBottom view{
		display: inline-block;
	}
	.postviewcount{
		padding-left: 5upx;
		font-size:32upx;
		line-height:32upx
	}
	.postClass{
		justify-self:start;
		text-align: center;
		border-radius: 25upx;
		height: 41upx;
		width: 142upx;
		line-height:41upx;
		font-size: 24upx;
		background-color: #79C498;
	}
	.postMenu{
		justify-items: end;
		padding-right: 10upx;
		width: 40upx;
		height: 40upx;
	}
	.like{
		width: 40upx;
		height: 32upx;
		background-image: url("static/zc.png");
		background-size: 40upx 32upx;
	}
	.likeing{
		width: 40upx;
		height: 32upx;
		background-image: url("static/zcxz.png");
		background-size: 40upx 32upx;
	}
	.aicon-Msg{
		background-image: url("static/msg.png")
	}
	.aicon-friend{
		background-image: url("static/friend.png")
	}
	.aicon-keep{
		background-image: url("static/keep.png")
	}
	
	.aicon-qianbao{
		background-image: url("static/qianbao.png")
	}
	.aicon-vip{
		background-image: url("static/vip.png")
	}
	.aicon-shop{
		background-image: url("static/shop.png")
	}
	.aicon-jifen{
		background-image: url("static/jifen.png")
	}
	.aicon-set{
		background-image: url("static/set.png")
	}
	
	.aicon-userinfo{
		background-image: url("static/set (2).png")
	}
	.aicon-anquan{
		background-image: url("static/set (7).png")
	}
	.aicon-showAd{
		background-image: url("static/set (1).png")
	}
	.aicon-about{
		background-image: url("static/set (3).png")
	}
	.aicon-help{
		background-image: url("static/set (4).png")
	}
	.aicon-yinsi{
		background-image: url("static/set (5).png")
	}
	.aicon-logout{
		background-image: url("static/set (6).png")
	}
</style>
