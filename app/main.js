import Vue from 'vue'
import App from './App'
import server from 'server.js';
import cuCustom from 'components/colorui/components/cu-custom.vue'
import sunUiOos from './components/sunui-upimg/sunui-upimg-alioos.vue'
Vue.component('cu-custom',cuCustom)
Vue.component('sunui-upoos',sunUiOos)
Vue.config.productionTip = false

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
			'Cookie':server.cookie
		},
		success: (res) => {
			return res.data.msg;
		}
	});
	return a;
}




App.mpType = 'app'

const app = new Vue({
    
})
app.$mount()
