(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/classPost/classPost"],{5255:function(t,e,o){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n,i=s(o("26f4"));function s(t){return t&&t.__esModule?t:{default:t}}function a(t,e,o){return e in t?Object.defineProperty(t,e,{value:o,enumerable:!0,configurable:!0,writable:!0}):t[e]=o,t}var c=function(){return o.e("components/uni-load-more").then(o.bind(null,"caa8"))},u={components:{uniLoadMore:c},data:function(){return{TabCur:0,postList:[],status:"loading",type:"new",statusTypes:[{value:"more",text:"加载前",checked:!0},{value:"loading",text:"加载中",checked:!1},{value:"noMore",text:"我是有底线的",checked:!1}],contentText:{contentdown:"查看更多",contentrefresh:"加载中",contentnomore:"我是有底线的"}}},onLoad:function(t){this.classId=t.id,this.getPostList(this.classId)},methods:(n={getbieren:function(e){t.navigateTo({url:"../i/bieren?auid="+e})},getPostList:function(e){var o=this;t.showLoading({title:"获取数据中"}),t.request({method:"GET",url:"https://api.angeli.top/post.php?type=getClassPostList",data:{page:1,count:20,classId:e,postType:this.type},header:{"content-type":"application/x-www-form-urlencoded",Cookie:i.default.cookie},success:function(e){t.hideLoading(),o.postList=[],console.log("————————————帖子列表——————————"),o.postList=e.data.data,console.log(o.postList),o.weikong=!1,"1"!==e.data.code&&t.showToast({title:"获取帖子失败，建议重启",position:"bottom",icon:"none"}),0==e.data.data&&(o.weikong=!0)},complete:function(){t.hideLoading()}})},showImage:function(e,o){t.previewImage({current:o,urls:e,longPressActions:{itemList:["发送给朋友","保存图片","收藏"],success:function(e){t.showToast({title:"选中了第"+(e.tapIndex+1)+"个按钮",position:"bottom",icon:"none"}),console.log("选中了第"+(e.tapIndex+1)+"个按钮")},fail:function(t){console.log(t.errMsg)}}})},caidan:function(e){console.log(e),this.Dindex=e,e.AuthorId==i.default.userinfo.Auid?this.menuList=["分享给朋友","生成海报","举报","删除帖子"]:this.menuList=["分享给朋友","生成海报","举报"],t.showActionSheet({itemList:this.menuList,success:function(e){switch(e.tapIndex){case 0:t.showToast({title:"分享"+e.Content,position:"bottom",icon:"none"});break;case 1:break;case 2:break;case 3:break;default:}},fail:function(t){console.log(t.errMsg)}})}},a(n,"showImage",function(e,o){t.previewImage({current:o,urls:e,longPressActions:{itemList:["发送给朋友","保存图片","收藏"],success:function(e){t.showToast({title:"选中了第"+(e.tapIndex+1)+"个按钮",position:"bottom",icon:"none"}),console.log("选中了第"+(e.tapIndex+1)+"个按钮")},fail:function(t){console.log(t.errMsg)}}})}),a(n,"Like",function(e,o,n,s){var a=this;if(!0===n)var c="del";else c="add";t.request({method:"GET",url:"https://api.angeli.top/post.php?type=Like",data:{fuid:o,postid:e,mode:c},header:{"content-type":"application/x-www-form-urlencoded",Cookie:i.default.cookie},success:function(e){"1"==e.data.code?("add"==c?(a.postList[s].Give=!0,t.showToast({title:"种草成功！",position:"bottom",icon:"none"})):(a.postList[s].Give=!1,t.showToast({title:"取消种草成功！",position:"bottom",icon:"none"})),a.$forceUpdate()):t.showToast({title:"种草失败！",position:"bottom",icon:"none"})},complete:function(){}})}),a(n,"tabSelect",function(t){this.TabCur=t,this.type=0==t?"new":"hot"}),n)};e.default=u}).call(this,o("543d")["default"])},"6a2d":function(t,e,o){"use strict";o.r(e);var n=o("5255"),i=o.n(n);for(var s in n)"default"!==s&&function(t){o.d(e,t,function(){return n[t]})}(s);e["default"]=i.a},c62f:function(t,e,o){"use strict";var n=o("d45b"),i=o.n(n);i.a},c72b:function(t,e,o){"use strict";o.r(e);var n=o("d8c0"),i=o("6a2d");for(var s in i)"default"!==s&&function(t){o.d(e,t,function(){return i[t]})}(s);o("c62f");var a=o("2877"),c=Object(a["a"])(i["default"],n["a"],n["b"],!1,null,null,null);e["default"]=c.exports},d45b:function(t,e,o){},d8c0:function(t,e,o){"use strict";var n=function(){var t=this,e=t.$createElement;t._self._c},i=[];o.d(e,"a",function(){return n}),o.d(e,"b",function(){return i})}},[["5140","common/runtime","common/vendor"]]]);