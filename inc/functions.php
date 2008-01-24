<?php
include _ROOT . "/lang/$language/global.php";
//xna functions
//$rss_feq = array("5", "10", "15", "20", "30", "60", "120");
$rss_feq  = array("5"=>"5","15"=>"15","20"=>"20","30"=>"30","60"=>"60","120"=>"120");
$rss_limit = "10";
//xna site.types
global $PlusDB;
$sql = "select cid,cate_title from {$tablepre}xna_category where cate_sort=0";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
  $id = $row["cid"];
  $types = $row["cate_title"];
  $rss_cate[$id] = $types;
}

function msubstr($str, $start, $len){
  $str = trim($str);
  mb_substr($str, $start, $len, "UTF-8");
  $tmpstr = mb_substr($str, $start, $len, "UTF-8");
  return $tmpstr;
}

function doFetch($id = ""){
  set_time_limit(0);
  global $PlusDB, $tablepre, $defaultoutput, $rss_limit;

  $siteid = $id == "" ? $_GET[id] : $id;
  $utime = strtotime(date("Y-m-d H:i:s"));
  $sql = "update {$tablepre}xna_site set site_utime='$utime' where sid='$siteid'";
  $PlusDB->execute($sql);
  
  $sql = "select * from {$tablepre}xna_site where sid='$siteid'";
  $siterow = $PlusDB->getrow($sql);
  $cid = $siterow["rss_cate"];

  include _ROOT . "/inc/config.php";

  if ($rssread == "lastrss") {
    include_once (_ROOT . "/inc/lastrss/lastrss.php");
    $rss = new lastRSS;

    $rss->cache_dir = _ROOT . '/cache';
    $rss->cache_time = $siterow["rss_feq"] * 60; // one hour
    $rss->CDATA = "strip";
    //$rss->cache_time = 0; // one hour
    $rss->setTags(array('title', 'link', 'description', 'dc:date', 'author', 'dc:creator', 'content:encoded', 'category', 'comments', 'enclosure', 'guid', 'pubDate', 'source'));

    if ($rs = $rss->get($siterow["rss_url"])) {

    } else {
      echo ("" . _LANG_0307 . "");
      return false;
    }
    include_once (_ROOT . "/inc/changecode.inc.php");
    $cnum = $sum = 0;
    $rss_encoding = trim($rs["encoding"]) > "" ? strtolower($rs["encoding"]) : $siterow["rss_language"];
    $items = $rs["items"];
    $num = $rs["items_count"];
    if ($num == 0){
      echo "" . _LANG_0310 . "";
      return false;
    }
    $ccnum = 0;
    foreach ($items as $key => $val){
      if ($ccnum > $rss_limit)
        continue;
      $ccnum++;

      $val[news_url] = $val["link"];
      $val[news_url_sum] = md5($val[news_url]);
      $val[site_id] = $siterow[sid];
      $val[site_name] = $siterow[site_title];
      $ctime = $val['dc:date'] ? $val['dc:date'] : $val['pubDate'];
      //$author = $val['dc:creator'] ? $val['dc:creator'] : $val['author'];
      $content = $val['content:encoded'] ? $val['content:encoded'] : $val['description'];
      $val[news_ctime] = $val[pubDate] = date("Y-m-d H:i:s", strtotime($ctime));
      $sql = "select count(*) from {$tablepre}xna_news where news_url_sum = '$val[news_url_sum]' ";
      $cnum = $PlusDB->getone($sql);
      if ($cnum > 0)
        continue;
      switch ($rss_encoding){
      case "$rss_encoding":
        $val[news_title] = c_iconv("$rss_encoding", "UTF8", addslashes($val["title"]));
        $val[news_content] = c_iconv("$rss_encoding", "UTF8", $content);
        break;
      case "utf8":
      case "utf-8":
        $val[news_title] = addslashes($val["title"]);
        $val[news_content] = $content;
        break;
      default:
        $val[news_title] = addslashes($val["title"]);
        $val[news_content] = $content;
      }
      $sql = "select count(*) as sum,id from {$tablepre}xna_news where news_title='" . $val[news_title] . "' and site_id=" . $siteid . " group by id";
      $rt = $PlusDB->execute($sql);
		while($row = $rt->fetchRow()){
			$cnum=$row["sum"];
			$rid = $row["id"];
		} 
      $feed_title = clearHtml($val["news_title"]);
      $feed_content = clearHtml($val["news_content"]);
      $feed_ctime = strtotime($val["news_ctime"]);
      $feed_url = $val["news_url"];
      $feed_url_sum = $val["news_url_sum"];
      $feed_site_id = $val["site_id"];
      $feed_site_name = $val["site_name"];
      if ($cnum > "0"){
        $sql =" 
			UPDATE {$tablepre}xna_news SET
				news_title='$feed_title' ,
				news_content='$feed_content' ,
				news_ctime='$feed_ctime' ,
				news_url='$feed_url',
				news_url_sum='$feed_url_sum',
				site_id='$feed_site_id',
				site_name='$feed_site_name'
			WHERE id=$rid";
		$rt = $PlusDB->execute($sql);
        echo "" . $siteid . ":" . $val[news_title] . "" . _LANG_0314 . "<br />";      
	  }
      if ($cnum == 0){
        $sql = "INSERT INTO {$tablepre}xna_news (news_title,news_content,news_ctime,news_url,news_url_sum,site_id,site_name)
						VALUES
						('$feed_title','$feed_content','$feed_ctime','$feed_url','$feed_url_sum','$feed_site_id','$feed_site_name')";
        $rt = $PlusDB->execute($sql);
        echo $PlusDB->errorMsg();
        $sum = $sum + 1;
      }
    } //end
  }

  if ($rssread == "magpierss")  {
    include_once (_ROOT . "/inc/magpierss/rss_fetch.inc");
    define('MAGPIE_CACHE_DIR', _ROOT . '/cache/');
    define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
    If( remote_file_exists($siterow["rss_url"])==false){
      echo ("" . _LANG_0307 . "");
      return false;
    } else {
      $rss = fetch_rss($siterow["rss_url"]);
    }
    $cnum = $sum = 0;

    $num = 10;
    if ($num == 0)    {
      echo "" . _LANG_0310 . "";
      return false;
    }
    $ccnum = 0;

    foreach ($rss->items as $item)    {
      if ($ccnum > $rss_limit)
        continue;
      $ccnum++;

      $val[news_url] = $item["link"];
      $val[news_url_sum] = md5($item["link"]);
      $val[site_id] = $siterow[sid];
      $val[site_name] = $siterow[site_title];
      $ctime = $item['dc']['date'] ? $item['dc']['date'] : ($item['published'] ? $item['published'] : $item['pubdate']);
      $content = $item['atom_content'] ? $item['atom_content'] : $item['description'];
      $val[news_ctime] = $ctime = date("Y-m-d H:i:s", strtotime($ctime));
      $sql = "select count(*) from {$tablepre}xna_news where news_url_sum = '$val[news_url_sum]' ";
      $cnum = $PlusDB->getone($sql);
      if ($cnum > 0)
        continue;
      $val[news_title] = addslashes($item["title"]);
      
      $sql = "select count(*) as sum,id from {$tablepre}xna_news where news_title='" . $val[news_title] . "' and site_id=" . $siteid . " group by id";
      $rt = $PlusDB->execute($sql);
		while($row = $rt->fetchRow()){
			$cnum=$row["sum"];
			$rid = $row["id"];
		} 
      $feed_title = clearHtml($val["news_title"]);
      $feed_content = clearHtml($content);
      $feed_ctime = strtotime($val["news_ctime"]);
      $feed_url = $val["news_url"];
      $feed_url_sum = $val["news_url_sum"];
      $feed_site_id = $val["site_id"];
      $feed_site_name = $val["site_name"];
      if ($cnum > "0"){
        $sql =" 
			UPDATE {$tablepre}xna_news SET
				news_title='$feed_title' ,
				news_content='$feed_content' ,
				news_ctime='$feed_ctime' ,
				news_url='$feed_url',
				news_url_sum='$feed_url_sum',
				site_id='$feed_site_id',
				site_name='$feed_site_name'
			WHERE id=$rid";
		$rt = $PlusDB->execute($sql);
        echo "" . $siteid . ":" . $val[news_title] . "" . _LANG_0314 . "<br />";      
	  }
      if ($cnum == 0) {
        $sql = "INSERT INTO {$tablepre}xna_news (news_title,news_content,news_ctime,news_url,news_url_sum,site_id,site_name)
						VALUES
						('$feed_title','$feed_content','$feed_ctime','$feed_url','$feed_url_sum','$feed_site_id','$feed_site_name')";
        $rt = $PlusDB->execute($sql);
        echo $PlusDB->errorMsg();
        $sum = $sum + 1;        
      }      
    } //end
  }

  if ($rssread == "simplepie")  {
    include_once (_ROOT . "/inc/simplepie/simplepie.inc");
    include_once (_ROOT . "/inc/simplepie/idn/idna_convert.class.php");

    $rss = new SimplePie();

    $rss->set_cache_location(_ROOT . '/cache');
    if (remote_file_exists($siterow["rss_url"]) == false) {
      echo ("" . _LANG_0307 . "");
      return false;
    } else {
      $rss->set_feed_url($siterow["rss_url"]);
    }
    $rss->init();
    //print_r($rss);
    $cnum = $sum = 0;

    $num = 10;
    if ($num == 0)    {
      echo "" . _LANG_0310 . "";
      return false;
    }
    $ccnum = 0;
    $items = $rss->get_items();

    foreach ($items as $rss)    {
      if ($ccnum > $rss_limit)
        continue;
      $ccnum++;

      $val[news_url] = $rss->get_permalink();
      $val[news_url_sum] = md5($rss->get_permalink());
      $val[site_id] = $siterow[sid];
      $val[site_name] = $siterow[site_title];
      $ctime = $rss->get_date(); 
      $content = $rss->get_content();
      $val[news_ctime] = $ctime = date("Y-m-d H:i:s", strtotime($ctime));
      $sql = "select count(*) from  {$tablepre}xna_news where news_url_sum = '$val[news_url_sum]' ";
      $cnum = $PlusDB->getone($sql);
      if ($cnum > 0)
        continue;
      $val[news_title] = addslashes($rss->get_title());

      $sql = "select count(*) as sum,id from {$tablepre}xna_news where news_title='" . $val[news_title] . "' and site_id=" . $siteid . " group by id";
      $rt = $PlusDB->execute($sql);
		while($row = $rt->fetchRow()){
			$cnum=$row["sum"];
			$rid = $row["id"];
		} 
      $feed_title = clearHtml($val["news_title"]);
      $feed_content = clearHtml($content);
      $feed_ctime = strtotime($val["news_ctime"]);
      $feed_url = $val["news_url"];
      $feed_url_sum = $val["news_url_sum"];
      $feed_site_id = $val["site_id"];
      $feed_site_name = $val["site_name"];
      if ($cnum > "0"){
        $sql =" 
			UPDATE {$tablepre}xna_news SET
				news_title='$feed_title' ,
				news_content='$feed_content' ,
				news_ctime='$feed_ctime' ,
				news_url='$feed_url',
				news_url_sum='$feed_url_sum',
				site_id='$feed_site_id',
				site_name='$feed_site_name'
			WHERE id=$rid";
		$rt = $PlusDB->execute($sql);
        echo "" . $siteid . ":" . $val[news_title] . "" . _LANG_0314 . "<br />";
        //continue;
      }
      if ($cnum == 0){
        $sql = "INSERT INTO {$tablepre}xna_news (news_title,news_content,news_ctime,news_url,news_url_sum,site_id,site_name)
						VALUES
						('$feed_title','$feed_content','$feed_ctime','$feed_url','$feed_url_sum','$feed_site_id','$feed_site_name')";
        $rt = $PlusDB->execute($sql);
        echo $PlusDB->errorMsg();
        $sum = $sum + 1;
      }
    } //end
  } 
  
  echo "" . $sum . " " . _LANG_0311 . "<br />";
}

