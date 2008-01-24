<?
require 'class.php';
require 'adminclass.php';
require 'config.php';
$mc = new admin;

$magic_quotes_gpc = get_magic_quotes_gpc();
$register_globals = @ini_get('register_globals');
if(!$register_globals || !$magic_quotes_gpc) {
        extract($mc->daddslashes($_GET ), EXTR_OVERWRITE);
        extract($mc->daddslashes($_POST), EXTR_OVERWRITE);
        if(!$magic_quotes_gpc) {
                $_SERVER = $mc->daddslashes($_SERVER);
                $_COOKIE = $mc->daddslashes($_COOKIE);
        }
}

//::::::::::::::::::THE END:::::::::::::::::

if ($action == 'add') {
	if ($_POST['Submit']) {
		$err = array();
		$news_voteco = strip_tags($news_voteco);
		if (!$news_voteco) {
			$err['news_voteco'] = '投票议题为空。';
		}
		
		$news_votenews_count = 0;
		for ($i=1;$i<=5;$i++) {
			if (${"cs".$i}) {
				$news_votenews_count++;
				${"cs".$i} = strip_tags(${"cs".$i});
			}
		}
		if ($news_votenews_count == 0) {
			$err['news_votenews_count'] = '请至少填写一个选项。';
		}
		
		if (!news_count($err)) {
			$bg_color = strip_tags($bg_color);
			$word_color = strip_tags($word_color);
			$word_size = strip_tags($word_size);
		
			$newnews_vote = array('', '', $news_voteco, $cs1, $cs2, $cs3, $cs4, $cs5, 0, 0, 0, 0, 0, 1, $bg_color, $word_color, $word_size, time(), 0, $sorm);
			$mc->newnews_vote($newnews_vote);
			$mc->msg('添加投票操作完成', '?');
		}
	}
	
	if ($sorm){
		$checktrue = "checked";
	} else {
		$checkfalse = "checked";
	}
	$title = '添加新投票';
	include './tpl/add.php';
} elseif ($action == 'edit') {
	if ($_POST['Submit']) {
		$err = array();
		$news_voteco = strip_tags($news_voteco);
		if (!$news_voteco) {
			$err['news_voteco'] = '投票议题为空。';
		}
		
		$news_votenews_count = 0;
		for ($i=1;$i<=5;$i++) {
			if (${'cs'.$i}) {
				$news_votenews_count++;
				${'cs'.$i} = strip_tags(${"cs".$i});
				${'cs'.$i.'_num'} = strip_tags(${'cs'.$i.'_num'});
			}
		}
		if ($news_votenews_count == 0) {
			$err['news_votenews_count'] = '请至少填写一个选项。';
		}
		
		if (!news_count($err)) {
			$bg_color = strip_tags($bg_color);
			$word_color = strip_tags($word_color);
			$word_size = strip_tags($word_size);
		
			$newnews_vote = array('', $id, $news_voteco, $cs1, $cs2, $cs3, $cs4, $cs5, $cs1_num, $cs2_num, $cs3_num, $cs4_num, $cs5_num, 1, $bg_color, $word_color, $word_size, '', '', $sorm);
			$mc->editnews_vote($newnews_vote);
			$mc->msg('修改投票操作完成', '?');
		}
	} 
	
	$news_vote = $mc->get_news_vote($id);
	if ($news_vote[19]){
		$checktrue = "checked";
	} else {
		$checkfalse = "checked";
	}
	$title = '修改当前投票项目';
	include './tpl/edit.php';
} elseif ($action == 'oorc') {
	$q = $mc->oorc($id);
?>
<script type="text/javascript">
	e = parent.document.getElementById("oorc_<?=$id?>");
	e.src = "images/<?=$q ? 'open' : 'close'?>.gif";
	e.alt = "<?=$q ? '关闭此投票' : '打开此投票'?>";
</script>
<?
} elseif ($action == 'del') {
	$mc->del($id);
	$mc->msg('删除投票操作完成', '?');
} elseif ($action == 'view') {
	$news_vote = $mc->get_news_vote($id);
	include './tpl/view.php';
} elseif ($action == 'editcnews_vote') {
	if ($_POST['Submit']) {
		if (md5($password) != $admin_pw || !$username || !$n_password || !$a_n_password) {
			$mc->msg('表单填写不完整！');
		}
		
		$data = "<?\n".
"\$admin_name = '$username';		//管理员用户名\n".
"\$admin_pw = '".md5($n_password)."';		//管理密码\n".
"\$news_votetime = $news_votetime;		//防止重复投票的小时数，默认为一周\n".
"?".">\n";
		$mc->wfile('./config.php', $data);
		$mc->msg('管理密码修改操作完成', '?');
	} else {
		$title = '修改管理密码';
		include './tpl/editcnews_vote.php';
	}
} elseif ($action == 'help') {
	$title = '帮助';
	include './tpl/help.php';
} elseif ($action == 'logout') {
	unset($_SESSION['isadmin']);
	$mc->msg('你已成功退出管理系统');
} else {
	$title = '显示管理投票';
	include './tpl/manage.php';
}

