var escaping = function(){
	if(!document.getElementById){ return false;}
	var interim;
	var temp = document.getElementsByTagName("div");
	var tempLen = temp.length;
	var pattern = new RegExp("(?:^|\\s)postentry(?:\\s|$)");	
	for (i = 0; i < tempLen; i++) {
		if ( pattern.test(temp[i].className) ) {
			//interim = temp[i].textContent;
			//if(interim == undefined || (interim.indexOf("&") == -1 && interim.indexOf("<") == -1)){/*_*/}
			temp[i].innerHTML = temp[i].firstChild.data;
		}
	}
};

window.load = escaping();
