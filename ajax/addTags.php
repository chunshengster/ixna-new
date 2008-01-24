<?php
DEFINE("_ROOT", "..");
include _ROOT . "/mainfile.php";

$iID = is_numeric($_GET["id"]) ? $_GET["id"]:"";
$sTag = $_POST["txtTag"];
if(!empty($iID) && !empty($sTag)){
	$sTag = addslashes($sTag);
	$sql="INSERT INTO  {$tablepre}xna_tags (tag_title,news_id) VALUES('$sTag','$iID')";
	$rt = $PlusDB->execute($sql);
	if($rt == 1){
		echo $sTag;
	}else{
		echo $rt;
	}
}else {
	echo "非法数据或为空";
}
?>