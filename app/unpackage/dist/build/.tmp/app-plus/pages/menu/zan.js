(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/menu/zan"],{"08e0":function(e,t,n){},"2be8":function(e,t,n){"use strict";n.r(t);var a=n("d6da"),o=n.n(a);for(var u in a)"default"!==u&&function(e){n.d(t,e,function(){return a[e]})}(u);t["default"]=o.a},"34d8":function(e,t,n){"use strict";n.r(t);var a=n("3e6e"),o=n("2be8");for(var u in o)"default"!==u&&function(e){n.d(t,e,function(){return o[e]})}(u);n("8b2b");var i=n("2877"),c=Object(i["a"])(o["default"],a["a"],a["b"],!1,null,null,null);t["default"]=c.exports},"3e6e":function(e,t,n){"use strict";var a=function(){var e=this,t=e.$createElement;e._self._c},o=[];n.d(t,"a",function(){return a}),n.d(t,"b",function(){return o})},"763f":function(e,t,n){"use strict";(function(e){n("7e16"),n("921b");a(n("66fd"));var t=a(n("34d8"));function a(e){return e&&e.__esModule?e:{default:e}}e(t.default)}).call(this,n("6e42")["createPage"])},"8b2b":function(e,t,n){"use strict";var a=n("08e0"),o=n.n(a);o.a},d6da:function(e,t,n){"use strict";(function(e,a){Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=u(n("26f4"));function u(e){return e&&e.__esModule?e:{default:e}}var i={data:function(){return{TabCur:0,msgList:[]}},onLoad:function(){var t=this;e.showLoading({title:"加载中"}),e.request({method:"GET",url:"https://api.angeli.top/user.php?type=getMyZan",data:{},header:{"content-type":"application/x-www-form-urlencoded",Cookie:o.default.cookie},success:function(e){console.log(a(e," at pages\\menu\\zan.vue:58")),"1"==e.data.code&&(t.msgList=e.data.data,t.markMsg())},complete:function(){e.hideLoading()}})},methods:{tabSelect:function(e){this.TabCur=e},getbieren:function(t){t==o.default.userinfo.Auid?e.navigateTo({url:"../i/i"}):e.navigateTo({url:"../i/bieren?auid="+t})},getPostInfo:function(t){e.navigateTo({url:"../postinfo/postinfo?id="+t})},markMsg:function(){e.request({method:"GET",url:"https://api.angeli.top/user.php?type=mark&class=zan",data:{},header:{"content-type":"application/x-www-form-urlencoded",Cookie:o.default.cookie},success:function(e){console.log(a(e," at pages\\menu\\zan.vue:104")),"1"==e.data.code?console.log(a("已将信息标记为已读"," at pages\\menu\\zan.vue:106")):console.log(a("标记已读失败！"," at pages\\menu\\zan.vue:108"))},complete:function(){}})}},onBackPress:function(){console.log(a("返回"," at pages\\menu\\zan.vue:119")),e.request({method:"GET",url:"https://api.angeli.top/user.php?type=mark",data:{},header:{"content-type":"application/x-www-form-urlencoded",Cookie:o.default.cookie},success:function(e){console.log(a(e," at pages\\menu\\zan.vue:131")),"1"==e.data.code?console.log(a("已将信息标记为已读"," at pages\\menu\\zan.vue:133")):console.log(a("标记已读失败！"," at pages\\menu\\zan.vue:135"))},complete:function(){}})}};t.default=i}).call(this,n("6e42")["default"],n("0de9")["default"])}},[["763f","common/runtime","common/vendor"]]]);