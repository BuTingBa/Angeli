(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/menu/huodong"],{"0736":function(e,t,n){"use strict";var o=n("f9b8"),u=n.n(o);u.a},"17bd":function(e,t,n){"use strict";(function(e,o){Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var u=a(n("26f4"));function a(e){return e&&e.__esModule?e:{default:e}}var i={data:function(){return{MsgList:[],auid:0,val:"",value:"",tuijianren:0}},onLoad:function(t){var n=this;console.log(e(t," at pages\\menu\\huodong.vue:32")),console.log(e("推荐人："+t.tuijianid," at pages\\menu\\huodong.vue:33")),this.getMyMsg(),t.tuijianid&&(this.tuijianren=t.tuijianid,u.default.tgid=this.tuijianren),o.login({provider:"weixin",success:function(t){console.log(e(t," at pages\\menu\\huodong.vue:42")),o.request({method:"POST",url:"https://api.angeli.top/reg.php?type=wxlogin",data:{code:t.code,tuijianId:n.tuijianren},header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(e(t," at pages\\menu\\huodong.vue:55")),u.default.usersk=t.data.data.session_key,u.default.cookie=t.header["Set-Cookie"],"0"!=t.data.code&&(console.log(e("已经存在有账号了，就不要注册了"," at pages\\menu\\huodong.vue:59")),n.tuijianren=0,console.log(e(n.tuijianren," at pages\\menu\\huodong.vue:61")),u.default.userinfo=t.data.data),console.log(e("输出登录结果"," at pages\\menu\\huodong.vue:64")),console.log(e(t," at pages\\menu\\huodong.vue:65"))}})}})},onShow:function(){},methods:{getreg:function(){o.navigateTo({url:"../reg/reg"})},input:function(e){this.val=e.target.value},login:function(){},getme:function(){o.navigateTo({url:"../i/i"})},markmsg:function(){o.request({method:"GET",url:"https://api.angeli.top/user.php?type=mark&class=system",data:{msgId:this.msgid,token:u.default.token},header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(e(t," at pages\\menu\\huodong.vue:108")),"1"==t.data.code?console.log(e("已将信息标记为已读"," at pages\\menu\\huodong.vue:110")):console.log(e("标记已读失败！"," at pages\\menu\\huodong.vue:112"))},complete:function(){}})},InputFocus:function(e){this.InputBottom=e.detail.height},InputBlur:function(e){this.InputBottom=0},getMyMsg:function(){var t=this;o.request({method:"GET",url:"https://api.angeli.top/user.php?type=getMyTG",data:{token:u.default.token},header:{"content-type":"application/x-www-form-urlencoded"},success:function(n){console.log(e(n," at pages\\menu\\huodong.vue:139")),"1"==n.data.code&&(t.MsgList=n.data.data),console.log(e(t.MsgList," at pages\\menu\\huodong.vue:143"))}})}},onShareAppMessage:function(t){return console.log(e("MyId:"+u.default.userinfo.Auid," at pages\\menu\\huodong.vue:149")),"button"===t.from&&console.log(e(t.target," at pages\\menu\\huodong.vue:151")),{title:"给你安利一个好东西",path:"/pages/menu/huodong?tuijianid="+u.default.userinfo.Auid}}};t.default=i}).call(this,n("0de9")["default"],n("6e42")["default"])},"651d":function(e,t,n){"use strict";(function(e){n("7e16"),n("921b");o(n("66fd"));var t=o(n("d4c4"));function o(e){return e&&e.__esModule?e:{default:e}}e(t.default)}).call(this,n("6e42")["createPage"])},c846:function(e,t,n){"use strict";var o=function(){var e=this,t=e.$createElement;e._self._c},u=[];n.d(t,"a",function(){return o}),n.d(t,"b",function(){return u})},d166:function(e,t,n){"use strict";n.r(t);var o=n("17bd"),u=n.n(o);for(var a in o)"default"!==a&&function(e){n.d(t,e,function(){return o[e]})}(a);t["default"]=u.a},d4c4:function(e,t,n){"use strict";n.r(t);var o=n("c846"),u=n("d166");for(var a in u)"default"!==a&&function(e){n.d(t,e,function(){return u[e]})}(a);n("0736");var i=n("2877"),s=Object(i["a"])(u["default"],o["a"],o["b"],!1,null,null,null);t["default"]=s.exports},f9b8:function(e,t,n){}},[["651d","common/runtime","common/vendor"]]]);