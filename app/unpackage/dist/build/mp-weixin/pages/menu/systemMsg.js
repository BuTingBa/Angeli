(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/menu/systemMsg"],{"0ca4":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=u(n("26f4"));function u(t){return t&&t.__esModule?t:{default:t}}var a={data:function(){return{MsgList:[],auid:0,val:"",value:""}},onLoad:function(t){this.getMyMsg()},onShow:function(){this.markmsg()},methods:{input:function(t){this.val=t.target.value},getme:function(){t.navigateTo({url:"../i/i"})},getLink:function(e){console.log(e),t.navigateTo({url:e})},markmsg:function(){t.request({method:"GET",url:"https://api.angeli.top/user.php?type=mark&class=system",data:{msgId:this.msgid},header:{"content-type":"application/x-www-form-urlencoded",Cookie:o.default.cookie},success:function(t){console.log(t),"1"==t.data.code?console.log("已将信息标记为已读"):console.log("标记已读失败！")},complete:function(){}})},InputFocus:function(t){this.InputBottom=t.detail.height},InputBlur:function(t){this.InputBottom=0},getMyMsg:function(){var e=this;t.request({method:"GET",url:"https://api.angeli.top/user.php?type=getSystemMsg",data:{},header:{"content-type":"application/x-www-form-urlencoded",Cookie:o.default.cookie},success:function(t){console.log(t),"1"==t.data.code&&(e.MsgList=t.data.data),console.log(e.MsgList)}})}}};e.default=a}).call(this,n("543d")["default"])},"3b90":function(t,e,n){"use strict";n.r(e);var o=n("95cf"),u=n("ee9a");for(var a in u)"default"!==a&&function(t){n.d(e,t,function(){return u[t]})}(a);var i=n("2877"),c=Object(i["a"])(u["default"],o["a"],o["b"],!1,null,null,null);e["default"]=c.exports},"95cf":function(t,e,n){"use strict";var o=function(){var t=this,e=t.$createElement;t._self._c},u=[];n.d(e,"a",function(){return o}),n.d(e,"b",function(){return u})},ee9a:function(t,e,n){"use strict";n.r(e);var o=n("0ca4"),u=n.n(o);for(var a in o)"default"!==a&&function(t){n.d(e,t,function(){return o[t]})}(a);e["default"]=u.a},f543:function(t,e,n){"use strict";(function(t){n("7e16"),n("921b");o(n("66fd"));var e=o(n("3b90"));function o(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])}},[["f543","common/runtime","common/vendor"]]]);