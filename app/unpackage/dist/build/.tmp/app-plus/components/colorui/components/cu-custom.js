(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/colorui/components/cu-custom"],{"285d":function(t,a,n){"use strict";var e=function(){var t=this,a=t.$createElement;t._self._c},u=[];n.d(a,"a",function(){return e}),n.d(a,"b",function(){return u})},"476e":function(t,a,n){"use strict";n.r(a);var e=n("285d"),u=n("7f68");for(var o in u)"default"!==o&&function(t){n.d(a,t,function(){return u[t]})}(o);var r=n("2877"),c=Object(r["a"])(u["default"],e["a"],e["b"],!1,null,null,null);a["default"]=c.exports},"7f68":function(t,a,n){"use strict";n.r(a);var e=n("9a11"),u=n.n(e);for(var o in e)"default"!==o&&function(t){n.d(a,t,function(){return e[t]})}(o);a["default"]=u.a},"9a11":function(t,a,n){"use strict";(function(t){Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{StatusBar:this.StatusBar,CustomBar:this.CustomBar}},name:"cu-custom",computed:{style:function(){var t=this.StatusBar,a=this.CustomBar,n=this.bgImage,e="height:".concat(a,"px;padding-top:").concat(t,"px;");return this.bgImage&&(e="".concat(e,"background-image:url(").concat(n,");")),e}},props:{bgColor:{type:String,default:""},isBack:{type:[Boolean,String],default:!1},bgImage:{type:String,default:""}},methods:{BackPage:function(){t.navigateBack({delta:1})}}};a.default=n}).call(this,n("6e42")["default"])}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/colorui/components/cu-custom-create-component',
    {
        'components/colorui/components/cu-custom-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('6e42')['createComponent'](__webpack_require__("476e"))
        })
    },
    [['components/colorui/components/cu-custom-create-component']]
]);                
