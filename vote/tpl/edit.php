<?
include './tpl/header.php';
?>
<table width="720" border="0" cellspacing="3" cellpadding="0">
  <tr> 
    <td style="border:1px solid #666666;padding:3px;"><table width="720" border="0" cellspacing="0" cellpadding="5">
        <form name="form1" method="post" action="">
          <tr> 
            <td width="120" rowspan="12" bgcolor="#8FE9FC">&nbsp;</td>
            <td colspan="2"><div align="center" class="tl">�޸ĵ�ǰͶƱ��Ŀ</div></td>
            <td width="170" rowspan="12" bgcolor="#8FE9FC">&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#FCFCFC">ͶƱ����</td>
            <td bgcolor="#F7FCDA"><input name="sorm" type="radio" value="0" <?=$checkfalse?>>
  ��ѡ
    <input type="radio" name="sorm" value="1" <?=$checktrue?>>
  ��ѡ</td>
          </tr>
          <tr> 
            <td width="70" bgcolor="#FCFCFC">ͶƱ����</td>
            <td width="360" bgcolor="#F7FCDA"><input name="news_voteco" type="text" id="news_voteco" value="<?=$news_vote[2]?>" size="35"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ�� 
              1 </td>
            <td bgcolor="#F7FCDA"><input name="cs1" type="text" value="<?=$news_vote[3]?>">
              Ʊ�� 
              <input name="cs1_num" type="text"  value="<?=$news_vote[8]?>" size="5"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ�� 
              2 </td>
            <td bgcolor="#F7FCDA"><input name="cs2" type="text" value="<?=$news_vote[4]?>">
              Ʊ�� 
              <input name="cs2_num" type="text"  value="<?=$news_vote[9]?>" size="5"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ�� 
              3 </td>
            <td bgcolor="#F7FCDA"><input name="cs3" type="text" value="<?=$news_vote[5]?>">
              Ʊ�� 
              <input name="cs3_num" type="text"  value="<?=$news_vote[10]?>" size="5"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ�� 
              4 </td>
            <td bgcolor="#F7FCDA"><input name="cs4" type="text" value="<?=$news_vote[6]?>">
              Ʊ�� 
              <input name="cs4_num" type="text"  value="<?=$news_vote[11]?>" size="5"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ�� 
              5 </td>
            <td bgcolor="#F7FCDA"><input name="cs5" type="text" value="<?=$news_vote[7]?>">
              Ʊ�� 
              <input name="cs5_num" type="text"  value="<?=$news_vote[12]?>" size="5"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">������ɫ</td>
            <td bgcolor="#F7FCDA"><input name="bg_color" type="text" id="bg_color" value="<?=$news_vote[14]?>">
              ��EEEEEE ���ü�&quot;<font color="#FF3300">#</font>&quot;</td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">������ɫ</td>
            <td bgcolor="#F7FCDA"><input name="word_color" type="text" id="word_color" value="<?=$news_vote[15]?>">
              ��000000 ���ü�&quot;<font color="#FF3300">#</font>&quot;</td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">���ִ�С</td>
            <td bgcolor="#F7FCDA"><input name="word_size" type="text" id="word_size" value="<?=$news_vote[16]?>">
              ��λpx �磺����12px <span style="font-size:14px">����14px</span> </td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC" align=center colspan=2><input type="submit" name="Submit" value="�޸ı���ͶƱ"></td>
          </tr>
        </form>
      </table></td>
  </tr>
<?
include './tpl/footer.php';
?>
