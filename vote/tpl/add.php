<?
include './tpl/header.php';
?>
<table width="720" border="0" cellspacing="2" cellpadding="0">
  <tr> 
    <td style="border:1px solid #666666;padding:3px;"><table width="720" border="0" cellspacing="0" cellpadding="5">
        <form name="form1" method="post" action="">
          <tr> 
            <td width="100" rowspan="12" bgcolor="#8FE9FC">&nbsp;</td>
            <td colspan="2"><div align="center" class="tl">����µ�ͶƱ��Ŀ</div></td>
            <td width="100" rowspan="12" bgcolor="#8FE9FC">&nbsp;</td>
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
            <td width="550" bgcolor="#F7FCDA"><input name="news_voteco" type="text" id="news_voteco" size="35">
              <?=$err['news_voteco']?"<font color=red>{$err['news_voteco']}</font>":''?>Ϊ����ʾ���ۣ���ע������</td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ��1</td>
            <td bgcolor="#F7FCDA"><input name="cs1" type="text" value="<?=$cs1?>"><?=$err['news_votenews_count']?"<font color=red>{$err['news_votenews_count']}</font>":''?></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ��2</td>
            <td bgcolor="#F7FCDA"><input name="cs2" type="text" value="<?=$cs2?>"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ��3</td>
            <td bgcolor="#F7FCDA"><input name="cs3" type="text" value="<?=$cs3?>"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ��4</td>
            <td bgcolor="#F7FCDA"><input name="cs4" type="text" value="<?=$cs4?>"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">ѡ��5</td>
            <td bgcolor="#F7FCDA"><input name="cs5" type="text" value="<?=$cs5?>"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">������ɫ</td>
            <td bgcolor="#F7FCDA"><input name="bg_color" type="text" id="bg_color" value="<?=$bg_color?>">
              ��EEEEEE ���ü�&quot;<font color="#FF3300">#</font>&quot; <font color="#FF3300">���Բ��Ĭ����EEEEEE</font></td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">������ɫ</td>
            <td bgcolor="#F7FCDA"><input name="word_color" type="text" id="word_color" value="<?=$word_color?>">
              ��000000 ���ü�&quot;<font color="#FF3300">#</font>&quot; <font color="#FF3300">���Բ��Ĭ����000000</font></td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">���ִ�С</td>
            <td bgcolor="#F7FCDA"><input name="word_size" type="text" id="word_size" value="<?=$word_size?>">
              ��λpx �磺����12px <span style="font-size:14px">����14px</span> <font color="#FF3300">���Բ���</font></td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">&nbsp;</td>
            <td bgcolor="#F7FCDA"><input type="submit" name="Submit" value="������ͶƱ"></td>
          </tr>
        </form>
      </table></td>
  </tr>
<?
include './tpl/footer.php';
?>