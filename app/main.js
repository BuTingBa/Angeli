import Vue from 'vue'
import App from './App'
import cuCustom from 'components/colorui/components/cu-custom.vue'
import sunUiOos from './components/sunui-upimg/sunui-upimg-alioos.vue'
Vue.component('cu-custom',cuCustom)
Vue.component('sunui-upoos',sunUiOos)
Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
