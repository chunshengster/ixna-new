<?php
      	$skin = "default";	//skin:default,bxna,digg
      	$rssread = "magpierss";	//rssread:lastrss,simplepie,magpierss
      	$rssnumber = "50";		//
      	$comments = "true";	//comments:false,true
      	$rewrite = "false";	//rewrite:false,true
      	$ispages = "ajax";		//rewrite:ajax,html,other
      	$language = "zh-cn";	//language:zh-cn,zh-tw,en
      	
if ($rewrite == 'true'){
    $days_links = "./days/1days";
    $cate_links = "./";
    $rss_links = "./rss/";
    $atom_links = "./atom/";
    $articles_links = "./articles/";
    $site_links = "./site/";
}else{
    $days_links = "./?days=1";
    $cate_links = "./?cate=";
    $rss_links = "./rss.php?type=";
    $atom_links = "./atom.php?type=";
    $articles_links = "./articles.php?id=";
    $site_links = "./?site=";
}
?>