<html lang="pt">
<head>
    <!-- Required meta tags -->
    <meta name="title" content="Opet INspira" />
    <meta name="description" content="Opet Inspira" />
    <meta name="keywords" content="Opet Inspira" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript">
        ;window.NREUM||(NREUM={});NREUM.init={privacy:{cookies_enabled:true}};
        window.NREUM||(NREUM={}),__nr_require=function(t,e,n){function r(n){if(!e[n]){var i=e[n]={exports:{}};t[n][0].call(i.exports,function(e){var i=t[n][1][e];return r(i||e)},i,i.exports)}return e[n].exports}if("function"==typeof __nr_require)return __nr_require;for(var i=0;i<n.length;i++)r(n[i]);return r}({1:[function(t,e,n){function r(t){try{c.console&&console.log(t)}catch(e){}}var i,o=t("ee"),a=t(24),c={};try{i=localStorage.getItem("__nr_flags").split(","),console&&"function"==typeof console.log&&(c.console=!0,i.indexOf("dev")!==-1&&(c.dev=!0),i.indexOf("nr_dev")!==-1&&(c.nrDev=!0))}catch(s){}c.nrDev&&o.on("internal-error",function(t){r(t.stack)}),c.dev&&o.on("fn-err",function(t,e,n){r(n.stack)}),c.dev&&(r("NR AGENT IN DEVELOPMENT MODE"),r("flags: "+a(c,function(t,e){return t}).join(", ")))},{}],2:[function(t,e,n){function r(t,e,n,r,c){try{p?p-=1:i(c||new UncaughtException(t,e,n),!0)}catch(f){try{o("ierr",[f,s.now(),!0])}catch(d){}}return"function"==typeof u&&u.apply(this,a(arguments))}function UncaughtException(t,e,n){this.message=t||"Uncaught error with no additional information",this.sourceURL=e,this.line=n}function i(t,e){var n=e?null:s.now();o("err",[t,n])}var o=t("handle"),a=t(25),c=t("ee"),s=t("loader"),f=t("gos"),u=window.onerror,d=!1,l="nr@seenError",p=0;s.features.err=!0,t(1),window.onerror=r;try{throw new Error}catch(h){"stack"in h&&(t(9),t(8),"addEventListener"in window&&t(5),s.xhrWrappable&&t(10),d=!0)}c.on("fn-start",function(t,e,n){d&&(p+=1)}),c.on("fn-err",function(t,e,n){d&&!n[l]&&(f(n,l,function(){return!0}),this.thrown=!0,i(n))}),c.on("fn-end",function(){d&&!this.thrown&&p>0&&(p-=1)}),c.on("internal-error",function(t){o("ierr",[t,s.now(),!0])})},{}],3:[function(t,e,n){t("loader").features.ins=!0},{}],4:[function(t,e,n){function r(t){}if(window.performance&&window.performance.timing&&window.performance.getEntriesByType){var i=t("ee"),o=t("handle"),a=t(9),c=t(8),s="learResourceTimings",f="addEventListener",u="resourcetimingbufferfull",d="bstResource",l="resource",p="-start",h="-end",m="fn"+p,w="fn"+h,v="bstTimer",g="pushState",y=t("loader");y.features.stn=!0,t(7),"addEventListener"in window&&t(5);var x=NREUM.o.EV;i.on(m,function(t,e){var n=t[0];n instanceof x&&(this.bstStart=y.now())}),i.on(w,function(t,e){var n=t[0];n instanceof x&&o("bst",[n,e,this.bstStart,y.now()])}),a.on(m,function(t,e,n){this.bstStart=y.now(),this.bstType=n}),a.on(w,function(t,e){o(v,[e,this.bstStart,y.now(),this.bstType])}),c.on(m,function(){this.bstStart=y.now()}),c.on(w,function(t,e){o(v,[e,this.bstStart,y.now(),"requestAnimationFrame"])}),i.on(g+p,function(t){this.time=y.now(),this.startPath=location.pathname+location.hash}),i.on(g+h,function(t){o("bstHist",[location.pathname+location.hash,this.startPath,this.time])}),f in window.performance&&(window.performance["c"+s]?window.performance[f](u,function(t){o(d,[window.performance.getEntriesByType(l)]),window.performance["c"+s]()},!1):window.performance[f]("webkit"+u,function(t){o(d,[window.performance.getEntriesByType(l)]),window.performance["webkitC"+s]()},!1)),document[f]("scroll",r,{passive:!0}),document[f]("keypress",r,!1),document[f]("click",r,!1)}},{}],5:[function(t,e,n){function r(t){for(var e=t;e&&!e.hasOwnProperty(u);)e=Object.getPrototypeOf(e);e&&i(e)}function i(t){c.inPlace(t,[u,d],"-",o)}function o(t,e){return t[1]}var a=t("ee").get("events"),c=t("wrap-function")(a,!0),s=t("gos"),f=XMLHttpRequest,u="addEventListener",d="removeEventListener";e.exports=a,"getPrototypeOf"in Object?(r(document),r(window),r(f.prototype)):f.prototype.hasOwnProperty(u)&&(i(window),i(f.prototype)),a.on(u+"-start",function(t,e){var n=t[1],r=s(n,"nr@wrapped",function(){function t(){if("function"==typeof n.handleEvent)return n.handleEvent.apply(n,arguments)}var e={object:t,"function":n}[typeof n];return e?c(e,"fn-",null,e.name||"anonymous"):n});this.wrapped=t[1]=r}),a.on(d+"-start",function(t){t[1]=this.wrapped||t[1]})},{}],6:[function(t,e,n){function r(t,e,n){var r=t[e];"function"==typeof r&&(t[e]=function(){var t=o(arguments),e={};i.emit(n+"before-start",[t],e);var a;e[m]&&e[m].dt&&(a=e[m].dt);var c=r.apply(this,t);return i.emit(n+"start",[t,a],c),c.then(function(t){return i.emit(n+"end",[null,t],c),t},function(t){throw i.emit(n+"end",[t],c),t})})}var i=t("ee").get("fetch"),o=t(25),a=t(24);e.exports=i;var c=window,s="fetch-",f=s+"body-",u=["arrayBuffer","blob","json","text","formData"],d=c.Request,l=c.Response,p=c.fetch,h="prototype",m="nr@context";d&&l&&p&&(a(u,function(t,e){r(d[h],e,f),r(l[h],e,f)}),r(c,"fetch",s),i.on(s+"end",function(t,e){var n=this;if(e){var r=e.headers.get("content-length");null!==r&&(n.rxSize=r),i.emit(s+"done",[null,e],n)}else i.emit(s+"done",[t],n)}))},{}],7:[function(t,e,n){var r=t("ee").get("history"),i=t("wrap-function")(r);e.exports=r;var o=window.history&&window.history.constructor&&window.history.constructor.prototype,a=window.history;o&&o.pushState&&o.replaceState&&(a=o),i.inPlace(a,["pushState","replaceState"],"-")},{}],8:[function(t,e,n){var r=t("ee").get("raf"),i=t("wrap-function")(r),o="equestAnimationFrame";e.exports=r,i.inPlace(window,["r"+o,"mozR"+o,"webkitR"+o,"msR"+o],"raf-"),r.on("raf-start",function(t){t[0]=i(t[0],"fn-")})},{}],9:[function(t,e,n){function r(t,e,n){t[0]=a(t[0],"fn-",null,n)}function i(t,e,n){this.method=n,this.timerDuration=isNaN(t[1])?0:+t[1],t[0]=a(t[0],"fn-",this,n)}var o=t("ee").get("timer"),a=t("wrap-function")(o),c="setTimeout",s="setInterval",f="clearTimeout",u="-start",d="-";e.exports=o,a.inPlace(window,[c,"setImmediate"],c+d),a.inPlace(window,[s],s+d),a.inPlace(window,[f,"clearImmediate"],f+d),o.on(s+u,r),o.on(c+u,i)},{}],10:[function(t,e,n){function r(t,e){d.inPlace(e,["onreadystatechange"],"fn-",c)}function i(){var t=this,e=u.context(t);t.readyState>3&&!e.resolved&&(e.resolved=!0,u.emit("xhr-resolved",[],t)),d.inPlace(t,g,"fn-",c)}function o(t){y.push(t),h&&(b?b.then(a):w?w(a):(E=-E,R.data=E))}function a(){for(var t=0;t<y.length;t++)r([],y[t]);y.length&&(y=[])}function c(t,e){return e}function s(t,e){for(var n in t)e[n]=t[n];return e}t(5);var f=t("ee"),u=f.get("xhr"),d=t("wrap-function")(u),l=NREUM.o,p=l.XHR,h=l.MO,m=l.PR,w=l.SI,v="readystatechange",g=["onload","onerror","onabort","onloadstart","onloadend","onprogress","ontimeout"],y=[];e.exports=u;var x=window.XMLHttpRequest=function(t){var e=new p(t);try{u.emit("new-xhr",[e],e),e.addEventListener(v,i,!1)}catch(n){try{u.emit("internal-error",[n])}catch(r){}}return e};if(s(p,x),x.prototype=p.prototype,d.inPlace(x.prototype,["open","send"],"-xhr-",c),u.on("send-xhr-start",function(t,e){r(t,e),o(e)}),u.on("open-xhr-start",r),h){var b=m&&m.resolve();if(!w&&!m){var E=1,R=document.createTextNode(E);new h(a).observe(R,{characterData:!0})}}else f.on("fn-end",function(t){t[0]&&t[0].type===v||a()})},{}],11:[function(t,e,n){function r(t){if(!c(t))return null;var e=window.NREUM;if(!e.loader_config)return null;var n=(e.loader_config.accountID||"").toString()||null,r=(e.loader_config.agentID||"").toString()||null,f=(e.loader_config.trustKey||"").toString()||null;if(!n||!r)return null;var h=p.generateSpanId(),m=p.generateTraceId(),w=Date.now(),v={spanId:h,traceId:m,timestamp:w};return(t.sameOrigin||s(t)&&l())&&(v.traceContextParentHeader=i(h,m),v.traceContextStateHeader=o(h,w,n,r,f)),(t.sameOrigin&&!u()||!t.sameOrigin&&s(t)&&d())&&(v.newrelicHeader=a(h,m,w,n,r,f)),v}function i(t,e){return"00-"+e+"-"+t+"-01"}function o(t,e,n,r,i){var o=0,a="",c=1,s="",f="";return i+"@nr="+o+"-"+c+"-"+n+"-"+r+"-"+t+"-"+a+"-"+s+"-"+f+"-"+e}function a(t,e,n,r,i,o){var a="btoa"in window&&"function"==typeof window.btoa;if(!a)return null;var c={v:[0,1],d:{ty:"Browser",ac:r,ap:i,id:t,tr:e,ti:n}};return o&&r!==o&&(c.d.tk=o),btoa(JSON.stringify(c))}function c(t){return f()&&s(t)}function s(t){var e=!1,n={};if("init"in NREUM&&"distributed_tracing"in NREUM.init&&(n=NREUM.init.distributed_tracing),t.sameOrigin)e=!0;else if(n.allowed_origins instanceof Array)for(var r=0;r<n.allowed_origins.length;r++){var i=h(n.allowed_origins[r]);if(t.hostname===i.hostname&&t.protocol===i.protocol&&t.port===i.port){e=!0;break}}return e}function f(){return"init"in NREUM&&"distributed_tracing"in NREUM.init&&!!NREUM.init.distributed_tracing.enabled}function u(){return"init"in NREUM&&"distributed_tracing"in NREUM.init&&!!NREUM.init.distributed_tracing.exclude_newrelic_header}function d(){return"init"in NREUM&&"distributed_tracing"in NREUM.init&&NREUM.init.distributed_tracing.cors_use_newrelic_header!==!1}function l(){return"init"in NREUM&&"distributed_tracing"in NREUM.init&&!!NREUM.init.distributed_tracing.cors_use_tracecontext_headers}var p=t(21),h=t(13);e.exports={generateTracePayload:r,shouldGenerateTrace:c}},{}],12:[function(t,e,n){function r(t){var e=this.params,n=this.metrics;if(!this.ended){this.ended=!0;for(var r=0;r<l;r++)t.removeEventListener(d[r],this.listener,!1);e.aborted||(n.duration=a.now()-this.startTime,this.loadCaptureCalled||4!==t.readyState?null==e.status&&(e.status=0):o(this,t),n.cbTime=this.cbTime,u.emit("xhr-done",[t],t),c("xhr",[e,n,this.startTime]))}}function i(t,e){var n=s(e),r=t.params;r.host=n.hostname+":"+n.port,r.pathname=n.pathname,t.parsedOrigin=s(e),t.sameOrigin=t.parsedOrigin.sameOrigin}function o(t,e){t.params.status=e.status;var n=w(e,t.lastSize);if(n&&(t.metrics.rxSize=n),t.sameOrigin){var r=e.getResponseHeader("X-NewRelic-App-Data");r&&(t.params.cat=r.split(", ").pop())}t.loadCaptureCalled=!0}var a=t("loader");if(a.xhrWrappable){var c=t("handle"),s=t(13),f=t(11).generateTracePayload,u=t("ee"),d=["load","error","abort","timeout"],l=d.length,p=t("id"),h=t(17),m=t(16),w=t(14),v=window.XMLHttpRequest;a.features.xhr=!0,t(10),t(6),u.on("new-xhr",function(t){var e=this;e.totalCbs=0,e.called=0,e.cbTime=0,e.end=r,e.ended=!1,e.xhrGuids={},e.lastSize=null,e.loadCaptureCalled=!1,t.addEventListener("load",function(n){o(e,t)},!1),h&&(h>34||h<10)||window.opera||t.addEventListener("progress",function(t){e.lastSize=t.loaded},!1)}),u.on("open-xhr-start",function(t){this.params={method:t[0]},i(this,t[1]),this.metrics={}}),u.on("open-xhr-end",function(t,e){"loader_config"in NREUM&&"xpid"in NREUM.loader_config&&this.sameOrigin&&e.setRequestHeader("X-NewRelic-ID",NREUM.loader_config.xpid);var n=f(this.parsedOrigin);if(n){var r=!1;n.newrelicHeader&&(e.setRequestHeader("newrelic",n.newrelicHeader),r=!0),n.traceContextParentHeader&&(e.setRequestHeader("traceparent",n.traceContextParentHeader),n.traceContextStateHeader&&e.setRequestHeader("tracestate",n.traceContextStateHeader),r=!0),r&&(this.dt=n)}}),u.on("send-xhr-start",function(t,e){var n=this.metrics,r=t[0],i=this;if(n&&r){var o=m(r);o&&(n.txSize=o)}this.startTime=a.now(),this.listener=function(t){try{"abort"!==t.type||i.loadCaptureCalled||(i.params.aborted=!0),("load"!==t.type||i.called===i.totalCbs&&(i.onloadCalled||"function"!=typeof e.onload))&&i.end(e)}catch(n){try{u.emit("internal-error",[n])}catch(r){}}};for(var c=0;c<l;c++)e.addEventListener(d[c],this.listener,!1)}),u.on("xhr-cb-time",function(t,e,n){this.cbTime+=t,e?this.onloadCalled=!0:this.called+=1,this.called!==this.totalCbs||!this.onloadCalled&&"function"==typeof n.onload||this.end(n)}),u.on("xhr-load-added",function(t,e){var n=""+p(t)+!!e;this.xhrGuids&&!this.xhrGuids[n]&&(this.xhrGuids[n]=!0,this.totalCbs+=1)}),u.on("xhr-load-removed",function(t,e){var n=""+p(t)+!!e;this.xhrGuids&&this.xhrGuids[n]&&(delete this.xhrGuids[n],this.totalCbs-=1)}),u.on("addEventListener-end",function(t,e){e instanceof v&&"load"===t[0]&&u.emit("xhr-load-added",[t[1],t[2]],e)}),u.on("removeEventListener-end",function(t,e){e instanceof v&&"load"===t[0]&&u.emit("xhr-load-removed",[t[1],t[2]],e)}),u.on("fn-start",function(t,e,n){e instanceof v&&("onload"===n&&(this.onload=!0),("load"===(t[0]&&t[0].type)||this.onload)&&(this.xhrCbStart=a.now()))}),u.on("fn-end",function(t,e){this.xhrCbStart&&u.emit("xhr-cb-time",[a.now()-this.xhrCbStart,this.onload,e],e)}),u.on("fetch-before-start",function(t){function e(t,e){var n=!1;return e.newrelicHeader&&(t.set("newrelic",e.newrelicHeader),n=!0),e.traceContextParentHeader&&(t.set("traceparent",e.traceContextParentHeader),e.traceContextStateHeader&&t.set("tracestate",e.traceContextStateHeader),n=!0),n}var n,r=t[1]||{};"string"==typeof t[0]?n=t[0]:t[0]&&t[0].url?n=t[0].url:window.URL&&t[0]&&t[0]instanceof URL&&(n=t[0].href),n&&(this.parsedOrigin=s(n),this.sameOrigin=this.parsedOrigin.sameOrigin);var i=f(this.parsedOrigin);if(i&&(i.newrelicHeader||i.traceContextParentHeader))if("string"==typeof t[0]||window.URL&&t[0]&&t[0]instanceof URL){var o={};for(var a in r)o[a]=r[a];o.headers=new Headers(r.headers||{}),e(o.headers,i)&&(this.dt=i),t.length>1?t[1]=o:t.push(o)}else t[0]&&t[0].headers&&e(t[0].headers,i)&&(this.dt=i)})}},{}],13:[function(t,e,n){var r={};e.exports=function(t){if(t in r)return r[t];var e=document.createElement("a"),n=window.location,i={};e.href=t,i.port=e.port;var o=e.href.split("://");!i.port&&o[1]&&(i.port=o[1].split("/")[0].split("@").pop().split(":")[1]),i.port&&"0"!==i.port||(i.port="https"===o[0]?"443":"80"),i.hostname=e.hostname||n.hostname,i.pathname=e.pathname,i.protocol=o[0],"/"!==i.pathname.charAt(0)&&(i.pathname="/"+i.pathname);var a=!e.protocol||":"===e.protocol||e.protocol===n.protocol,c=e.hostname===document.domain&&e.port===n.port;return i.sameOrigin=a&&(!e.hostname||c),"/"===i.pathname&&(r[t]=i),i}},{}],14:[function(t,e,n){function r(t,e){var n=t.responseType;return"json"===n&&null!==e?e:"arraybuffer"===n||"blob"===n||"json"===n?i(t.response):"text"===n||""===n||void 0===n?i(t.responseText):void 0}var i=t(16);e.exports=r},{}],15:[function(t,e,n){function r(){}function i(t,e,n){return function(){return o(t,[f.now()].concat(c(arguments)),e?null:this,n),e?void 0:this}}var o=t("handle"),a=t(24),c=t(25),s=t("ee").get("tracer"),f=t("loader"),u=NREUM;"undefined"==typeof window.newrelic&&(newrelic=u);var d=["setPageViewName","setCustomAttribute","setErrorHandler","finished","addToTrace","inlineHit","addRelease"],l="api-",p=l+"ixn-";a(d,function(t,e){u[e]=i(l+e,!0,"api")}),u.addPageAction=i(l+"addPageAction",!0),u.setCurrentRouteName=i(l+"routeName",!0),e.exports=newrelic,u.interaction=function(){return(new r).get()};var h=r.prototype={createTracer:function(t,e){var n={},r=this,i="function"==typeof e;return o(p+"tracer",[f.now(),t,n],r),function(){if(s.emit((i?"":"no-")+"fn-start",[f.now(),r,i],n),i)try{return e.apply(this,arguments)}catch(t){throw s.emit("fn-err",[arguments,this,t],n),t}finally{s.emit("fn-end",[f.now()],n)}}}};a("actionText,setName,setAttribute,save,ignore,onEnd,getContext,end,get".split(","),function(t,e){h[e]=i(p+e)}),newrelic.noticeError=function(t,e){"string"==typeof t&&(t=new Error(t)),o("err",[t,f.now(),!1,e])}},{}],16:[function(t,e,n){e.exports=function(t){if("string"==typeof t&&t.length)return t.length;if("object"==typeof t){if("undefined"!=typeof ArrayBuffer&&t instanceof ArrayBuffer&&t.byteLength)return t.byteLength;if("undefined"!=typeof Blob&&t instanceof Blob&&t.size)return t.size;if(!("undefined"!=typeof FormData&&t instanceof FormData))try{return JSON.stringify(t).length}catch(e){return}}}},{}],17:[function(t,e,n){var r=0,i=navigator.userAgent.match(/Firefox[\/\s](\d+\.\d+)/);i&&(r=+i[1]),e.exports=r},{}],18:[function(t,e,n){function r(){return c.exists&&performance.now?Math.round(performance.now()):(o=Math.max((new Date).getTime(),o))-a}function i(){return o}var o=(new Date).getTime(),a=o,c=t(26);e.exports=r,e.exports.offset=a,e.exports.getLastTimestamp=i},{}],19:[function(t,e,n){function r(t){return!(!t||!t.protocol||"file:"===t.protocol)}e.exports=r},{}],20:[function(t,e,n){function r(t,e){var n=t.getEntries();n.forEach(function(t){"first-paint"===t.name?d("timing",["fp",Math.floor(t.startTime)]):"first-contentful-paint"===t.name&&d("timing",["fcp",Math.floor(t.startTime)])})}function i(t,e){var n=t.getEntries();n.length>0&&d("lcp",[n[n.length-1]])}function o(t){t.getEntries().forEach(function(t){t.hadRecentInput||d("cls",[t])})}function a(t){if(t instanceof h&&!w){var e=Math.round(t.timeStamp),n={type:t.type};e<=l.now()?n.fid=l.now()-e:e>l.offset&&e<=Date.now()?(e-=l.offset,n.fid=l.now()-e):e=l.now(),w=!0,d("timing",["fi",e,n])}}function c(t){d("pageHide",[l.now(),t])}if(!("init"in NREUM&&"page_view_timing"in NREUM.init&&"enabled"in NREUM.init.page_view_timing&&NREUM.init.page_view_timing.enabled===!1)){var s,f,u,d=t("handle"),l=t("loader"),p=t(23),h=NREUM.o.EV;if("PerformanceObserver"in window&&"function"==typeof window.PerformanceObserver){s=new PerformanceObserver(r);try{s.observe({entryTypes:["paint"]})}catch(m){}f=new PerformanceObserver(i);try{f.observe({entryTypes:["largest-contentful-paint"]})}catch(m){}u=new PerformanceObserver(o);try{u.observe({type:"layout-shift",buffered:!0})}catch(m){}}if("addEventListener"in document){var w=!1,v=["click","keydown","mousedown","pointerdown","touchstart"];v.forEach(function(t){document.addEventListener(t,a,!1)})}p(c)}},{}],21:[function(t,e,n){function r(){function t(){return e?15&e[n++]:16*Math.random()|0}var e=null,n=0,r=window.crypto||window.msCrypto;r&&r.getRandomValues&&(e=r.getRandomValues(new Uint8Array(31)));for(var i,o="xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx",a="",c=0;c<o.length;c++)i=o[c],"x"===i?a+=t().toString(16):"y"===i?(i=3&t()|8,a+=i.toString(16)):a+=i;return a}function i(){return a(16)}function o(){return a(32)}function a(t){function e(){return n?15&n[r++]:16*Math.random()|0}var n=null,r=0,i=window.crypto||window.msCrypto;i&&i.getRandomValues&&Uint8Array&&(n=i.getRandomValues(new Uint8Array(31)));for(var o=[],a=0;a<t;a++)o.push(e().toString(16));return o.join("")}e.exports={generateUuid:r,generateSpanId:i,generateTraceId:o}},{}],22:[function(t,e,n){function r(t,e){if(!i)return!1;if(t!==i)return!1;if(!e)return!0;if(!o)return!1;for(var n=o.split("."),r=e.split("."),a=0;a<r.length;a++)if(r[a]!==n[a])return!1;return!0}var i=null,o=null,a=/Version\/(\S+)\s+Safari/;if(navigator.userAgent){var c=navigator.userAgent,s=c.match(a);s&&c.indexOf("Chrome")===-1&&c.indexOf("Chromium")===-1&&(i="Safari",o=s[1])}e.exports={agent:i,version:o,match:r}},{}],23:[function(t,e,n){function r(t){function e(){t(a&&document[a]?document[a]:document[i]?"hidden":"visible")}"addEventListener"in document&&o&&document.addEventListener(o,e,!1)}e.exports=r;var i,o,a;"undefined"!=typeof document.hidden?(i="hidden",o="visibilitychange",a="visibilityState"):"undefined"!=typeof document.msHidden?(i="msHidden",o="msvisibilitychange"):"undefined"!=typeof document.webkitHidden&&(i="webkitHidden",o="webkitvisibilitychange",a="webkitVisibilityState")},{}],24:[function(t,e,n){function r(t,e){var n=[],r="",o=0;for(r in t)i.call(t,r)&&(n[o]=e(r,t[r]),o+=1);return n}var i=Object.prototype.hasOwnProperty;e.exports=r},{}],25:[function(t,e,n){function r(t,e,n){e||(e=0),"undefined"==typeof n&&(n=t?t.length:0);for(var r=-1,i=n-e||0,o=Array(i<0?0:i);++r<i;)o[r]=t[e+r];return o}e.exports=r},{}],26:[function(t,e,n){e.exports={exists:"undefined"!=typeof window.performance&&window.performance.timing&&"undefined"!=typeof window.performance.timing.navigationStart}},{}],ee:[function(t,e,n){function r(){}function i(t){function e(t){return t&&t instanceof r?t:t?f(t,s,a):a()}function n(n,r,i,o,a){if(a!==!1&&(a=!0),!p.aborted||o){t&&a&&t(n,r,i);for(var c=e(i),s=m(n),f=s.length,u=0;u<f;u++)s[u].apply(c,r);var l=d[y[n]];return l&&l.push([x,n,r,c]),c}}function o(t,e){g[t]=m(t).concat(e)}function h(t,e){var n=g[t];if(n)for(var r=0;r<n.length;r++)n[r]===e&&n.splice(r,1)}function m(t){return g[t]||[]}function w(t){return l[t]=l[t]||i(n)}function v(t,e){u(t,function(t,n){e=e||"feature",y[n]=e,e in d||(d[e]=[])})}var g={},y={},x={on:o,addEventListener:o,removeEventListener:h,emit:n,get:w,listeners:m,context:e,buffer:v,abort:c,aborted:!1};return x}function o(t){return f(t,s,a)}function a(){return new r}function c(){(d.api||d.feature)&&(p.aborted=!0,d=p.backlog={})}var s="nr@context",f=t("gos"),u=t(24),d={},l={},p=e.exports=i();e.exports.getOrSetContext=o,p.backlog=d},{}],gos:[function(t,e,n){function r(t,e,n){if(i.call(t,e))return t[e];var r=n();if(Object.defineProperty&&Object.keys)try{return Object.defineProperty(t,e,{value:r,writable:!0,enumerable:!1}),r}catch(o){}return t[e]=r,r}var i=Object.prototype.hasOwnProperty;e.exports=r},{}],handle:[function(t,e,n){function r(t,e,n,r){i.buffer([t],r),i.emit(t,e,n)}var i=t("ee").get("handle");e.exports=r,r.ee=i},{}],id:[function(t,e,n){function r(t){var e=typeof t;return!t||"object"!==e&&"function"!==e?-1:t===window?0:a(t,o,function(){return i++})}var i=1,o="nr@id",a=t("gos");e.exports=r},{}],loader:[function(t,e,n){function r(){if(!E++){var t=b.info=NREUM.info,e=p.getElementsByTagName("script")[0];if(setTimeout(f.abort,3e4),!(t&&t.licenseKey&&t.applicationID&&e))return f.abort();s(y,function(e,n){t[e]||(t[e]=n)});var n=a();c("mark",["onload",n+b.offset],null,"api"),c("timing",["load",n]);var r=p.createElement("script");r.src="https://"+t.agent,e.parentNode.insertBefore(r,e)}}function i(){"complete"===p.readyState&&o()}function o(){c("mark",["domContent",a()+b.offset],null,"api")}var a=t(18),c=t("handle"),s=t(24),f=t("ee"),u=t(22),d=t(19),l=window,p=l.document,h="addEventListener",m="attachEvent",w=l.XMLHttpRequest,v=w&&w.prototype;if(d(l.location)){NREUM.o={ST:setTimeout,SI:l.setImmediate,CT:clearTimeout,XHR:w,REQ:l.Request,EV:l.Event,PR:l.Promise,MO:l.MutationObserver};var g=""+location,y={beacon:"bam.nr-data.net",errorBeacon:"bam.nr-data.net",agent:"js-agent.newrelic.com/nr-1208.min.js"},x=w&&v&&v[h]&&!/CriOS/.test(navigator.userAgent),b=e.exports={offset:a.getLastTimestamp(),now:a,origin:g,features:{},xhrWrappable:x,userAgent:u};t(15),t(20),p[h]?(p[h]("DOMContentLoaded",o,!1),l[h]("load",r,!1)):(p[m]("onreadystatechange",i),l[m]("onload",r)),c("mark",["firstbyte",a.getLastTimestamp()],null,"api");var E=0}},{}],"wrap-function":[function(t,e,n){function r(t,e){function n(e,n,r,s,f){function nrWrapper(){var o,a,u,l;try{a=this,o=d(arguments),u="function"==typeof r?r(o,a):r||{}}catch(p){i([p,"",[o,a,s],u],t)}c(n+"start",[o,a,s],u,f);try{return l=e.apply(a,o)}catch(h){throw c(n+"err",[o,a,h],u,f),h}finally{c(n+"end",[o,a,l],u,f)}}return a(e)?e:(n||(n=""),nrWrapper[l]=e,o(e,nrWrapper,t),nrWrapper)}function r(t,e,r,i,o){r||(r="");var c,s,f,u="-"===r.charAt(0);for(f=0;f<e.length;f++)s=e[f],c=t[s],a(c)||(t[s]=n(c,u?s+r:r,i,s,o))}function c(n,r,o,a){if(!h||e){var c=h;h=!0;try{t.emit(n,r,o,e,a)}catch(s){i([s,n,r,o],t)}h=c}}return t||(t=u),n.inPlace=r,n.flag=l,n}function i(t,e){e||(e=u);try{e.emit("internal-error",t)}catch(n){}}function o(t,e,n){if(Object.defineProperty&&Object.keys)try{var r=Object.keys(t);return r.forEach(function(n){Object.defineProperty(e,n,{get:function(){return t[n]},set:function(e){return t[n]=e,e}})}),e}catch(o){i([o],n)}for(var a in t)p.call(t,a)&&(e[a]=t[a]);return e}function a(t){return!(t&&t instanceof Function&&t.apply&&!t[l])}function c(t,e){var n=e(t);return n[l]=t,o(t,n,u),n}function s(t,e,n){var r=t[e];t[e]=c(r,n)}function f(){for(var t=arguments.length,e=new Array(t),n=0;n<t;++n)e[n]=arguments[n];return e}var u=t("ee"),d=t(25),l="nr@original",p=Object.prototype.hasOwnProperty,h=!1;e.exports=r,e.exports.wrapFunction=c,e.exports.wrapInPlace=s,e.exports.argsToArray=f},{}]},{},["loader",2,12,4,3]);
        ;NREUM.loader_config={accountID:"3140311",trustKey:"3140311",agentID:"478755666",licenseKey:"NRJS-e8934a5c19d7b343604",applicationID:"478755666"}
        ;NREUM.info={beacon:"bam.nr-data.net",errorBeacon:"bam.nr-data.net",licenseKey:"NRJS-e8934a5c19d7b343604",applicationID:"478755666",sa:1}
    </script>

    <link rel="icon" href="{{ config('app.cdn') }}/fr/imagens/favicon.png" type="image/x-icon" />

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/2021/stylesheet.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />

    <!-- JS -->
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>
    <script src="{{ config('app.cdn') }}/fr/includes/js/sweetalert.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_v5.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_2021_v1.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/style_avaliacao_online_v2.css">

    <title></title>

    <style type="text/css">
        .letraQuestaoAberta {
            border: 1px solid;
            border-radius: 100%;
            width: 20px;
            height: 20px;
            text-align: center;
            margin: 0 5px 0 0;
            float: left;
        }
        .corretaAlternativa{
            background-color: #a8f986;
        }
    </style>
    <script>
        var ordemQuestao = new Array();
    </script>
