<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>显示<?=$id?>号投票内容 . 公用投票系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style>
	td{
			font-size:12px;
			color:000000;
			background-color:EEEEEE;
		}
</style>
</head>

<body>

<table width="98%" border="0" cellspacing="1" cellpadding="4">
  <tr> 
    <td colspan="2"  bgcolor="#8FE9FC"><em><strong>投票议题：</strong></em><?=$news_vote[2]?></td>
  </tr>
<?
for ($i=1;$i<6;$i++) {
	if ($news_vote[$i+2]) {
?>
  <tr bgcolor="#FCFEEF"> 
    <td><b><?=$i?></b> <?=$news_vote[$i+2]?></td>
    <td><?=$news_vote[$i+7]?></td>
  </tr>
<?
	}
}
?>
    
  <tr> 
    <td colspan="2"  bgcolor="#8FE9FC"><div align="center">&copy; 2005 <strong><a href="http://www.mecee.com" target="_blank">美刻时空</a></strong> 
        <a href="http://news_vote.lygpc.com/" target="_blank"><span class=en>(基于ASP版前台)</span></a></div></td>
  </tr>
</table>
</body>
</html>