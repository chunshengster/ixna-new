<?php
include _ROOT . "/inc/functions.php";

function doList(){
	global $PlusDB, $tablepre, $lang;
	$start = $_GET["start"];
	$start = empty($start)?0:$start;
	$sql	=	"select count(*) as sum ,site_id from {$tablepre}xna_news group by site_id ";
	$rt		=  $PlusDB->execute($sql);	
	while($row = $rt->fetchRow()){
		$xnum[$row[site_id]] = $row[sum];
	}
	$sql="select * from {$tablepre}xna_site where site_audit = 0 order by sid desc ";
	$rt = $PlusDB->execute($sql);
	$num =  $rt->recordcount();
	$num = $PlusDB->getone($sql);
	$start = ($start>$num)?0:$start;
	$page = 25;
	include _ROOT."/inc/page.php";
	$rt = $PlusDB->SelectLimit($sql,$page,$start);
	while($row = $rt->fetchRow()){
		$row["rss_cate"] = $rss_cate[$row["rss_cate"]];
		$row[site_ctime] = date("Y-m-d h:m:s",$row["site_ctime"]);
		$row[site_utime] = date("Y-m-d h:m:s",$row["site_utime"]);
		$row[sum] = $xnum[$row[sid]];
		
		$ret[]=$row;
	} 
  doTpl();
  include template("list_site.html");
}

function doAdd(){
  global $rss_feq, $rss_cate, $lang;
  doTpl();
  include template("add_site.html");
}

function doAddTypes(){
  global $lang;
   doTpl();
  include template("add_types.html");
}

function doUser(){
  global $PlusDB, $tablepre, $lang;
  $sql = "select * from {$tablepre}xna_users order by uid desc";
  $rt = $PlusDB->execute($sql);
	while($row = $rt->fetchRow()){
		$ret[]=$row;
	} 
  doTpl();
  include template("list_user.html");
}

function doAddUser(){
  global $lang;
   doTpl();
  include template("add_user.html");
}


