/**
 *定义ID,参数数组arguments[0]
 * */
function $(){
	var arIDS=[];	
	if(arguments.length==1){
		arIDS=document.getElementById(arguments[0]);		
	}else {
		for(var i=0,j=arguments.length;i<j;i++){
			arIDS.push(document.getElementById(arguments[i]));			
		}
	}
	return arIDS;	
}
/**
 * 表单验证类v0.1
 *
 * @类型：类
 * @参数：a_sID:表单ID
 * @返回：无
 * @作者：[BI]CJJ http://www.imcjj.com
 * @时间：2006-9-8
 * @备注：
 */
function TFormValid(a_sID) {
    var oForm=document.getElementById(a_sID);

    if(!oForm) return;

    this.Form=oForm;
    this.dontValidID=null;
    //增加表单元素验证规则
    this.addRule=TFormValid_addRule;
    //设置表单元素验证集合
    this.Rules=[];
    //表单验证监听
    this.Listen=TFormValid_Listen;
    //添加外部函数
    this.addPlugin = TFormValid_Plugin;
    this.Plugins=[];
    this.PluginReturns=[];
}

/**
 * 表单验证外部函数
 *
 * @类型：公共函数
 * @参数：无
 * @返回：无
 * @作者：[BI]CJJ http://www.imcjj.com
 * @时间：2006-6-15
 * @备注：
 */
function TFormValid_Plugin(a_fHandler, a_bHasReturn) {
    var oPlugins = this.Plugins, oPluginReturns = this.PluginReturns;
    var iLen = oPlugins.length;

    if (oPluginReturns.length==iLen) {
        this.Plugins[iLen]=a_fHandler;
        this.PluginReturns[iLen]=a_bHasReturn;
    }

    return;
}


/**
 * 监听表单，当表单提交时，进行验证
 *
 * @类型：公共函数
 * @参数：无
 * @返回：无
 * @作者：[BI]CJJ http://www.imcjj.com
 * @时间：2006-6-15
 * @备注：
 */
function TFormValid_Listen() {
    var oForm=this.Form;
    var oRules=this.Rules;
    var sDontValidID = this.dontValidID;
    var oPlugins = this.Plugins;
    var oReturnValues = this.PluginReturns;

    if(!oForm) return;

    oForm.onsubmit=function() {
        var oMsg, oRule, oElement;
        var bReturn=true, bResult=true;
        var aResults, aMsgs;
        var i;

        if (sDontValidID) {
            oElement = document.getElementById(sDontValidID);

            if(oElement) {
                if(oElement.value=="1") {
                    return true;
                }
            }
        }

        //执行外部函数列表
        for(i=0;i<oPlugins.length;i++) {
            if(oReturnValues[i]) {
                if(!oPlugins[i]()) {return false;}
            } else {
                oPlugins[i]();
            }
        }

        for(i=0;i<oRules.length;i++) {
            oRule = oRules[i];
            aResults=_FormValid(oRule.Name,oRule.Elements);
//alert('results:'+typeof(aResults));
            if(typeof(aResults)=="object") {

                for(var j=0;j<aResults.length;j++) {
                    oMsg=oRule.Msg[j];
                    bResult=aResults[i];
                    //表单验证结果
                    if(!bResult) {
                        bReturn=false;
                        oRule.Status=0;
                        oMsg.Show();
                        break;
                    } else {
                        oRule.Status=1;
                        oMsg.Hide();
                    }// end if
                } //end for
         
//alert("l_type:"+oRules[i].Name+"\nl_value:"+oRules[i].Elements[0].value+"\nl_result:"+bValidResult);
            } else {
                    oMsg=oRule.Msg[0];
                    bResult=aResults;

                    //表单验证结果
                    if(!bResult) {
                        bReturn=false;
                        oRule.Status=0;
                        oMsg.Show();
                    } else {
                        oRule.Status=1;
                        oMsg.Hide();
                    }// end if
            }//end if
        }//end for

        return bReturn;
    }
}


