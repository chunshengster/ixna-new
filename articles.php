<?php
//xna artilces
session_start();
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/inc/functions.php";

$id = is_numeric($_GET[id]) ? $_GET[id] : "";
if (!$id){
  echo "Error id";
  return false;
}
RunTime_Begin();
$cate_ids = implode(",", array_keys($rss_cate));
$sql = "select count(*) as sum ,rss_cate from {$tablepre}xna_site where rss_cate in ($cate_ids) group by rss_cate ";
$rt = $PlusDB->execute($sql);
while ($crow = $rt->fetchRow()){
    $crow[id] = $crow[rss_cate];
    $crow[name] = $rss_cate[$crow[rss_cate]];
    $crow[sum] = $crow[sum];
    $cates[] = $crow;
}

$sql = "select * from {$tablepre}xna_news where news_state<2 and id=$id";
$news = $PlusDB->getrow($sql);
if ($news){
  $news[title] = "" . $news["news_title"] . " - ";
  $news[ctime] = date("Y-m-d H:i:s",$news["news_ctime"]);
  $news[sum] = $xnum[$news[id]];
  $news[hist] = $news[hist];
}else{
  echo "Error id";
  exit;
}
$sql = "update {$tablepre}xna_news set news_hits=news_hits + 1 where id=$id";
$rt = $PlusDB->execute($sql);

$sql = "select id,news_title from {$tablepre}xna_news  where news_state<2 and id>$id order by id ASC limit 1";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
  $news["back_id"] = $row["id"];
  $news["back_title"] = $row["news_title"];
}

$sql = "select id,news_title from {$tablepre}xna_news where news_state<2 and id<$id order by id desc limit 1";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
  $news["pre_id"] = $row["id"];
  $news["pre_title"] = $row["news_title"];
}
RunTime_End();
$sql = "select count(*) as sum from {$tablepre}xna_comment where news_id=$id ";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
  $sum = $row[sum];
}

$sql = "select * from {$tablepre}xna_comment where news_id=$id order by cid ASC";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
  $n++;
  $row["n"] = $n;
  $row[user_ctime] = date("Y-m-d H:i:s",$row["user_ctime"]);
  $comm[] = $row;
}
define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("articles.html");
?>