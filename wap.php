<?php
//xna wap
DEFINE("_ROOT",".");
include  _ROOT."/mainfile.php";
include _ROOT."/inc/functions.php";

$start = $_GET["start"];
$start = empty($start)?0:$start;
$sql = "select count(*) from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid=n.site_id) where 1 $arg";

$num = $PlusDB->getone($sql);
$start = ($start>$num)?0:$start;
$page = 10;
include _ROOT."/inc/page.php";
$id= is_numeric( $_GET[id ])  ? $_GET[id] : "";
if(empty($id)){
$sql="select *,n.news_ctime as ctime,n.id as id  from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid=n.site_id) where site_audit=0  order by n.news_ctime desc";
}else{
$sql="select * from {$tablepre}xna_news where id=$id";
}
$rt = $PlusDB->SelectLimit($sql, $page, $start);
while ($row = $rt->fetchRow()){
	$row[news_title] = strip_tags($row["news_title"]);
	$row[news_content] = strip_tags($row["news_content"]);
	$ret[]=$row;
}
header("Content-type: text/vnd.wap.wml; charset=utf-8");
define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("wap.html");
?>