/*------------------------------------------
Name: AJAXRequest
Author: xujiwei
Website: http://www.xujiwei.cn
E-mail: vipxjw@163.com
Copyright (c) 2007, All Rights Reserved

AJAXRequest Deveoper Manual:
  http://www.xujiwei.cn/works/ajaxrequest/
------------------------------------------*/
function AJAXRequest() {
	var xmlPool=new Array,AJAX=this,ac=arguments.length,av=arguments;
	var xmlVersion = ["MSXML2.XMLHTTP","Microsoft.XMLHTTP"];
	var nullfun=function(){return;};
	var av=ac>0?typeof(av[0])=="object"?av[0]:{}:{};
	var encode=av.charset?av.charset.toUpperCase()=="UTF-8"?encodeURIComponent:escape:encodeURIComponent;
	this.url=av.url?av.url:"";
	this.oncomplete=av.oncomplete?av.oncomplete:nullfun;
	this.content=av.content?av.content:"";
	this.method=av.method?av.method:"POST";
	this.async=av.async?async:true;
	this.onexception=av.onexception?av.exception:nullfun;
	this.ontimeout=av.ontimeout?av.ontimeout:nullfun;
	this.timeout=av.timeout?av.timeout:3600000;
	if(!getObj()) return false;
	function getObj() {
		var i,tmpObj;
		for(i=0;i<xmlPool.length;i++) if(xmlPool[i].readyState==0||xmlPool[i].readyState==4) return xmlPool[i];
		try { tmpObj=new XMLHttpRequest; }
		catch(e) {
			for(i=0;i<xmlVersion.length;i++) {
				try { tmpObj=new ActiveXObject(xmlVersion[i]); } catch(e2) { continue; }
				break;
			}
		}
		if(!tmpObj) return false;
		else { xmlPool[xmlPool.length]=tmpObj; return xmlPool[xmlPool.length-1]; }
	}
	function $(id) { return document.getElementById(id); }
	function varobj(val) {
		if(typeof(val)=="string") {
			if(val=$(val)) return val;
			else return false;
		}
		else return val;
	}
	this.setcharset=function(cs) {
		if(cs.toUpperCase()=="UTF-8") encode=encodeURIComponent;
		else encode=escape;
	}
	this.send=function() {
		var purl,pc,pcbf,pm,pa,ct,ctf=false,xmlObj=getObj(),ac=arguments.length,av=arguments;
		if(!xmlObj) return false;
		purl=ac>0?av[0]:this.url;
		pc=ac>1?av[1]:this.content;
		pcbf=ac>2?av[2]:this.oncomplete;
		pm=ac>3?av[3].toUpperCase():this.method;
		pa=ac>4?av[4]:this.async;
		if(!pm||!purl||!pa) return false;
		var ev={url:purl, content:pc, method:pm};
		purl+=(purl.indexOf("?")>-1?"&":"?")+Math.random();
		xmlObj.open(pm,purl,pa);
		if(pm=="POST") xmlObj.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ct=setTimeout(function(){ctf=true;xmlObj.abort();},AJAX.timeout);
		xmlObj.onreadystatechange=function() {
			if(ctf) AJAX.ontimeout(ev);
			else if(xmlObj.readyState==4) {
				ev.status=xmlObj.status;
				try{ clearTimeout(ct); } catch(e) {};
				try{
					if(xmlObj.status==200) pcbf(xmlObj);
					else AJAX.onexception(ev);
				}
				catch(e) { AJAX.onexception(ev); }
			}
		}
		if(pm=="POST") xmlObj.send(pc); else xmlObj.send("");
	}
	this.get=function() {
		var purl,pcbf,ac=arguments.length,av=arguments;
		purl=ac>0?av[0]:this.url;
		pcbf=ac>1?av[1]:this.oncomplete;
		if(!purl&&!pcbf) return false;
		this.send(purl,"",pcbf,"GET",true);
	}
	this.update=function() {
		var purl,puo,pinv,pcnt,rinv,ucb,ac=arguments.length,av=arguments;
		puo=ac>0?av[0]:null;
		purl=ac>1?av[1]:this.url;
		pinv=ac>2?(isNaN(parseInt(av[2]))?1000:parseInt(av[2])):null;
		pcnt=ac>3?(isNaN(parseInt(av[3]))?null:parseInt(av[3])):null;
		if(puo=varobj(puo)) {
			ucb=function(obj) {
				var nn=puo.nodeName.toUpperCase();
				if(nn=="INPUT"||nn=="TEXTAREA") puo.value=obj.responseText;
				else try{puo.innerHTML=obj.responseText;} catch(e){};
			}
		}
		else ucb=nullfun;
		if(pinv) {
			AJAX.send(purl,"",ucb,"GET",true);
			if(pcnt&&--pcnt) {
				var cf=function(cc) {
					AJAX.send(purl,"",ucb,"GET",true);
					if(cc<1) return; else cc--;
					setTimeout(function(){cf(cc);},pinv);
				}
				setTimeout(function(){cf(--pcnt);},pinv);
			}
			else return(setInterval(function(){AJAX.send(purl,"",ucb,"GET",true);},pinv));
		}
		else this.send(purl,"",ucb,"GET",true);
	}
	this.post=function() {
		var purl,pcbf,pc,ac=arguments.length,av=arguments;
		purl=ac>0?av[0]:this.url;
		pc=ac>1?av[1]:"";
		pcbf=ac>2?av[2]:this.oncomplete;
		if(!purl&&!pcbf) return false;
		this.send(purl,pc,pcbf,"POST",true);
	}
	this.postf=function() {
		var fo,pcbf,purl,pc,pm,ac=arguments.length,av=arguments;
		if(!(fo=ac>0?av[0]:null)) return false;
		if(fo=varobj(fo)) {
			if(fo.nodeName!="FORM") return false;
		}
		else return false;
		pcbf=ac>1?av[1]:this.oncomplete;
		purl=ac>2?av[2]:(fo.action?fo.action:this.url);
		pm=ac>3?av[3]:(fo.method?fo.method.toUpperCase():"POST");
		if(!pcbf&&!purl) return false;
		pc=this.formToStr(fo);
		if(!pc) return false;
		if(pm) {
			if(pm=="POST") this.send(purl,pc,pcbf,"POST",true);
			else if(purl.indexOf("?")>0) this.send(purl+"&"+pc,"",pcbf,"GET",true);
				else this.send(purl+"?"+pc,"",pcbf,"GET",true);
		}
		else this.send(purl,pc,pcbf,"POST",true);
	}
	/* formToStr
	// from SurfChen <surfchen@gmail.com>
	// @url     http://www.surfchen.org/
	// @license http://www.gnu.org/licenses/gpl.html GPL
	// modified by xujiwei
	// @url     http://www.xujiwei.cn/
	*/
	this.formToStr=function(fc) {
		var i,qs="",and="",ev="";
		for(i=0;i<fc.length;i++) {
			e=fc[i];
			if (e.name!='') {
				if (e.type=='select-one'&&e.selectedIndex>-1) ev=e.options[e.selectedIndex].value;
				else if (e.type=='checkbox' || e.type=='radio') {
					if (e.checked==false) continue;
					ev=e.value;
				}
				else ev=e.value;
				ev=encode(ev);
				qs+=and+e.name+'='+ev;
				and="&";
			}
		}
		return qs;
	}
}