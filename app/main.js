import Vue from 'vue'
import App from './App'
import cook from 'server.js'
import cuCustom from 'components/colorui/components/cu-custom.vue'
import sunUiOos from './components/sunui-upimg/sunui-upimg-alioos.vue'
Vue.component('cu-custom',cuCustom)
Vue.component('sunui-upoos',sunUiOos)
Vue.config.productionTip = false

//获取用户信息
Vue.prototype.$getUserinfo=function(){
	uni.request({
		method:'GET',
		url: "https://api.angeli.top/user.php?type=getUserInfo", //仅为示例，并非真实接口地址。
		data: {
			auid:cook.userinfo.Auid,
			token:cook.token
		},
		header: {
			'content-type': 'application/x-www-form-urlencoded',
			'system':cook.system
		},
		header: {
			'content-type': 'application/x-www-form-urlencoded',
		},
		success: (res) => {
			cook.userInfo=res.data.data;
			console.log(cook.userInfo)
		}
	});
}



//举报信息
Vue.prototype.$jubao=function (postId,auid,beijubao,liyou){
	uni.request({
		method:'GET',
		url: "https://api.angeli.top/post.php?type=jubao", //仅为示例，并非真实接口地址。
		data: {
			postId:postId,
			auid:auid,
			beijubao:beijubao,
			liyou:liyou,
			token:cook.token
		},
		header: {
			'content-type': 'application/x-www-form-urlencoded',
			'system':cook.system
		},
		success: (res) => {
			return res.data.msg;
		}
	});
}
Vue.prototype.$delPost=function(postid){
	uni.request({
		method:'GET',
		url: "https://api.angeli.top/post.php?type=delPost", //仅为示例，并非真实接口地址。
		data: {
			postId:postid,
			token:cook.token
		},
		header: {
			'content-type': 'application/x-www-form-urlencoded',
		},
		success: (res) => {
			return res.data.msg;
		}
	});
}




App.mpType = 'app'

const app = new Vue({
       ...App
})
app.$mount()