function doUpdateConfig(){
  $str = '<?php
      	$skin = "' . $_POST["skin"] . '";	//skin:default,bxna,digg
      	$rssread = "' . $_POST["rssread"] . '";	//rssread:lastrss,simplepie,magpierss
      	$rssnumber = "' . $_POST["rssnumber"] . '";		//
      	$comments = "' . $_POST["comments"] . '";	//comments:false,true
      	$rewrite = "' . $_POST["rewrite"] . '";	//rewrite:false,true
      	$ispages = "' . $_POST["ispages"] . '";		//rewrite:ajax,html,other
      	$language = "' . $_POST["language"] . '";	//language:zh-cn,zh-tw,en
      	$Version = "IXNA 0.4 (20070911)";
if ($rewrite == "true"){
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
?>';
  $fp = fopen(_ROOT . "/inc/config.php", w);
  @fputs($fp, $str);
  fclose($fp);
  echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
}

function doConfig(){
  global $lang;
  doTpl();
  include template("config.html");
}

function doUpdateUser(){
  global $PlusDB, $tablepre;
  $id = $_POST["id"];
  if (!is_numeric($id)){
    echo "" . _LANG_0304 . "";
    return false;
  }
  $admin_username = $_POST["username"];
  $admin_email = $_POST["email"];
  $admin_time = date("Y-m-d H:i:s");
  if (empty($_POST["password"]))  {
    $sql = "
		UPDATE {$tablepre}xna_users SET
		    username='$admin_username' ,
		    email='$admin_email' ,
		    lasttime='$admin_time'
		WHERE uid='$id'";
  } else  {
    $admin_password = md5($_POST["password"]);
      $sql = "
		UPDATE {$tablepre}xna_users SET
		    username='$admin_username' ,
		    password='$admin_password' ,
		    email='$admin_email' ,
		    lasttime='$admin_time'
		WHERE uid='$id'";
  }

  $rt = $PlusDB->execute($sql);
  if ($rt)  {
    echo "" . _LANG_0301 . "";
    echo "<br>";
    echo "<a href=?action=logout>" . _LANG_0303 . "</a>";
  } else  {
    echo "" . _LANG_0302 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doSave(){
  global $PlusDB, $tablepre;
  if (empty($_POST["title"]) || empty($_POST["site_url"]) || empty($_POST["rss_url"]) ||
    empty($_POST["icon"]) || empty($_POST["email"]) || empty($_POST["rss_cate"]))  {
    echo "<script>alert('" . _LANG_0103 . "');history.back();</Script>";
    exit;
  }

  $sql = "select rss_url from {$tablepre}xna_site where rss_url='" . $_POST["rss_url"] . "'";
  $rt = $PlusDB->execute($sql);
  while ($row = $rt->fetchRow())  {
    if ($row[rss_url] = $_POST["rss_url"])    {
      echo "<a href=?module=xna>" . _LANG_0314 . "</a>";exit;
    }
  }
  $site_title = $_POST["title"];
  $site_site_url = $_POST["site_url"];
  $site_rss_url = $_POST["rss_url"];
  $site_icon = $_POST["icon"];
  $site_rss_feq = $_POST["rss_feq"];
  $rss_cate = $_POST["rss_cate"];
  $site_email = $_POST["email"];
  $site_content = $_POST["content"];
  $site_ctime = strtotime(date("Y-m-d H:i:s"));
  $sql = "INSERT INTO {$tablepre}xna_site (site_title,site_url,rss_url,site_icon,rss_feq,rss_cate,site_email,site_content,site_ctime)
		   VALUES
		  ('$site_title','$site_site_url','$site_rss_url','$site_icon','$site_rss_feq','$rss_cate','$site_email','$site_content','$site_ctime')";
  $rt = $PlusDB->execute($sql);
  echo $sql;
  if ($rt){
    echo "" . _LANG_0309 . "";
    echo "<br>";
    echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
  } else  {
    echo "" . _LANG_1111 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doSaveFeed(){
  global $PlusDB, $tablepre;
  $id = $_POST["id"];
  if (!is_numeric($id)){
    echo "" . _LANG_0304 . "";
    return false;
  }
  $feed_title = $_POST["title"];
  $feed_url = $_POST["news_url"];
  $feed_content = $_POST["content"];
  $feed_ctime = $_POST["ctime"];
  $feed_news_hits = $_POST["news_hits"];
  $sql = "
		UPDATE {$tablepre}xna_news SET
		    news_title='$feed_title' ,
		    news_url='$feed_url',
		    news_content='$feed_content',
		    news_ctime='$feed_ctime',
		    news_hits='$feed_news_hits'
		WHERE id='$id'";
  $rt = $PlusDB->execute($sql);
  if ($rt) {
    echo "" . _LANG_0301 . "";
    echo "<br>";
    echo "<a href=?action=doListFeed>" . _LANG_0303 . "</a>";
  } else  {
    echo "" . _LANG_0302 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doSavetypes(){
  global $PlusDB, $tablepre;
  if (empty($_POST["types"]) || empty($_POST["name"]))  {
    echo "<script>alert('" . _LANG_0103 . "');history.back();</Script>";exit;
  }
  $sql = "select cate_title from {$tablepre}xna_category where cate_title='" . $_POST["types"] . "'";
  $rt = $PlusDB->execute($sql);
  while ($row = $rt->fetchRow()) {
    if ($row[types] = $_POST["types"]) {
      echo "<a href=?action=doListTypes>" . _LANG_0312 . "</a>";
      exit();
    }
  }
  $cate_title = $_POST["types"];
  $cate_content = $_POST["name"];
  $cate_sort = $_POST["sort"];
  $sql = "INSERT INTO {$tablepre}xna_category (cate_title,cate_content,cate_sort)
		   VALUES
		  ('$cate_title','$cate_content','$cate_sort')";
  $rt = $PlusDB->execute($sql);
  if ($rt)  {
    echo "" . _LANG_0309 . "";
    echo "<br>";
    echo "<a href=?action=doListTypes>" . _LANG_0303 . "</a>";
  } else {
    echo "" . _LANG_1111 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doListFeed(){
  global $PlusDB, $tablepre, $lang;
		$start = $_GET["start"];
		$start = empty($start)?0:$start;
		$sql = "select * from {$tablepre}xna_news where news_state<2 order by news_state desc,id desc ";
		//echo $sql;
		$rt = $PlusDB->execute($sql);
		$num =  $rt->recordcount();
		$num = $PlusDB->getone($sql);
		$start = ($start>$num)?0:$start;
		$page = 25;
		include _ROOT."/inc/page.php";
		$rt = $PlusDB->SelectLimit($sql,$page,$start);
		while($row = $rt->fetchRow()){
			$row["rss_cate"] = $rss_cate[$row["site_id"]];
			$row["news_ctime"] = date("Y-m-d h:m:s",$row["news_ctime"]);
			//$row[sum] = $xnum[$row[id]];
			$ret[]=$row;
		}
  doTpl();
  include template("list_feed.html");
}

function doListComm(){
  global $PlusDB, $tablepre, $lang;
 		$start = $_GET["start"];
		$start = empty($start)?0:$start;
		$sql="select * from {$tablepre}xna_comment  order by cid desc ";
		$rt = $PlusDB->execute($sql);
		$page = 25;
		include _ROOT."/inc/page.php";
		$rt = $PlusDB->SelectLimit($sql,$page,$start);
		while($row = $rt->fetchRow()){
			$row["rss_cate"] = $rss_cate[$row["rss_cate"]];
			$row["user_ctime"] = date("Y-m-d H:i:s",$row["user_ctime"]);
    //$row[sum] = $xnum[$row[id]];
    $ret[] = $row;
  }
  doTpl();
  include template("list_comm.html");
}

function doListAudit(){
  global $PlusDB, $tablepre, $lang;
	$start = $_GET["start"];
	$start = empty($start)?0:$start;
	$sql	=	"select count(*) as sum ,site_id from {$tablepre}xna_news group by site_id ";
	$rt		=  $PlusDB->execute($sql);
	while($row = $rt->fetchRow()){
		$xnum[$row[site_id]] = $row[sum];
	}
	$sql="select * from {$tablepre}xna_site where site_audit = 1 order by sid desc ";
	$rt = $PlusDB->execute($sql);
	$num =  $rt->recordcount();
	$num = $PlusDB->getone($sql);
	$start = ($start>$num)?0:$start;
	$page = 25;
	include _ROOT."/inc/page.php";
	$rt = $PlusDB->SelectLimit($sql,$page,$start);
	while($row = $rt->fetchRow()){
		$row["rss_cate"] = $rss_cate[$row["rss_cate"]];
		$row[sum] = $xnum[$row[id]];
		$ret[]=$row;
	}
  doTpl();
  include template("list_audit.html");
}

function doListFeedAudit(){
  global $PlusDB, $tablepre, $lang;
	$start = $_GET["start"];
	$start = empty($start)?0:$start;
	$sql = "select * from {$tablepre}xna_news where news_state=2 order by news_state desc,id desc ";
	$rt = $PlusDB->execute($sql);
	$num =  $rt->recordcount();
	$num = $PlusDB->getone($sql);
	$start = ($start>$num)?0:$start;
	$page = 25;
	include _ROOT."/inc/page.php";
	$rt = $PlusDB->SelectLimit($sql,$page,$start);
	while($row = $rt->fetchRow()){
		$row["rss_cate"] = $rss_cate[$row["site_id"]];
		//$row[sum] = $xnum[$row[id]];
		$ret[]=$row;
	}
  doTpl();
  include template("list_feedaudit.html");
}

function doUpdateFeed(){
  global $PlusDB, $tablepre;
  $id = $_GET["id"];
  if (!is_numeric($id)){
    echo "" . _LANG_0304 . "";
    return false;
  }
  $value = $_GET["value"];

  $sql = "select news_state from {$tablepre}xna_news where id='$id'";
  $row = $PlusDB->getrow($sql);
  $show = $row["news_state"];

  if ($value == 2)  {
    $sql = "update {$tablepre}xna_news set news_state=2 where id='$id'";
  } elseif ($value == 1)  {
    $sql = "update {$tablepre}xna_news set news_state=1 where id='$id'";
  } else  {
    $sql = "update {$tablepre}xna_news set news_state=0 where id='$id'";
  }

  $ok = $PlusDB->execute($sql);
  if (!$ok){
    echo $PlusDB->errorMsg();
    echo "<a href=?action=doListFeed>" . _LANG_0303 . "</a>";
    return false;
  }
  echo "" . _LANG_0308 . "<br>";
  echo "<a href=?action=doListFeed>" . _LANG_0303 . "</a>";
}

function doUpdateAudit(){
  global $PlusDB, $tablepre;
  $id = $_GET["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "select site_audit from {$tablepre}xna_site where sid='$id'";
  $row = $PlusDB->getrow($sql);
  $auditing = $row["site_audit"];

  if ($auditing == 0){
    $sql = "update {$tablepre}xna_site set site_audit=1 where sid='$id'";
  } else {
    $sql = "update {$tablepre}xna_site set site_audit=0 where sid='$id'";
  }

  $ok = $PlusDB->execute($sql);
  if (!$ok){
    echo $PlusDB->errorMsg();
    echo "<a href=?action=doListAudit>" . _LANG_0303 . "</a>";
    return false;
  }
  echo "" . _LANG_0308 . "<br>";
  echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
}

function doIcon(){
  set_time_limit(0);
  global $PlusDB, $tablepre;
  $sql = "select max(sid)  from {$tablepre}xna_site";
  $cnum = $PlusDB->getone($sql);
  for ($i = 1; $i <= $cnum; $i++)  {
    $icon = _ROOT . '/cache/ico/' . $i . "_favicon.ico";
    $sql = "select site_icon from {$tablepre}xna_site where site_audit=0 and sid=" . $i . "";
    $rt = $PlusDB->execute($sql);
    while ($row = $rt->fetchRow())    {
      $sRemoteFile = $row["icon"];
    }
    if (($fh = fopen($sRemoteFile, 'r')) === false)    {
      $sRemoteFile = _ROOT . '/favicon.ico';
    }
    remote_copy($sRemoteFile, $icon);
    echo "" . $i . ",OK<br />";
  }
  echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
  exit;
}

function doListTypes(){
  global $PlusDB, $tablepre, $rss_cate, $lang;
	$start = $_GET["start"];
	$start = empty($start)?0:$start;
	$sql="select * from {$tablepre}xna_category order by cid desc ";
	$rt = $PlusDB->execute($sql);
	$num =  $rt->recordcount();
	$num = $PlusDB->getone($sql);
	$start = ($start>$num)?0:$start;
	$page = 25;
	include _ROOT."/inc/page.php";
	$rt = $PlusDB->SelectLimit($sql,$page,$start);
	while($row = $rt->fetchRow()){
		$row["rss_cate"] = $rss_cate[$row["rss_cate"]];
		//$row[sum] = $xnum[$row[id]];
		$ret[]=$row;
	}
  doTpl();
  include template("list_types.html");
}

function doListLinks(){
  global $PlusDB, $tablepre, $rss_cate, $lang;
	$start = $_GET["start"];
	$start = empty($start)?0:$start;
	$sql = "select * from {$tablepre}xna_links order by lid desc ";
	$rt = $PlusDB->execute($sql);
	$num =  $rt->recordcount();
	$num = $PlusDB->getone($sql);
	$start = ($start>$num)?0:$start;
	$page = 25;
	include _ROOT."/inc/page.php";
	$rt = $PlusDB->SelectLimit($sql,$page,$start);
	while ($row = $rt->fetchRow()){
		$row["rss_cate"] = $rss_cate[$row["rss_cate"]];
		//$row[sum] = $xnum[$row[id]];
		$ret[] = $row;
  }
  doTpl();
  include template("list_links.html");
}

function doAddLinks(){
  global $lang;
  doTpl();
  include template("add_links.html");
}

function doSaveLinks(){
  global $PlusDB, $tablepre, $lang;
  if (empty($_POST["name"]) || empty($_POST["links"])){
    echo "<script>alert('" . _LANG_0103 . "');history.back();</Script>";
    exit;
  }
  $Link_name = $_POST["name"];
  $Link_links = $_POST["links"];
  $Link_images = $_POST["images"];
  $sql = "INSERT INTO {$tablepre}xna_links (link_title,link_url,link_img)
		   VALUES
		  ('$Link_name','$Link_links','$Link_images')";
  $rt = $PlusDB->execute($sql);
  if ($rt)  {
    echo "" . _LANG_0301 . "";
    echo "<br>";
    echo "<a href=?action=doListLinks>" . _LANG_0303 . "</a>";
   }else{
    echo "" . _LANG_0302 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doEditUser(){
  global $PlusDB, $tablepre, $lang;
   $id = $_GET["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "select * from {$tablepre}xna_users where uid='$id'";
  $rt = $PlusDB->execute($sql);
  if (!$rt){
    echo "" . _LANG_0304 . "";
    return false;
  }
  $user = $rt->fetchRow();
  doTpl();
  include template("edit_user.html");
}

function doEditLinks(){
  global $PlusDB, $tablepre, $lang;
  $id = $_GET[id];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "select * from {$tablepre}xna_links where lid='$id'";
  $rt = $PlusDB->execute($sql);
  if (!$rt){
    echo "" . _LANG_0304 . "";
    return false;
  }
  $link = $rt->fetchRow();
  doTpl();
  include template("edit_links.html");
}

function doEditComm(){
  global $PlusDB, $tablepre, $lang;
  $id = $_GET[id];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "select * from {$tablepre}xna_comment where cid='$id'";
  $rt = $PlusDB->execute($sql);
  if (!$rt)  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $comm = $rt->fetchRow();
  doTpl();
  include template("edit_comm.html");
}

function doUpdateLinks(){
  global $PlusDB, $tablepre;
  $id = $_POST["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  if (empty($_POST["name"]) || empty($_POST["links"]))  {
    echo "<script>alert('" . _LANG_0103 . "');history.back();</Script>";
    exit;
  }
  $link_name = $_POST["name"];
  $link_links = $_POST["links"];
  $link_images = $_POST["images"];
  $sql = "
		UPDATE {$tablepre}xna_links SET
		    link_title='$link_name' ,
		    link_url='$link_links' ,
		    link_img='$link_images' 
		WHERE lid='$id'";
  $rt = $PlusDB->execute($sql);
  if ($rt)  {
    echo "" . _LANG_0301 . "";
    echo "<br>";
    echo "<a href=?action=doListLinks>" . _LANG_0303 . "</a>";
  } else  {
    echo "" . _LANG_0302 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doUpdateComm(){
  global $PlusDB, $tablepre;
  $id = $_POST["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  if (empty($_POST["name"]) || empty($_POST["content"]))  {
    echo "<script>alert('" . _LANG_0103 . "');history.back();</Script>";
    exit;
  }
  $Comm_name = $_POST["name"];
  $Comm_url = $_POST["url"];
  $Comm_email = $_POST["email"];
  $Comm_content = $_POST["content"];
  $Comm_date = $_POST["date"];
  $Comm_support = $_POST["support"];
  $Comm_against = $_POST["against"];
  $sql = "
		UPDATE {$tablepre}xna_comment SET
		    user_name='$Comm_name' ,
		    user_url='$Comm_url' ,
		    user_email='$Comm_email' ,
		    user_content='$Comm_content',
		    support='$Comm_support',
		    against='$Comm_against'
		WHERE cid='$id'";
  $rt = $PlusDB->execute($sql);
  if ($rt)  {
    echo "" . _LANG_0301 . "";
    echo "<br>";
    echo "<a href=?action=doListComm>" . _LANG_0303 . "</a>";
  } else  {
    echo "" . _LANG_0302 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doDeleteLinks(){
  global $PlusDB, $tablepre;
  $id = $_GET["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }

  $sql = "delete from {$tablepre}xna_links where lid='$id' ";
  //echo $sql;
  $ok = $PlusDB->execute($sql);
  if ($ok)  {
    echo "" . _LANG_0305 . "";
    echo "<br>";
    echo "<a href=?action=doListLinks>" . _LANG_0303 . "</a>";
    return false;
  }
}

function doDelComm(){
  global $PlusDB, $tablepre;
  $id = $_GET["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "delete from {$tablepre}xna_comment where cid='$id' ";
  $ok = $PlusDB->execute($sql);
  if ($ok)  {
    echo $PlusDB->errorMsg();
    echo "<a href=?action=doListComm>" . _LANG_0303 . "</a>";
    return false;
  }
}

function doEdit(){
  global $PlusDB, $tablepre, $rss_feq, $rss_cate,$lang;
  $id = $_GET[id];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "select * from {$tablepre}xna_site where sid='$id'";
  $rt = $PlusDB->execute($sql);
  if (!$rt)  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $site = $rt->fetchRow();
  doTpl();
  include template("edit_site.html");
}

function doEditFeed(){
  global $PlusDB, $tablepre, $rss_feq, $rss_cate,$lang;
  $id = $_GET[id];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }

  $sql = "select * from {$tablepre}xna_news where id=$id";
  //echo $sql;
  $rt = $PlusDB->execute($sql);
  if (!$rt)  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $news = $rt->fetchRow();
  doTpl();
  include template("edit_feed.html");
}

function doEditTypes(){
  global $PlusDB, $tablepre, $rss_feq, $rss_cate,$lang;
  $id = $_GET[id];
  if (!is_numeric($id))
  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "select * from {$tablepre}xna_category where cid='$id'";
  $rt = $PlusDB->execute($sql);
  if (!$rt)  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $type = $rt->fetchRow();
  doTpl();
  include template("edit_types.html");
}

function doUpdate(){
  global $PlusDB, $tablepre,$lang;
  $id = $_POST["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  if (empty($_POST["title"]) || empty($_POST["site_url"]) || empty($_POST["rss_url"]) ||
    empty($_POST["icon"]) || empty($_POST["email"]) || empty($_POST["rss_cate"]))  {
    echo "<script>alert('" . _LANG_0103 . "');history.back();</Script>";
    exit;
  }

  $sql = "select sid,rss_url from {$tablepre}xna_site where rss_url='" . $_POST["rss_url"] . "'";
  $rt = $PlusDB->execute($sql);
  while ($row = $rt->fetchRow())  {
    if ($row[rss_url] = $_POST["rss_url"] and $row[sid] <> $id) {
      echo "<a href=?module=xna>" . _LANG_0312 . "</a>";exit;
    }
  }
  $icon = _ROOT . '/cache/ico/' . $_POST["id"] . "_favicon.ico";
  $sRemoteFile = $_POST["icon"];
  if (($fh = fopen($sRemoteFile, 'r')) === false)  {
    $sRemoteFile = _ROOT . '/favicon.ico';
  }
  remote_copy($sRemoteFile, $icon);
  $site_title = $_POST["title"];
  $site_url = $_POST["site_url"];
  $site_rss_url = $_POST["rss_url"];
  $site_icon = $_POST["icon"];
  $site_rss_feq = $_POST["rss_feq"];
  $rss_cate = $_POST["rss_cate"];
  $site_email = $_POST["email"];
  $site_content = $_POST["content"];
  $sql = "
		UPDATE {$tablepre}xna_site SET
		    site_title='$site_title' ,
		    site_url='$site_url' ,
		    rss_url='$site_rss_url' ,
		    site_icon='$site_icon' ,
		    rss_feq='$site_rss_feq',
		    rss_cate='$rss_cate',
		    site_email='$site_email',
		    site_content='$site_content'
		WHERE sid='$id'";
  $rt = $PlusDB->execute($sql);
  if ($rt)  {
    echo "" . _LANG_0301 . "";
    echo "<br>";
    echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
   }else{
    echo "" . _LANG_0302 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doUpdateTypes(){
  global $PlusDB, $tablepre,$lang;
  $id = $_POST["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  if (empty($_POST["types"]) || empty($_POST["name"]))  {
    echo "<script>alert('" . _LANG_0103 . "');history.back();</Script>";exit;
  }
  $sql = "select * from {$tablepre}xna_category where cate_title='" . $_POST["types"] . "'";

  $rt = $PlusDB->execute($sql);
  while ($row = $rt->fetchRow())  {
    if ($row[types] = $_POST["types"] and $row[cid] <> $id)    {
      echo "<a href=?action=doListTypes>" . _LANG_0312 . "</a>";exit;
    }
  }
  $type_types = $_POST["types"];
  $type_name = $_POST["name"];
  $type_sort = $_POST["sort"];

  $sql = "
			UPDATE {$tablepre}xna_category SET
			    cate_title='$type_types' ,
			    cate_content='$type_name',
			    cate_sort='$type_sort'
			WHERE cid='$id'";
  $rt = $PlusDB->execute($sql);
  if ($rt)  {
    echo "" . _LANG_0301 . "";
    echo "<br>";
    echo "<a href=?action=doListTypes>" . _LANG_0303 . "</a>";
  } else  {
    echo "" . _LANG_0302 . "";
    echo "<br>";
    echo $PlusDB->errorMsg();
    echo "<br>";
    echo "<input type=\"button\" value=\"   " . _LANG_0313 . "   \" onclick=\"history.go(-1)\" class=\"btn\">\n";
  }
}

function doDelete(){
  global $PlusDB, $tablepre,$lang;
  $id = $_GET["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }

  $sql = "delete from {$tablepre}xna_news where site_id='$id' ";
  $ok = $PlusDB->execute($sql);
  if (!$ok)  {
    echo $PlusDB->errorMsg();
    echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
    return false;
  }
  $sql = "delete from {$tablepre}xna_site where sid='$id' ";
  $ok = $PlusDB->execute($sql);
  if (!$ok)  {
    echo $PlusDB->errorMsg();
    echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
    return false;
  }
  echo "" . _LANG_0305 . "<br>";
  echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
}

function doDeleteFeed(){
  global $PlusDB, $tablepre,$lang;
  $id = $_GET["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "delete from {$tablepre}xna_news where id='$id' ";
  $ok = $PlusDB->execute($sql);
  if (!$ok)  {
    echo $PlusDB->errorMsg();
    echo "<a href=?action=doListFeed>" . _LANG_0303 . "</a>";
    return false;
  }
  echo "" . _LANG_0305 . "<br>";
  echo "<a href=?action=doListFeed>" . _LANG_0303 . "</a>";
}

function doDeleteTypes(){
  global $PlusDB, $tablepre,$lang;
  $id = $_GET["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "delete from {$tablepre}xna_category where cid='$id' ";
  $ok = $PlusDB->execute($sql);
  if (!$ok)  {
    echo $PlusDB->errorMsg();
    echo "<a href=?action=doListTypes>" . _LANG_0303 . "</a>";
    return false;
  }
  $sql = "delete from {$tablepre}xna_site where rss_cate='$id' ";
  $ok = $PlusDB->execute($sql);
  if (!$ok)  {
    echo $PlusDB->errorMsg();
    echo "<a href=?action=doListTypes>" . _LANG_0303 . "</a>";
    return false;
  }
  echo "" . _LANG_0305 . "<br>";
  echo "<a href=?action=doListTypes>" . _LANG_0303 . "</a>";
}

function doClear(){
  global $PlusDB, $tablepre,$lang;
  $id = $_GET["id"];
  if (!is_numeric($id))  {
    echo "" . _LANG_0304 . "";
    return false;
  }
  $sql = "delete from {$tablepre}xna_news where site_id='$id' ";
  //echo "$sql";
  $ok = $PlusDB->execute($sql);
  if (!$ok)  {
    echo $PlusDB->errorMsg();
    echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
    return false;
  }
  echo "" . _LANG_0306 . "<br>";
  echo "<a href=?module=xna>" . _LANG_0303 . "</a>";
}

function doOpml(){
  global $PlusDB, $tablepre,$lang;
  $str = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="../images/xsl/opml.xsl"?>
<!-- Generated by IXNA 0.4 -->
<opml version="2.0">
	<head>
		<title>IXNA OPML Feed</title>
		<dateCreated>' . $date . '</dateCreated>
	</head>	
	<body>';
  $sql = "select * from {$tablepre}xna_site where site_audit=0";
  $rt = $PlusDB->execute($sql);
  while ($row = $rt->fetchRow())  {
    $row[title] = htmlspecialchars($row[title]);
    $row[site_url] = htmlspecialchars($row[site_url]);
    $row[rss_url] = htmlspecialchars($row[rss_url]);
    $row[site_name] = htmlspecialchars($row[site_name]);
    $ret[] = $row;
    $str .= '<outline text="' . $row["title"] . '" content="' . $row["site_title"] .
      '" type="rss" htmlUrl="' . $row["site_url"] . '" xmlUrl="' . $row["rss_url"] .
      '" />';
  }
  $str .= '</body></opml>';
  $file = _ROOT . "/cache/opml.xml";
  $fp = fopen($file, w);
  @fputs($fp, $str);
  fclose($fp);
  echo "" . _LANG_0301 . "<br>";
  echo "<a href=?action=doListTypes>" . _LANG_0303 . "</a>";
}

function dositeMAP(){
  global $PlusDB, $tablepre,$lang;
  $site_url = "http://" . $_SERVER["HTTP_HOST"];
  //$site_url = "http://www.ixna.net";
  $file = _ROOT . "/sitemap.xml";
  //setting
  //
  $site_freg = "weekly"; //"always", "hourly", "daily", "weekly", "monthly", "yearly" and "never".
  $cate_freg = "weekly";
  //
  $lastmodify = "filedate";
  switch ($lastmodify)  {
    case "now":
      $lastmod = date("Y-m-d");
      break;
    default:
      $lastmod = $lastmodify;
  }
  //header('Content-type: application/xml; charset="utf-8"',true);
  $str = '<?xml version="1.0" encoding="UTF-8"?>
		<?xml-stylesheet type="text/xsl" href="./images/xsl/sitemap.xsl"?>
			<!-- generator="ixna.net" -->
			<!-- generated-on="' . date . '" -->
		<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/09/sitemap.xsd"	xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		   <url>
			  <loc>' . $site_url . '</loc>
			  <lastmod>' . DATE(DATE_ATOM) . '</lastmod>
			  <changefreq>' . $site_freg . '</changefreq>
			  <priority>1.0</priority>
		   </url>';
  $cdb = $PlusDB->prefix(category);
  $sql = "select id,news_title,news_ctime from {$tablepre}xna_news ";
  //echo $sql;
  $rt = $PlusDB->execute($sql);
  while ($row = $rt->fetchRow())  {
    $row[id] = htmlspecialchars($row[id]);
    //$ctime = DATE(DATE_ATOM,$row[ctime]);
    $ctime = DATE(DATE_ATOM);
    include _ROOT . "/inc/config.php";
    if ($rewrite == "true")    {
      $url = $site_url . "/articles/" . $row[id] . "";
    } else  {
      $url = $site_url . "/articles.php?id=" . $row[id] . "";
    }
    //die($url);
    $str .= '<url>
			  <loc>' . $url . '</loc>
			  <lastmod>' . $ctime . '</lastmod>
			  <changefreq>' . $cate_freg . '</changefreq>
			  <priority>0</priority>
		   </url>';
  }
  $str .= '</urlset>';
  $fp = fopen($file, w);
  @fputs($fp, $str);
  fclose($fp);
  $sitemap = _ROOT . "/" . $file;

  $file = _ROOT . "/news.xml";
  $strbaidu = '<?xml version="1.0" encoding="utf-8"?>
<!-- generator="ixna.net" -->
<document>
	<title>Spvrk XML News Aggregator</title>
	<webSite>' . $site_url . '</webSite>
	<language>zh-cn</language>
	<generator>iXNA.Net</generator>
	<webMaster>spvrk@spvrk.com</webMaster>
	<updatePeri>15</updatePeri>
	';
  $sql = "select * ,n.news_ctime as ctime from {$tablepre}xna_news n left join {$tablepre}xna_site x on (x.sid = n.site_id)  where site_audit=0 and n.news_state<2 $arg  order by n.news_state desc,n.news_ctime desc";
  $rt = $PlusDB->selectLimit($sql, 50, 0);
  while ($row = $rt->fetchRow())  {
    $row["rss_cate"] = $rss_cate[$row["rss_cate"]];
    $row[sum] = $xnum[$row[id]];

    $strbaidu .= '<item>
		<title><![CDATA[' . $row["news_title"] . ']]></title>
		<link><![CDATA[' . $row["news_url"] . ']]></link>
		<description><![CDATA[' . $row["news_content"] . ']]></description>
		<text></text>
		<category>' . $row["rss_cate"] . '</category>
		<image></image>
		<headlineImg></headlineImg>
		<keywords></keywords>
		<author><![CDATA[' . $row["site_name"] . ']]></author>
		<source>ixna.net</source>
		<pubDate>' . $row["news_ctime"] . '</pubDate>
	</item>';
  }
  $strbaidu .= '</document>';
  $fp = fopen($file, w);
  @fputs($fp, $strbaidu);
  fclose($fp);
  $baidu = _ROOT . "/" . $file;
  echo "<a href=" . $sitemap . ">sitemap</a> <a href = " . $baidu . ">baidu</a>";
}

function doCache(){
  $dir = _ROOT . '/cache/';
  $directory = dir($dir);
  while ($entry = $directory->read())  {
    $filename = $dir . '/' . $entry;
    if (is_file($filename))    {
      @unlink($filename);
    }
  }
  $directory->close();
  echo " <script> alert('" . _LANG_0316 . "');history . back(); </Script>";
  return true;
}

function doTpl(){
  define("_TPLPath_", _ROOT . "/template/admin/");
  define("_TPLCachePath_", _ROOT . '/cache/');
  define("_TPLCacheLimit_", 1800);
}
?>