</head>
<body class="interna">
@php
    $alternativas = [
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D',
        '5' => 'E',
        '6' => 'F',
        '7' => 'G',
    ];
@endphp
<header class="section" style="height: 75px">
    <div class="container-fluid">
        <div class="flex-container">
            <div class="flexgrow-0">
                <!-- Logo -->
                @php
                    $instituicao = session('instituicao');
                @endphp
                <div id="logo" style="margin-top: 7px">
                    <a href="#">
                        <img src="{{ config('app.cdn') }}/fr/imagens/2021/logo.png" />
                    </a>
                @if($instituicao['tipo'] == 2) <!-- publica -->
                    <a href="#" class="parceiros not-mobile">
                        <img style="height: 60px;" src="{{config('app.cdn')}}/fr/imagens/2021/logo_sefe.png" />
                    </a>
                @elseif($instituicao['tipo'] == 1)<!-- privada -->
                    <a href="#" class="parceiros not-mobile">
                        <img src="{{ config('app.cdn') }}/fr/imagens/2021/logo_solucoes_educacionais.png" />
                    </a>
                    @endif
                </div>

                <!-- Fim logo -->
            </div>
            <div class="flexgrow-1 d-flex justify-content-center">
                <!-- Busca -->
                <!-- Fim Busca -->
            </div>
            <div class="flexgrow-0">
                <!-- User -->
                <div id="user">

                    <div class="user-area dropdown">
                        <button class="user-area dropdown-toggle-split" style="margin-top: 7px">
                            <span class="avatar">
                                <img src="{{auth()->user()->avatar}}" />
                            </span>
                            <span class="username text-truncate">
                                {{ ucwords(Auth::user()->name) }}
                            </span>
                        </button>

                    </div>
                </div>
                <!-- Fim User -->
            </div>
        </div>
    </div>
