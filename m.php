<?php
//xna
set_time_limit(0);
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/inc/functions.php";

$sql = "select *,n.news_ctime as ctime,n.id as id  from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid=n.site_id) where site_audit=0 and n.news_state<2 order by n.news_state desc,n.news_ctime desc ";
$rt = $PlusDB->SelectLimit($sql, 100, $start);
//echo $sql;
while ($row = $rt->fetchRow()){
    $row[title] = $row["news_title"];
    $ret[] = $row;
}

define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("m.html");
?>