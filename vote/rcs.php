<?
require './class.php';
require './config.php';
$mc = new mc_vote;
$mychose = $_POST['mychoose'];
$voteid = $_POST['voteid'];

if ($_COOKIE['vote'.$voteid] == 'pubvote_'.$voteid) {
	echo '&back=�Ѿ������ͶƱ��лл';
} else {
	setcookie('vote'.$voteid, 'pubvote_'.$voteid, time() + $votetime*3600);
	$mc->updatevote($voteid, $mychose);
	//echo '&back='.$mychoose;
	echo '&back=ͶƱ�Ѿ��ʹлл����';
}
