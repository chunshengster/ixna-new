<?php
define("_ROOT", "..");
include _ROOT . "/mainfile.php";
include _ROOT . "/lang/$language/global.php";
$id = is_numeric($_GET["id"]) ? $_GET["id"]:"";
if (!empty($id))
{
$sql = "select news_content from {$tablepre}xna_news where id='$id' ";
$content = $PlusDB->getone($sql);
//$name="newscontent".$id;
echo $content;
$sql = "update {$tablepre}xna_news set hits=hits + 1 where id=$id";
$rt = $PlusDB->execute($sql);
}
?>