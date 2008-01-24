/**
 *����ID,��������arguments[0]
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
 * ����֤��v0.1
 *
 * @���ͣ���
 * @������a_sID:��ID
 * @���أ���
 * @���ߣ�[BI]CJJ http://www.imcjj.com
 * @ʱ�䣺2006-9-8
 * @��ע��
 */
function TFormValid(a_sID) {
    var oForm=document.getElementById(a_sID);

    if(!oForm) return;

    this.Form=oForm;
    this.dontValidID=null;
    //���ӱ�Ԫ����֤����
    this.addRule=TFormValid_addRule;
    //���ñ�Ԫ����֤����
    this.Rules=[];
    //����֤����
    this.Listen=TFormValid_Listen;
    //����ⲿ����
    this.addPlugin = TFormValid_Plugin;
    this.Plugins=[];
    this.PluginReturns=[];
}

/**
 * ����֤�ⲿ����
 *
 * @���ͣ���������
 * @��������
 * @���أ���
 * @���ߣ�[BI]CJJ http://www.imcjj.com
 * @ʱ�䣺2006-6-15
 * @��ע��
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
 * �������������ύʱ��������֤
 *
 * @���ͣ���������
 * @��������
 * @���أ���
 * @���ߣ�[BI]CJJ http://www.imcjj.com
 * @ʱ�䣺2006-6-15
 * @��ע��
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

        //ִ���ⲿ�����б�
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
                    //����֤���
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

                    //����֤���
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
 * Ϊ��Ԫ��������֤����
 *
 * @���ͣ���������
 * @������a_sID: Ԫ��id
 *      a_sType:��֤����
 *        a_sMsg:��Ҫ��ʾ����Ϣ
 * @���أ���
 * @���ߣ�[BI]CJJ http://www.imcjj.com
 * @ʱ�䣺2006-6-15
 * @��ע��
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

        //Ԫ�����ݸı�ʱ
        oElement.onchange=function(){
            oRule.Status=-1;
        };

        //ʧȥ����ʱ��֤��Ԫ��
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
                    //����֤���
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
 * �������
 *
 * @���ͣ���
 * @������a_oElements:������ص�Ԫ���б�
 *        a_sType:��������
 *         a_sMsg:������ʾ��Ϣ
 * @���أ���
 * @���ߣ�[BI]CJJ http://www.imcjj.com
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
function TRule(a_oElements, a_sType, a_sMsg) {
    var oElements=a_oElements;
    var oNode=oElements[0], oPNode, oMsgs=[], oMsg;
    var aMsgs = a_sMsg.split(",");

    if(!oNode) {return;}

    if(typeof(aMsgs)=="object") {
        //֧��һ����������ʾ�����ʾ��Ϣ
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
    //����״̬-1:δ������֤;0��֤ʧ��;1:��֤�ɹ�
    this.Stauts=-1;
}

/**
 * ��ʾ��Ϣ����
 *
 * @���ͣ���
 * @������a_oNode:��ָ���������ʾ��ʾ��Ϣ
 *        a_sMsg:��Ҫ��ʾ����Ϣ
 * @���أ���
 * @���ߣ�[BI]CJJ http://www.imcjj.com
 * @ʱ�䣺2006-6-15
 * @��ע��
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

//***********����Ϊ����֤����************
/**
 * ��Ԫ����֤
 *
 * @���ͣ���������
 * @������a_sType:��֤����
 *        a_soObj:����֤��Ԫ�ؼ���
 * @���أ����ͨ����֤������true�����򷵻�false
 * @���ߣ�[BI]CJJ http://www.imcjj.com
 * @ʱ�䣺2006-6-15
 * @��ע��
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
 * ����Ƿ�Ϊ��
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ������Ϊ�գ�����true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
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

    //�ո�������ִ��������
    if (i==j) return false;

    return true;
}

/**
 * ����Ƿ�Ϊ����
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ���������֣�����true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
function isNumber(a_sStr) {
    var reg=/^\d+$/; 
    return reg.test(a_sStr);
}

/**
 * �������ֵ�Ƿ����
 *
 * @���ͣ���������
 * @������a_sStr1:����֤���ַ���1
 *        a_sStr2:����֤���ַ���2
 * @���أ������ȣ�����true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
 function isSame(a_sStr1,a_sStr2) {
    var sStr1 = a_sStr1, sStr2 = a_sStr2;

    if (!isNotNull(sStr1)) return false;
    if (sStr1 == sStr2) return true;

    return false;
}

/**
 * �Ƿ�ΪӢ���ַ�
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ������Ӣ���ַ�������true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
 function isLetter(a_sStr) {
    var reg=/^[A-Za-z0-9 ]+$/; 
    return reg.test(a_sStr);
}

/**
 * �Ƿ���
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ�����ַ����о��Ǻ��֣�����true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
 function isChinese(a_sStr) {
    var reg=/^[\u0391-\uFFE5]+$/; 
    return reg.test(a_sStr);
}

/**
 * Email��ַ�Ƿ���ȷ
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ���������ַ��ȷ������true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
 function isMail(a_sStr) {
    var reg=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; 
    return reg.test(a_sStr);
}

/**
 * �ֻ������Ƿ���ȷ
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ�����ֻ�������ȷ������true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
function isMobile(a_sStr) {
    var reg=/^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$/; 
    return reg.test(a_sStr);
}

/**
 * �绰�Ƿ���ȷ����ʽ����Ϊ123(����) -(�ָ���) 123456(����)
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ�����绰������ȷ������true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
function isTel(a_sStr) {
    var reg=/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/; 
    return reg.test(a_sStr);
}

/**
 * ��������Ƿ���ȷ
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ�����ʱ���ȷ������true�����򷵻�false
 * @ʱ�䣺2006-6-15
 * @��ע��
 */
function isZip(a_sStr) {
    var reg=/^[1-9]\d{5}$/; 
    return reg.test(a_sStr);
}

/**
 * URL��ַ�Ƿ�Ϸ�
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ����URL��ȷ������true�����򷵻�false
 * @ʱ�䣺2007-8-7
 * @��ע��
 */
function isURL(a_sStr) {
    var reg=/^http|ftp:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/;
    return reg.test(a_sStr);
}

/**
 * ���֤�����Ƿ���ȷ
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ�������֤������ȷ������true�����򷵻�false
 * @ʱ�䣺2007-8-7
 * @��ע��
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
 * YYYY-MM-DD��ʽ�������Ƿ���ȷ
 *
 * @���ͣ���������
 * @������a_sStr:����֤���ַ���
 * @���أ����������ȷ������true�����򷵻�false
 * @ʱ�䣺2007-8-7
 * @��ע��
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
//* �������ƣ�DataLength
//* �� �ܣ��������ݵĳ���
//* ��ڲ�����fData����Ҫ���������
//* ���ڲ���������fData�ĳ���(Unicode����Ϊ2����Unicode����Ϊ1)
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