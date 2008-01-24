<?php
define("_ROOT", "..");
include _ROOT . "/mainfile.php";
include _ROOT . "/lang/$language/global.php";
$id = is_numeric($_GET["id"]) ? $_GET["id"]:"";
$sql = "select count(*) as sum from {$tablepre}xna_comment where news_id=$id ";
$rt = $PlusDB->execute($sql);
$sum = $PlusDB->getOne($sql);
$str = '<div id="normal">
		<div class="subTitle">' . $sum . '  ' . _LANG_0135 . '</div>';
echo $str;

$sql = "select * from {$tablepre}xna_comment where news_id=$id order by cid ASC";
$rt = $PlusDB->execute($sql);
$n = 0;
while ($row = $rt->fetchRow()){
    $n++;
    $str1 = '<div id="n_content' . $n . ' ">
			 <dl><dt class="re_author"><span><strong>' . $n . ' ' . _LANG_0131 . '</strong>' . $row["user_name"] .
        '' . _LANG_0132 . '' . $row["user_date"] . '</span></dt>
			<dd class="quotecomment"></dd>
			<dd class="re_detail">' . $row["comm_content"] . '</dd>
			<dd class="re_mark"><span class="mark">
			<a href="javascript:void(0);" onclick="javascript:ReplyVote(\'' . $row["cid"] .
        '\',"support");">' . _LANG_0133 . '</a>
			(<span id="' . $row["cid"] . '">' . $row["support"] .
        '</span>) <a href="javascript:void(0);" onclick="javascript:ReplyVote(\'' .
        $row["cid"] . '\',"against");">' . _LANG_0134 . '</a>(<span id="' . $row["cid"] . '">' . $row["against"] .
        '</span>)<div id="info' . $cid . '"></div>
			</dd></dl>';
    echo $str1;
}
echo $str2 = '</div></div></div>';
?>