/**
 * 为表单元素增加验证规则
 *
 * @类型：公共函数
 * @参数：a_sID: 元素id
 *      a_sType:验证类型
 *        a_sMsg:需要显示的信息
 * @返回：无
 * @作者：[BI]CJJ http://www.imcjj.com
 * @时间：2006-6-15
 * @备注：
 */
function TFormValid_addRule(a_sID, a_sType, a_sMsg) {
    var oRules = this.Rules, aMsgs=a_sMsg.split(",");
    var iLen = oRules.length, i;
    var oElement, oRule, oElements=[], oElmIDs=a_sID.split(",");

    for(i=0;i<oElmIDs.length;i++) {
        oElement=document.getElementById(oElmIDs[i]);

        if(oElement) {
            oElements[i]=oElement;
        }
    }

    if(oElements.length<=0) return;

    oRule=new TRule(oElements, a_sType, a_sMsg);
    oRule.Status=-1;
    oRules.push(oRule);

    for(i=0;i<oElements.length;i++) {
        oElement=oElements[i];

        //元素内容改变时
        oElement.onchange=function(){
            oRule.Status=-1;
        };

        //失去焦点时验证表单元素
        oElement.onblur=function() {
            var sTemp, sType;
            var oMsg;
            var aReturns, aMsgs;
            var bReturn;

            sType=oRule.Name;
            aReturns=_FormValid(sType,oElements);

            if(typeof(aReturns)=="object") {
                for(var i=0;i<aReturns.length;i++) {
                    oMsg=oRule.Msg[i];
                    bReturn=aReturns[i];
                    //表单验证结果
                    if(!bReturn) {
                        oRule.Status=0;
                        oMsg.Show();
                        break;
                    } else {
                        oRule.Status=1;
                        oMsg.Hide();
                    }
                } //end for
            } else {
                oMsg=oRule.Msg[0];
                bReturn=aReturns;

                if(!bReturn) {
                    oRule.Status=0;
                    oMsg.Show();
                } else {
                     oRule.Status=1;
                     oMsg.Hide();
                } 
            }
        };

         this.Rules=oRules;
    }
}

/**
 * 规则对象
 *
 * @类型：类
 * @参数：a_oElements:规则相关的元素列表
 *        a_sType:规则类型
 *         a_sMsg:规则提示信息
 * @返回：无
 * @作者：[BI]CJJ http://www.imcjj.com
 * @时间：2006-6-15
 * @备注：
 */
function TRule(a_oElements, a_sType, a_sMsg) {
    var oElements=a_oElements;
    var oNode=oElements[0], oPNode, oMsgs=[], oMsg;
    var aMsgs = a_sMsg.split(",");

    if(!oNode) {return;}

    if(typeof(aMsgs)=="object") {
        //支持一条规则中显示多个提示信息
        for(var i=0;i<aMsgs.length;i++) {
           oPNode=oElements[i].parentNode;
           oMsg = new TMsg(oPNode, aMsgs[i]);
           oMsg.Hide();
           oMsgs.push(oMsg);
        }
    } else {
       oPNode=oElements[0].parentNode;
       oMsg = new TMsg(oPNode, a_sMsg);
       oMsg.Hide();
       oMsgs.push(oMsg);
    }

    this.Elements=oElements;
    this.Element=oElements[0];
    this.Name=a_sType;
    this.Msg=oMsgs;
    //规则状态-1:未经过验证;0验证失败;1:验证成功
    this.Stauts=-1;
}

/**
 * 提示信息对象
 *
 * @类型：类
 * @参数：a_oNode:在指定结点中显示提示信息
 *        a_sMsg:需要显示的信息
 * @返回：无
 * @作者：[BI]CJJ http://www.imcjj.com
 * @时间：2006-6-15
 * @备注：
 */
