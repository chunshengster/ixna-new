<?php
//xna Atom1.0
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
	}if (eregi('^[a-zA-Z]+$', $type)){
		$arg = $type > "" ? " and x.rss_cate='$id' ":"";
	}else{
		$arg .= "";
	}
		$type = " for " . $type . "";
}
$str = '<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title type="text">Spvrk XML News Aggregator' . $type . '</title>
<updated>' . $date . '</updated>
<id>tag:www.ixna.net,2007:7</id>
<link rel="alternate" type="text/html" hreflang="zh-cn" href="http://www.ixna.net/" />
<generator>iXNA</generator>
<managingEditor>spvrk@spvrk.com</managingEditor>
<email>spvrk@spvrk.com</email>
<image>
<link>http://www.ixna.net/</link>
<url></url>
<title>iXNA.Net</title>
</image>';
echo $str;

$sql = "select * ,n.news_ctime as ctime from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid = n.site_id)  where site_audit=0 and n.news_state<2 $arg order by n.news_state desc,n.news_ctime desc";
$rt = $PlusDB->selectLimit($sql, $rssnumber, 0);
while ($row = $rt->fetchRow()){
    $row["rss_cate"] = $rss_cate[$row["rss_cate"]];
    $row[content] = msubstr(strip_tags($row["news_content"]), 0, 200) . "...";
    $row["news_ctime"] = date("Y-m-d H:i:s",$row["news_ctime"]);
    $str = '<entry>
<title>' . $row["news_title"] . '</title>
<link rel="alternate" type="text/html" href="' . $row["news_url"] . '"  title="' . $row["news_title"] . '" />
<id>tag:www.ixna.net.://' . $row["site_name"] . '</id>
<updated>' . $row["ctime"] . '</updated>
<published>' . $row["ctime"] . '</published>
<summary type="html"><![CDATA[' . $row["content"] . ']]></summary>
<author>
<name>' . $row["site_name"] . '</name>
<uri>' . $row["news_url"] . '</uri>
</author>
<category term="' . $row["rss_cate"] . '" />    
<content type="html" xml:lang="ja" xml:base="' . $row["news_url"] . '">
<![CDATA[' . $row["news_content"] . ']]>        
</content>
</entry>';
    echo $str;
}
$str = '	</feed>';
@header("Content-type:application/xml; charset=utf-8");
echo $str;
?>