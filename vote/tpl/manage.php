<?
	include './tpl/header.php';

?>

<table width="720" border="0" align="center" cellpadding="1" cellspacing="2">
  <tr> 
    <td  style="border:1px solid #666666;padding:3px;"><table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
        <tr> 
          <td class="en">All:3 </td>
          <td class="en"> </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="69" style="border:1px solid #666666;padding:3px;"> <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr> 
          <td width="3%" bgcolor="#DDDDDD">&nbsp;</td>
          <td width="2%" bgcolor="#E4E6CE"><div align="center"><font face="Verdana, Arial, Helvetica, sans-serif">ID</font></div></td>
          <td width="37%" bgcolor="#8FE9FC"> 
            <div align="center">ͶƱ��Ŀ</div></td>
          <td width="9%" bgcolor="#EDFAB1"> 
            <div align="center">ѡ����Ŀ</div></td>
          <td width="19%" bgcolor="#E3BFC8"> 
            <div align="center">����ʱ��</div></td>
          <td width="9%" bgcolor="#D1FBBD"> 
            <div align="center">ͶƱ����</div></td>
          <td width="7%" bgcolor="#F7F7F7">
<div align="center">��/��</div></td>
          <td width="7%" bgcolor="#EEEEEE"> 
            <div align="center">�޸�</div></td>
          <td width="7%" bgcolor="#E3E3E3"> 
            <div align="center">ɾ��</div></td>
        </tr>
<?
foreach ($mc->data AS $news_vote) {
	$news_vote = explode($mc->sep, trim($news_vote));
	$news_votenews_count = $news_votenum = 0;
	for ($i=0;$i<5;$i++) {
		if ($news_vote[$i+3]) {
			$news_votenews_count++;
			$news_votenum += $news_vote[$i+8];
		}
	}

?>        
        <tr> 
          <td><a href="../flashnews_vote.swf?thisnews_voteid=<?=$news_vote[1]?>" target="_blank"><img src="images/dot.gif" width="22" height="21" border="0"></a></td>
          <td bgcolor="#F9F9F7"  class="en" style="border-bottom:1px solid #E0F2FF;"><div align="center"><?=$news_vote[1]?></div></td>
          <td bgcolor="#E0F8FE" style="border-bottom:1px solid #E0F2FF;padding-left:8px;"><a href="?action=view&id=<?=$news_vote[1]?>" target="_blank"><?=$news_vote[2]?></a></td>
          <td bgcolor="#F7FCDA" class="en" style="border-bottom:1px solid #F3FBC8;"> 
            <div align="center"> 
              <?=$news_votenews_count?>
            </div></td>
          <td bgcolor="#FCF8F9" class="en" style="border-bottom:1px solid #FAF8F2;"><div align="center"><?=date('Y-m-d H:i:s', $news_vote[17])?></div></td>
          <td bgcolor="#EFFFEE" class="en" style="border-bottom:1px solid #DDFFED;"><div align="center"><?=$news_votenum?></div></td>
          <td><div align="center"><a href="?action=oorc&id=<?=$news_vote[1]?>" target="return"><img id="oorc_<?=$news_vote[1]?>" src="images/<?=$news_vote[13] ? 'open' : 'close'?>.gif" alt="<?=$news_vote[13] ? '�رմ�ͶƱ' : '�򿪴�ͶƱ'?>" width="22" height="21" border="0"></a></div></td>
          <td><div align="center"><a href="?action=edit&id=<?=$news_vote[1]?>"><img src="images/edit.gif" alt="�޸�" width="22" height="21" border="0"></a></div></td>
          <td><div align="center"><a href="?action=del&id=<?=$news_vote[1]?>" onClick="javascript:if(!confirm('ȷ��ɾ����ͶƱ��')){return false;}"><img src="images/del.gif" alt="ɾ��" width="22" height="21" border="0"></a></div></td>
        </tr>
<?
}
?>      
        
      </table></td>
  </tr>
  <tr> 
    <td style="border:1px solid #666666;padding:3px;">ͼ��˵����<img src="images/dot.gif" alt="��flashͶƱ��ʾ" width="22" height="21" align="absmiddle"> 
      ��flashͶƱ��ʾ <img src="images/open.gif" alt="�򿪵�ͶƱ" width="22" height="21" align="absmiddle">�򿪵�ͶƱ 
      <img src="images/close.gif" alt="�رյ�ͶƱ" width="22" height="21" align="absmiddle"> 
      �رյ�ͶƱ <img src="images/edit.gif" alt="�༭ͶƱ��ť" width="22" height="21" align="absmiddle">�༭ͶƱ��ť 
      <img src="images/del.gif" alt="ɾ��ͶƱ��ť" width="22" height="21" align="absmiddle"> 
      ɾ��ͶƱ��ť</td>
  </tr>
<?
	include './tpl/footer.php';
?>
<iframe name="return" frameborder=0 height=0 width=0 scrolling=no></iframe>
