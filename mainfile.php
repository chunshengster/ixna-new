<?php
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);
include _ROOT ."/db.php";
include _ROOT . "/inc/mysql.php";
include _ROOT . "/inc/config.php";
/*
database
*/
$PlusDB = new DBA(false);
#echo "$db_host, $db_port, $db_username, $db_password";
if ($pconnect == "1"){
    $PlusDB->pconnect($db_host, $db_port, $db_username, $db_password);
}else{
    $PlusDB->connect($db_host, $db_port, $db_username, $db_password);
}
$PlusDB->SelectDB($db_table);
$PlusDB->SetCharset("UTF-8");
$PlusDB->debug = false;
/*
template
*/
include_once (_ROOT . "/inc/template.php");
?>