function unescape($str){
  $str = rawurldecode($str);
  preg_match_all("/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U", $str, $r);
  $ar = $r[0];
  foreach ($ar as $k => $v){
    if (substr($v, 0, 2) == "%u")
      $ar[$k] = iconv("UCS-2", "GBK", pack("H4", substr($v, -4)));
    elseif (substr($v, 0, 3) == "&#x")
      $ar[$k] = iconv("UCS-2", "GBK", pack("H4", substr($v, 3, -1)));
    elseif (substr($v, 0, 2) == "&#") {
      $ar[$k] = iconv("UCS-2", "GBK", pack("n", substr($v, 2, -1)));
    }
  }
  return join("", $ar);
}

function clearHtml($document){						
		$search = array(
						"/onload\=([\"|\'|\\\"|\\\']*)([^\"\']+)([\"|\'|\\\"|\\\']*)/si",
						"/(\<a[^\<\>]*href\=[\"|\'|\\\"|\\\']*)([^\"\'\s\>\\]+)([\"|\'|\\\"|\\\']*)([^\<\>]*\>)(.*?)\<\/a\>/si",//a tag
						"/(\<img[^\<\>]*)(src)(\=[\"|\'|\\\"|\\\']*)([^\"\'\s\>\\]+)([\"|\'|\\\"|\\\']*)([^\<\>]*)(\>)/si",//img tag
						"'<!-- Feedsky ad -->[^>]*?>.*?/Feedsky flare -->'si", //Feedsky ad
						"'<br([^>]*)>'si",
						"'<p([^>]*)>'si",
						"'</p>'si",						
						"'<script[^>]*?>.*?</script>'si",	// strip out javascript
						"'<[\/\!]*?[^<>]*?>'si",			// strip out html tags
						"'([\r\n])[\s]+'",					// strip out white space
						"'&(quot|#34|#034|#x22);'i",		// replace html entities
						"'&(amp|#38|#038|#x26);'i",			// added hexadecimal values
						"'&(lt|#60|#060|#x3c);'i",
						"'&(gt|#62|#062|#x3e);'i",
						"'&(nbsp|#160|#xa0);'i",
						"'&(iexcl|#161);'i",
						"'&(cent|#162);'i",
						"'&(pound|#163);'i",
						"'&(copy|#169);'i",
						"'&(reg|#174);'i",
						"'&(deg|#176);'i",
						"'&(#39|#039|#x27);'",
						"'&(euro|#8364);'i",				// europe
						"'&a(uml|UML);'",					// german
						"'&o(uml|UML);'",
						"'&u(uml|UML);'",
						"'&A(uml|UML);'",
						"'&O(uml|UML);'",
						"'&U(uml|UML);'",
						"'&szlig;'i",
						"'@@@'i",
						"'###'i",
						"/'/i",
						);
		$replace = array(
						"",
						'@@@a href="\\2" rel="external"###\\4@@@/a###',
						'@@@img src="\\4" alt="photo"###',
						"",
						'@@@br /###',
						'@@@p###',
						'@@@/p###',						
						"",
						"",
						"\\1",
						"\"",
						"&",
						"<",
						">",
						" ",
						chr(161),
						chr(162),
						chr(163),
						chr(169),
						chr(174),
						chr(176),
						chr(39),
						chr(128),
						"",
						"",
						"",
						"",
						"",
						"",
						"",
						"<",
						">",
						"",
						);				
		$text = preg_replace($search,$replace,$document);
		return $text;
	}
	
