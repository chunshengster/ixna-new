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
			$err['news_voteco'] = 'ͶƱ����Ϊ�ա�';
		}
		
		$news_votenews_count = 0;
		for ($i=1;$i<=5;$i++) {
			if (${"cs".$i}) {
				$news_votenews_count++;
				${"cs".$i} = strip_tags(${"cs".$i});
			}
		}
		if ($news_votenews_count == 0) {
			$err['news_votenews_count'] = '��������дһ��ѡ�';
		}
		
		if (!news_count($err)) {
			$bg_color = strip_tags($bg_color);
			$word_color = strip_tags($word_color);
			$word_size = strip_tags($word_size);
		
			$newnews_vote = array('', '', $news_voteco, $cs1, $cs2, $cs3, $cs4, $cs5, 0, 0, 0, 0, 0, 1, $bg_color, $word_color, $word_size, time(), 0, $sorm);
			$mc->newnews_vote($newnews_vote);
			$mc->msg('���ͶƱ�������', '?');
		}
	}
	
	if ($sorm){
		$checktrue = "checked";
	} else {
		$checkfalse = "checked";
	}
	$title = '�����ͶƱ';
	include './tpl/add.php';
} elseif ($action == 'edit') {
	if ($_POST['Submit']) {
		$err = array();
		$news_voteco = strip_tags($news_voteco);
		if (!$news_voteco) {
			$err['news_voteco'] = 'ͶƱ����Ϊ�ա�';
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
			$err['news_votenews_count'] = '��������дһ��ѡ�';
		}
		
		if (!news_count($err)) {
			$bg_color = strip_tags($bg_color);
			$word_color = strip_tags($word_color);
			$word_size = strip_tags($word_size);
		
			$newnews_vote = array('', $id, $news_voteco, $cs1, $cs2, $cs3, $cs4, $cs5, $cs1_num, $cs2_num, $cs3_num, $cs4_num, $cs5_num, 1, $bg_color, $word_color, $word_size, '', '', $sorm);
			$mc->editnews_vote($newnews_vote);
			$mc->msg('�޸�ͶƱ�������', '?');
		}
	} 
	
	$news_vote = $mc->get_news_vote($id);
	if ($news_vote[19]){
		$checktrue = "checked";
	} else {
		$checkfalse = "checked";
	}
	$title = '�޸ĵ�ǰͶƱ��Ŀ';
	include './tpl/edit.php';
} elseif ($action == 'oorc') {
	$q = $mc->oorc($id);
?>
<script type="text/javascript">
	e = parent.document.getElementById("oorc_<?=$id?>");
	e.src = "images/<?=$q ? 'open' : 'close'?>.gif";
	e.alt = "<?=$q ? '�رմ�ͶƱ' : '�򿪴�ͶƱ'?>";
</script>
<?
} elseif ($action == 'del') {
	$mc->del($id);
	$mc->msg('ɾ��ͶƱ�������', '?');
} elseif ($action == 'view') {
	$news_vote = $mc->get_news_vote($id);
	include './tpl/view.php';
} elseif ($action == 'editcnews_vote') {
	if ($_POST['Submit']) {
		if (md5($password) != $admin_pw || !$username || !$n_password || !$a_n_password) {
			$mc->msg('����д��������');
		}
		
		$data = "<?\n".
"\$admin_name = '$username';		//����Ա�û���\n".
"\$admin_pw = '".md5($n_password)."';		//��������\n".
"\$news_votetime = $news_votetime;		//��ֹ�ظ�ͶƱ��Сʱ����Ĭ��Ϊһ��\n".
"?".">\n";
		$mc->wfile('./config.php', $data);
		$mc->msg('���������޸Ĳ������', '?');
	} else {
		$title = '�޸Ĺ�������';
		include './tpl/editcnews_vote.php';
	}
} elseif ($action == 'help') {
	$title = '����';
	include './tpl/help.php';
} elseif ($action == 'logout') {
	unset($_SESSION['isadmin']);
	$mc->msg('���ѳɹ��˳�����ϵͳ');
} else {
	$title = '��ʾ����ͶƱ';
	include './tpl/manage.php';
}

