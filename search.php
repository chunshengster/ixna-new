<?php
//xna search
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/inc/functions.php";

$s = $_GET[s];
if (!empty($s)){
    $keyword = "LIKE '%$s%'";
}else{
    $keyword = "";
}

$a = $_GET[a];
if ($a == "where author"){
    $area = "and n.site_name";
}elseif ($a == "content"){
    $area = "and n.news_content";
}else{
    $area = "and n.news_title";
}
$start = $_GET["start"];
$start = empty($start) ? 0:$start;
$sql = "select count(*)  from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid=n.site_id) where 1 $arg";

$num = $PlusDB->getone($sql);
$start = ($start > $num) ? 0:$start;
$page = 32;
include _ROOT."/inc/page.php";
$sql = "select n.id,n.site_id,n.news_url,n.news_title,n.site_name,n.news_ctime,x.site_url as ctime,n.id as id2 from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid=n.site_id) where site_audit=0 and n.news_state<2 $area  $keyword order by n.news_ctime desc";
$rt = $PlusDB->SelectLimit($sql, $page, $start);
while ($row = $rt->fetchRow()){
    $row["rss_cate"] = $site_types[$row["rss_cate"]];
    $row[sum] = $xnum[$row[id]];
    $ret[] = $row;
}

define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("search.html");
?>