<?php
DEFINE("_ROOT", "..");
include _ROOT . "/mainfile.php";

$iID = is_numeric($_GET["id"]) ? $_GET["id"]:"";
$sTag=$_POST["txtTag"];

	$sql="INSERT INTO  {$tablepre}xna_tags (tags,newsid) VALUES('".$sTag."','".$iID."')";
	$rt = $PlusDB->execute($sql);
	echo $sql;
?>