function TMsg(a_oNode, a_sMsg) {
    var oMsg = document.createElement("span");
    var oPNode=a_oNode;
    var oText = document.createTextNode(a_sMsg);

    oMsg.className="Msg_Error";
    oMsg.style.display = "none";
    oMsg.appendChild(oText);
    oPNode.appendChild(oMsg);
    this.ParentNode=oPNode;

    this.Show = function() {
        oMsg.style.display = "";
    };

    this.Hide = function() {
        oMsg.style.display = "none";
    };
}

//***********以下为表单验证函数************
/**
 * 表单元素验证
 *
 * @类型：公共函数
 * @参数：a_sType:验证类型
 *        a_soObj:待验证的元素集合
 * @返回：如果通过验证，返回true，否则返回false
 * @作者：[BI]CJJ http://www.imcjj.com
 * @时间：2006-6-15
 * @备注：
 */
function _FormValid(a_sType,a_oObj) {
    var bReturn=false;
    var sType=a_sType;
    var oElements=a_oObj;
    var sTemp=oElements[0].value;

    switch(sType) {
    case "NotNull":
        bReturn = isNotNull(sTemp);
        break;
    case "SameValue":
         bReturn = [isNotNull(sTemp),isSame(sTemp,oElements[1].value)];
         break;
    case "Number":
         bReturn = isNumber(sTemp);
         break;
    case "Tel": 
         bReturn = isTel(sTemp);
         break;
    case "Mobile": 
         bReturn = isMobile(sTemp);
         break;
    case "Zip":
         bReturn = isZip(sTemp);
         break;
    case "Email":
         bReturn=isMail(sTemp);
         break;
    case "CardID":
         bReturn=isCardID(sTemp);
         break;
    case "URL":
         bReturn=isURL(sTemp);
         break;
    case "YMDDate":
         bReturn=isYMDDate(sTemp);
	 break;
    case "Length":
         bReturn=DataLength(sTemp);
		 break;	 
    default:
         bReturn=true;
         break;
    }

//alert("type:"+sType+"\nvalue:"+sTemp+"\nresult:"+bReturn);

    return bReturn;
}

/**
 * 检查是否不为空
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果不为空，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
function isNotNull(a_sStr) {
    var sStr = a_sStr, sTemp;
    var i=sStr.length, j, k;

    if (i<=0) return false;
    j=0,k=0;
    sTemp='';

    while (k<i) {
        sTemp = sStr.charAt(k);
        k = k +1;
        if (sTemp != " ") break;
        j = j + 1;
    }

    //空格个数和字串长度相等
    if (i==j) return false;

    return true;
}

/**
 * 检查是否为数字
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果是数字，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
function isNumber(a_sStr) {
    var reg=/^\d+$/; 
    return reg.test(a_sStr);
}

/**
 * 检查两个值是否相等
 *
 * @类型：公共函数
 * @参数：a_sStr1:待验证的字符串1
 *        a_sStr2:待验证的字符串2
 * @返回：如果相等，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
 function isSame(a_sStr1,a_sStr2) {
    var sStr1 = a_sStr1, sStr2 = a_sStr2;

    if (!isNotNull(sStr1)) return false;
    if (sStr1 == sStr2) return true;

    return false;
}

/**
 * 是否为英文字符
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果是英文字符，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
 function isLetter(a_sStr) {
    var reg=/^[A-Za-z0-9 ]+$/; 
    return reg.test(a_sStr);
}

/**
 * 是否汉字
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果字符串中均是汉字，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
 function isChinese(a_sStr) {
    var reg=/^[\u0391-\uFFE5]+$/; 
    return reg.test(a_sStr);
}

/**
 * Email地址是否正确
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果邮箱地址正确，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
 function isMail(a_sStr) {
    var reg=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; 
    return reg.test(a_sStr);
}

/**
 * 手机号码是否正确
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果手机号码正确，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
function isMobile(a_sStr) {
    var reg=/^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$/; 
    return reg.test(a_sStr);
}

/**
 * 电话是否正确，格式必须为123(区号) -(分隔符) 123456(号码)
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果电话号码正确，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
function isTel(a_sStr) {
    var reg=/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/; 
    return reg.test(a_sStr);
}

/**
 * 邮箱编码是否正确
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果邮编正确，返回true，否则返回false
 * @时间：2006-6-15
 * @备注：
 */
