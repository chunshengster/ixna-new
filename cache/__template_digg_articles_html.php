<?php
//Nemo Cache @ 2008-01-24 19:10:18
include_once template("header.html",'./template/digg/','','','');
echo '
<div id="contents">
<div id="wrapper">
<div class="news-full" id="enclosure">
<div class="news-body">
<h3 id="title">
'.$news['news_title'].'
</h3>
<p id="news_author"><span><a href="'.$site_links.$news['site_id'].'">'.$news['site_name'].'</a> 发布于'.$news['ctime'].' | '.$news['news_hits'].'次阅读 | 字体:<a href="javascript:fontZoomB();">大</a>  <a href="javascript:fontZoomA();">小</a>  <a href="./print.php?id='.$news['id'].'">打印预览</a></span></p><br />
<div id="news_content">'.$news['news_content'].'</div><br />本文出处 : <a href="'.$news['news_url'].'" rel="external" title = "'.$news['news_title'].'">'.$news['news_url'].'</a> <img src="./images/default/end.gif" alt="end" />
<br />';
if ($news['pre_id']!='') {
echo '<p>
上一篇:<a href="'.$articles_links.$news['pre_id'].'">'.$news['pre_title'].'</a></p>
';
}
if ($news['back_id']!='') {
echo '<p>
下一篇:<a href="'.$articles_links.$news['back_id'].'">'.$news['back_title'].'</a></p>
';
}
echo '
</div>
<ul class="news-digg">
  <li class="digg-count" id="main'.$news['id'].'">
   <h2 id="diggs-strong-'.$news['id'].'">'.$news['news_vote'].'</h2>
   <span id="e'.$news['id'].'"><a href="javascript:void(0);" onclick="javascript:NewsVote('.$news['id'].');" id="diggs'.$news['id'].'">digg it</a></span>
  </li>
 </ul>
</div>
';
if ($comments=="true") {
echo '
<div class="comment">
<div class="subTitle">现在评论本文</div>
<div class="Content">
<script type="text/javascript" src="./ajax/tformvalid.js"></script>
<script type="text/javascript">
window.onload=function() {
    var oValid = new TFormValid("addcomm");
    oValid.addRule("nowname", "NotNull", "请输入您的大名");
    oValid.addRule("comment", "NotNull", "请填写评论后再发表");
    oValid.addRule("comment", "Length", "评论字数超过限制");
    oValid.Listen();
};
</script>
<div id="info"></div>
<form id="addcomm" name="addcomm" action="./comment.php" method="post"><p>
<input id="id" type="hidden" name="id" value="'.$news['id'].'" />
<div class="Content">
<div class="row"><label>您的大名:</label><input class="input" id="nowname" name="nowname" type="text" /></div>
<div class="row"><label>您的主页:</label><input class="input" name="nowpage" type="text" /></div>
<div class="row"><label>您的邮箱:</label><input class="input" name="nowemail" id="nowemail" type="text" /></div>
<div class="row"><label>您的评论:</label><textarea id="comment" name="comment" cols="85" rows="5"></textarea></div>
<div class="row"><label>　验证码:</label><input name="authnum" class="txtinput" id="authnum" style="width: 80px;" maxlength="16" /> <img src=\'./inc/authnum.php\' onclick="this.src=\'./inc/authnum.php?r=\'+Math.random()" alt="authnum" /></div>
<div class="row" id="bottom0" style="text-align: center;">
<input type="hidden" name="refer" value="refer" />
<input type="button" onclick="e_comm();" value="提交" />
</p></form>
</div>
</div>
<div id="normal">
<div class="subTitle">'.$news['news_count'].'  条评论</div>
';
if(is_array($comm)) foreach ($comm as $key => $comm) {
echo '
<div id="n_content'.$comm['n'].'">
<dl>
<dt class="re_author"><span><strong>'.$comm['n'].' 楼</strong> ';
if ($comm['user_name']=='') {
echo '匿名人士 ';
} else {
echo $comm['user_name'].'';
}
echo ' 发表于'.$comm['user_ctime'].' </span></dt>
<dd class="quotecomment"></dd>
<dd class="re_detail">'.$comm['comm_content'].'</dd>
<dd class="re_mark"><span class="mark">
<a href="javascript:void(0);" onclick="javascript:ReplyVote(\''.$comm['cid'].'\',\'support\');">支持</a>(<span id="s'.$comm['cid'].'">'.$comm['support'].'</span>) <a href="javascript:void(0);" onclick="javascript:ReplyVote(\''.$comm['cid'].'\',\'against\');">反对</a>(<span id="a'.$comm['cid'].'">'.$comm['against'].'</span>)<div id="info'.$comm['cid'].'"></div>
</span>
</dd>
 </dl>
</div>
';
}
echo '
</div>
</div>
</div>
';
}
echo '
</div>
</div>
<br style="clear: both;" />
';
include_once template("footer.html",'./template/digg/','','','');
?>