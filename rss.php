<?php
//xna Rss2.0
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/inc/functions.php";

$type = is_string($_GET[type]) ? $_GET[type]:"";
if (!empty($type)){
	$sql = "select cid,cate_title from {$tablepre}xna_category where cate_title='" . $type . "'";
	$rt = $PlusDB->execute($sql);
	while ($row = $rt->fetchRow()){
		$id = $row["cid"];
		$types = $row["site_title"];
	}
	if (eregi('^[a-zA-Z]+$', $type)){
		$arg = $type > "" ? " and x.rss_cate='$id' ":"";
	}
	else{
		$arg .= "";
	}
    $type = " for " . $type . "";
}

$str = '<?xml version="1.0" encoding="utf-8"?>
<!-- generator="ixna.net" -->
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	>
<channel>
	<title>Spvrk XML News Aggregator' . $type . '</title>
	<content>XML News Aggregator</content>
	<link>http://www.ixna.net/</link>
	<language>zh-cn</language>
	<generator>iXNA.Net</generator>
	<managingEditor>spvrk@spvrk.com</managingEditor>
	<webMaster>spvrk@spvrk.com</webMaster>
	<image>
	<url>http://www.ixna.com/images/logo.gif</url>
	<link>http://www.ixna.net/</link>
	<title>iXNA.Net</title>
	</image>';
echo $str;

$sql = "select * ,n.news_ctime as ctime from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid = n.site_id)  where site_audit=0 and n.news_state<2 $arg order by n.news_state desc,n.news_ctime desc";
$rt = $PlusDB->selectLimit($sql, $rssnumber, 0);
while ($row = $rt->fetchRow()){
    $row["rss_cate"] = $rss_cate[$row["rss_cate"]];
    $row["news_ctime"] = date("Y-m-d H:i:s",$row["news_ctime"]);
    $str = '<item>
		<title><![CDATA[' . $row["news_title"] . ']]></title>
		<link><![CDATA[' . $row["news_url"] . ']]></link>
		<author><![CDATA[' . $row["site_name"] . ']]></author>
		<pubDate>' . $row["news_ctime"] . '</pubDate>
		<description><![CDATA[' . $row["news_content"] . ']]></description>
		<category>' . $row["rss_cate"] . '</category>
	</item>';
    echo $str;
}
$str = '	</channel>
</rss>';
@header("Content-type:application/xml; charset=utf-8");
echo $str;
?>