function remote_copy($src_file, $dst_file){
  $fd_src = @fopen($src_file, "r");
  if (!$fd_src)
    return false;
  $fd_dst = fopen($dst_file, "wb+");
  if (!$fd_dst)  {
    fclose($fd_src);
    return false;
  }
  while (!feof($fd_src))  {
    $buffer = fread($fd_src, 1024);
    fwrite($fd_dst, $buffer);
  }
  fclose($fd_src);
  fclose($fd_dst);
  return true;
}

function RunTime_Begin(){
  global $_starttime;
  $_nowtime = explode(" ", microtime());
  $_starttime = $_nowtime[1] + $_nowtime[0];
}

function RunTime_End(){
  global $_starttime;
  $_nowtime = explode(" ", microtime());
  $_endtime = $_nowtime[1] + $_nowtime[0];
  $_totaltime = $_endtime - $_starttime;
  return $_totaltime;
}

function c_iconv($s_code, $t_code, $str){
  if (function_exists("iconv")){
    $s_code = str_replace("UTF8", "UTF-8", $s_code);
    $t_code = str_replace("UTF8", "UTF-8", $t_code);
    $str = iconv($s_code, $t_code, $str);
    if ($s_code == "UTF-8")
      $str = unescape($str);
  } else  {
    $conv = new ChangeCode($s_code, $t_code);
    $str = $conv->ConvertIt($str);
  }
  return $str;
}

