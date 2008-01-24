<?php
//xna
set_time_limit(0);
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/inc/functions.php";

$is_run = $_COOKIE["is_run"];
if($is_run!='yes'){
	//这句话没用？
	//$is_run='no';
	setcookie("is_run","yes");
}

$cate = is_string($_GET[cate]) ? $_GET[cate]:"";
// 增加sql注入过滤函数
$cate = sql_injection($cate);
if (!empty($cate)){
	$sql = "select cid,cate_title from {$tablepre}xna_category where cate_title='" . $cate . "'";
	$rt = $PlusDB->execute($sql);
	while ($row = $rt->fetchRow()){
		$id = $row["cid"];
		$types = $row["cate_title"];
	}
}
if (eregi('^[a-zA-Z]+$', $cate)){
    $arg = $cate > "" ? " and x.rss_cate='$id' ":"";
}else{
    $arg .= "";
}
$cate_ids = implode(",", array_keys($rss_cate));

$days = is_numeric($_GET["days"]) ? $_GET["days"]:"";
if (!empty($days)){
    $xtime = time() - $days * 60 * 60 * 24;
    $xtime = strtotime(date("Y-m-d", $xtime));
    $arg .= " and n.news_ctime>='$xtime' ";
}else{
    $arg .= "";
}

$hot = $_GET["hot"];
$hot = sql_injection($hot);
if (!empty($hot)){
    $hots .= " n.news_vote desc,";
}else{
    $hots .= "";
}

$sql = "select count(*) as sum ,rss_cate from {$tablepre}xna_site where rss_cate in ($cate_ids) group by rss_cate ";
$rt = $PlusDB->execute($sql);
while ($crow = $rt->fetchRow()){
    $crow[id] = $crow[rss_cate];
    $crow[name] = $rss_cate[$crow[rss_cate]];
    $crow[sum] = $crow[sum];
    $cates[] = $crow;
}

$site = is_numeric($_GET[site]) ? $_GET[site]:"";
if (!empty($site)){
    $arg .= $site > "" ? "  and site_id=$site ":"";
}else{
    $arg .= "";
}

RunTime_Begin();
$start = $_GET["start"];
$start = empty($start)?0:$start;
$sql="select count(*)  from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid=n.site_id) where 1 $arg";
$num = $PlusDB->getone($sql);
$start = ($start>$num)?0:$start;

$page = 20;
include _ROOT."/inc/page.php";
$sql = "select *,n.news_ctime as ctime,n.id as id  from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid=n.site_id) where site_audit=0 and n.news_state<2 $arg order by $hots n.news_state desc,n.news_ctime desc ";
$rt = $PlusDB->SelectLimit($sql, $page, $start);
//echo $sql;
while ($row = $rt->fetchRow()){
    $row[title] = $row["news_title"];
    $row[content] = msubstr(strip_tags($row["news_content"]),0,200)."...";
    $row[ctime] = date("Y-m-d H:m:s",$row["news_ctime"]);
    $n++;
    $row["n"] = $n;
    $ret[] = $row;
}
RunTime_End();
$sql = "select * from {$tablepre}xna_tags ";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
    $tags[] = $row;
}

$sql = "select lid,link_title,link_url from {$tablepre}xna_links order by lid desc";
$rt = $PlusDB->selectLimit($sql, 10, 0);
while ($row = $rt->fetchRow()){
    $row[name] = $row[name];
    $row[links] = $row[links];
    $link[] = $row;
}

$sql = "select id,news_title,news_vote from {$tablepre}xna_news order by news_vote desc";
$rt = $PlusDB->selectLimit($sql, 10, 0);
while ($row = $rt->fetchRow()){
    $topics[] = $row;
}
//comments
$sql = "select *,n.id as  id,n.news_title as  news_title from {$tablepre}xna_comment x  left join {$tablepre}xna_news n on (x.news_id=n.id) order by  x.user_ctime desc";
$rt = $PlusDB->SelectLimit($sql, 10, 0);
while ($row = $rt->fetchRow()){
	$comm[] = $row;
}

//RSS type
if (!empty($cate)){
    $type = "" . $cate . "";
}else{
    $type = "";
}
//end
echo rewrite() ;
define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("index.html");
?>