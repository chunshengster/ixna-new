String.prototype.format = function(){
	var tmpStr = this;
	var iLen = arguments.length;
	for(var i=0;i<iLen;i++){
		tmpStr = tmpStr.replace(new RegExp("\\{" + i + "\\}", "g"), arguments[i]);
	}
	return tmpStr;
}
String.prototype.trim = function(){
	return this.replace(/(^[ \s]+)|([ \s]+$)/g, "");
}
String.prototype.leftTrim = function(){
	return this.replace(/^[ \s]+/, "");
}
String.prototype.rightTrim = function(){
	return this.replace(/[ \s]+$/, "");
}
function $Extend(src, des){
 for(var i in des)src[i] = des[i];
 return src;
}
Date.prototype.isDateTime = function(){
	return this;
}
String.prototype.isDateTime = function(){
	var format = arguments[0] || "yyyy-MM-dd";
	var input = this;
	var f1 = format.split(/[^a-z]+/gi);
	var f2 = input.split(/\D+/g);
	var f3 = format.split(/[a-z]+/gi);
	var f4 = input.split(/\d+/g);
	var len = f1.length;
	var len1 = f3.length;
	if(len != f2.length) return false;
	if(len1 != f4.length)return false;
	for(var i=0;i<len1;i++)if(f3[i] != f4[i]) return false;
	var o = new Object();
	for(var i=0;i<len;i++) o[f1[i]] = f2[i];
	var d = new Date();
	o.yyyy = s(o.yyyy, o.yy, d.getFullYear(), 9999, 4);
	o.MM = s(o.MM, o.M, d.getMonth()+1, 12);
	o.dd = s(o.dd, o.d, d.getDate(), 31);
	o.hh = s(o.hh, o.h, d.getHours(), 24);
	o.mm = s(o.mm, o.m, d.getMinutes());
	o.ss = s(o.ss, o.s, d.getSeconds());
	o.ms = s(o.ks, o.ms, d.getMilliseconds(), 999);
	if(typeof(o.yyyy) == "boolean")return false;
	if(typeof(o.MM) == "boolean")return false;
	if(typeof(o.dd) == "boolean")return false;
	if(typeof(o.hh) == "boolean")return false;
	if(typeof(o.mm) == "boolean")return false;
	if(typeof(o.ss) == "boolean")return false;
	if(typeof(o.ms) == "boolean")return false;
	if(o.yyyy < 100)o.yyyy += (o.yyyy > 30?1900:2000);
	d = new Date(o.yyyy, o.MM - 1, o.dd, o.hh, o.mm, o.ss, o.ms);
	var reVal = d.getFullYear() == o.yyyy && d.getMonth() + 1 == o.MM && d.getDate() == o.dd && d.getHours() == o.hh && d.getMinutes() == o.mm && d.getSeconds() == o.ss && d.getMilliseconds() == o.ms;
	if(reVal && arguments.length == 2) return d;
	else return reVal;
	function s(s1, s2, s3, s4, s5){
		var y1 = typeof(s1) == "undefined";
		var y2 = typeof(s2) == "undefined";
		s4 = s4 || 60;
		s5 = s5 || 2;
		var reVal = s1;
		if(y1 && y2) reVal = s3;
		else if(!y1){
			if(s1.length != s5) return false;
			else if(s1 == "" || isNaN(s1)) reVal = s3;
		}
		else{
			if(s2 == "" || isNaN(s2)) reVal = s3;
			else reVal = s2;
		}
		reVal *= 1;
		if(reVal > s4)return false;
		return reVal;
	}
}
function $() {
  var elements = new Array();

  for (var i = 0; i < arguments.length; i++) {
    var element = arguments[i];
    if (typeof element == 'string'){
		  element = document.getElementById(element) || document.getElementsByName(element)[0];
	  }
    if (arguments.length == 1) 
      return element;

    elements.push(element);
  }
  return elements;
}

