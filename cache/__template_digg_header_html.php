<?php
//Nemo Cache @ 2008-01-24 19:22:38
echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="'.$WebPath.'" />
<title>'.$news['title'].'Spvrk XML News Aggregator</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-language" content="zh-CN" />
<meta name="author" content="spvrk@spvrk.com" />
<meta name="Copyright" content="www.ixna.net,Attribution-NonCommercial" />
<meta http-equiv="Generator" content="IXNA" />
<meta name="keywords" content="aggregator, Spvrk, xml, news, xhtml, css, web standards, aggregate" />
<meta name="content" content="Spvrk XML News Aggregator. Aggregates Your Ideas." />
<meta http-equiv="Page-Exit" content="blendTrans(Duration=0.5)" />
<meta name="verify-v1" content="i2jvLT9bkBihKPSmWYHkXTKWerNW2ROYVmhnive7F9Q=" />
<meta name="robots" content="all" />
<link href="./images/digg/xna.css" rel="stylesheet" type="text/css"/>
<link rel=\'alternate\' type=\'application/rss+xml\' title=\'RSS 2.0\' href=\'./rss/\' />
<link rel=\'alternate\' type=\'application/atom+xml\' title=\'ATOM 1.0\' href=\'./atom/\' />
<link rel=\'alternate\' type=\'application/rss+xml\' title=\'RDF 1.0\' href=\'./rdf.xml\' />
<link rel="icon" href="./favicon.ico" type="image/x-icon" />
';
if ($is_run=="no") {
echo '
<script type="text/javascript">
var Inttime=10000;
</script>
<script src="./ajax/autoupdate.js" type="text/javascript"></script>
';
}
echo '
<script src="./ajax/ajax.js" type="text/javascript"></script>
<script src="./ajax/common.js" type="text/javascript"></script>
</head>
<body>
<div id="container">
<div id="header">
<h1><a href="/">BXNA</a></h1>
<div class="side-header"><a href="./">Home</a> <a href="'.$days_links.'">1Days</a> <a href="./feed/">My Feeds</a>  <a href="./site.php">Site</a> <a href="./submit.php">Submit</a> <a href="http://zxsv.com/post/241.html" rel="external">Down</a><form method="get" action="search.php" id="search" onsubmit="topsearch()"><p>
<input type="hidden" name="section" value="news" />
<input type="text" name="s" id="top-keywords" value="" />
<input type="image" id="top-submit" src="./images/digg/search.gif" alt="Search" />
</p></form>
</div><div id="header-primary" class="menu-single">
<ul>
<li><a href="./" title="All" class="navLight">All</a></li>
';
if(is_array($cates)) foreach ($cates as $key => $cate) {
echo '
<li><a href="'.$cate_links.$cate['name'].'" title="'.$cate['sum'].'"  class="navLight">'.$cate['name'].'</a></li>
';
}
echo '
<li><a href="'.$rss_links.$type.'" title="RSS 2.0" rel="external"><img src="./images/digg/rss.gif" class="img" alt="rss" /></a></li><li><a href="'.$atom_links.$type.'" class="img" title="ATOM 1.0" rel="external"><img src="./images/digg/atom.gif" class="img" alt="atom" /></a></li>
</ul>
</div>
</div>
';
?>