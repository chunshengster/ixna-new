// AJAXRequest
var ajax=new AJAXRequest;

//Load...
ajax.onrequeststart=function() {
     document.getElementById("loadingDiv").style.display="block";
}
ajax.onrequestend=function() {
    document.getElementById("loadingDiv").style.display="none";
}

// show_xna_content
function show_xna_content(id) {
	ajax.get(
		"./ajax/getcontent.php?id=" + id,
		function(obj) { document.getElementById("newscontent"+id).innerHTML=obj.responseText; }
	);
}

// post
function e_postf() {
	ajax.postf(
		"addsite",
		function(obj) {
			document.getElementById("info").innerHTML=obj.responseText;		
		//	ajax.get(
		//		"./ajax/getcomments.php?id=" + id,
		//		function(obj) { document.getElementById("normal").innerHTML+=obj.responseText; }
		//	);
		}
	);
}

// Page
function process(url) {       
		document.getElementById('m_Body').innerHTML = '<div align="center"><img src="./images/default/loading.gif" alt="loading"></div>'
	ajax.get(
		url,
		function(obj) { document.getElementById("m_Body").innerHTML=obj.responseText;
		//document.getElementById('m_Body').innerHTML = ''; // 
		}
	);
}

function e_comm(id) {
	id = document.getElementById("id").value
	ajax.postf(
		"addcomm",
		function(obj) {
		document.getElementById("info").innerHTML=obj.responseText;		
			ajax.get(
				"./ajax/getcomments.php?id=" + id,
				function(obj) { document.getElementById("normal").innerHTML+=obj.responseText; }
			);
		}
	);
}

//Newsnews_vote
function NewsVote(id) {
	ajax.get(
		"./ajax/getcontent.php?vote=" + id,
		function(obj) {
				var sText=obj.responseText;
			 	var aText=sText.split("|");
				document.getElementById("diggs-strong-"+id).innerHTML=aText[0];
				document.getElementById("e"+id).innerHTML=aText[1];
		}
	);
}


// comment news_vote
function ReplyVote(id, c) {
	//alert (id);
	ajax.get(
		"./comment.php?" + c + "=" + id,
		function(obj) {
		//alert(c);
				var sText=obj.responseText;
			 	var aText=sText.split("|");
				switch (c){
					case "support":
					document.getElementById("s"+id).innerHTML=aText[0];
					document.getElementById("info"+id).innerHTML=aText[1];
					break
					case "against":
					document.getElementById("a"+id).innerHTML=aText[0];
					document.getElementById("info"+id).innerHTML=aText[1];
					break
					default:
					//document.getElementById("info"+id).innerHTML=obj.responseText; 
					break
				}
		}
	);
}

// show comment
function show_comment(id) {
	ajax.get(
		"./getComment.php?id=" + id,
		function(obj) { document.getElementById("comentinfo").innerHTML=obj.responseText; }
	);
}

// update link
function link(id) {
	ajax.get(
		"./ajax/getcontent.php?id=" + id,
		function(obj) {
		//alert(id);
		document.getElementById("info").innerHTML=obj.responseText; }
	);
}

//show_tags
function showtags(a_sID){
      var oa = document.getElementById(a_sID);
      var ob = document.getElementById("add" + a_sID);
      if(oa.style.display == "block"){
              oa.style.display = "none";
              ob.innerHTML = "[+]";
      }else{
              oa.style.display = "block";
              ob.innerHTML = "[-]";
      }
      return false;
}
 
//submitTag
function submitTag(a_sID){
    //update TAG
	//alert("ID:"+a_sID);
	txtTag=document.getElementById("txtTag"+a_sID).value;
    //alert(txtTag);
	ajax.post(
		"./ajax/addtags.php?id="+a_sID,
		"txtTag="+txtTag,
		function(obj) { document.getElementById("tag_message_"+a_sID).innerHTML+=" "+obj.responseText;  }
	);
}

function closeBox(tar){    
       var tarID = document.getElementById(tar);	  
	   if(tarID.style.display!='none') {tarID.style.display='none'}else{tarID.style.display=''}       
	   document.getElementById("efeed").className=(document.getElementById('editfeed').style.display=="")?"o":"";
	   document.getElementById("dfeed").className=(document.getElementById('delfeed').style.display=="")?"o":"";	   
       return false;
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

//font size
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
//
if (top.location != self.location){ 
    alert("The framework includes prohibiting illegal!");
    top.location = self.location;} 

  function InitSync(){  
      if( "object" == typeof( top.deeptree ) && "unknown" == typeof( top.deeptree.Sync ) )
      {top.deeptree.Sync(); }  
 }