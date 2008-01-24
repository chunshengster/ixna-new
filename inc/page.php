<?php
if(!defined("__CLASS_PAGETURN__")){
    define("__CLASS_PAGETURN__",1);

class xnapage{
	var $db;
	var $system_msg = array(
    4001 => "'%s' an invalid time unit",
    4002 => "too many records,please use Pagination",
    5001 => "First Page",
    5002 => "Previous",
    5003 => "Next",
    5004 => "Last Page",
    5005 => "Page %d",
    5006 => "Totally %d pages",
    5007 => "%d",
    5008 => "/%d",
    5009 => "This page starts from Item %d",
    5010 => "To Item %d",
    5011 => "Show %d items per page",
);
	function xnapage(){
		global $db,$in,$category_db,$news_db;
		$this->in=$in;
		$this->db=$db;
		$this->category_db=$category_db;
		$this->news_db = $news_db;
	}

	function getSysMsg($num,$otherMsg='')
    {
            $msg = sprintf($this->system_msg[$num],$otherMsg) ;
            return $msg;
    }
}
    class PageTurn extends  xnapage
    {
    	var $maxnum;                 //每页显示数
    	var $maxnum_max_size=100; //每页最多显示数
    	var $navchar=array('|&lt;','&lt;','&gt;','&gt;|');
    	                             //导航条的显示字符，值可以自定义，如一个img标签
    	                             //$navchar[0]表示第一页，$navchar[1]表示前一页，$navchar[2]表示后一页，$navchar[3]表示最后页

        var $key;                    //如果一个页面中有多个分页时作为区别标记

    	var $totalnum;               //总记录数
    	var $totalpage;              //总页数
    	var $startnum;               //本页的第一条在总数中的序数
    	var $endnum;                 //本页的最后一条在总数中的序数
    	var $pageID;                //本页在总页数中的序数
    	var $shownum;                //本页实际显示数
    	var $field;                  //结果记录的集合
    	var $linkhead;               //链接指定的url及要传递的相关参数
        var $form_vars = array();

    	function PageTurn($totalnum='', $maxnum='',$form_vars = '',$key="") {
            $this->xnapage();

            $this->totalnum = $totalnum;
            $this->maxnum   = $maxnum;
            $this->key      = $key;

            if (!empty($form_vars))
            {
                if (!is_array($form_vars)) $form_vars = (array)$form_vars;
                $this->form_vars = $form_vars;
            }

            $ifpost=false;           //是否有$_POST变量,如果有的话,则在翻页时只传递其值,其它的一律省略

            if (sizeof($this->form_vars)>0)
            {
                $formlink = "";

                foreach ($this->form_vars as $val)
                {
                    if (isset($_POST[$val])) $formlink.= $val."=".urlencode($_POST[$val])."&";
                }

                if ($formlink != "")
                {
                    $ifpost=true;
                    $querystring = $formlink;
                }
            }
            else  if (count($_GET) > 0)                  //如果没有$_POST变量,则将$_GET变量分析后作为翻页时传递的参数
            {
                $querystring = '';
                foreach ($_GET as $key => $val)
                {
					if ($key != "totalnum".$this->key && $key != "pageID".$this->key)$querystring .= $key."=".urlencode($val)."&amp;";
                }
            }

            if (isset($_GET["maxnum".$this->key]) && $_GET["maxnum".$this->key] > 0)
            {
                $this->maxnum = sprintf('%d',$_GET["maxnum".$this->key]);
            }

            if ($this->maxnum < 1 ) $this->maxnum = $this->totalnum;

            if ($this->totalnum < 1){
                $this->totalnum  = 0 ;
                $this->totalpage = 0 ;
                $this->pageID   = 0 ;
                $this->startnum  = 0 ;
                $this->endnum    = 0 ;
                $this->shownum   = 0 ;
            } else{
                $this->totalpage = ceil($this->totalnum/$this->maxnum);

                $this->pageID   = (isset($_GET["pageID".$this->key]) && $_GET["pageID".$this->key]>0 && !$ifpost)
                                   ? sprintf('%d',$_GET["pageID".$this->key])
                                   : 1;

                if ($this->pageID > $this->totalpage) $this->pageID = $this->totalpage;

                $this->startnum = max(($this->pageID - 1) * $this->maxnum,0);
                $this->endnum   = min($this->startnum + $this->maxnum, $this->totalnum);
                $this->shownum  = $this->endnum - $this->startnum;
            }

            //$querystring .= "totalnum" . $this->key . "=" . $this->totalnum;

            if (isset($_GET["maxnum" . $this->key])) $querystring .= "&maxnum" . $this->key . "=" . $this->maxnum;
            $this->linkhead = $_SERVER['PHP_SELF'] . "?" . $querystring;
    	}

        //显示如"共14页27条"
        function total() {
            return $this->getSysMsg('5007',$this->totalnum)."  ".$this->getSysMsg('5007',$this->pageID)."".$this->getSysMsg('5008',$this->totalpage)." ";
        }

        //显示如"本页从第9条到第10条"
        function fromto() {
            $startnum = $this->startnum + 1;
            if ($this->totalnum==0)$startnum = 0;

            return $this->getSysMsg('5009',$startnum)." ".$this->getSysMsg('5010',$this->endnum)." ";
        }

        function pagehtml($num_size=0,$nolink_show=false,$nolink_color="nextprev") {
            if ($this->totalpage <= 1) return;

            $str_first = $str_pre = $str_frontell = $str_num = $str_backell = $str_next = $str_last = '';

            if ($num_size>0) {
                $tmpnum    = floor($num_size/2);
                $startpage = max(min($this->pageID - $tmpnum, $this->totalpage - $num_size + 1), 1);
                $endpage   = min($startpage + $num_size - 1, $this->totalpage);

                if ($startpage > 1)              $str_frontell = "<span>...</span>";

                if ($endpage < $this->totalpage) $str_backell  = "<span>...</span>";

                $str_num = "";
				/*如果存在了分类的ID*/
					if(!empty($_GET["cate"])){
						/*有分类ID*/
						/*获取url的前缀*/
                 		$cate_str ="./".$_GET["cate"]."/page";
						/*获取分类的名称*/
						$f_page = strtolower($rss_cate[$_GET["cate"]]);
					}elseif(!empty($_GET["site"])){
						$cate_str ="./".$_GET["site"]."/page";
						$f_page="/";
					}elseif(!empty($_GET["days"])){
						$cate_str ="./".$_GET["days"]."/page";
						$f_page="/";
					}else{
						$cate_str="./all/page";
						$f_page="/";
					}
                for ($i = $startpage; $i <= $endpage; $i++){
				   if ($i == $this->pageID) $str_num .= " <span class=\"".$nolink_color."\">".$i."</span> ";
					//假静态
					else $str_num .= " <a href='".$cate_str.$i."' title=\"".$this->getSysMsg('5005',$i)."\">".$i."</a> ";
                }
            }

            if ($this->pageID > 1){
                $str_first = " <a href='".$f_page."' title=\"".$this->getSysMsg('5001')."\">".$this->navchar[0]."</a> ";
				$str_pre   = " <a href='".$cate_str.($this->pageID-1)."' title=\"".$this->getSysMsg('5002')."\">".$this->navchar[1]."</a> ";
            } else if ($nolink_show) {
                $str_first = " <span class=\"".$nolink_color."\">".$this->navchar[0]."</span> ";
                $str_pre   = " <span class=\"".$nolink_color."\">".$this->navchar[1]."</span> ";
            }

            if ($this->pageID<$this->totalpage) {
                $str_next  = " <a href='".$cate_str.($this->pageID+1)."' title=\"".$this->getSysMsg('5003')."\">".$this->navchar[2]."</a> ";
				$str_last  = " <a href='".$cate_str."$this->totalpage' title=\"".$this->getSysMsg('5004')."\">".$this->navchar[3]."</a>  ";
            }else if ($nolink_show){
                $str_next  ="".$this->navchar[2]."";
                $str_last  ="".$this->navchar[3]."";
            }

            return $str_first.$str_pre.$str_frontell.$str_num.$str_backell.$str_next.$str_last." ";
        }


		function pageajax($num_size=0,$nolink_show=false,$nolink_color="nextprev"){
			if(!empty($_GET["cate"])){
				$cate_ajax = "cate=".$_GET["cate"]."&amp;";
				$this->link_ajax="./pagedata.php?".$cate_ajax."";
			}elseif(!empty($_GET["site"])){
				$site_ajax = "site=".$_GET["site"]."&amp;";
				$this->link_ajax="./pagedata.php?".$site_ajax."";
			}elseif(!empty($_GET["days"])){
				$days_ajax = "days=".$_GET["days"]."&amp;";
				$this->link_ajax="./pagedata.php?".$days_ajax."";
			}else{
				$this->link_ajax="./pagedata.php?";
			}
            if ($this->totalpage <= 1) return;

            $str_first = $str_pre = $str_frontell = $str_num = $str_backell = $str_next = $str_last = '';

            if ($num_size>0){
                $tmpnum    = floor($num_size/2);
                $startpage = max(min($this->pageID - $tmpnum, $this->totalpage - $num_size + 1), 1);
                $endpage   = min($startpage + $num_size - 1, $this->totalpage);

                if ($startpage > 1)              $str_frontell = "<span>...</span>";

                if ($endpage < $this->totalpage) $str_backell  = "<span>...</span>";

                $str_num = "";

                for ($i = $startpage; $i <= $endpage; $i++)
                {
                    if ($i == $this->pageID) $str_num .= " <a style=\"cursor:pointer\" onclick=\"javascript:process('".$this->link_ajax."pageID".$this->key."=".$i."')\">".$i."</a> ";
                    else $str_num .= " <a style=\"cursor:pointer\" onclick=\"javascript:process('".$this->link_ajax."pageID".$this->key."=".$i."')\">".$i."</a> ";
                }
            }

            if ($this->pageID > 1){
                $str_first = " <a style=\"cursor:pointer\" onclick=\"javascript:process('".$this->link_ajax."pageID".$this->key."=1')\">".$this->navchar[0]."</a> ";
                $str_pre   = " <a style=\"cursor:pointer\" onclick=\"javascript:process('".$this->link_ajax."pageID".$this->key."=".($this->pageID-1)."')\">".$this->navchar[1]."</a> ";
            }else if ($nolink_show) {
                $str_first = " <span class=\"".$nolink_color."\">".$this->navchar[0]."</span> ";
                $str_pre   = " <span class=\"".$nolink_color."\">".$this->navchar[1]."</span> ";
            }

            if ($this->pageID<$this->totalpage){
                $str_next  = " <a style=\"cursor:pointer\" onclick=\"javascript:process('".$this->link_ajax."pageID".$this->key."=".($this->pageID+1)."')\">".$this->navchar[2]."</a> ";
                $str_last  = " <a style=\"cursor:pointer\" onclick=\"javascript:process('".$this->link_ajax."pageID".$this->key."=".$this->totalpage."')\">".$this->navchar[3]."</a> ";
            } else if ($nolink_show){
                $str_next  =" <span class=\"".$nolink_color."\">".$this->navchar[2]."</span> ";
                $str_last  =" <span class=\"".$nolink_color."\">".$this->navchar[3]."</span> ";
            }
            return $str_first.$str_pre.$str_frontell.$str_num.$str_backell.$str_next.$str_last." ";
        }


		function pagenav($num_size=0,$nolink_show=false,$nolink_color="nextprev"){
            if ($this->totalpage <= 1) return;

            $str_first = $str_pre = $str_frontell = $str_num = $str_backell = $str_next = $str_last = '';

            if ($num_size>0){
                $tmpnum    = floor($num_size/2);
                $startpage = max(min($this->pageID - $tmpnum, $this->totalpage - $num_size + 1), 1);
                $endpage   = min($startpage + $num_size - 1, $this->totalpage);

                if ($startpage > 1)              $str_frontell = "<span>...</span>";

                if ($endpage < $this->totalpage) $str_backell  = "<span>...</span>";

                $str_num = "";

                for ($i = $startpage; $i <= $endpage; $i++)
                {
                    if ($i == $this->pageID) $str_num .= " <span class=\"".$nolink_color."\">".$i."</span> ";
                    else                      $str_num .= " <a href=\"".$this->linkhead."pageID".$this->key."=".$i."\" title=\"".$this->getSysMsg('5005',$i)."\">".$i."</a> ";
                }
            }

            if ($this->pageID > 1) {
                $str_first = " <a href=\"".$this->linkhead."pageID".$this->key."=1\" title=\"".$this->getSysMsg('5001')."\">".$this->navchar[0]."</a> ";
                $str_pre   = " <a href=\"".$this->linkhead."pageID".$this->key."=".($this->pageID-1)."\" title=\"".$this->getSysMsg('5002')."\">".$this->navchar[1]."</a> ";
            }else if ($nolink_show) {
                $str_first = " <span class=\"".$nolink_color."\">".$this->navchar[0]."</span> ";
                $str_pre   = " <span class=\"".$nolink_color."\">".$this->navchar[1]."</span> ";
            }

            if ($this->pageID<$this->totalpage){
                $str_next  = " <a href=\"".$this->linkhead."pageID".$this->key."=".($this->pageID+1)."\" title=\"".$this->getSysMsg('5003')."\">".$this->navchar[2]."</a> ";
                $str_last  = " <a href=\"".$this->linkhead."pageID".$this->key."=".$this->totalpage."\" title=\"".$this->getSysMsg('5004')."\">".$this->navchar[3]."</a> ";
            }else if ($nolink_show){
                $str_next  =" <span class=\"".$nolink_color."\">".$this->navchar[2]."</span> ";
                $str_last  =" <span class=\"".$nolink_color."\">".$this->navchar[3]."</span> ";
            }

            return $str_first.$str_pre.$str_frontell.$str_num.$str_backell.$str_next.$str_last." ";
        }

		//用下拉列表显示如"到第n页\共m页"
        function pagejump($class = ''){
            if ($this->totalpage <= 1) return;

            $name  = "pageID".$this->key;

            $write ="<select name='".$name."' ";

            if (!empty($class)) $write .= "class='".$class."' ";

            $write .= "onchange='javascript:location.href=this.options[this.selectedIndex].value'>";

            for ($i = 1; $i <= $this->totalpage; $i++) {
                $write .= "<option value=".$this->linkhead."&".$name."=".$i;

                if ($this->pageID == $i) $write .= " selected";

                $write .= ">".$i."</option>";
            }

            $write .= "</select>";

            return $this->getSysMsg('5006',$write)."/".$this->getSysMsg('5007',$this->totalpage)." ";
        }

        //显示如"每页显示n条 "
        function maxnum() {
            return $this->getSysMsg('5011',$this->maxnum)." ";
        }

    } //end class

}//end if defined

		if($this){
					$pageID = $_GET["pageID"]*$page-$page;
					$start = $pageID>$num?0:$pageID;
					$start = $start<0?"0":$start;
					$pages = new PageTurn($num,$page);
					//$pages->this_pageID='当前页';
					$total = $pages->total();
					$pagehtml = $pages->pagehtml(10);
					$pageajax = $pages->pageajax(10);
					$pagenav = $pages->pagenav(10);
		}else{
					//global $in;
					if (array_key_exists('pageID',$_GET))
					$pageID = $_GET["pageID"]*$page-$page;
					$start = $pageID>$num?0:$pageID;
					$start = $start<0?"0":$start;
					$pages = new PageTurn($num,$page);
					//$pages->this_pageID='当前页';
					$total = $pages->total();
					$pagehtml = $pages->pagehtml(10);
					$pageajax = $pages->pageajax(10);
					$pagenav = $pages->pagenav(10);
		}
?>