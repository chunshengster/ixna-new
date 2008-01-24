<?php
/**
* -------------- [Nemo Template Engine] --------------
* PHPSo MVC System * Nemo 模板引擎
* 版本：V1.20 Build 2006-12-25 15:57
* 版权所有：Monky(QQ 10433182)
* PHPSo.Com
*
* PHPSoMVC及本Nemo模板引擎均为开源的免费工具，任何人均可以修改，修改后请保留原作者姓名及网站，谢谢。
* 如有任何开发的意见及建议欢迎和我联系，最新版本在官方网站MVC.PHPSo.Com予以发布。
*/

//模板编译类
class nemo {

var $template;
var $cachefile;
var $htmlfile;
var $userpack;
var $extraparms;

//错误显示
function error($no) {
	switch ($no) {
		case 1: exit('template cache have no access!');break;
		case 2: exit('userpack have no access!');break;
	}
}

//子功能函数
function a($s, $u = true) {
	$s = str_replace('\\\"', '"', $s);
	$s = preg_replace('#{((\$[a-zA-Z_][a-zA-Z0-9_\->]*)(\[\'[a-zA-Z0-9_\.\"\'\[\]\$]+?\'\]))}#', '\1', $s);
	if (!$u) $s = preg_replace('#echo "(.+?)";#e', "'echo \"'.\$this->b('\\1').'\";'", $s);
	return "]];\n".$s."\necho [[";
}

function b($s) {
	$s = str_replace("\\\"", "\"", $this->c($s));
	$s = preg_replace('#(\$[a-zA-Z_][a-zA-Z0-9_\->]*\[\'[a-zA-Z0-9_\.\"\'\[\]\$]+?\'\])#', '{\1}', $s);
	return $s;
}

function c($v) {
	return preg_replace("/\[([a-zA-Z0-9_\.]+)\]/s", "['\\1']", $v);
}

function d($s) {
	$s = str_replace('\\\"', '"', $s);
	$s = str_replace('\'', '\\\'', $s);
	$s = preg_replace('#(\$[a-zA-Z_][a-zA-Z0-9_\->]*(\[[a-zA-Z0-9_\.\[\]\$]+?\])*)#', '\'.\1.\'', $s);
	$s = str_replace(']].\'', '].\']', $s);
	$s = str_replace('.\'\'.', '.', $s);
	return '\''.$s.'\';';
}

function e() {
	if (!file_exists($this->userpack)) $this->error(2);
	include $this->userpack;
	if (is_array($data)) {
		$this->template = preg_replace('/{\#(.+?)\#}/e', "\$data['\\1'];", $this->template);
	}
}

//模板的编译
function compile() {
	$var = '(\$[a-zA-Z_][a-zA-Z0-9_\->\.\[\]\$]*)';
	$this->template = str_replace('"', '\"', $this->template);
	if ($this->userpack != '') $this->e();

	//模板语法
	$find[] = '#<\?.*?\?>#is';
	$replace[] = '';
	$find[] = '#<!--\*\*.+?\*\*-->#s';
	$replace[] = '';
	$find[] = '#{\[(\S+?)\]}#';
	$replace[] = '$_v_[\1]';
	$find[] = '#\t*<(!--)?if:(.+?)-->#ie';
	$replace[] = '$this->a(\'if (\2) {\')';
	$find[] = '#\t*<(!--)?else-->#ie';
	$replace[] = '$this->a(\'} else {\')';
	$find[] = '#\t*<(!--)?elseif:(.+?)-->#ie';
	$replace[] = '$this->a(\'} elseif (\\2) {\')';
	$find[] = '#\t*<(!--)?switch:(.+?)-->#ie';
	$replace[] = '$this->a(\'switch (\2) {default:\')';
	$find[] = '#\t*<(!--)?case:(.+?)-->#ie';
	$replace[] = '$this->a(\'break;case "\2":\')';
	$find[] = '#\t*<(!--)?for:(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\2)) foreach (\2 as \3) {\')';
	$find[] = '#\t*<(!--)?for:(\S+?)\s+(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\2)) foreach (\2 as \3 => \4) {\')';
	$find[] = '#\t*<(!--)?for_option:(\S+?)\s+(\S+?)\s+(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\2)) foreach (\2 as $_k_ => \4) {echo \\\'<option value="\\\'.\4["\3"].\\\'"\\\'.((\4["\3"] == \5 || @in_array(\4["\3"], \5))?\\\' selected class="tpl_select"\\\':\\\'\\\').\\\'>\\\';\')';
	$find[] = '#\t*<(!--)?for_checkbox\s+(\S+?):(\S+?)\s+(\S+?)\s+(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\3)) foreach (\3 as $_k_ => \5) {echo \\\'<input type="checkbox" name="\2" value="\\\'.\5["\4"].\\\'"\\\'.((\5["\4"] == \6 || @in_array(\5["\4"], \6))?\\\' checked class="tpl_checkbox"\\\':\\\'\\\').\\\'>\\\';\')';
	$find[] = '#\t*<(!--)?for_radio\s+(\S+?):(\S+?)\s+(\S+?)\s+(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\3)) foreach (\3 as $_k_ => \5) {echo \\\'<input type="radio" name="\2" value="\\\'.\5["\4"].\\\'"\"\\\'.((\5["\4"] == \6)?\\\' checked class="tpl_radio"\\\':\\\'\\\').\\\'>\\\';\')';
	$find[] = '#\t*<(!--)?loop:(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\2)) foreach (\2 as $_v_) {\')';
	$find[] = '#\t*<(!--)?loop:(\S+?)\s+(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'for(\2;\3;\4) {\')';
	$find[] = '#\t*<(!--)?include:(\S+?)-->#ie';
	$replace[] = '$this->a(\'include_once template("\2"'.$this->extraparms.');\')';
	$find[] = '#\t*<(!--)?func\s+(\S+?):(.*?)-->#ie';
	$replace[] = '$this->a(\'function \\2(\\3) {\')';
	$find[] = '#\t*<(!--)?func:(\S+?)\((.*?)\)-->#ie';
	$replace[] = '$this->a(\'\\2(\\3);\')';
	$find[] = '#\t*<(!--)?php:(.+?)-->#ies';
	$replace[] = '$this->a(\'\2;\', false)';
	$find[] = '#{echo:(.+?)}#ie';
	$replace[] = '$this->a(\'echo \1;\', false)';
	$find[] = '#{'.$var.':date\s+(.+?)}#ie';
	$replace[] = '$this->a(\'echo date("\2", \1);\')';
	$find[] = '#{'.$var.':default\s+(.+?)}#ie';
	$replace[] = '$this->a(\'if (!isset(\1) || empty(\1)) echo [[\2]];\')';
	$find[] = '#{'.$var.':format\s+(.+?)}#ie';
	$replace[] = '$this->a(\'echo sprintf("\2", \1);\')';
	$find[] = '#{'.$var.':float\s+(.+?)}#ie';
	$replace[] = '$this->a(\'echo sprintf("%\2f", \1);\')';
	$find[] = '#{'.$var.':specialchar\s+(.+?)}#ie';
	$replace[] = '$this->a(\'echo htmlspecialchars(\1, \2);\')';
	$find[] = '#{'.$var.':specialchar}#ie';
	$replace[] = '$this->a(\'echo htmlspecialchars(\1, ENT_QUOTES);\')';
	$find[] = '#{'.$var.':(\S+?)\((.*?)\)}#ie';
	$replace[] = '$this->a(\'echo \'.("\3"== \'\' ? \'\2(\1);\' : \'\2(\1,\3);\'))';
	$find[] = '#\t*<(!--)?option:(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\2)) foreach (\2 as $_k_ => $_v_) echo \\\'<option value="\\\'.$_k_.\\\'"\\\'.(($_k_ == \3 || @in_array($_k_, \3))?\\\' selected class="tpl_select"\\\':\\\'\\\').\\\'>\\\'.$_v_.\\\'</option>\\\';\')';
	$find[] = '#\t*<(!--)?checkbox\s+(\S+?):(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\3)) foreach (\3 as $_k_ => $_v_) echo \\\'<input type="checkbox" name="\2" value="\\\'.$_k_.\\\'"\\\'.(($_k_ == \4 || @in_array($_k_, \4))?\\\' checked class="tpl_checkbox"\\\':\\\'\\\').\\\'>\\\'.$_v_;\')';
	$find[] = '#\t*<(!--)?radio\s+(\S+?):(\S+?)\s+(\S+?)-->#ie';
	$replace[] = '$this->a(\'if(is_array(\3)) foreach (\3 as $_k_ => $_v_) echo \\\'<input type="radio" name="\2" value="\\\'.$_k_.\\\'"\\\'.(($_k_ == \4)?\\\' checked class="tpl_radio"\\\':\\\'\\\').\\\'>\\\'.$_v_;\')';

	$obs_news_count = 0;
	$find[] = '#\t*<(!--)?htmlcache-->#ie';
	$replace[] = '$this->a(\'/*Nemo_OB_Start_\'.(++$obs_news_count).\'*/ob_start();\')';
	$obe_news_count = 0;
	$find[] = '#\t*<(!--)?/htmlcache-->#ie';
	$replace[] = '$this->a(\'\$ob_\'.(++$obe_news_count).\'=ob_get_contents();ob_end_clean();echo \$ob_\'.$obe_news_count.\';/*Nemo_OB_End*/\')';
	//更多语法请您自由添加 ^_^

	//编译文件结构调整
	$this->template = preg_replace($find, $replace, $this->template);
	$find = array('#<(!--)?/if-->#ie', '#<(!--)?/switch-->#ie', '#<(!--)?/for-->#ie', '#<(!--)?/for_option-->#ie', '#<(!--)?/for_checkbox-->#ie', '#<(!--)?/for_radio-->#ie', '#<(!--)?/loop-->#ie', '#<(!--)?/func-->#ie');
	$this->template = preg_replace($find, '$this->a(\'}\')', $this->template);
	$this->template = preg_replace('#echo "\s*";#is', '', "echo [[\n".$this->template."\n]];");
	$this->template = preg_replace('#\[\[(.*?)\]\];#es', '$this->d(\'\1\')', $this->template);
	$this->template = "<?php\n//Nemo Cache @ ".date('Y-m-d H:i:s')."\n".$this->template."\n?>";
	$this->template = str_replace('echo \'\'.', 'echo ', $this->template);
	$find = array('#((\$[a-zA-Z_][a-zA-Z0-9_]*)(\[[a-zA-Z0-9_\.\[\]\$]+\])+)#e', '#echo \'\s+\';#', '#(\r|\n)+#', '#{(\'\.\$[a-zA-Z_][a-zA-Z0-9_\->]*(\[[a-zA-Z0-9_\.\[\]\$]+?\])*\.\')}#');
	$replace = array('$this->c(\'\1\')', '', '\1', '\1');
	$this->template = preg_replace($find, $replace, $this->template);

	if($obs_news_count) {
		$this->template .= '<?/*Nemo_OB_Script*/$fp=fopen(__FILE__, \'rb\');$selfcontent=fread($fp,filesize(__FILE__));fclose($fp);$selfcontent=preg_replace("/\/\*Nemo_OB_Start_(\d+)\*\/.+?\/\*Nemo_OB_End\*\//ies","\'?>\'.\$ob_\\\\1.\'<?\'",$selfcontent);$selfcontent=preg_replace("/<\?\/\*Nemo_OB_Script\*\/.+?\/\*Nemo_OB_Script\*\/\?>/is",\'\',$selfcontent);$fp=fopen(__FILE__,\'wb\');@flock($fp,LOCK_EX);fwrite($fp,$selfcontent);@flock($fp,LOCK_UN);fclose($fp);/*Nemo_OB_Script*/?>';
	}

	//写入编译文件
	if (!$fp = @fopen($this->cachefile, 'wb')) $this->error(1);
	@flock($fp, LOCK_EX);
	fwrite($fp, $this->template);
	@flock($fp, LOCK_UN);
	fclose($fp);
	return $this->cachefile;
	}
}
?>