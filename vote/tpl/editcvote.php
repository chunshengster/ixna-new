<?
include './tpl/header.php';
?>
<table width="720" border="0" cellspacing="2" cellpadding="0">
  <tr> 
    <td style="border:1px solid #666666;padding:3px;"><table width="720" border="0" cellspacing="0" cellpadding="5">
        <form name="form1" method="post" action="">
          <tr> 
            <td width="120" rowspan="10" bgcolor="#8FE9FC">&nbsp;</td>
            <td colspan="2"><div align="center" class="tl">修改登陆信息</div></td>
            <td width="170" rowspan="10" bgcolor="#8FE9FC">&nbsp;</td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">当前密码</td>
            <td bgcolor="#F7FCDA"><input name="password" type="password" id="password2">
              必须填写</td>
          </tr>
          <tr> 
            <td width="70" bgcolor="#FCFCFC">新用户名</td>
            <td width="360" bgcolor="#F7FCDA"><input name="username" type="text" id="username">
              必须填写</td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">新的密码</td>
            <td bgcolor="#F7FCDA"><input name="n_password" type="password" id="n_password">
              必须填写</td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">确认一下</td>
            <td bgcolor="#F7FCDA"><input name="a_n_password" type="password" id="a_n_password">
              必须填写 </td>
          </tr>
          <tr> 
            <td bgcolor="#FCFCFC">&nbsp; </td>
            <td bgcolor="#F7FCDA"><input type="submit" name="Submit" value="修改登陆信息"></td>
          </tr>
        </form>
      </table></td>
  </tr>
<?
include './tpl/footer.php';
?>
