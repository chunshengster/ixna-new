<?
include './tpl/header.php';
?>
<table width="720" border="0" cellspacing="2" cellpadding="0">
  <tr> 
    <td style="border:1px solid #666666;padding:3px;"><table width="720" border="0" cellspacing="0" cellpadding="5">
        <form name="form1" method="post" action="">
          <tr> 
            <td width="100" rowspan="12" bgcolor="#8FE9FC">&nbsp;</td>
            <td colspan="2"><div align="center" class="tl">添加新的投票项目</div></td>
            <td width="100" rowspan="12" bgcolor="#8FE9FC">&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#FCFCFC">投票类型</td>
            <td bgcolor="#F7FCDA"><input name="sorm" type="radio" value="0" <?=$checkfalse?>>
            单选
              <input type="radio" name="sorm" value="1" <?=$checktrue?>>
            多选</td>
          </tr>
          <tr> 
            <td width="70" bgcolor="#FCFCFC">投票议题</td>
            <td width="550" bgcolor="#F7FCDA"><input name="news_voteco" type="text" id="news_voteco" size="35">
              <?=$err['news_voteco']?"<font color=red>{$err['news_voteco']}</font>":''?>为了显示美观，请注意字数</td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">选项1</td>
            <td bgcolor="#F7FCDA"><input name="cs1" type="text" value="<?=$cs1?>"><?=$err['news_votenews_count']?"<font color=red>{$err['news_votenews_count']}</font>":''?></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">选项2</td>
            <td bgcolor="#F7FCDA"><input name="cs2" type="text" value="<?=$cs2?>"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">选项3</td>
            <td bgcolor="#F7FCDA"><input name="cs3" type="text" value="<?=$cs3?>"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">选项4</td>
            <td bgcolor="#F7FCDA"><input name="cs4" type="text" value="<?=$cs4?>"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">选项5</td>
            <td bgcolor="#F7FCDA"><input name="cs5" type="text" value="<?=$cs5?>"></td>
          </tr>
          
          <tr> 
            <td bgcolor="#FCFCFC">背景颜色</td>
            <td bgcolor="#F7FCDA"><input name="bg_color" type="text" id="bg_color" value="<?=$bg_color?>">
              如EEEEEE 不用加&quot;<font color="#FF3300">#</font>&quot; <font color="#FF3300">可以不填，默认是EEEEEE</font></td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">文字颜色</td>
            <td bgcolor="#F7FCDA"><input name="word_color" type="text" id="word_color" value="<?=$word_color?>">
              如000000 不用加&quot;<font color="#FF3300">#</font>&quot; <font color="#FF3300">可以不填，默认是000000</font></td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">文字大小</td>
            <td bgcolor="#F7FCDA"><input name="word_size" type="text" id="word_size" value="<?=$word_size?>">
              单位px 如：这是12px <span style="font-size:14px">这是14px</span> <font color="#FF3300">可以不填</font></td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">&nbsp;</td>
            <td bgcolor="#F7FCDA"><input type="submit" name="Submit" value="发表本次投票"></td>
          </tr>
        </form>
      </table></td>
  </tr>
<?
include './tpl/footer.php';
?>