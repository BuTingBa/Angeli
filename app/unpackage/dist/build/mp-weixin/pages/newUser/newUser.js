(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/newUser/newUser"],{"6e64":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=a(n("9ca3"));function a(t){return t&&t.__esModule?t:{default:t}}var i={data:function(){return{imgurl:{man:"../../static/11.png",woman:"../../static/21.png"},dataSex:1,name:"安个利用户",tishi:"用户名能修改的次数很少，谨慎填写",zhuce:!1,phone:""}},onLoad:function(t){console.log(t.phone),this.phone=t.phone},methods:{login:function(){t.showLoading({title:"正在注册"}),console.log(this.name,this.dataSex,this.phone),t.request({method:"GET",url:"https://api.angeli.top/reg.php?type=reg",data:{username:this.name,gender:this.dataSex,phone:this.phone},header:{"content-type":"application/x-www-form-urlencoded",Cookie:o.default.cookie},success:function(e){console.log(e),t.hideLoading(),"1"==e.data.code&&(t.showToast({title:e.data.msg,position:"bottom",icon:"none",duration:2e3,mask:!0}),o.default.userinfo=e.data.data,o.default.cookie=e.header["Set-Cookie"],setTimeout(function(){t.redirectTo({url:"../Home/Home"})},1500))},fail:function(){console.log("失败"),t.hideLoading(),t.showToast({title:"未知原因注册失败",position:"bottom",icon:"none",duration:2e3,mask:!0})}})},sex:function(t){this.dataSex=t,"1"==t?(this.imgurl.man="../../static/10.png",this.imgurl.woman="../../static/21.png"):(this.imgurl.man="../../static/11.png",this.imgurl.woman="../../static/20.png")},inpuing:function(e){var n=this;this.name=e.detail.value,t.request({method:"GET",url:"https://api.angeli.top/reg.php?type=checkName&name="+this.name,data:{},header:{"content-type":"application/x-www-form-urlencoded",Cookie:o.default.cookie},success:function(t){"0"==t.data.code?(n.tishi=t.data.msg,n.zhuce=!1):(n.tishi=t.data.msg,n.zhuce=!0),console.log(n.zhuce)}})}}};e.default=i}).call(this,n("543d")["default"])},"752e":function(t,e,n){"use strict";n.r(e);var o=n("6e64"),a=n.n(o);for(var i in o)"default"!==i&&function(t){n.d(e,t,function(){return o[t]})}(i);e["default"]=a.a},"9e12":function(t,e,n){"use strict";var o=n("a6a1"),a=n.n(o);a.a},a292:function(t,e,n){"use strict";n.r(e);var o=n("b9c4"),a=n("752e");for(var i in a)"default"!==i&&function(t){n.d(e,t,function(){return a[t]})}(i);n("9e12");var u=n("2877"),s=Object(u["a"])(a["default"],o["a"],o["b"],!1,null,null,null);e["default"]=s.exports},a6a1:function(t,e,n){},b9c4:function(t,e,n){"use strict";var o=function(){var t=this,e=t.$createElement;t._self._c},a=[];n.d(e,"a",function(){return o}),n.d(e,"b",function(){return a})}},[["f762","common/runtime","common/vendor"]]]);