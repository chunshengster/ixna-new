<?php
//xna artilces
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/inc/functions.php";
$id = $_GET[id];
if (!is_numeric($id)){
  echo "Error id";
  return false;
}
$sql = "select * from {$tablepre}xna_news where id=$id";
$news = $PlusDB->getrow($sql);
if ($news){
  $news[title] = "" . $news["news_title"] . " - ";
  $news[sum] = $xnum[$news[id]];
  $news[hist] = $news[hist];
  $news[ctime] = date("Y-m-d H:m:s",$news["news_ctime"]);
} else{
  echo "Error id";exit;
}
$sql = "update {$tablepre}xna_news set news_hits=news_hits + 1 where id=$id";
$rt = $PlusDB->execute($sql);

define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("print.html");
?>