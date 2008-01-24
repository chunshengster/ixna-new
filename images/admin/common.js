function selectAll(a_sID){
    if(!a_sID) return;

    var oForm=document.getElementById(a_sID), oElements, oElement, oCheck=document.getElementById('chkSelectAll');

    if((!oForm) || (!oCheck)) return;

    var bIsChecked = false;
    if(oCheck.checked) bIsChecked=true;

    oElements=oForm.elements;

    for(var i = 0; i<oElements.length; i++){
        oElement = oElements[i];
        if(oElement.type=="checkbox" && oElement.name!="unSelectall") {oElement.checked=bIsChecked;}
    }
}

function setstate(id){
	if(confirm("锁定吗?")){
		location.href="?action=doUpdateFeed&id="+id+"&value=2"
		return;
	}
	else{
		if(confirm("置顶吗?")){
			location.href="?action=doUpdateFeed&id="+id+"&value=1"
			return;
		}
		else{
			location.href="?action=doUpdateFeed&id="+id+"&value=0"
			return;
		}
	}
}
function setaudit(id){
	if(confirm("通过吗?")){
		location.href="?action=doUpdateFeed&id="+id+"&value=0"
		return;
	}
}