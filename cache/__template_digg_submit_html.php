<?php
//Nemo Cache @ 2008-01-24 09:06:15
include_once template("header.html",'./template/digg/','','','');
echo '
<div id="m_Body">
<div id="wrapper">
<div class="news-full">
<strong class="categoryCaption">怎样加入 IXNA<br /></strong>
<dd>
<p class="message">内容健康,没有违法行为,长期提供技术信息的 Blog 或新闻站点,都可以在本页申请加入 Spvrk XML News Aggregator(IXNA)，申请加入时请注明您的站点及 XML 的 URL 地址,以及简短的描述,我们会对您的站点进行评价,并保留添加和删除贵站的权利，推荐使用<a href="http://www.feedsky.com/">feedsky</a>和<a href="http://www.feedburner.com/">feedburner</a>烧录站点</p>
<p class="message">请注意,为保证内容质量,同时也为了避免法律问题,我们不欢迎如下站点:<br /></p>
<ul class="message">
<li>使用免费域名或空间,经常性更改域名,无法确定长期能使用的站点</li>
<li>包含大量转载内容的站点</li>
<li>人云亦云,没有主见,没有个性的站点</li>
<li>只包含设计资源的设计类站点</li>
<li>只包含个人感情交流的站点</li>
<li>有侵权行为的站点,这些行为包括提供盗版信息、非法转载、严重抄袭、侵犯著作权等</li>
<li>刚开办不久或内容少于20篇的站点</li>
<li>无法在低带宽环境下正常浏览的站点,如果您的站点包括自动播放的视频/音频内容,我们建议您关闭它再申请加入IXNA</li>
<li>站点访问困难,或兼容性低下(以 Firefox 1.0 和 IE 6.0 能正常访问为底限)</li>
<li>包含大量政治言论、色情内容、大量赚钱广告的站点</li>
<li>传播迷信内容、提供大量占卜网站链接的站点</li>
<li>RSS XML ATOM的文档不标准,输出时间或时区有问题而无法聚合的站点</li>
</ul>
</dd>
<dt>
</div>
<script src="./ajax/tformvalid.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
window.onload=function() {
    var oValid = new TFormValid("addsite");
    oValid.addRule("title", "NotNull", "请输入站点名称");
    oValid.addRule("site_url", "URL", "非法的站点地址");
    oValid.addRule("rss_url", "URL", "非法的RSS地址");
    oValid.addRule("icon", "URL", "非法的图标地址");
    oValid.addRule("email", "Email", "非法的邮件地址");
    oValid.addRule("content", "Length", "简介字数超过限制");
    oValid.Listen();
};
</script>
<div id="info"></div>
<table style="height: 100%">
<form id="addsite" name="addsite" action="./submit.php" method="post">
<tr><td>站点名称</td><td><input id="title" name="title" size="70" maxlength="100"  type="text" /></td></tr>
<tr><td>站点地址</td><td><input id="site_url" name="site_url" size="70" maxlength="100"  type="text" /></td></tr>
<tr><td>RSS地址</td><td><input id="rss_url" name="rss_url" size="70" maxlength="100"  type="text" /></td></tr>
<tr><td>Favicon图标</td><td><input id="icon" name="icon" size="70" maxlength="100"  type="text" value="http://www.ixna.net/favicon.ico" /></td></tr>
<tr><td>email</td><td><input id="email" name="email" size="70" maxlength="100"  type="text" /></td></tr>
<tr><td>站点说明</td><td><textarea name="content" cols="85" rows="3"></textarea></td></tr>
<tr><td>RSS分类</td><td><select id="rss_cate" name="rss_cate">';
if(is_array($rss_cate)) foreach ($rss_cate as $_k_ => $_v_) echo '<option value="'.$_k_.'"'.(($_k_ == $selected || @in_array($_k_, $selected))?' selected class="tpl_select"':'').'>'.$_v_.'</option>';
echo '</select></td></tr>
<tr><td>更新频率</td><td><select id="rss_feq" name="rss_feq">';
if(is_array($rss_feq)) foreach ($rss_feq as $_k_ => $_v_) echo '<option value="'.$_k_.'"'.(($_k_ == $selected || @in_array($_k_, $selected))?' selected class="tpl_select"':'').'>'.$_v_.'</option>';
echo '</select> 分钟 </td></tr>
<tr><td>验证码</td><td><input name="authnum" class="txtinput" id="authnum" style="width: 80px;" maxlength="16" /><img src=\'./inc/authnum.php\' onClick="this.src=\'./inc/authnum.php?r=\'+Math.random()" /></td></tr>
<tr><td colspan="2" align="center">
<input type="hidden" name="refer" value="refer">
<!--<input type="submit" name="submit" value="提交">--->
<input type="button" onclick="e_postf();" value="提交" />&nbsp;&nbsp; 
<input name="reset" type="reset" value="重置" />
</td>
</form>
</table>
</div>
</div>
<br style="clear: both;" />
';
include_once template("footer.html",'./template/digg/','','','');
?>