</header>
<!-- Codigo para mostrar o resultado --->
<script type="text/javascript" src="{{url('fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

    <section class="section section-interna" style="padding-top: 30px">
        <div class="container">
            <div class="row title-page">
                <div class="col-8">
                    <h2 >
                        Avaliação EAD - {{$avaliacao->titulo}}
                        <br><span class="badge badge-secondary" style="font-size: 14px">{{$avaliacao->usuario->nome}}</span>
                    </h2>
                </div>
                <div class="col-4">
                    <h2 class="text-right">
                        <i class="far fa-clock"></i> <span id="hora">00</span>:<span id="minuto">00</span>:<span id="segundo">00</span>
                        <br>
                        <small style="font-size: 13px">O tempo máximo para<br>realização da avalição é de<br><b>{{\Carbon\CarbonInterval::minutes($avaliacao->tempo_maximo)->cascade()->forHumans()}}</b></small>
                    </h2>
                </div>
            </div>


        </div>
    </section>
@php
if($avaliacao->ordenacao == 1)
    $questoes = $questoes->shuffle();
@endphp
<section class="section">

    <div class="container">

        <div class=" w-100">
            <div class="fs-13 mb-2 text-right font-weight-bold">Meu Progresso</div>
            <div class="progress" style="height: 20px;">
                <div id="barraProgresso" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
            <div class="fs-13 mt-2 text-center"><strong id="concluidas">0</strong> de <strong>{{count($questoes)}}</strong> questões concluídas</div>
        </div>
        <h2 class="text-left mt-3" >
            @php $i=1; @endphp
            <form id="formFinalizar" method="post" action="{{url('ead/avaliacao/finalizar')}}">
                @csrf
                <input type="hidden" name="avaliacao_id" value="{{$avaliacao->id}}">
                <input type="hidden" name="trilha_id" value="{{$trilhaId}}">
                <input type="hidden" id="dataHora" name="data_hora"  value="">
                <input type="hidden" id="tempo" name="tempo"  value="">
                <input type="hidden" id="questaoAtiva" name="questao_ativa"  value="">
            @foreach($questoes as $q)
                <script>
                    ordemQuestao.push({{$q->id}});
                </script>
                <input type="hidden" id="qInativo{{$q->id}}" name="qInativo{{$q->id}}" value="0">
                <input type="hidden" id="qAtivo{{$q->id}}" name="qAtivo{{$q->id}}" value="0">
                <input type="hidden" id="qtdEscolha{{$q->id}}" name="qtdEscolha{{$q->id}}" value="0">
                <a id="badge{{$q->id}}" href="javascript:void(0)"  onclick="proximo({{$i-1}})" class="badge badge-secondary" style="font-size: 14px; width: 70px; "> {{$i}} </a>
                @php $i++; @endphp
            @endforeach
            </form>
        </h2>
        <div class="avaliacao-online" style="margin-top: 0px">
            @php $i=1; @endphp
            @foreach($questoes as $p)

                <div class="divPergunta" pergunta="{{$p->id}}" id="pergunta{{$i}}" @if($i!=1) style="display: none" @endif >
                    <!-- PEGUNTA --->
                    <div class="avaliacao-footer mt-4" style="padding-top: 20px; padding-bottom: 20px" >
                        <div class="row">
                            <div class="col-4 text-left" >
                                <button class="btn btn-outline-secondary" onclick="anterior({{$i}})">Anterior</button>
                            </div>
                            <div class="col-4 text-center">
                                    <button  class="btn btn-warning" onclick="modalFinalizar()">Finalizar</button>
                            </div>
                            <div class="col-4 text-right">
                                <button id="btnProximo" class="btn btn-outline-secondary" onclick="proximo({{$i}})">Próximo</button>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-12 text-center">
                            <span style="color: #757575; font-size: 24px">Questão {{$i}}</span>
                        </div>
                    </div>
                    @if($p->tipo == 'o')
                        @include('fr/avaliacao/exibir/questao_objetiva')
                    @else
                        @include('fr/avaliacao/exibir/questao_aberta')
                    @endif
                    <div class="pt-5" >
                        <div class="row">
                            <div class="col-4 text-left" >
                                <button class="btn btn-outline-secondary" onclick="anterior({{$i}})">Anterior</button>
                            </div>
                            <div class="col-4 text-center divBtnVerificar">
                                <button  class="btn btn-warning" onclick="modalFinalizar()">Finalizar</button>
                             </div>
                            <div class="col-4 text-right">
                                <button id="btnProximo" class="btn btn-outline-secondary" onclick="proximo({{$i}})">Próximo</button>
                            </div>
                        </div>
                    </div>
                </div>
                @php $i++; @endphp
            @endforeach
        </div>
    </div>
