<?
	include './tpl/header.php';
?>
<table width="720" border="0" cellspacing="2" cellpadding="0">
  <tr> 
    <td style="border:1px solid #666666;padding:3px;"><table width="100%" border="0" cellpadding="3" cellspacing="0">
        <tr> 
          <td bgcolor="#F3F3F3"><strong><font color="#FF6600">&gt;&gt;系统简介</font></strong></td>
        </tr>
        <tr> 
          <td><blockquote> 
              <br><p>
                Minenews_vote投票系统 是一个开放源程序的PHP投票系统，程序采用文本方式存储数据，无需数据库的支持。
                <p>运行环境：服务器端为支持PHP主机，非win系统需要将data.php设为777；客户端为6.0或者以上的Flash播放器。
                <p>系统演示：<a href="http://www.mecee.com" target="_blank">www.mecee.com</a>
                <p>技术支持：<a href="http://bbs.mecee.com" target="_blank">bbs.mecee.com</a>
                <p>注：该系统使用了ASP版公用投票系统的Flash及后台的界面方案<a href="http://news_vote.lygpc.com/" target="_blank"><span class=en>(news_vote.lygpc.com)</span></a>。在些对原作者表示感谢！
              </p>
            </blockquote></td>
        </tr>
        <tr> 
          <td bgcolor="#F3F3F3"><font color="#FF6600"><strong>&gt;&gt;界面说明</strong></font></td>
        </tr>
        <tr> 
          <td><blockquote> 
              <p><br>
                <img src="images/help/flash_interface.gif" width="240" height="355"></p>
            </blockquote></td>
        </tr>
        <tr> 
          <td bgcolor="#F3F3F3"><strong><font color="#FF6600">&gt;&gt;第一次使用？</font></strong></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"> 
            <blockquote><font color="#FF6600"><strong><br>
              进入后可以看到以下界面</strong></font><br>
              <br>
              <img src="images/help/control_interface1.gif" width="573" height="144"> 
              <br>
              <br>
              如果还不是很清楚，可以看以下的图标说明<br>
              <img src="images/dot.gif" alt="打开flash投票演示" width="22" height="21" align="absmiddle"> 
              打开flash投票演示 <img src="images/open.gif" alt="打开的投票" width="22" height="21" align="absmiddle">打开的投票 
              <img src="images/close.gif" alt="关闭的投票" width="22" height="21" align="absmiddle"> 
              关闭的投票 <img src="images/edit.gif" alt="编辑投票按钮" width="22" height="21" align="absmiddle">编辑投票按钮 
              <img src="images/del.gif" alt="删除投票按钮" width="22" height="21" align="absmiddle"> 
              删除投票按钮 <br>
              <br>
              <font color="#FF6600"><strong>下边开始添加我们的第一个投票 </strong></font><br>
              <br>
              <font color="#FF6600"><strong>添加投票</strong></font> <br>
              添加投票的时候注意依次添加投票选项，比如：你的选项是3个，那么就填写前3个，其他保持空白就可以，字数没有苛刻限制，当以显示效果为标准。<br>
              背景颜色和文字颜色以及字体大小等项目可以为空，系统将使用默认配置，当然制作自己的风格更好，但是注意配色等等。<br>
              <font color="#FF6600"><strong><br>
              修改投票</strong></font><br>
              如果你对你目前的投票项目某个方面不满意，你可以对投票项目进行修改，修改的时候，如果你要添加投票项目，直接增加项目就可以了，如果你要减少，同样，删除多余的项目就可以了<br>
              <br>
              <font color="#FF6600"><strong>打开/关闭投票</strong></font> <br>
              关于打开关闭功能，如果你有一个投票项目，现在不想进行投票，你可以将他关闭，等到合适的时间再打开<br>
              <br>
              好了，我们熟悉了后台的管理，下边的工作就是如何调用我们的投票了<br>
              <br>
              <font color="#FF6600"><strong>调用投票 </strong></font><br>
              我们可以先通过访问<font color="#FF6600">demo.htm</font>页面来看实际效果 或者点<img src="images/dot.gif" alt="打开flash投票演示" width="22" height="21" align="absmiddle">来查看当前的投票项目<br>
              这里调用非常简单，只需要调用根目录下的<font color="#FF6600">shownews_vote.js</font>就可以了，具体的调用方法是，在你需要插入投票系统的地方，插入这样一条语句 
              <font color="#FF6600"><br>
              &lt;script src=&quot;shownews_vote.js&quot;&gt;&lt;/script&gt;<br>
              </font>这样投票系统打开的就是最新的一个并且打开的投票项目。那么如果我需要指定打开某一个项目那怎么办呢？这个也非常简单，还是插入这样的语句，不同的是需要打开<font color="#FF6600">shownews_vote.js</font>这个页面，在<br>
              <font color="#FF6600">document.write(&quot; &lt;param name='movie' 
              value='flashnews_vote.swf'&gt;&quot;);<br>
              </font>这个语句中的<font color="#FF6600">flashnews_vote.swf</font>后加上<font color="#FF6600">?thisnews_voteid=你所要显示的投票项目的ID号</font>，如<br>
              <font color="#FF6600">document.write(&quot; &lt;param name='movie' 
              value='flashnews_vote.swf?thisnews_voteid=28'&gt;&quot;);<br>
              </font>这样就首先打开ID为28的投票项目。（项目的ID号可以在后台管理中看到）<br>
              如果你需要调用的flash在网页中背景透明，那么就把<font color="#FF6600">shownews_vote.js</font>页面中的第四行，这个语句<br>
              <font color="#FF6600">document.write(&quot; &lt;param name='wmode' 
              value='transparent'&gt;&quot;);</font><br>
              的注释去掉就可以了（去掉前边的两个<font color="#FF6600">//</font>）<br>
              <br>
              <font color="#FF6600"><strong>恭喜你，一个属于你自己的并且拥有你的风格的投票项目完成。</strong></font></blockquote></td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td bgcolor="#F3F3F3"><strong><font color="#FF6600">&gt;&gt;系统特点</font></strong></td>
        </tr>
        <tr> 
          <td><blockquote> 
              <p><br>
                1 自定义前台显示flash的主色调，文字颜色以及字体大小，打造自己的风格。<br>
                2 前台显示flash有相当大的弹性，所以对文字多少没有苛刻的限制。 <br>
                3 前台显示flash可以通过其中的翻页来查看所有开放的投票项目。<br>
                4 强大的后台管理功能。<br>
                5 前台显示flash调用非常简单，只需要调用一个js文件即可。<br>
                6 具有投票项目打开关闭的功能。<br>
                7 防止多次提交。<br>
            </blockquote></td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
<?
	include './tpl/footer.php';
?>