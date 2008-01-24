<?php
//xna subnit
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/lang/$language/global.php";
include _ROOT . "/inc/functions.php";

$action = $_REQUEST["action"];
if ($_POST['refer'] == 'refer'){
	session_start();
    if($_SESSION["verifyCode"] != $_POST['authnum']) {
        echo "" . _LANG_1112 . "";
        return false;
    }
    if (empty($_POST["title"]) || empty($_POST["site_url"]) || empty($_POST["rss_url"]) ||
        empty($_POST["icon"]) || empty($_POST["email"]) || empty($_POST["rss_cate"])) {
        echo "" . _LANG_0103 . "";exit;
    }
    $sql = "select rss_url from {$tablepre}xna_site where rss_url='" . $_POST["rss_url"] . "'";
    $rt = $PlusDB->execute($sql);
    while ($row = $rt->fetchRow())    {
        if ($row[rss_url] = $_POST["rss_url"] || empty($_POST["rss_url"])){
            echo "" . _LANG_0101 . "";exit;
        }
    }
    $site_title = $_POST["title"];
    $site_url = $_POST["site_url"];
    $site_rss_url = $_POST["rss_url"];
    $site_icon = $_POST["icon"];
    $site_rss_feq = $_POST["rss_feq"];
    $rss_cate = $_POST["rss_cate"];
    $site_audit = 1;
    $site_emai = $_POST["email"];
    $site_content = $_POST["content"];
    $site_ctime = strtotime(date("Y-m-d H:i:s"));
    $votetime = 24*1;
    if ($_COOKIE['submit'.$site_audit] == 'pubsubmit_'.$site_audit) {
		echo "" . _LANG_0104 . "";exit;
	} else {
		setcookie('submit'.$site_audit, 'pubsubmit_'.$site_audit, time() + $votetime*3600);
		$sql = "INSERT INTO {$tablepre}xna_site (site_title,site_url,rss_url,site_icon,rss_feq,rss_cate,site_audit,site_email,site_content,site_ctime)
			   VALUES
			  ('$site_title','$site_url','$site_rss_url','$site_icon','$site_rss_feq','$rss_cate','$site_audit','$site_emai','$site_content','$site_ctime')";
		$rt = $PlusDB->execute($sql);    
		if (!$rt){
			echo $PlusDB->errorMsg();
			echo "" . _LANG_1111 . "";exit;
		}else{
			echo "" . _LANG_0102 . "";exit;
		}
    }
}
define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("submit.html");
?>