<?
require './class.php';

$voteid = $_GET['voteid'];
$mc = new mc_vote;

if (!$voteid) {
	$vote = $mc->get_end_line();
} else {
	$vote = $mc->get_vote($voteid);
}
//print_r($vote);
if (!$vote[13]) {
	echo '&back=连接数据发生错误'.$vote[13];
	exit;
}

$bgcolor = $vote[14] ? $vote[14] : 'EEEEEE';
$wordcolor = $vote[15] ? $vote[15] : '000000';
$wordsize = $vote[16] ? $vote[16] : '12';
$sorm = $vote[19] ? 'True' : 'False';

$votecount = 0;
for ($i=0;$i<5;$i++) {
	if ($vote[$i+3]) $votecount++;
}

//$mc->updateview();	//更新查看次数

header("Content-type: application/xml");
echo '<?xml version="1.0" encoding="GB2312"?'.'>';
?>
 
 <vote>
 	<system>
 		<voteid><?=$vote[1]?></voteid>
 		<voteco><![CDATA[<?=$vote[2]?>]]></voteco>
 		<votevi><?=$vote[18]?></votevi>
 		<votebgcolor><![CDATA[<?=$bgcolor?>]]></votebgcolor>
 		<votewordcolor><![CDATA[<?=$wordcolor?>]]></votewordcolor>
 		<votecount><?=$votecount?></votecount>
 		<word_size><?=$wordsize?></word_size>
 		<sorm><?=$sorm?></sorm>
 	</system>
<?
for ($i=0;$i<5;$i++) {
	if ($vote[$i+3]) {
?>
 	<cs>
 		<csco><![CDATA[<?=$vote[$i+3]?>]]></csco>
 		<csnum><?=$vote[$i+8]?></csnum>
 	</cs>
<?
	}
}
?>
 	<prenext>
 		<next><?=$mc->get_nextid()?></next>
 		<pre><?=$mc->get_preid()?></pre>
 	</prenext>
 </vote>