function isZip(a_sStr) {
    var reg=/^[1-9]\d{5}$/; 
    return reg.test(a_sStr);
}

/**
 * URL地址是否合法
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果URL正确，返回true，否则返回false
 * @时间：2007-8-7
 * @备注：
 */
function isURL(a_sStr) {
    var reg=/^http|ftp:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/;
    return reg.test(a_sStr);
}

/**
 * 身份证号码是否正确
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果身份证号码正确，返回true，否则返回false
 * @时间：2007-8-7
 * @备注：
 */
function isCardID(a_sStr) {
    var iLen=a_sStr.Length;
    if(iLen<15||iLen==16||iLen==17||iLen>18) {return false;}
 
    var Ai;
    if(iLen==18) {Ai=a_sStr.substring(0,17);}
    else{Ai =a_sStr.substring(0,6)+"19"+a_sStr.substring(6,9);}
 
    if(!isNumer(Ai)){return false;}

    var strYear,strMonth,strDay,strBirthDay;
    strYear = parseInt(Ai.substring(Ai,6,4)); 
    strMonth = parseInt(Ai.substring(Ai,10,2)) ;
    strDay = parseInt(Ai.substring(Ai,12,2));

    if (!isYMDDate(strYear+'-'+strMonth+'-'+strDay)) {return false;}

    var arrVerifyCode = new Array("1","0","x","9","8","7","6","5","4","3","2");
    var Wi = new Array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);

    var i,TotalmulAiWi=0;
    for (i=0; loop<16;loop++) { 
        TotalmulAiWi = TotalmulAiWi + parseInt(Ai.substring(i+1,1)) * Wi[i];
    }

    var modValue =TotalmulAiWi%11 ;
    var strVerifyCode = arrVerifyCode[modValue];
    Ai = Ai & strVerifyCode;

    if((iLen== 18)&&(a_sStr!=Ai)){return false;}

    return true;
}

/**
 * YYYY-MM-DD格式的日期是否正确
 *
 * @类型：公共函数
 * @参数：a_sStr:待验证的字符串
 * @返回：如果日期正确，返回true，否则返回false
 * @时间：2007-8-7
 * @备注：
 */
function isYMDDate(a_sDate) {
    var reg = /[-|\\|\.|\/|\s]/g;
    a_sDate = a_sDate.replace(reg, "-");
    //"dddd-dd-dd"
    var regDate = /^(\d{2,4})(-)(\d{1,2})\2(\d{1,2})$/; 
    var result = a_sDate.match(regDate);
    if ( result == null ){return false;}

    var month = ((""+result[3]).length < 2)?("0" + result[3]):("" + result[3]);
    var day = ((""+result[4]).length < 2)? ("0" + result[4]):("" + result[4]);
    a_sDate = result[1] + result[2] + month + result[2] + day;
    var date = new Date(result[1], result[3]-1,result[4]);
    month = ((date.getMonth() + 1) < 10)?("0" + (date.getMonth() + 1)):("" + (date.getMonth() + 1));
    day = (date.getDate() < 10)?("0" + date.getDate()):("" + date.getDate());
    var newStr=date.getFullYear() + result[2] + month + result[2] + day;

    return (newStr == a_sDate||newStr=="19"+a_sDate);
}
//***
//* 名　　称：DataLength
//* 功 能：计算数据的长度
//* 入口参数：fData：需要计算的数据
//* 出口参数：返回fData的长度(Unicode长度为2，非Unicode长度为1)
//***
function DataLength(fData){
    var intLength=0
    for (var i=0;i<fData.length;i++) {
        if ((fData.charCodeAt(i) < 0) || (fData.charCodeAt(i) > 255))
            intLength=intLength+2
        else
            intLength=intLength+1   
    }
    return intLength
}