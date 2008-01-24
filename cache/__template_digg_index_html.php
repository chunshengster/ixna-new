<?php
//Nemo Cache @ 2008-01-24 09:05:12
include_once template("header.html",'./template/digg/','','','');
echo '
<div id="contents">
<div id="wrapper">
<div class="extra-nav">
<h2>All News</h2>
<ul>
<li><a href="./hot/">Hot</a></li>
<li class="active"><span>New</span></li>
</ul>
</div>
<div class="sub-menu">
<span class="tool"><strong>Newly Popular</strong></span>
<a href="'.$days_links.'" class="tool">Top in 1 Days</a>
<a href="./days/7days" class="tool">7 Days</a>
<a href="./days/30days" class="tool">30 Days</a>
<a href="./days/365days" class="tool">365 Days</a>
</div>
<div class="sidebar">
<div class="side-container">
<h2>Photo News</h2>
<p class="first intro"></p>
<p class="join">
<a href="./submit.php">Join</a>
<a href="./feed/">My Feeds</a>
<a href="./about" class="learn">Abouts...</a></p>
</div>
<div class="side-container">
<h2>Top 10 in All Topics</h2>
';
if(is_array($topics)) foreach ($topics as $key => $topics) {
echo '
<div class="news-summary">
<h3><a href="'.$articles_links.$topics['id'].'">'.$topics['news_title'].'</a></h3>
<ul class="news-digg">
<li class="digg-count"><a href="'.$articles_links.$topics['id'].'"><strong>'.$topics['news_vote'].'</strong></a></li>
</ul>
</div>
';
}
echo '
</div>
<div class="side-container">
<h2>Comments</h2>
';
if(is_array($comm)) foreach ($comm as $key => $comm) {
echo '
<div class="news-comm">
<ul>
<li>'.$comm['comm_content'].'</li>
<li>'.$comm['user_name'].' <a href="'.$articles_links.$comm['id'].'"><strong>'.$comm['news_title'].'</strong></a></li>
</ul>
</div>
';
}
echo '
</div>
<div class="side-container">
<h2>Tags</h2>
</div>
<div class="side-container">
<h2>Site Vote</h2>
<object type="application/x-shockwave-flash" data="./vote/flashvote.swf" width="180" height="220">
<param name="movie" value="./vote/flashvote.swf" />
<a href="./vote/flashvote.swf"><img src="./vote/flashvote.jpg" alt="Flash" /></a>
</object>
</div>
<div class="side-container">
<h2>Site Links</h2>
<ul>
';
if(is_array($link)) foreach ($link as $key => $link) {
echo '
<li><a href="'.$link['link_url'].'" class="navBlack" rel="external">'.$link['link_title'].'</a></li>
';
}
echo '<li><a href="wap.php" class="navBlack" rel="external">Wap</a></li></ul>
</div></div>
<div id="m_Body">
<div class="pages"><span class="nextprev">'.$total.'</span>
';
if ($ispages==ajax) {
echo $pageajax.'';
} elseif ($ispages==html) {
echo $pagehtml.'';
} else {
echo $pagenav.'';
}
echo '
</div>
<div class="main">
';
if(is_array($ret)) foreach ($ret as $key => $list) {
echo '
<div class="news-summary" id="enclosure'.$list['id'].'">
<div class="news-body">
<h3 id="title'.$list['id'].'"><a href="'.$articles_links.$list['id'].'">
'.$list['news_title'].'</a></h3>
<p class="news-submitted">
<a href="'.$site_links.$list['site_id'].'">';
if ($list['news_state']=="1") {
echo '<img src="./images/default/peak.png" style="height:16px" class="img" alt="peak" />';
} else {
echo '<img src="./cache/ico/'.$list['site_id'].'_favicon.ico" style="height:16px" class="img" alt="articles" />';
}
echo '</a>
<a href="'.$list['news_url'].'" onclick="javascript:link('.$list['id'].');" class="simple" rel="external"><span class="d">[ '.$list['site_name'].' ]</span></a> Time:'.$list['ctime'].' Hits:'.$list['news_hits'].'</p>
<p>'.$list['content'].'</p>
	<div id="tag_message_'.$list['id'].'">Tags: <a style="cursor:pointer" onclick="javascript:showtags(\'Tags_'.$list['id'].'\');" id="addTags_'.$list['id'].'">[+]</a> add</div>
	<div id="Tags_'.$list['id'].'" style="display:none;">
	<form id="frmEditTag'.$list['id'].'" action=""><p>
        <input type="text" id="txtTag'.$list['id'].'" />
   	    <input type="button" name="btnAction" onclick="javascript:submitTag(\''.$list['id'].'\');" value="go" />
    </p></form>
    </div>
<div class="news-details">
<a href="'.$articles_links.$list['id'].'" class="tool comments">'.$list['news_count'].' comments</a> 
<ul class="probdrop">
<li><a href="#" onclick="" class="toplinep">Bury</a></li>
</ul>
</div>
</div>
<ul class="news-digg">
  <li class="digg-count" id="main'.$list['id'].'">
   <h2 id="diggs-strong-'.$list['id'].'">'.$list['news_vote'].'</h2>
   <span id="e'.$list['id'].'"><a href="javascript:void(0);" onclick="javascript:NewsVote('.$list['id'].');" id="diggs'.$list['id'].'">digg it</a></span>
  </li>
 </ul>
</div>
';
}
echo '
</div>
<div class="pages"><span class="nextprev">'.$total.'</span>
';
if ($ispages==ajax) {
echo $pageajax.'';
} elseif ($ispages==html) {
echo $pagehtml.'';
} else {
echo $pagenav.'';
}
echo '
</div>
</div>
</div>
</div>
<br style="clear: both;" />
';
include_once template("footer.html",'./template/digg/','','','');
?>