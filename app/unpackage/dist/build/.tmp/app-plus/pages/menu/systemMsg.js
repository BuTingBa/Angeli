(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/menu/systemMsg"],{"0ca4":function(t,e,n){"use strict";(function(t,u){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=o(n("26f4"));function o(t){return t&&t.__esModule?t:{default:t}}var s={data:function(){return{MsgList:[],auid:0,val:"",value:""}},onLoad:function(t){this.getMyMsg()},onShow:function(){this.markmsg()},methods:{input:function(t){this.val=t.target.value},getme:function(){t.navigateTo({url:"../i/i"})},getLink:function(e){console.log(u(e," at pages\\menu\\systemMsg.vue:54")),t.navigateTo({url:e})},markmsg:function(){t.request({method:"GET",url:"https://api.angeli.top/user.php?type=mark&class=system",data:{msgId:this.msgid,token:a.default.token},header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(u(t," at pages\\menu\\systemMsg.vue:72")),"1"==t.data.code?console.log(u("已将信息标记为已读"," at pages\\menu\\systemMsg.vue:74")):console.log(u("标记已读失败！"," at pages\\menu\\systemMsg.vue:76"))},complete:function(){}})},InputFocus:function(t){this.InputBottom=t.detail.height},InputBlur:function(t){this.InputBottom=0},getMyMsg:function(){var e=this;t.request({method:"GET",url:"https://api.angeli.top/user.php?type=getSystemMsg",data:{token:a.default.token},header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(u(t," at pages\\menu\\systemMsg.vue:103")),"1"==t.data.code&&(e.MsgList=t.data.data),console.log(u(e.MsgList," at pages\\menu\\systemMsg.vue:108"))}})}}};e.default=s}).call(this,n("6e42")["default"],n("0de9")["default"])},"3b90":function(t,e,n){"use strict";n.r(e);var u=n("e2a4"),a=n("ee9a");for(var o in a)"default"!==o&&function(t){n.d(e,t,function(){return a[t]})}(o);var s=n("2877"),i=Object(s["a"])(a["default"],u["a"],u["b"],!1,null,null,null);e["default"]=i.exports},e2a4:function(t,e,n){"use strict";var u=function(){var t=this,e=t.$createElement;t._self._c},a=[];n.d(e,"a",function(){return u}),n.d(e,"b",function(){return a})},ee9a:function(t,e,n){"use strict";n.r(e);var u=n("0ca4"),a=n.n(u);for(var o in u)"default"!==o&&function(t){n.d(e,t,function(){return u[t]})}(o);e["default"]=a.a},f543:function(t,e,n){"use strict";(function(t){n("7e16"),n("921b");u(n("66fd"));var e=u(n("3b90"));function u(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("6e42")["createPage"])}},[["f543","common/runtime","common/vendor"]]]);