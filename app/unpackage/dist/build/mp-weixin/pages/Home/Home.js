(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/Home/Home"],{"2d52":function(t,o,e){"use strict";var n=function(){var t=this,o=t.$createElement;t._self._c},a=[];e.d(o,"a",function(){return n}),e.d(o,"b",function(){return a})},"615c":function(t,o,e){"use strict";e.r(o);var n=e("2d52"),a=e("bc13");for(var i in a)"default"!==i&&function(t){e.d(o,t,function(){return a[t]})}(i);e("9871");var s=e("2877"),u=Object(s["a"])(a["default"],n["a"],n["b"],!1,null,null,null);o["default"]=u.exports},9871:function(t,o,e){"use strict";var n=e("e4be"),a=e.n(n);a.a},bc13:function(t,o,e){"use strict";e.r(o);var n=e("ef7c"),a=e.n(n);for(var i in n)"default"!==i&&function(t){e.d(o,t,function(){return n[t]})}(i);o["default"]=a.a},e4be:function(t,o,e){},ef7c:function(t,o,e){"use strict";(function(t){Object.defineProperty(o,"__esModule",{value:!0}),o.default=void 0;var n=a(e("26f4"));function a(t){return t&&t.__esModule?t:{default:t}}function i(t,o,e){return o in t?Object.defineProperty(t,o,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[o]=e,t}var s=function(){return e.e("components/uni-load-more").then(e.bind(null,"caa8"))},u={components:{uniLoadMore:s},data:function(){return{Dindex:[],CustomBar:this.CustomBar,modalName:null,AvatarUrl:"",TabCur:0,gaodu:0,msgNumber:0,weikong:!0,username:"游客",page:1,postList:[],scrollTop:0,old:{scrollTop:0},status:"loading",statusTypes:[{value:"more",text:"加载前",checked:!0},{value:"loading",text:"加载中",checked:!1},{value:"noMore",text:"我是有底线的",checked:!1}],contentText:{contentdown:"查看更多",contentrefresh:"加载中",contentnomore:"我是有底线的"},zhongcao:"0",guanzhu:"0",userid:"0",fensi:"0",dengji:"0",systemConfig:"",index:!1,userInfo:[],menuList:["分享给朋友","生成海报","举报"]}},onShow:function(){var o=this;t.request({method:"GET",url:"https://api.angeli.top/user.php?type=getMyNoRead",data:{auid:this.userid},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(t){console.log(t),"1"==t.data.code&&(o.msgNumber=t.data.data.count),console.log(o.msgNumber)},complete:function(){}})},onLoad:function(){var o=this;this.postList=[],""!=n.default.userinfo.Auid&&null!=n.default.userinfo.Auid||t.login({provider:"weixin",success:function(e){console.log(e),t.request({method:"POST",url:"https://api.angeli.top/reg.php?type=wxlogin",data:{code:e.code},header:{"content-type":"application/x-www-form-urlencoded"},success:function(e){console.log(e),"0"==e.data.code?(n.default.usersk=e.data.data.session_key,t.showToast({title:"欢迎你，游客",position:"bottom",icon:"none"}),o.AvatarUrl="https://sz.oss.data.angeli.top/angeli-image/1562320238188110.png"):"1"!=e.data.code&&"2"!=e.data.code||(o.AvatarUrl=e.data.data.AvatarUrl,o.username=e.data.data.UserName,o.zhongcao=e.data.data.ZhongcaoCount,o.guanzhu=e.data.data.FollowedCount,o.fensi=e.data.data.FollowerCount,o.dengji=e.data.data.Rank,o.userid=e.data.data.Auid,o.userInfo=e.data.data,n.default.userinfo=e.data.data,n.default.cookie=e.header["Set-Cookie"],"2"==e.data.code?t.showToast({title:e.data.msg,position:"bottom",icon:"none"}):o.userInfo.VIPEndTime>0?t.showToast({title:"欢迎VIP："+o.username,position:"bottom",icon:"none"}):t.showToast({title:"欢迎你,"+o.username,position:"bottom",icon:"none"}))},complete:function(){o.getPostData("new",0)},fail:function(o){t.showToast({title:"未知原因，登录失败！",position:"bottom",icon:"none"})}})},fail:function(o){t.showToast({title:"登录失败呢！关闭之后重新打开吧！",position:"bottom"}),console.error("授权登录失败："+JSON.stringify(o))}}),this.getSysConfig("home_txt")},onReady:function(){this.getHei()},methods:{aotuloding:function(){t.showToast(i({title:"刚刚出现问题，已经为你修复",position:"bottom",icon:"none"},"position","center")),this.getPostData("new",0)},getshop:function(){this.systemConfig="商城正在建设中",this.modalName="DialogModal2"},getSysConfig:function(o){var e=this;t.request({method:"GET",url:"https://api.angeli.top/user.php?type=getSysConfig",data:{configName:o},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(t){console.log(t),"1"==t.data.code&&(e.systemConfig=t.data.data,t.data.data?e.modalName="DialogModal2":e.modalName=""),console.log(e.msgNumber)},complete:function(){}})},getFriend:function(){t.navigateTo({url:"../menu/friend"})},getbieren:function(o){o==n.default.userinfo.Auid?t.navigateTo({url:"../i/i"}):t.navigateTo({url:"../i/bieren?auid="+o})},getShoucang:function(){t.navigateTo({url:"../menu/shoucang"})},getJifen:function(){t.navigateTo({url:"../menu/jifen"})},getMessage:function(){t.navigateTo({url:"../menu/Message"})},wallet:function(){t.navigateTo({url:"../menu/wallet"})},set:function(){t.navigateTo({url:"../set/set"})},logout:function(){t.clearStorage(),t.showToast(i({title:"已退出登录",position:"bottom",icon:"none"},"position","center")),n.default.userinfo=[],n.default.cookie="",this.modalName=null},cs:function(t){this.old.scrollTop=t.detail.scrollTop},sousuo:function(){t.navigateTo({url:"../sousuo/sousuo"})},getClass:function(o){t.navigateTo({url:"../classPost/classPost?id="+o})},showModal:function(o){var e=this;this.modalName=o.currentTarget.dataset.target,this.AvatarUrl=n.default.userinfo.AvatarUrl,this.username=n.default.userinfo.UserName,this.zhongcao=n.default.userinfo.ZhongcaoCount,this.guanzhu=n.default.userinfo.FollowedCount,this.fensi=n.default.userinfo.FollowerCount,this.dengji=n.default.userinfo.Rank,this.userid=n.default.userinfo.Auid,console.log("测试！：",this.username),t.request({method:"GET",url:"https://api.angeli.top/user.php?type=getMyNoRead",data:{auid:this.userid},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(t){console.log(t),"1"==t.data.code&&(e.msgNumber=t.data.data.count),console.log(e.msgNumber)},complete:function(){}})},hideModal:function(t){this.modalName=null},tabSelect:function(t){this.TabCur=t,this.scrollTop=this.old.scrollTop,this.$nextTick(function(){this.scrollTop=0}),console.log(t),0==t?this.getPostData("new",0):1==t?this.getPostData("hot",0):2==t&&this.getPostData("guanzhu",0)},Like:function(o,e,a,i){var s=this;if(!0===a)var u="del";else u="add";t.request({method:"GET",url:"https://api.angeli.top/post.php?type=Like",data:{fuid:e,postid:o,mode:u},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(o){"1"==o.data.code?("add"==u?(s.postList[i].Give=!0,t.showToast({title:"种草成功！",position:"bottom",icon:"none"})):(s.postList[i].Give=!1,t.showToast({title:"取消种草成功！",position:"bottom",icon:"none"})),s.$forceUpdate()):t.showToast({title:"种草失败！",position:"bottom",icon:"none"})},complete:function(){}})},caidan:function(o){console.log(o),this.Dindex=o,o.AuthorId==n.default.userinfo.Auid?this.menuList=["分享给朋友","生成海报","举报","删除帖子"]:this.menuList=["分享给朋友","生成海报","举报"],t.showActionSheet({itemList:this.menuList,success:function(o){switch(o.tapIndex){case 0:t.showToast({title:"分享"+o.Content,position:"bottom",icon:"none"});break;case 1:break;case 2:break;case 3:break;default:}},fail:function(t){console.log(t.errMsg)}})},getHei:function(){var o=this,e=t.createSelectorQuery().select("#topbox");e.fields({size:!0,scrollOffset:!0},function(t){o.gaodu=t.height-5,console.log("总高度："+o.gaodu)}).exec()},showImage:function(o,e){t.previewImage({current:e,urls:o,longPressActions:{itemList:["发送给朋友","保存图片","收藏"],success:function(o){t.showToast({title:"选中了第"+(o.tapIndex+1)+"个按钮",position:"bottom",icon:"none"}),console.log("选中了第"+(o.tapIndex+1)+"个按钮")},fail:function(t){console.log(t.errMsg)}}})},getPostData:function(o,e){var a=this;console.log(o),t.request({method:"GET",url:"https://api.angeli.top/post.php?type=outPostList",data:{page:0,postType:o,count:10,classId:e},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(o){a.postList=[],console.log(o.data.data),console.log("————————————帖子列表——————————"),a.postList=o.data.data,console.log(a.postList),a.weikong=!1,"1"!==o.data.code&&t.showToast({title:"获取帖子失败，建议重启",position:"bottom",icon:"none"}),0==o.data.data&&(a.weikong=!0)},complete:function(){a.$forceUpdate()}})},reguser:function(){""==n.default.userinfo.Auid||null==n.default.userinfo.Auid?t.navigateTo({url:"../reg/reg"}):t.navigateTo({url:"../i/i"})},getVip:function(){t.navigateTo({url:"../vip/vip"})},pluspost:function(){console.log("发帖：",n.default.userinfo.Auid),""==n.default.userinfo.Auid||null==n.default.userinfo.Auid?(t.showToast({title:"你还没有登录，请登录后再来吧",position:"bottom",icon:"none"}),setTimeout(function(){t.navigateTo({url:"../reg/reg"})},1200)):t.navigateTo({url:"../post/post"})},EndLoding:function(o){var e=this;this.page++,console.log(this.scrollTop),t.request({method:"GET",url:"https://api.angeli.top/post.php?type=outPostList",data:{page:this.page,posttype:"1",sort:"PsotDate",count:10},header:{"content-type":"application/x-www-form-urlencoded",Cookie:n.default.cookie},success:function(t){console.log("————————————帖子列表——————————"+e.page),void 0==t.data.data.length?(e.page--,e.status="noMore"):(e.postList=e.postList.concat(t.data.data),e.status="loading",console.log(e.postList))}})},getpostinfo:function(o){t.navigateTo({url:"../postinfo/postinfo?id="+o})}},onShareAppMessage:function(t){return"button"===t.from&&console.log(t.target),{title:"给你安利一个安个利",path:"/pages/Home/Home",desc:"超级无敌安个利！"}}};o.default=u}).call(this,e("543d")["default"])}},[["8506","common/runtime","common/vendor"]]]);