var now=new Date();var millis=now.getTime();var ord=""+millis;var ORD="";if(typeof(netDrive)=="undefined"){var netDrive={}}if(typeof netDrive.Initialize=="undefined"){netDrive.Initialize={COMonPage:new Array(),CATonPage:new Array(),ROSonPage:new Array(),setRand:function(){var b=new Date();var c=b.getTime();var a=""+c;return a},IsNumeric:function(b){var d="0123456789";var c=true;var a;for(i=0;i<b.length&&c==true;i++){a=b.charAt(i);if(d.indexOf(a)==-1){c=false}}return c},collectCookie:function(){var c=document.cookie.split(";");var b="";for(var a=0;a<c.length;a++){var e=c[a].split("=");var d=e[0].replace(/^\s+|\s+$/g,"");if(/^ndx/.test(d)){b+="&"+d;b+="="+unescape(e[1])}}return b},getCookie:function(c){var d=document.cookie.indexOf(c+"=");var a=d+c.length+1;if((!d)&&(c!=document.cookie.substring(0,c.length))){return null}if(d==-1){return null}var b=document.cookie.indexOf(";",a);if(b==-1){b=document.cookie.length}return unescape(document.cookie.substring(a,b))},setCookie:function(c,e,a,h,d,g){var b=new Date();b.setTime(b.getTime());if(a){if(this.IsNumeric(a)){a=a*1000*60*60*24;var f=new Date(b.getTime()+(a));f=f.toGMTString()}else{f=a}}document.cookie=c+"="+escape(e)+((a)?";expires="+f:"")+((h)?";path="+h:"")+((d)?";domain="+d:"")+((g)?";secure":"")},deleteCookie:function(a,c,b){if(this.getCookie(a)){document.cookie=a+"="+((c)?";path="+c:"")+((b)?";domain="+b:"")+";expires=Thu, 01-Jan-1970 00:00:01 GMT"}},getFlashVersion:function(){try{try{var a=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");try{a.AllowScriptAccess="always"}catch(b){return"6,0,0"}}catch(b){}return new ActiveXObject("ShockwaveFlash.ShockwaveFlash").GetVariable("$version").replace(/\D+/g,",").match(/^,?(.+),?$/)[1]}catch(b){try{if(navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin){return(navigator.plugins["Shockwave Flash 2.0"]||navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g,",").match(/^,?(.+),?$/)[1]}}catch(b){}}return"0,0,0"}}}var ndRES=screen.width+"x"+screen.height;var ndDoc=encodeURIComponent(document.title);if(typeof ndFla=="undefined"){var ndFla=netDrive.Initialize.getFlashVersion()}netDrive.Initialize.init=function(l,h){var k=netDrive.Initialize.collectCookie();if(k!==""){k="&"+k}netDrive.Initialize.domain=h;if(typeof(ndINIT)==="undefined"){ndINIT=0}var l=l.split(";");var p=l[0];var q=l[1];var n=l[2];var d=l[3];var o=l[4];var e=l[5];if(l.length>6){var f=l[6]}if(l.length>7){var g=l[7]}var a="ndx_"+netDrive.Initialize.setRand();if(netDrive.Initialize.getCookie("ndxUsrId")==null){netDrive.Initialize.setCookie("ndxUsrId",a,3000,"",netDrive.Initialize.domain)}if(netDrive.Initialize.getCookie("URLToken")!==null){netDrive.Initialize.URLToken="&"+netDrive.Initialize.getCookie("URLToken")}else{netDrive.Initialize.URLToken=""}var c=netDrive.Initialize.COMonPage.join(";");var m=netDrive.Initialize.CATonPage.join(";");var b=netDrive.Initialize.ROSonPage.join(";");var j='<script language="JavaScript" type="text/javascript" src="http://'+p+"/DFA/js_ndxtag_v409.cfm?legend="+q+"&pgn="+n+"&lev="+d+"&pos="+o+"&typ="+e+"&output="+f+"&charset="+g+"&COMonPage="+c+"&ROSonPage="+b+"&CATonPage="+m+"&ran="+ord+"&ndxUsrId="+netDrive.Initialize.getCookie("ndxUsrId")+"&loc="+encodeURIComponent(window.location.href)+"&res="+ndRES+"&ndDoc="+ndDoc+"&ref="+encodeURIComponent(document.referrer)+"&fla="+ndFla+"&ndINIT="+ndINIT+netDrive.Initialize.URLToken+k+'"><\/script>';document.write(j);ndINIT=1};if(typeof(init)==="undefined"){var init=netDrive.Initialize.init};