<?php
//xna subnit
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/lang/$language/global.php";
include _ROOT . "/inc/functions.php";

$action = $_REQUEST["action"];

$SERVER_V1 = $_SERVER["HTTP_REFERER"];
$SERVER_V2 = $_SERVER["SERVER_NAME"];
$SERVER_V3 = $_SERVER["REMOTE_ADDR"];
$SERVER_V4 = $_SERVER["HTTP_USER_AGENT"];

if ($comments == 'true'){
    if ($_POST['refer'] == 'refer')    {
        $id = $_POST["id"];
        if (!is_numeric($id))        {
            echo "Error id";
            return false;
        }
        session_start();
		if($_SESSION["verifyCode"] != $_POST['authnum']) {
			echo "" . _LANG_1112 . "";
			return false;
		}
        if (empty($_POST["nowname"]) || empty($_POST["comment"])){
            echo "" . _LANG_0103 . "";
            exit;
        }

        /*
        评论字数限制
        */
        if ((strlen($_POST["comment"])) > 255) {
            echo "" . _LANG_0121 . "";
            exit;
            //return FALSE;
        }
        /*
        禁止外部提交数据
        */
        if (substr("$SERVER_V1", 7, strlen("$SERVER_V2")) <> $SERVER_V2) {
            echo "" . _LANG_0122 . "";
            exit;
            exit;
        }
        /*
        广告过滤关键字


        $search = array("六合彩","|免费[\s]*电影|","安利");
        $text = preg_replace($search,"11111",$text);
        return trim($text);
        */
        /*
        评论发表时间间隔
        */
        $xtime = strtotime(date("Y-m-d H:i:s", time() - 60));
        $sql = "select count( * ) from {$tablepre}xna_comment where news_id=$id and user_ip='$SERVER_V3' and user_ctime>'$xtime'";
        $num = $PlusDB->getone($sql);
        if ($num > 0){
            echo "" . _LANG_0124 . "";exit;
        }

        $comm_news_id = $id;
        $comm_user_name = $_POST["nowname"];
        $comm_user_url = $_POST["nowpage"];
        $comm_user_email = $_POST["nowemail"];
        $comm_user_content = $_POST["comment"];
        $comm_user_IP = $SERVER_V3;
        $comm_user_agent = $SERVER_V4;
        $comm_user_date = strtotime(date("Y-m-d H:i:s"));
		$sql = "INSERT INTO {$tablepre}xna_comment (news_id,user_name,user_url,user_email,comm_content,user_IP,user_agent,user_ctime)
		   VALUES
		  ('$comm_news_id','$comm_user_name','$comm_user_url','$comm_user_email','$comm_user_content','$comm_user_IP','$comm_user_agent','$comm_user_date')";
        $rt = $PlusDB->execute($sql);
        if ($rt){
            $sql = "update {$tablepre}xna_news set news_count=news_count + 1 where id=$id";
            $rt = $PlusDB->execute($sql);
            echo "" . _LANG_0125 . "";exit;
        }else{
            echo $PlusDB->errorMsg();
            echo "" . _LANG_1111 . "";exit;
        }
    }

    $support = is_numeric($_GET[support]) ? $_GET[support]:"";
    $against = is_numeric($_GET[against]) ? $_GET[against]:"";
    $news_votetime = 24*1;
    if (!empty($support)){
		if (!$_COOKIE['ReplyVote'.$support] == 'pubReplyVote_'.$support) {
		    setcookie('ReplyVote'.$support, 'pubReplyVote_'.$support, time() + $news_votetime*3600);
			$sql = "update {$tablepre}xna_comment set support=support + 1 where cid=$support";
			$rt = $PlusDB->execute($sql);
			$sql = "select support from {$tablepre}xna_comment where cid=$support";
			$srt = $PlusDB->getone($sql);
			echo $srt."|" . _LANG_0127;exit;
		} else {
			$sql = "select support from {$tablepre}xna_comment where cid=$support";
			$srt = $PlusDB->getone($sql);
			echo $srt."|" . _LANG_0128;exit;
        }
    }
    elseif (!empty($against)){
		if (!$_COOKIE['ReplyVote'.$against] == 'pubReplyVote_'.$against) {
			setcookie('ReplyVote'.$against, 'pubReplyVote_'.$against, time() + $news_votetime*3600);
			$sql = "update {$tablepre}xna_comment set against=against + 1 where cid=$against";
			$rt = $PlusDB->execute($sql);
			$sql = "select against from {$tablepre}xna_comment where cid=$against";
			$art = $PlusDB->getone($sql);
			echo $art."|" . _LANG_0127;exit;
		} else {
			$sql = "select against from {$tablepre}xna_comment where cid=$against";
			$art = $PlusDB->getone($sql);
			echo $art."|" . _LANG_0128;exit;
        }
    }
}else{
    echo "" . _LANG_0129 . "";exit;
}
?>