<?php
define("_ROOT", "..");
include _ROOT . "/mainfile.php";
include _ROOT . "/lang/$language/global.php";
$id = is_numeric($_GET["vote"]) ? $_GET["vote"]:"";
if (!empty($id))
{
//$sql = "select news_content from {$tablepre}xna_news where id='$id' ";
//$content = $PlusDB->getone($sql);
//$name="newscontent".$id;
//echo $content;
$sql = "update {$tablepre}xna_news set news_vote=news_vote+1 where id='$id'";
$rt = $PlusDB->execute($sql);
$sql = "select news_vote from {$tablepre}xna_news where id='$id' ";
$content = $PlusDB->getone($sql);
echo $content.'|'.'投票成功';
#echo $rt;
}
?>