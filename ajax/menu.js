<!--
function killerr() {
return true;
	}
	window.onerror=killerr;
	function scrollPageHome(){
	window.scrollTo(0,0);
	}
	
	function onNodesExpandAll(NodeCount)
	{
        var oAllNodes = document.getElementsByName('NodeContent');
        var sNodeID="";
        for (var i = 0; i < oAllNodes.length; i ++) {
            sNodeID =oAllNodes[i].parentNode.id;
			onNodeClick(sNodeID.replace("NodeContent",""));
     }

			btnExpandAll.innerHTML=(btnExpandAll.innerHTML=="Expand"?"Hide":"Expand");
	}
	
	function onNodeClick(NodeId)
	{
		var targetNodeImg=document.getElementById("NodeImg"+NodeId);
		var targetNodeContent=document.getElementById("NodeContent"+NodeId);
		var targetNodeImgSrc=new String(targetNodeImg.src);

		var ImgSrcLeft=new String(targetNodeImgSrc.substr(0,targetNodeImgSrc.length-8));
		var ImgSrcCenter=new String(targetNodeImgSrc.substr(targetNodeImgSrc.length-8,3));
		var ImgSrcRight=new String(targetNodeImgSrc.substr(targetNodeImgSrc.length-5,5));
		alert(targetNodeContent.innerHTML);
		//window.alert(ImgSrcLeft+"\n"+ImgSrcCenter+"\n"+ImgSrcRight);

		alert(targetNodeContent.style.display);

		if(targetNodeContent.style.display=="none")
		{
			targetNodeContent.style.display="";
			ImgSrcCenter="ext";
			alert("xxxid:"+NodeId);
			show_xna_content(NodeId);
			alert("show_xna_content("+NodeId+");");
		}
		else
		{
			targetNodeContent.style.display="none";
			ImgSrcCenter="col";
		}

		alert(targetNodeContent.style.display);

		targetNodeImg.src=ImgSrcLeft+ImgSrcCenter+ImgSrcRight;
	}
function externallinks() { 
 if (!document.getElementsByTagName) return; 
 var anchors = document.getElementsByTagName("a"); 
 for (var i=0; i<anchors.length; i++) { 
   var anchor = anchors[i]; 
   if (anchor.getAttribute("href") && 
       anchor.getAttribute("rel") == "external") 
     anchor.target = "_blank"; 
 } 
} 
window.onload = externallinks;

//更改字体大小
var status0='';
var curfontsize=12;
var curlineheight=15;
function fontZoomA(){
  if(curfontsize>8){
    document.getElementById('news_content').style.fontSize=(--curfontsize)+'pt';
	document.getElementById('news_content').style.lineHeight=(--curlineheight)+'pt';
  }
}
function fontZoomB(){
  if(curfontsize<64){
    document.getElementById('news_content').style.fontSize=(++curfontsize)+'pt';
	document.getElementById('news_content').style.lineHeight=(++curlineheight)+'pt';
  }
}

if (top.location != self.location){ 
    alert("禁止非法框架包含,请尊重作者劳动!!!");
    top.location = self.location;} 

  function InitSync(){  
      if( "object" == typeof( top.deeptree ) && "unknown" == typeof( top.deeptree.Sync ) )
      {top.deeptree.Sync(); }  
 }
-->