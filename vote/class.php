<?
class mc_vote {
	var $sep = "\x0E";
	var $file = './data.php';
	
	
	function mc_vote() {
		$this->data = file($this->file);
		$this->datanum = count($this->data);
	}
	
	//**********将字符串写入文件******
	//参数$method为"W"时为换掉原文件内容
	function wfile($name, $data, $method = 'wb') {
		$num=@fopen($name, $method);
		if ($num) {
			flock($num, LOCK_EX);
			$data=fwrite($num,$data);
			$this->close($num);
			$this->wfnum++;
			return $data;
		} else {
			exit('文件写入错误。'.$name);
		}
	}
	//以二维数组方式返回最后一行数据
	function get_end_line() {
		$next = $n = 1;
		while ($next) {
			$line = explode($this->sep, trim($this->data[$this->datanum-$n]));
			if (!$line[0] || $line[13]) {
				$next = 0;
			} else {
				$n++;
			}
		}
		$this->index = $this->datanum - $n;
		return $line;
	}
	
	//以二维数组方式返回指定ID的数据
	function get_vote($id) {
		$a = 0;
		$b = $this->datanum;
		while($a < $b){
			$t = floor(($a + $b)/2);
			$line = explode($this->sep, trim($this->data[$t]));
			if ($line[1] == $id) {
				$this->index = $t;
				return $line;
			}
			
			if ($id > $line[1]) {
				$a = $t;
			} else {
				$b = $t;
			}
		}
		return False;
	}
	
	function get_preid() {
		$next = 1;
		$n = $this->datanum - $this->index + 1;
		while ($next) {
			$line = explode($this->sep, trim($this->data[$this->datanum-$n]));
			if (!$line[0] || $line[13]) {
				$next = 0;
			} else {
				$n++;
			}
		}
		return $line[1];
	}
	
	function get_nextid() {
		$next = 1;
		$n = $this->index + 1;
		while ($next) {
			$line = explode($this->sep, trim($this->data[$n]));
			if (!$line[0] || $line[13]) {
				$next = 0;
			} else {
				$n++;
			}
		}
		return $line[1];
		
		
	}
	
	function updateview() {
		$vote = explode($this->sep, trim($this->data[$this->index]));
		$vote[18]++;
		$this->data[$this->index] = implode($this->sep, $vote);
		$this->writedata();
	}
	
	function updatevote($id, $choose) {
		$a = 0;
		$b = $this->datanum;
		while($a < $b){
			$t = floor(($a + $b)/2);
			$line = explode($this->sep, trim($this->data[$t]));
			if ($line[1] == $id && $line[13]) {
				$this->index = $t;
				$choose = explode(',', $choose);
				for ($i=0;$i<count($choose);$i++) {
					if ($choose[$i] == 'true') {
						$line[$i+8] = $line[$i+8] + 1;
					}
				}
				$this->data[$t] = implode($this->sep, $line);
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
	
	function writedata() {
		$data = implode("\n", $this->data);
		$data = str_replace("\n\n", "\n", $data);
		$this->wfile($this->file, $data);
	}
	
	function close($num){
		return fclose($num);
	}
}