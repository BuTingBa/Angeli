import Vue from 'vue'
import App from './App'
import cook from 'server.js'
import cuCustom from 'components/colorui/components/cu-custom.vue'
import sunUiOos from './components/sunui-upimg/sunui-upimg-alioos.vue'
Vue.component('cu-custom',cuCustom)
Vue.component('sunui-upoos',sunUiOos)
Vue.config.productionTip = false

Vue.prototype.$getUserinfo=function(){
	uni.request({
		method:'GET',
		url: "https://api.angeli.top/user.php?type=getUserInfo", //仅为示例，并非真实接口地址。
		data: {
			auid:cook.userinfo.Auid
		},
		header: {
			'content-type': 'application/x-www-form-urlencoded',
			'Cookie':cook.cookie
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
Vue.prototype.$jubao=function (postId,auid,beijubao,liyou){
	uni.request({
		method:'GET',
		url: "https://api.angeli.top/post.php?type=jubao", //仅为示例，并非真实接口地址。
		data: {
			postId:postId,
			auid:auid,
			beijubao:beijubao,
			liyou:liyou
		},
		header: {
			'content-type': 'application/x-www-form-urlencoded',
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
			postId:postid
		},
		header: {
			'content-type': 'application/x-www-form-urlencoded',
			'Cookie':cook.cookie
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