</section>
<footer class="section " style="margin-top: 15px">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md"></div>
            <div class="col-md">
            </div>
            <div class="col-auto">
                <div class="col-auto">

                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Finalizar avaliacao -->
<div class="modal fade" id="formPublicar" tabindex="-1" role="dialog" aria-labelledby="formPublicar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Finalizar avaliação</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você está prestes a finalizar a avaliação:<br>
                            <p><b id="tituloAvaliacao"></b></p>
                            <br><br>

                            <p ><b class="text-danger">Texto para a finalização da avaliação.</b> </p>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="button" onclick="finalizar()"  class="btn btn-success">Sim, finalizar</button>
            </div>
        </div>
    </div>
</div>
<!-- Finalizar avaliacao -->

</body>
<script>

    var tempoMaximoAvaliacao = {{$avaliacao->tempo_maximo}};
    function dataHoraAtual(){
        data = new Date;
        return data.getFullYear()+'-'+(data.getMonth()+1)+'-'+data.getDate()+' '+data.getHours()+':'+data.getMinutes()+':'+data.getSeconds();
    }

    $(document).ready(function(){
        $(document).bind('paste', function(e) {
            e.preventDefault();
        });
        $(document).bind('copy', function(e) {
            e.preventDefault();
        });
        $(document).bind("contextmenu",function(e){
            return false;
        });

        $('.ht-skip').children().css('height','50px'); /// div do acessivel em libras
        $('[data-toggle="tooltip"]').tooltip();

        var obj = new Object();
        obj.questao_id = questaoAtiva;
        obj.iniciou_ativo = dataHoraAtual();
        obj.ordem_questao = ordemQuestao;
        enviaLogGeral(obj,token)
        cronometro();
    })

    var s = 1;
    var m = 0;
    var h = 0;

    var perguntasTotal = {{count($questoes)}};
    var perguntasConcluidas = 0;
    var vetMarcadas = Array();

    var iniciouAtivo = Date.now();
    var iniciouInativo = Date.now();
    var iniciouEvento = Date.now();
    var tempoAtivo = 0;
    var tempoInativo = 0;
    var questaoAtiva =  {{$questoes[0]->id}};
    var token = '{{csrf_token()}}'

    function proximo(i)
    {
        if(i<perguntasTotal) {
            $('.divPergunta').hide();
            $('#pergunta' + (i + 1)).show();
            questaoAtiva = $('#pergunta' + (i + 1)).attr('pergunta');
            var obj = new Object();
            obj.questao_id = questaoAtiva;
            obj.iniciou_ativo = dataHoraAtual();
            obj.tempo_ativo = Math.floor((Date.now()-iniciouEvento)/1000);
            iniciouEvento = Date.now();
            enviaLogGeral(obj,token);
            $('#qAtivo'+questaoAtiva).val(parseInt($('#qAtivo'+questaoAtiva).val())+obj.tempo_ativo);
        }
        if(i+1 == perguntasTotal){
            $('#btnProximo').hide();
        }
    }

    function anterior(i)
    {
        if(i>1) {
            $('.divPergunta').hide();
            $('#pergunta' + (i -1)).show();
            questaoAtiva = $('#pergunta' + (i - 1)).attr('pergunta');
            var obj = new Object();
            obj.questao_id = questaoAtiva;
            obj.iniciou_ativo = dataHoraAtual();
            obj.tempo_ativo = Math.floor((Date.now()-iniciouEvento)/1000);
            iniciouEvento = Date.now();
            enviaLogGeral(obj,token);
            $('#qAtivo'+questaoAtiva).val(parseInt($('#qAtivo'+questaoAtiva).val())+obj.tempo_ativo);
        }
        $('#btnProximo').show();
    }

    function marcaAlternativa(perguntaId, elemento, alternativa)
    {
        if(typeof vetMarcadas.find(element => element == perguntaId) == "undefined"){
            perguntasConcluidas++;
            vetMarcadas.push(perguntaId);
        }

        $('#badge'+perguntaId).removeClass('badge-secondary');
        $('#badge'+perguntaId).addClass('badge-info');

        $('.questaoObjetiva'+perguntaId).removeClass('corretaAlternativa');
        $(elemento).addClass('corretaAlternativa');

        var obj = new Object();
        obj.questao_id = questaoAtiva;
        obj.resposta = alternativa;
        obj.iniciou_ativo = dataHoraAtual();
        obj.tempo_ativo = Math.floor((Date.now()-iniciouEvento)/1000);
        iniciouEvento = Date.now();
        enviaLogGeral(obj,token);
        $('#qAtivo'+questaoAtiva).val(parseInt($('#qAtivo'+questaoAtiva).val())+obj.tempo_ativo);
        $('#qtdEscolha'+questaoAtiva).val(parseInt($('#qtdEscolha'+questaoAtiva).val())+1);
        var obj = new Object();
        obj.questao_id = questaoAtiva;
        obj.resposta = alternativa;
        obj.tempo_ativo = $('#qAtivo'+questaoAtiva).val();
        obj.tempo_inativo = $('#qInativo'+questaoAtiva).val();
        enviaLogAtividade(obj,token);
        mudaBarraProgresso()
    }

    function marcaAberta(perguntaId)
    {
        var texto = $('#questaoAberta'+perguntaId).val();
        if( texto!= '') {
            if (typeof vetMarcadas.find(element => element == perguntaId) == "undefined") {
                perguntasConcluidas++;
                vetMarcadas.push(perguntaId);
            }
            $('#badge' + perguntaId).removeClass('badge-secondary');
            $('#badge' + perguntaId).addClass('badge-info');

        }
        else{
            if (typeof vetMarcadas.find(element => element == perguntaId) != "undefined") {
                perguntasConcluidas--;
                var index = vetMarcadas.indexOf(perguntaId);
                if (index > -1) {
                    vetMarcadas.splice(index, 1);
                }
            }
            $('#badge' + perguntaId).addClass('badge-secondary');
            $('#badge' + perguntaId).removeClass('badge-info');
        }
        var obj = new Object();
        obj.questao_id = questaoAtiva;
        obj.resposta = texto;
        obj.iniciou_ativo = dataHoraAtual();
        obj.tempo_ativo = Math.floor((Date.now()-iniciouEvento)/1000);
        iniciouEvento = Date.now();
        enviaLogGeral(obj,token);
        $('#qAtivo'+questaoAtiva).val(parseInt($('#qAtivo'+questaoAtiva).val())+obj.tempo_ativo);
        var obj = new Object();
        obj.questao_id = questaoAtiva;
        obj.resposta = texto;
        obj.tempo_ativo = $('#qAtivo'+questaoAtiva).val();
        obj.tempo_inativo = $('#qInativo'+questaoAtiva).val();
        enviaLogAtividade(obj,token);
        mudaBarraProgresso()
    }

    function mudaBarraProgresso()
    {
        porcentagem = parseInt((100*perguntasConcluidas)/perguntasTotal);
        if(porcentagem>100)
            porcentagem = 100;
        $('#barraProgresso').css('width',porcentagem+'%');
        $('#barraProgresso').attr('aria-valuenow',porcentagem);
        $('#barraProgresso').html(porcentagem+'%');
        $('#concluidas').html(perguntasConcluidas);

    }

    function cronometro() {

        intervalo = window.setInterval(function() {
            if (s == 60) { m++; s = 0; }
            if (m == 60) { h++; s = 0; m = 0; }
            if (h < 10) document.getElementById("hora").innerHTML = "0" + h ; else document.getElementById("hora").innerHTML = h ;
            if (s < 10) document.getElementById("segundo").innerHTML = "0" + s ; else document.getElementById("segundo").innerHTML = s ;
            if (m < 10) document.getElementById("minuto").innerHTML = "0" + m ; else document.getElementById("minuto").innerHTML = m ;
            if(m>=tempoMaximoAvaliacao){
                finalizar();
            }
            s++;
        },1000);
    }

    function enviaLogGeral(obj,token){
        $.ajax({
            url: '{{url('avaliacao_ead/avaliacao/logGeral')}}',
            type: 'post',
            dataType: 'json',
            data: {
                obj: obj,
                avaliacao_id: {{$avaliacao->id}},
                _token: token
            },
            error: function() {
                swal("Informe seu professor.", "Sua avaliação não está sendo gravada, suas notas não serão computadas.", "error");
            }
        });
    }

    function enviaLogAtividade(obj,token){
        $.ajax({
            url: '{{url('avaliacao_ead/avaliacao/logAtividade')}}',
            type: 'post',
            dataType: 'json',
            data: {
                obj: obj,
                avaliacao_id: {{$avaliacao->id}},
                _token: token
            },
            error: function() {
                swal("Informe seu professor.", "Sua avaliação não está sendo gravada, suas notas não serão computadas.", "error");
            }
        });
    }
    function modalFinalizar(){
        $('#formPublicar').modal('show');
    }

    function finalizar(){
        $('#dataHora').val(dataHoraAtual());
        $('#tempo').val(Math.floor((Date.now()-iniciouEvento)/1000));
        $('#questaoAtiva').val(questaoAtiva);
        $('#formFinalizar').submit();
    }
</script>
</html>
