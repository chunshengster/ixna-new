var Rash=true;
var msg="";

function norash()
{
if (confirm("确定要取消吗"))
Rash=false;
}
 
 function rashit()
{
setInterval('getrss()',Inttime);
}

function getrss()
{
		if (Rash==true)
		{
		head=document.getElementsByTagName('head')[0];
		script=document.createElement('script');
		script.src='./update.php';
		script.type='text/javascript';
		script.defer=true;
		void(head.appendChild(script));
		window.status=msg;
		}
}
rashit();