function getip(){
  if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
    $onlineip = getenv('HTTP_CLIENT_IP');
  } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))  {
    $onlineip = getenv('HTTP_X_FORWARDED_FOR');
  } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))  {
    $onlineip = getenv('REMOTE_ADDR');
  } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp ($_SERVER['REMOTE_ADDR'], 'unknown')) {
    $onlineip = $_SERVER['REMOTE_ADDR'];
  }
  $onlineip = preg_replace("/^([d.]+).*/", "1", $onlineip);
  return $onlineip;
}

function rewrite() {
	if ($rewrite == 'true'){
		$days_links = "./days/1days";
		$cate_links = "./";
		$rss_links = "./rss/";
		$atom_links = "./atom/";
		$articles_links = "./articles/";
		$site_links = "./site/";
	}else{
		$days_links = "./?days=1";
		$cate_links = "./?cate=";
		$rss_links = "./rss.php?type=";
		$atom_links = "./atom.php?type=";
		$articles_links = "./articles.php?id=";
		$site_links = "./?site=";
	}
}

function remote_file_exists($url_file){
  $url_file = trim($url_file);
  if (empty($url_file)){
    return false;
  }
  $url_arr = parse_url($url_file);
  if (!is_array($url_arr) || empty($url_arr)){
    return false;
  }

  $host = $url_arr['host'];
  $path = $url_arr['path'] . "?" . $url_arr['query'];
  $port = isset($url_arr['port']) ? $url_arr['port'] : "80";

  $fp = fsockopen($host, $port, $err_no, $err_str, 30);
  if (!$fp)  {
    return false;
  }
  
  $request_str = "GET " . $path . " HTTP/1.1\r\n";
  $request_str .= "Host: " . $host . "\r\n";
  $request_str .= "Connection: Close\r\n\r\n";

  fwrite($fp, $request_str);
  $first_header = fgets($fp, 1024);
  fclose($fp);

  if (trim($first_header) == "")  {
    return false;
  }
  if (!preg_match("/200/", $first_header))  {
    return false;
  }
  return true;
}