var Validator = {
	Version : '3.0.0',
	Author : '我佛山人',
	toString : function(){
		return ["关于Validator 3.0.0", "Version:" + this.Version, "Author:" + this.Author].join("\t\t\t\n\n");
	},
	XmlReader : function(){
		this.GetHttpRequest = function(){
			if (window.XMLHttpRequest) return new XMLHttpRequest();
			else if (window.ActiveXObject) return new ActiveXObject("MsXml2.XmlHttp") ;
		}
		this.LoadUrl = function(urlToCall, asyncFunctionPointer, refObjectName){
			var oXmlReader = this;
			var bAsync = (typeof(asyncFunctionPointer) == "function");	
			var oXmlHttp = this.GetHttpRequest();	
			oXmlHttp.open("GET", urlToCall, bAsync);
			if(Validator.Config.Default.Debug){
				oXmlHttp.setRequestHeader("pragma","no-cache");
				oXmlHttp.setRequestHeader("cache-control","no-cache");
				oXmlHttp.setRequestHeader("expires","0");
				//oXmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			}
			if (bAsync){	
				oXmlHttp.onreadystatechange = function(){
					if (oXmlHttp.readyState == 4){
						oXmlReader.DOMDocument = oXmlHttp.responseXML;
						if (oXmlHttp.status == 200) {
							if(typeof(refObjectName) == "string"){
								Validator.Config.Settings[refObjectName].DataSource = oXmlReader;
								asyncFunctionPointer(oXmlReader, refObjectName);
							}
							else asyncFunctionPointer(oXmlReader)
						}
						else alert("XML request error: " + oXmlHttp.statusText + " (" + oXmlHttp.status + ")" ) ;
					}
				}
			}			
			oXmlHttp.send(null) ;	
			if (!bAsync){
				if (oXmlHttp.status == 200) this.DOMDocument = oXmlHttp.responseXML ;
				else {alert("XML request error: " + oXmlHttp.statusText + " (" + oXmlHttp.status + ")") ;}
			}
		}
		this.SelectNodes = function(xpath){
			if (document.all) return this.DOMDocument.selectNodes(xpath) ;
			else{
				var aNodeArray = new Array();	
				var xPathResult = this.DOMDocument.evaluate(xpath, this.DOMDocument, this.DOMDocument.createNSResolver(this.DOMDocument.documentElement), XPathResult.ORDERED_NODE_ITERATOR_TYPE, null) ;
				if (xPathResult){
					var oNode = xPathResult.iterateNext() ;
					while(oNode){
						aNodeArray[aNodeArray.length] = oNode ;
						oNode = xPathResult.iterateNext();
					}
				} 
				return aNodeArray ;
			}
		}
		this.SelectSingleNode = function(xpath){
			if (document.all)return this.DOMDocument.selectSingleNode(xpath) ;
			else{
				var xPathResult = this.DOMDocument.evaluate(xpath, this.DOMDocument, this.DOMDocument.createNSResolver(this.DOMDocument.documentElement), 9, null);
				if (xPathResult && xPathResult.singleNodeValue) return xPathResult.singleNodeValue ;
				else return null ;
			}
		}
	},
	Config : {
		Default : {
			Name : null,
			DataSourceType : 1 | 2,
			DataSource : null,
			Xml : null,
			XmlPath : '/plus/Validator/Validation.xml',
			XPath : {Path : location.pathname.toLowerCase(), Name : null, toString : function(){return "//form[@Path='{0}' and @Name='{1}']/item".format(this.Path, this.Name);}},
			ValidateType : 2,
			AlertType : 16,
			ShowTipsType : 4,
			Debug : true,
			Summary : null,
			WarnColor : 'red',
			SuccessColor : 'green',
			Errors : {
				Title : '发生以下错误，提交失败，请逐条检查输入：',
				Items : [],
				Add : function(e){
					this.Items[this.Items.length] = e;
				},
				Clear : function(){this.Items.length = 0}
			}
		},
		Settings : {},
		Forms : {},
		Add : function(formID, elements){
			if(Validator.Config.Forms[formID]){
				Validator.Config.Forms[formID] = $Extend(Validator.Config.Forms[formID], elements);
			}
			else {
				Validator.Config.Forms[formID] = elements;
				Validator.Config.Forms[formID].id = formID;
			}
		}
	},
	Enumerate : {
		ValidateType : {
			Blur : 1,
			Submit : 2
		},
		DataSourceType : {
			Xml : 1,
			Attribute : 2,
			Object : 4
		},
		AlertType : {
			AlertSingle : 1,
			AlertAll : 2,
			Color : 4,
			SummarySingle : 8,
			SummaryAll : 16
		},
		ShowTipsType : {
			Title : 1,
			Follow : 2,
			Layer : 4
		}
	},
	DataType : {
		Regex : {
			Require : /.+/,
			Email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
			Phone : /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
			Mobile : /^((\(\d{2,3}\))|(\d{3}\-))?((13\d{9})|(15[389]\d{8}))$/,
			Url : /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
			IP : /^(0|[1-9]\d?|[0-1]\d{2}|2[0-4]\d|25[0-5]).(0|[1-9]\d?|[0-1]\d{2}|2[0-4]\d|25[0-5]).(0|[1-9]\d?|[0-1]\d{2}|2[0-4]\d|25[0-5]).(0|[1-9]\d?|[0-1]\d{2}|2[0-4]\d|25[0-5])$/,
			Currency : /^\d+(\.\d+)?$/,
			Number : /^\d+$/,
			Zip : /^\d{6}$/,
			QQ : /^[1-9]\d{4,8}$/,
			Integer : /^[-\+]?\d+$/,
			Double : /^[-\+]?\d+(\.\d+)?$/,
			English : /^[A-Za-z]+$/,
			Chinese :  /^[\u0391-\uFFE5]+$/,
			Username : /^[a-z]\w{3,19}$/i,
			UnSafe : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/
		},
		Element : {
			IElement : function(){
				this.Name = null;
				this.DataType = null;
				this.Message = null;
				this.Tips = null;
				this.DependOn = null;
				this.Require = true;
				this.IsValid = false;
				this.Trim = "All";
				this.TrimValue = function(value){
					switch(this.Trim.toLowerCase()){
						case "left" :
							return value.leftTrim();
						case "right" :
							return value.rightTrim();
						case "all" :
							return value.trim();
						default :
							return value;
					}
				}
				this.GetValue = function(){
					if($(this.Name)) return this.TrimValue($(this.Name).value);
				}
				this.toString = function(){return '[Validator Element]';}
				this.Validate = function(){
					if(this.DependOn != null){try{eval(this.DependOn);}catch(e){alert(e);}};
					this.IsValid = (!this.Require || this.Require == "false" || this.Require == "0") && this.GetValue() == "" || this.Process();
					return this.IsValid;
				}
				this.Throw = function(){
					var oForm = arguments[0];
					Validator.Config.Settings[oForm.id].Errors.Add(this);
					if((this.AlertType & Validator.Enumerate.AlertType.SummarySingle) == Validator.Enumerate.AlertType.SummarySingle){ 
						var container = $(this.Name + "__MessagePanel");
						if(!container){
							container = document.createElement("span");
							with(container){
								id = this.Name + "__MessagePanel";
								className = "Validator_MessagePanel_Warn";
							}
							$(this.Name).parentNode.appendChild(container);
						}
						container.innerHTML = this.Message;
						container.style.display = "";
					}
					if((this.AlertType & Validator.Enumerate.AlertType.Color) == Validator.Enumerate.AlertType.Color){ 
						$(this.Name).style.color = Validator.Config.Settings[oForm.id].WarnColor;
					}
					if((this.AlertType & Validator.Enumerate.AlertType.AlertSingle) == Validator.Enumerate.AlertType.AlertSingle && Validator.Config.Settings[oForm.id].Errors.Items.length == 1){ 
						alert(this.Message);
						this.Focus();
					}
				}
				this.ShowTips = function(){
					var container = $(this.Name + "__MessagePanel_Tips");
					if(!container){
						container = document.createElement("div");
						with(container){
							id = this.Name + "__MessagePanel_Tips";
							className = "Validator_MessagePanel_Tips";
							style.position = "absolute";
							innerHTML = this.Tips;
							$(this.Name).parentNode.appendChild(container);
						}
					}
					with(container){
						with(Validator.Util.GetPosition(this.Name)){
							style.left = X + "px";
							style.top = Math.max(Y - $(this.Name).offsetHeight + (document.all?-10:-10), 0) + "px";
						}
					}
					container.style.display = "";
				}
				this.HideMessage = function(){
					if($(this.Name + "__MessagePanel"))$(this.Name + "__MessagePanel").style.display = "none";
					if($(this.Name + "__MessagePanel_Tips"))$(this.Name + "__MessagePanel_Tips").style.display = "none";
					if((Validator.Config.Settings[$(this.Name).form.id].AlertType & Validator.Enumerate.AlertType.Color) == Validator.Enumerate.AlertType.Color)$(this.Name).style.color = Validator.Config.Settings[$(this.Name).form.id].SuccessColor;
				}
				this.Focus = function(){try{$(this.Name).focus();}catch(e){}}
				this.OnBlur = function(){
					this.Validate();
					if(!this.IsValid) this.Throw(Validator.Config.Forms[$(this.Name).form.id]);
					else this.HideMessage();
				}
				this.OnFocus = function(){
					this.ShowTips();
				}
				this.Process = function(){alert('未实现IElement.Process接口');return false;}
			},
			RegexElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.Pattern = null;
				this.Process = function(){
					this.Pattern = this.Pattern || Validator.DataType.Regex[this.DataType];
					return this.Pattern != null && this.Pattern.test(this.GetValue());
				}
			},
			DateTimeElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.Format = "yyyy-MM-dd";
				this.Process = function(){
					return this.GetValue().isDateTime(this.Format);
				}
			},
			PasswordElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.Regulars = {
					low : /^\w{1,3}$/,
					medium : /^(\d{1,5}|[a-z]{1,5}|[A-Z]{1,5})$/,
					high : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/
				}
				this.Level = "high";
				this.Process = function(){
					return !this.Regulars[this.Level.toLowerCase()].test(this.GetValue());
				}
			},
			RangeElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.Min = 0;
				this.MinElement = null;
				this.Max = 100;
				this.MaxElement = null;
				this.Value = 0;
				this.Format = "yyyy-MM-dd";
				this.ParseType = "Number";
				this.Types = {
					String : function(){this.Format = null},
					CaseInsensitiveString : function(){this.Value = this.Value.toUpperCase();this.Min = this.Mim.toUpperCase();this.Max = this.Max.toUpperCase();},
					Number : function(){this.Value = this.Value*1;this.Min = this.Min*1;this.Max = this.Max * 1;},
					DateTime : function(){this.Value = this.Value.isDateTime(this.Format, true);this.Min = this.Min.isDateTime(this.Format, true);this.Max = this.Max.isDateTime(this.Format, true);},
					IPAddress : function(){this.Value = Validator.Util.IPAddressFix(this.Value);this.Min = Validator.Util.IPAddressFix(this.Min);this.Max = Validator.Util.IPAddressFix(this.Max);}
				}
				this.Process = function(){
					this.SetValue();
					if(this.MinElement != null) this.Min = $(this.MinElement).value;
					if(this.MaxElement != null) this.Max = $(this.MaxElement).value;
					this.Types[this.ParseType].call(this, null);
					if((this.Value != '0' && this.Value == false) || (this.Min != '0' && this.Min == false) || (this.Max != '0' && this.Max == false))return false;
					else return this.Min <= this.Value && this.Value <= this.Max;
				}
				this.SetValue = function(){
					this.Value = this.GetValue();
				}
			},
			GroupElement : function(){
				Validator.DataType.Element.RangeElement.apply(this, arguments)
				this.ParseType = "Number";
				this.SetValue = function(){
					this.Value = 0;
					var elements = document.getElementsByName(this.Name);
					for(var i=elements.length-1;i>-1;i--){
						if(elements[i].checked) this.Value ++;
					}
				}
			},
			LimitElement : function(){
				Validator.DataType.Element.RangeElement.apply(this, arguments)
				this.SetValue = function(){
					this.Value = this.GetValue().length;
				}
			},
			LimitBElement : function(){
				Validator.DataType.Element.RangeElement.apply(this, arguments)
				this.SetValue = function(){
					this.Value = this.GetValue().replace(/[^\x00-\xff]/g,"**").length;
				}
			},
			RepeatElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.To = null;
				this.Process = function(){
					return this.GetValue() == $(this.To).value;
				}
			},
			FilterElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.Accept = null;
				this.ParseType = "File";
				this.Types = {
					File : function(){
						return this.Accept != null && new RegExp("^.+\\.(?=EXT)(EXT)$".replace(/EXT/g, this.Accept.split(/\s*,\s*/).join("|")), "gi").test(this.GetValue());
					},
					Badword : function(){
						return !this.Types.Keyword.call(this, null);
					},
					Keyword : function(){
						return this.Accept != null && new RegExp(this.Accept.split(/\s*,\s*/).join("|"), "gi").test(this.GetValue());
					},
					BeginWith : function(){
						return this.Accept != null && new RegExp("^(?=EXT)(EXT)".replace(/EXT/g, this.Accept.split(/\s*,\s*/).join("|")), "gi").test(this.GetValue());
					},
					EndWith : function(){
						return this.Accept != null && new RegExp(".*(?=EXT)(EXT)$".replace(/EXT/g, this.Accept.split(/\s*,\s*/).join("|")), "gi").test(this.GetValue());
					}
				}
				this.Process = function(){
					return this.Types[this.ParseType].call(this, null);
				}
			},
			CustomElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.ParseType = "Regex";
				this.Value = "";
				this.Types = {
					Regex : function(){
						var reVal = false;
						try{
							reVal = new RegExp(this.Regex, this.Options).test(this.Value);
						} catch(e) { reVal = false;alert(['正则表达式错误：', e.description].join("\n"));}
						return reVal;
					},
					Script : function(){
						var reVal = false;
						try{
							eval(this.Script);
						} catch(e){ reVal = false;alert(['自定义脚本错误：', e.description].join("\n"));}
						return reVal;
					},
					Elements : function(){
						var elements = this.Elements.split(/[^a-z\d]+/ig);
						var result = this.Elements;
						for(var i=elements.length-1;i>-1;i--){
							this.DataType = elements[i];
							var o, e;
							o = {};
							for(e in this){if(typeof(this[e]) == "string")o[e] = this[e];}
							result = result.replace(new RegExp(elements[i], "g"), Validator.ElementFactory.Build(o).Validate());
						}
						return eval(result);
					}
				}
				this.Process = function(){
					this.SetValue();
					return this.Types[this.ParseType].call(this, null);
				}
				this.SetValue = function(){
					this.Value = this.GetValue();
				}
			},
			CompareElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.To = null;
				this.ToElement = null;
				this.Value = "";
				this.Format = "yyyy-MM-dd";
				this.ParseType = "Number";
				this.Types = {
					String : function(){this.Format = null},
					CaseInsensitiveString : function(){this.Value = this.Value.toUpperCase();this.To = this.To.toUpperCase();},
					Number : function(){this.Value = this.Value*1;this.To = this.To*1},
					DateTime : function(){this.Value = this.Value.isDateTime(this.Format, true);this.To = this.To.isDateTime(this.Format, true);},
					IPAddress : function(){this.Value = Validator.Util.IPAddressFix(this.Value);this.To = Validator.Util.IPAddressFix(this.To);}
				}
				this.Operator = "Equal";
				this.Operators = {
					NotEqual : 'return this.Value != this.To',
					GreaterThan : 'this.Value > this.To',
					GreaterThanEqual : 'this.Value >= this.To',
					LessThan : 'this.Value < this.To',
					LessThanEqual : 'this.Value <= this.To',
					Equal : 'this.Value == this.To'
				}
				this.Process = function(){
					if(this.ToElement != null) this.To = $(this.ToElement).value;
					this.Value = this.GetValue();
					this.Types[this.ParseType].call(this, null);
					return eval(this.Operators[this.Operator])
				}
			},
			IdCardElement : function(){
				Validator.DataType.Element.IElement.apply(this, arguments)
				this.To = null;
				this.Process = function(){
					var number = this.GetValue().toLowerCase();
					var date, Ai;
					var verify = "10x98765432";
					var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
					var area = ['','','','','','','','','','','','北京','天津','河北','山西','内蒙古','','','','','','辽宁','吉林','黑龙江','','','','','','','','上海','江苏','浙江','安微','福建','江西','山东','','','','河南','湖北','湖南','广东','广西','海南','','','','重庆','四川','贵州','云南','西藏','','','','','','','陕西','甘肃','青海','宁夏','新疆','','','','','','台湾','','','','','','','','','','香港','澳门','','','','','','','','','国外'];
					var re = number.match(/^(\d{2})\d{4}(((\d{2})(\d{2})(\d{2})(\d{3}))|((\d{4})(\d{2})(\d{2})(\d{3}[x\d])))$/i);
					if(re == null) return false;
					if(re[1] >= area.length || area[re[1]] == "") return false;
					if(re[2].length == 12){
						Ai = number.substr(0, 17);
						date = [re[9], re[10], re[11]].join("-");
					}
					else{
						Ai = number.substr(0, 6) + "19" + number.substr(6);
						date = ["19" + re[4], re[5], re[6]].join("-");
					}
					if(!date.isDateTime("yyyy-MM-dd")) return false;
					var sum = 0;
					for(var i = 0;i<=16;i++){
						sum += Ai.charAt(i) * Wi[i];
					}
					Ai +=  verify.charAt(sum%11);
					return (number.length ==15 || number.length == 18 && number == Ai);
				}
			}
		},
		Extends : {}
	},
	ElementFactory : {
		Build : function(o){
			var element;
			var dataType = o.DataType;
			if(dataType == "Date" || dataType == "Time") dataType = "DateTime";
			dataType += "Element";
			if(typeof(Validator.DataType.Element[dataType]) == "function"){
				element = new Validator.DataType.Element[dataType]();
			}
			else if(typeof(Validator.DataType.Extends[dataType]) == "function"){
				element = new Validator.DataType.Extends[dataType]();
			}
			else {element = new Validator.DataType.Element.RegexElement();}
			return $Extend(element, o);
		}
	},
	Setup : function(){
		var e;
		if(arguments.length == 0 || typeof(arguments[0]) != "object") return ;
		for(var i=arguments.length-1;i>-1;i--){
			var o = arguments[i];
			Validator.Config.Settings[o.Name] = $Extend(Validator.Config.Default, o);
			with(Validator.Config.Settings[o.Name]){
				if(!o.XPath || !o.XPath.Name)XPath.Name = o.Name;
				if(!o.XPath || !o.XPath.Path)XPath.Path = location.pathname.toLowerCase();
				if((DataSourceType & Validator.Enumerate.DataSourceType.Xml) == Validator.Enumerate.DataSourceType.Xml){
					if(Xml == null)new Validator.XmlReader().LoadUrl(XmlPath, Validator.XmlReaderCallBack, o.Name);
					else Validator.XmlReaderCallBack(Xml, o.Name);
				}
			}
		}
	},
	Validate : function(e){
		var e = e || window.event;
		var form = e.target || e.srcElement;
		var oForm = Validator.Config.Forms[form.id];
		var e, ie;
		var reVal = true;
		Validator.Config.Settings[form.id].Errors.Clear();
		var alertType = Validator.Config.Settings[form.id].AlertType;
		for(e in oForm){
			if(e == "id")continue;
			if(!oForm[e].Validate()) {reVal = false;oForm[e].Throw(oForm);}
			else oForm[e].HideMessage();
		}
		with(Validator.Config.Settings[form.id].Errors){
			var len = Items.length;
			var alertType = Validator.Config.Settings[form.id].AlertType;
			if(!reVal && (alertType & Validator.Enumerate.AlertType.AlertAll) == Validator.Enumerate.AlertType.AlertAll){
				var errors = [];
				for(var i=0;i<len;i++){
					errors[i] = (i + 1) + "." + Items[i].Message;
				}
				alert(Title + "\t\t\t\n" + errors.join("\n"));
			}
			if(!reVal && (alertType & Validator.Enumerate.AlertType.SummaryAll) == Validator.Enumerate.AlertType.SummaryAll){
				var errors = [];
				for(var i=0;i<len;i++){
					errors[i] = "<li>" + Items[i].Message + "</li>";
				}
				if($(Validator.Config.Settings[form.id].Summary)){
					$(Validator.Config.Settings[form.id].Summary).innerHTML = "<li class='title'>" + Title + "</li>\n" + errors.join("\n");
					$(Validator.Config.Settings[form.id].Summary).style.display = "";
				}
			}
		}
		return reVal;
	},
	KeyboardHandler : {
		NumberFilter : function(e){
			if(e.keyCode <49 || e.keyCode >59) return false;
		}
	},
	MouseHandler : {
	},
	EventHandler : {
		OnBlur : function(e){
			var evt = e || window.event;
			var e = evt.target || evt.srcElement;
			var name = e.id || e.name;
			Validator.Config.Forms[e.form.id][name].OnBlur();
		},
		OnFocus : function(e){
			var evt = e || window.event;
			var e = evt.target || evt.srcElement;
			var name = e.id || e.name;
			Validator.Config.Forms[e.form.id][name].OnFocus();
		}
	},
	XmlReaderCallBack : function(xml, formID){
		var elements;
		try{
			elements = xml.SelectNodes(Validator.Config.Settings[formID].XPath);
		} catch(e){
			var forms = xml.DOMDocument.getElementsByTagName("form");
			elements = [];
			for(var i=forms.length-1;i>-1;i--) if(forms[i].getAttribute("Name") == Validator.Config.Settings[formID].XPath.Name && forms[i].getAttribute("Path") == Validator.Config.Settings[formID].XPath.Path) { elements = forms[i].getElementsByTagName("item");break;}
		}
		var len = elements.length;
		var alertType = Validator.Config.Settings[formID].AlertType;
		if(len == 0)return;
		var elementObjects = {};
		for(var i=0;i<len;i++){
			var obj = {};
			var eachAtt;
			var curElementAtts = elements[i].attributes;
			var len1 = curElementAtts.length;
			for(var j=0;j<len1;j++) obj[curElementAtts[j].name] = elements[i].getAttribute(curElementAtts[j].name);
			if(obj.DataType == "Custom" && elements[i].childNodes.length > 0) obj.Script = elements[i].childNodes[0].nodeValue;
			if(!obj.AlertType) obj.AlertType = alertType;
			elementObjects[obj.Name] = Validator.ElementFactory.Build(obj);
		}
		Validator.Config.Add(formID, elementObjects)
	},
	AttributeReader : function(formID){
		var elements = document.forms[formID].elements;
		var len = elements.length;
		var alertType = Validator.Config.Settings[formID].AlertType;
		if(len == 0)return;
		var elementObjects = {};
		var customAttributes = "DataType,Message,Require,To,Min,Max,ToElement,ParseType,MinElement,MaxElement,Format,Elements,Accept,DependOn,Script,Pattern,Operator,AlertType,Trim,Tips,name,id,Regex,Options".split(",");
		var len1 = customAttributes.length;
		for(var i=0;i<len;i++){
			var obj = {};
			if(!elements[i].getAttribute("DataType"))continue;
			for(var j=0;j<len1;j++){
				var name = customAttributes[j];
				if(!elements[i].getAttribute(name))continue;
				if(name == "id" || name == "name"){
					if(elements[i].getAttribute("id") && elements[i].getAttribute("id") != elements[i].getAttribute("name"))
						obj.Name = elements[i].getAttribute("id");
					else obj.Name = elements[i].getAttribute("name");
				}
				else obj[name] = elements[i].getAttribute(name);
			}
			if(obj.DataType == "Custom" && $(obj.Name + "__Script")) obj.Script = $(obj.Name + "__Script").innerHTML;
			if(!obj.AlertType) obj.AlertType = alertType;
			elementObjects[obj.Name] = Validator.ElementFactory.Build(obj);
		}
		Validator.Config.Add(formID, elementObjects)
	},
	SetTips : function(formID){
		var form = Validator.Config.Forms[formID];
		var e;
		if(Validator.Config.Settings[formID].ShowTipsType == Validator.Enumerate.ShowTipsType.Title){
			for(e in form){if(e == "id" || !form[e].Tips || form[e].Tips == "")continue;$(form[e].Name).title = form[e].Tips;}
		}
		if(Validator.Config.Settings[formID].ShowTipsType == Validator.Enumerate.ShowTipsType.Follow){
		}
		if(Validator.Config.Settings[formID].ShowTipsType == Validator.Enumerate.ShowTipsType.Layer){
			for(e in form){
				if(e == "id" || !form[e].Tips || form[e].Tips == "")continue;
				try{
				if(typeof($(form[e].Name).onfocus) != "function") $(form[e].Name).onfocus = function(e){Validator.EventHandler.OnFocus(e);}
				else {
					__onfocus = $(form[e].Name).onfocus;
					$(form[e].Name).onfocus = function(e){__onfocus();Validator.EventHandler.OnFocus(e);}
				}
				}catch(ex){alert([form[e].Name, ex])}
			}
		}
	},
	RegisterElementEvent : function(formID){
		var form = Validator.Config.Forms[formID];
		var e;
		for(e in form){
			if(e == "id")continue;
			try{
			if(typeof($(form[e].Name).onblur) != "function") $(form[e].Name).onblur = function(e){Validator.EventHandler.OnBlur(e);}
			else {
				__onblur = $(form[e].Name).onblur;
				$(form[e].Name).onblur = function(e){__onblur();Validator.EventHandler.OnBlur(e);}
			}
			}catch(ex){alert([form[e].Name, ex])}
		}
	},
	RegisterFormEvent : function(formID){
			try{
			if(typeof($(formID).onsubmit) != "function") $(formID).onsubmit = function(e){return Validator.Validate(e)};
			else {
				_onsubmit = $(formID).onsubmit;
				$(formID).onsubmit = function(e){
					_onsubmit();
					return Validator.Validate(e);
				}
			}
			}catch(ex){alert([form[e].Name, ex])}
	},
	PageLoadHandler : function(){
		var setting;
		var settings = Validator.Config.Settings;
		for(setting in settings){
			with(settings[setting]){
				if((DataSourceType & Validator.Enumerate.DataSourceType.Attribute) == Validator.Enumerate.DataSourceType.Attribute){
					Validator.AttributeReader(setting);
				}
				Validator.SetTips(setting);
				if((ValidateType & Validator.Enumerate.ValidateType.Blur) == Validator.Enumerate.ValidateType.Blur){
					Validator.RegisterElementEvent(setting);
				}
				if((ValidateType & Validator.Enumerate.ValidateType.Submit) == Validator.Enumerate.ValidateType.Submit){
					Validator.RegisterFormEvent(setting);
				}
			}
		}
	},
	Util : {
		NumberFilter : function(input){
			return input.replace(/\D/g, "");
		},
		IPAddressFix : function(input){
			return Validator.DataType.Regex.IP.test(input)?input.replace(/([^\d])(\d{1,2})(?=\.|\b)/g, "$100$2"):false;
		},
		GetPosition : function(){
			var obj = typeof(arguments[0]) == "string" ? $(arguments[0]):arguments[0];
			var point = {X:0,Y:0};
			while(obj != document.body){
				point.X += obj.offsetLeft;
				point.Y += obj.offsetTop;
				obj = obj.offsetParent;
			}
			return point;
		}
	},
	RegisterExtendElement : function(){
		var len = arguments.length;
		if(!len%2) return;
		for(var i=0;i<len;i+=2){
			if(typeof(arguments[i]) == "string"){
				if(typeof(arguments[i+1]) == "function") Validator.DataType.Extends[arguments[i]] = arguments[i+1];
				else continue;
			} else continue;
		}
	},
	RegisterPageLoadHandler : function(){
		if(document.all) window.attachEvent("onload", Validator.PageLoadHandler);
		else window.addEventListener("load", Validator.PageLoadHandler, false);
	}
}
Validator.RegisterPageLoadHandler();