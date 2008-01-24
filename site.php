<?php
//xna site
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/inc/functions.php";

$sql = "select count(*) as sum ,site_id from {$tablepre}xna_news group by site_id ";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
    $xnum[$row[site_id]] = $row[sum];
}

$sql = "select x.sid,x.site_title,x.site_url,x.rss_url,x.rss_cate,x.site_audit,x.site_content,n.news_ctime as ctime from (select max(news_ctime) as news_ctime,site_id from  {$tablepre}xna_news group by site_id) n inner join {$tablepre}xna_site x on (x.sid=n.site_id) where  site_audit=0 order by rss_cate,site_ctime desc";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
    $row["rss_cate"] = $rss_cate[$row["rss_cate"]];
	$row[ctime] = date("Y-m-d H:m:s",$row["site_utime"]);
    $row[sum] = $xnum[$row[sid]];
    $ret[] = $row;
}

define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("site.html");
?>