function jsread($var){
	$js = $var["js"];
	echo $js;
	$content = file_get_contents($js);
	$sp = array("document.writeln(\"","\");","document.write(\"");
	$rp = array("","","");
	$content =  stripslashes(str_replace($sp,$rp,$content));
	return $content;
}

function template($tplfile, $tplpath = '', $tplcachepath = '', $userpack = '', $userpackpath = ''){
	$tplpath = $tplpath != '' ? $tplpath : (defined("_TPLPath_") ? _TPLPath_ : '');
	$tplfile = $tplpath.$tplfile;
	$tplcachelimit = defined("_TPLCacheLimit_") ? _TPLCacheLimit_ : 0;
	$cachefile = ($tplcachepath != '' ? $tplcachepath : (defined("_TPLCachePath_") ? _TPLCachePath_ : '')).str_replace(array('/', '.'), '_', $tplfile.($userpack ? '.'.($userpackpath ? $userpackpath : '').$userpack : '')).'.php';
	$cachetime = @filemtime($cachefile);
	if (@filemtime($tplfile) <= $cachetime && (!$tplcachelimit || time() - $cachetime <= $tplcachelimit)) return $cachefile;
	$nemotpl = new nemo;
	$nemotpl->userpack = $userpack ? ($userpackpath ? $userpackpath : $tplpath).$userpack.'.php' : '';
	$nemotpl->template = file_get_contents($tplfile);
	$nemotpl->cachefile = $cachefile;
	$nemotpl->extraparms = ',\\\''.$tplpath.'\\\',\\\''.$tplcachepath.'\\\',\\\''.$userpack.'\\\',\\\''.$userpackpath.'\\\'';
	return $nemotpl->compile();
}

// 简单的防注入函数，过滤一级数组；
function sql_injection($content){
	if(!get_magic_quotes_gpc()){
		if(is_array($content)){
			foreach ($content as $key => $value){
				$content[$key] = addslashes($value);
			}
		}else {
			addslashes($content);
		}
	}
	return $content;
}

?>