(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/menu/guanzhu"],{"1e5d":function(t,e,n){"use strict";var o=n("e8a6"),a=n.n(o);a.a},"3be3":function(t,e,n){"use strict";n.r(e);var o=n("67d6"),a=n("5074");for(var u in a)"default"!==u&&function(t){n.d(e,t,function(){return a[t]})}(u);n("1e5d");var c=n("2877"),i=Object(c["a"])(a["default"],o["a"],o["b"],!1,null,null,null);e["default"]=i.exports},5074:function(t,e,n){"use strict";n.r(e);var o=n("b1f8"),a=n.n(o);for(var u in o)"default"!==u&&function(t){n.d(e,t,function(){return o[t]})}(u);e["default"]=a.a},"67d6":function(t,e,n){"use strict";var o=function(){var t=this,e=t.$createElement;t._self._c},a=[];n.d(e,"a",function(){return o}),n.d(e,"b",function(){return a})},"739f":function(t,e,n){"use strict";(function(t){n("7e16"),n("921b");o(n("66fd"));var e=o(n("3be3"));function o(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])},b1f8:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=a(n("26f4"));function a(t){return t&&t.__esModule?t:{default:t}}var u={data:function(){return{TabCur:0,msgList:[]}},onLoad:function(){var e=this;t.showLoading({title:"加载中"}),t.request({method:"GET",url:"https://api.angeli.top/user.php?type=getMyFans",data:{token:o.default.token},header:{"content-type":"application/x-www-form-urlencoded"},success:function(n){console.log(n),"1"==n.data.code?(e.msgList=n.data.data,e.markMsg()):t.showToast({title:"暂无新粉丝通知",position:"bottom",icon:"none"})},complete:function(){t.hideLoading()}})},methods:{getChat:function(e){var n=parseInt(e)+parseInt(o.default.userinfo.Auid);t.navigateTo({url:"../menu/chat?id="+n+"&toid="+e})},tabSelect:function(t){this.TabCur=t},getbieren:function(e){t.navigateTo({url:"../i/bieren?auid="+e})},getPostInfo:function(e){t.navigateTo({url:"../postinfo/postinfo?id="+e})},markMsg:function(){t.request({method:"GET",url:"https://api.angeli.top/user.php?type=mark&class=fans",data:{token:o.default.token},header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(t),"1"==t.data.code?console.log("已将信息标记为已读"):console.log("标记已读失败！")},complete:function(){}})}},onBackPress:function(){console.log("返回"),t.request({method:"GET",url:"https://api.angeli.top/user.php?type=mark&class=fans",data:{token:o.default.token},header:{"content-type":"application/x-www-form-urlencoded"},success:function(t){console.log(t),"1"==t.data.code?console.log("已将信息标记为已读"):console.log("标记已读失败！")},complete:function(){}})}};e.default=u}).call(this,n("543d")["default"])},e8a6:function(t,e,n){}},[["739f","common/runtime","common/vendor"]]]);