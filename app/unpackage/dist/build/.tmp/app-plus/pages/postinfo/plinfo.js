(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/postinfo/plinfo"],{"03bd":function(t,o,e){"use strict";(function(t,i){Object.defineProperty(o,"__esModule",{value:!0}),o.default=void 0;var n=a(e("26f4"));function a(t){return t&&t.__esModule?t:{default:t}}var s={data:function(){return{list:[],plnr:"",huifuid:0,setvar:"",pluid:0,InputBottom:0,postid:0}},onLoad:function(o){console.log(t(o," at pages\\postinfo\\plinfo.vue:59")),this.huifuid=o.CommentsId,this.postid=o.postId,this.getpl(o.CommentsId,o.postId)},methods:{getbieren:function(t){t==n.default.userinfo.Auid?i.navigateTo({url:"../i/i"}):i.navigateTo({url:"../i/bieren?auid="+t})},sendpl:function(o){var e=this;if(console.log(t(this.plnr," at pages\\postinfo\\plinfo.vue:77")),"null"!=this.plnr&&""!=this.plnr)if(this.plnr.length>300||this.plnr.length<1)i.showToast({title:"内容最少1个字，最多300字",position:"bottom",icon:"none",mask:!0});else if(this.huifuid&&1==this.huifu){var a=this.plnr.substring(0,this.plnr.indexOf(":")+1),s=this.plnr.replace(a,"");i.showLoading({title:"正在提交数据"}),i.request({method:"POST",url:"https://api.angeli.top/post.php?type=hfpl",data:{id:this.huifuid,txt:s,postid:this.postid,uid:this.pluid,auid:n.default.userinfo.Auid},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(o){"1"==o.data.code&&(e.setvar="",e.plnr="",i.request({method:"GET",url:"https://api.angeli.top/post.php?type=getpl",data:{postid:e.postid},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(o){console.log(t("————————————评论详情——————————"," at pages\\postinfo\\plinfo.vue:133")),e.pllist=o.data.data,e.plnr,console.log(t(e.pllist," at pages\\postinfo\\plinfo.vue:136"))}})),i.showToast({title:o.data.msg,position:"bottom",icon:"none",duration:2e3,mask:!0}),setTimeout(function(){i.hideLoading()},2e3)},complete:function(){i.hideLoading()}})}else i.showLoading({title:"正在提交数据"}),i.request({method:"POST",url:"https://api.angeli.top/post.php?type=pl",data:{auid:n.default.userinfo.Auid,txt:this.plnr,postid:this.postid,uid:this.postInfo.AuthorId},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(o){"1"==o.data.code&&(e.plnr="",e.setvar="",i.request({method:"GET",url:"https://api.angeli.top/post.php?type=getpl",data:{postid:e.postid},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(o){console.log(t("————————————评论详情——————————"," at pages\\postinfo\\plinfo.vue:190")),e.pllist=o.data.data,console.log(t(e.pllist," at pages\\postinfo\\plinfo.vue:192")),e.plnr}})),i.showToast({title:o.data.msg,position:"bottom",icon:"none",duration:2e3,mask:!0}),setTimeout(function(){i.hideLoading()},2e3)},complete:function(){i.hideLoading()}});else i.showToast({title:"请输入评论内容！",position:"bottom",icon:"none",mask:!0})},InputFocus:function(t){this.InputBottom=t.detail.height},InputBlur:function(t){this.InputBottom=0},pinglun:function(t){this.plnr=t.target.value,0==this.plnr.indexOf("@")?this.huifu=!0:this.huifu=!1},getpl:function(t,o){var e=this;i.showLoading({title:"正在请求服务器"}),i.request({method:"GET",url:"https://api.angeli.top/post.php?type=getdupl&CommentsId="+t,data:{},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(n){"1"==n.data.code?(e.plnr=n.data.data,e.getList(o,t,n.data.data.userinfo.Auid),e.$forceUpdate()):i.showToast({title:n.data.msg,position:"bottom",icon:"none"})},complete:function(){i.hideLoading()}})},huifua:function(o,e,i){this.setvar="@"+o+": ",this.huifu=!0,this.pluid=i,console.log(t("回复",o,i," at pages\\postinfo\\plinfo.vue:264"))},getList:function(t,o,e){var a=this;i.showLoading({title:"正在请求服务器"}),i.request({method:"GET",url:"https://api.angeli.top/post.php?type=gethflist&CommentsId="+o+"&postid="+t,data:{pluid:e},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(t){"1"==t.data.code?(a.list=t.data.data,a.$forceUpdate()):i.showToast({title:t.data.msg,position:"bottom",icon:"none"})},complete:function(){i.hideLoading()}})}}};o.default=s}).call(this,e("0de9")["default"],e("6e42")["default"])},1814:function(t,o,e){"use strict";e.r(o);var i=e("3923"),n=e("909c");for(var a in n)"default"!==a&&function(t){e.d(o,t,function(){return n[t]})}(a);e("c696");var s=e("2877"),u=Object(s["a"])(n["default"],i["a"],i["b"],!1,null,null,null);o["default"]=u.exports},2742:function(t,o,e){"use strict";(function(t){e("7e16"),e("921b");i(e("66fd"));var o=i(e("1814"));function i(t){return t&&t.__esModule?t:{default:t}}t(o.default)}).call(this,e("6e42")["createPage"])},3923:function(t,o,e){"use strict";var i=function(){var t=this,o=t.$createElement;t._self._c},n=[];e.d(o,"a",function(){return i}),e.d(o,"b",function(){return n})},"909c":function(t,o,e){"use strict";e.r(o);var i=e("03bd"),n=e.n(i);for(var a in i)"default"!==a&&function(t){e.d(o,t,function(){return i[t]})}(a);o["default"]=n.a},a733:function(t,o,e){},c696:function(t,o,e){"use strict";var i=e("a733"),n=e.n(i);n.a}},[["2742","common/runtime","common/vendor"]]]);