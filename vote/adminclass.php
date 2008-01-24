<?
/*::::::::::::::::::::Class::::::::::::::::::::::*/
class admin extends mc_news_vote {
	
	function daddslashes($string, $force = 0) {
		if(!$GLOBALS['magic_quotes_gpc'] || $force) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					//$string[$key] = daddslashes($val, $force);
				}
			} else {
				$string = addslashes($string);
			}
		}
		return $string;
	}
	
	//添加
	function newnews_vote($news_vote) {
		$lastnews_vote = explode($this->sep, trim($this->data[$this->datanum-1]));
		$newid = $lastnews_vote[1] + 1;
		$news_vote[0] = '<?die();?'.'>';
		$news_vote[1] = $newid;
		$this->wfile($this->file, "\n".implode($this->sep, $news_vote), 'ab');
		
	}
	
	//修改
	function editnews_vote($news_vote) {
		$a = 0;
		$b = $this->datanum;
		while($a < $b){
			$t = floor(($a + $b)/2);
			$line = explode($this->sep, trim($this->data[$t]));
			if ($line[1] == $news_vote[1]) {
				$this->index = $t;
				$news_vote[0] = $line[0];
				$news_vote[17] = $line[17];
				$news_vote[18] = $line[19];
				$this->data[$t] = implode($this->sep, $news_vote);
				break;
			}
			
			if ($id > $line[1]) {
				$a = $t;
			} else {
				$b = $t;
			}
		}
		$this->writedata();
	}
	
	function oorc($id) {
		$news_vote = $this->get_news_vote($id);
		$news_vote[13] = $news_vote[13] ? 0 : 1;
		$this->data[$this->index] = implode($this->sep, $news_vote);
		$this->writedata();
		return $news_vote[13];
	}
	
	function del($id) {
		$this->get_news_vote($id);
		unset($this->data[$this->index]);
		$this->writedata();
		//print_r($this->data);
	}
	
	function msg($message, $url_forward = '') {
		if($url_forward) {
			$message .= "<br><br><a href=\"$url_forward\">如果您的浏览器没有自动跳转，请点击这里</a>\n";
			$message .= "<meta http-equiv=\"refresh\" content=\"2;url=$url_forward\">\n";
		}
		include './tpl/msg.php';
		exit;
	}
		
}
?>