<?
require './class.php';
require './config.php';
$mc = new mc_vote;
$mychose = $_POST['mychoose'];
$voteid = $_POST['voteid'];

if ($_COOKIE['vote'.$voteid] == 'pubvote_'.$voteid) {
	echo '&back=已经参与过投票，谢谢';
} else {
	setcookie('vote'.$voteid, 'pubvote_'.$voteid, time() + $votetime*3600);
	$mc->updatevote($voteid, $mychose);
	//echo '&back='.$mychoose;
	echo '&back=投票已经送达，谢谢参与';
}
