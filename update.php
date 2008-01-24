<?php
/*
update
*/
define("_ROOT", ".");
set_time_limit(0);
error_reporting(0);
include _ROOT . "/mainfile.php";
include _ROOT . "/lang/$language/global.php";
include _ROOT . "/inc/functions.php";
$sql = "select * from {$tablepre}xna_site where site_audit=0";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
    $done = "no";
    $utime = $row["site_utime"];
    
	    if (DateDiff("n",$utime,time())< $row["rss_feq"]) break;
	    if ($row["isupdate"]==1) exit;
	$row["isupdate"] = 0;
    	if ($utime == "943891200") $done = "yes";
    $xtime = time() - $row["rss_feq"] * 60;
    	if ($utime < $xtime) $done = "yes";
	    if ($done == "yes") {
	        ob_start();
	        doFetch($row[sid]);
	        $msg .= ob_get_contents();
	        ob_get_clean();
	        $msg .= "" . $row[sid] . "" . _LANG_0001 . "";
	    }
	$row["isupdate"] = 1;
}
$msg = $msg == "" ? "" . _LANG_0002 . "":$msg;
echo "msg=\"" . $msg . "\";";
$time = date('Y-m-d h:m:s') . "\n";
$date = date("Ymd");
if ($msg != "" . _LANG_0002 . ""){
	@header("Content-Type: text/html; charset=utf-8");
    $content = "$time$msg\n";
    $fp = fopen(_ROOT . "/cache/log/$date.log", a);
    fputs($fp, $content);
    fclose($fp);
}

function DateDiff($interval, $date1, $date2) { 
	$time_difference = $date2 - $date1; 
		switch ($interval) {
		case "w": $retval = bcdiv($time_difference, 604800); break; 
		case "d": $retval = bcdiv($time_difference, 86400); break; 
		case "h": $retval = bcdiv($time_difference, 3600); break; 
		case "n": $retval = bcdiv($time_difference, 60); break; 
		case "s": $retval = $time_difference; break; 
		} 
	return $retval;
} 
?>