<?php
class DBA{
   var $conID;
   var $database;
   var $lastErrorMsg;
   var $debug = false;
   var $fetchMode = MYSQL_BOTH;
   var $PHPVERSION;
   var $MYSQLVERSION;
   var $magic_quotes_gpc;
   var $charset = 'UTF-8'; 
  
   function DBA($AutoConnect = true){
    if($AutoConnect){
     if($this->Connect(DB_HOST,DB_PORT,DB_USERNAME,DB_PASSWORD)) $this->SelectDB(DB_DATABASE);
    }
    $this->magic_qutoes_gpc = get_magic_quotes_gpc();
  } 

  function Connect($host,$port,$username,$password){
  		//echo "$host,$port,$username,$password";
       $this->conID = mysql_connect($host.':'.$port,$username,$password) or $this->_Error();
       return $this->conID;
  } 
  
  function pConnect($host,$port,$username,$password){
       $this->conID = mysql_pconnect($host.':'.$port,$username,$password) or $this->_Error();
       return $this->conID;
  } 
  //mysql 4.1 charset
  function SetCharset($charset = 'UTF-8'){       
    $this->charset  = $charset ;       
       if($this->MysqlVersion() >= 4.1){
     $this->_Query("SET NAMES ".str_replace("-","",$charset));
    }   
  }

  function SelectDB($database){
     $this->database;
	  $ret = mysql_select_db($database,$this->conID) or $this->_Error();
     return $ret;
   }

  function _Query($sql){
	$sql  = $this->PreFix($sql);
   
    if($this->debug){
     echo "<pre>\n";
     echo "$sql\n";
     echo "</pre>\n";
  }
        $result =  mysql_query($sql,$this->conID) or $this->_Error();
        return $result;
   }

   //
   function PreFix($sql){
       return str_replace("#@#",DB_PREFIX,$sql);
   }

   function Strip($str){
      return $this->Qstr($str , $this->magic_quotes_gpc);
   }

// htmlspecialchars
   function HtmlEncode($str){
     $ret = $this->Qstr($str , $this->magic_quotes_gpc);
        return htmlspecialchars($ret,ENT_QUOTES,$this->charset);
   }

// For GetInsertSQL , GetUpdateSQL
  function GetPost(){
     $num_args = func_num_args();
     $args = func_get_args();
     $post = array();
     foreach($_POST as $k => $v){
         if($num_args > 0 && in_array($k,$args)){
         $post[$k] = $this->Qstr($v ,$this->magic_qutoes_gpc);
      }else{
         $post[$k] = $this->HtmlEncode($v);
      }
     }
     return $post;
  }
  
 function Execute($sql){
   $sql = trim($sql);
    if((strncasecmp("SELECT",$sql,6) == 0) || (strncasecmp("SHOW",$sql,4) == 0)){
        $result = new DBA_Result($this,$sql);
      return $result;
    }else{
           return $this->_Query($sql);
    }
   }
   
 function Select($table , $where='' , $GetRow = false){
     
	if($where != ''){
		$prefix = substr(strtoupper(trim($where)),0,8);
		if( !in_array($prefix , array("GROUP BY","ORDER BY","WHERE 1"))){
			$where = " WHERE $where";
		}
   }  
   if($GetRow !== false)
	  return $this->GetRow("SELECT * FROM `$table` $where");
      return $this->Execute("SELECT * FROM `$table` $where");
 }
 
 function Insert($table,$arr =''){    
	$post = $this->GetPost(); 
  if(!empty($arr)){
     $post = array_merge($post,$arr);
  } 
     return $this->Execute($this->GetInsertSQL($this->Select($table),$post));
 }

 function Update($table,$where='' ,$arr = ''){
     $post = $this->GetPost();
  if(!empty($arr)){
     $post = array_merge($post,$arr);
  }
  return $this->Execute($this->GetUpdateSQL($this->Select($table,$where),$post));
 }

 function Delete($table , $where = ''){
      if($where != ''){
      $where = " WHERE $where";
      }
   return $this->Execute("DELETE FROM `$table` $where");   
 }

 function SelectLimit($sql,$max=0,$offset=0){
       $max = intval($max);
	   $offset = intval($offset);
    if($max>0){
       $sql = sprintf("%s LIMIT $offset , $max",$sql);  
    }
    return $this->Execute($sql);   
  }
	 
  function GetRow($sql){
      $rs = $this->_Query(sprintf("%s LIMIT 0,1",$sql));
	  return mysql_fetch_array($rs,$this->fetchMode);
  }

  function GetOne($sql){
	$rs = $this->_Query(sprintf("%s LIMIT 0,1",$sql));
		if(mysql_num_rows($rs)>0) {
			return mysql_result($rs,0);
		}
  }
 
  function GetArray($sql){
      $rs = $this->Execute($sql);
	  $rows = array();  
   while($row = $rs->FetchRow()){
      $rows[] = $row;
   }
   return $rows;
  } 
 
  function Last_insert_id(){
      return $this->GetOne("SELECT LAST_INSERT_ID()");
  }
 
  function Insert_ID(){
      return $this->Last_insert_id();
  }
 
  function Affected_Rows(){
      return mysql_affected_rows($this->conID);
  }
  
  function GetInsertSQL(&$rs,$array){
    $field_arr = $rs->Fields();
    $table = $rs->fieldTable;
    $sql_k = array();
    $sql_v = array();
 
    foreach($field_arr as $field){
     $name  = $field['name'];
     $type = $field['type'];
     $len = $field['len'];
     $flags = $field['flags'];
        

     if(isset($array[$name])){     
        $sql_k[] = "`".$name."`";
      //if(strncasecmp("date",$type,4) ==0 || strncasecmp("int",$type,3) == 0){
       //   $sql_v[] = $this->Qstr($array[$name]);
      //}else{
          $sql_v[] = "'".$array[$name]."'";
      //}
     }          
    }
       $count = count($sql_k);
       
    $sql_k_str = implode(",",$sql_k);
    $sql_v_str = implode(",",$sql_v);
      
    $sql = "INSERT INTO $table($sql_k_str) VALUES($sql_v_str)";
    unset($sql_k,$sql_v);
    return $sql; 
  }

  function GetUpdateSQL(&$rs,$array){
        
   $sql = $rs->sql;
   $fields_arr = $rs->Fields();
         $table = $rs->fieldTable;
         $where = '';
   $sql_upper = strtoupper($sql);

   $pos = strpos($sql_upper," WHERE ");
     
         if($pos !== false){
    $sql_len = strlen($sql);
             $offset =  $sql_len - $pos;
           
   if(false !== ($pos3 = strpos($sql_upper," GROUP BY "))){
      $offset = $pos3  - $pos ;
   }elseif(false !== ($pos3 =strpos($sql_upper," ORDER BY "))){
      $offset = $pos3 - $pos ;
   }
      $where = substr($sql,$pos,$offset);
   }
       $set_arr = array();
  
   foreach($fields_arr as $field){
       $name  = $field['name'];
       $type = $field['type'];
       $len = $field['len'];
       $flags = $field['flags'];
    if(isset($array[$name])){
       // if(strncasecmp("date",$type,4) ==0 || strncasecmp("int",$type,3) == 0){
      //  $v =  $array[$name];
     //}else{
        $v = "'".$array[$name]."'";
     //}
     $set_arr[] =" `$name`=$v";
    }
   }
   if(!empty($set_arr)){
     $set = " SET ".implode(",",$set_arr);
   }else{
     $set = " SET 1=1 ";
   }  
        $sql = "UPDATE `$table` $set $where";
	return $sql;       
  }

   function Qstr($str,$magic_quotes_gpc = false){
       if($magic_quotes_gpc === true){
        $str = stripslashes($str);
    }
   
    if($this->PHPVERSION() >= 4.3){
		return mysql_real_escape_string($str,$this->conID);
    }else{
		return mysql_escape_string($str);
    }
  }
 
  function QMagic($str){
     return $this->Qstr($str,get_magic_quotes_gpc());
  }

  function MysqlVersion(){
  if(!empty($this->MYSQLVERSION)) return $this->MYSQLVERSION;
  $result = mysql_query("SELECT VERSION()",$this->conID);
  $row = mysql_fetch_assoc($result);

  foreach($row as $v){
      if (preg_match('/([0-9]+\.([0-9\.])+)/',$v, $arr)){
       $this->MYSQLVERSION = (float)$arr[1];
      }
  }
     return $this->MYSQLVERSION;
 }

   function PHPVERSION(){
      if(!empty($this->PHPVERSION)) return $this->PHPVERSION;
	  return PHPVERSION();
   }
   function Password($password){
      return md5($password);
  
   } 
   function ErrorMsg(){
        return  $this->lastErrorMsg;
   }
  
  function _Error(){
       $this->lastErrorMsg = mysql_error();
   
    if($this->debug && !empty($this->lastErrorMsg)){
      echo $this->ErrorMsg();exit;
    }
    return false;
   }
 
  function SetFetchMode($fetchMode){
    $this->fetchMode = $fetchMode;
   }

  function Debug($str){
      echo "<pre>\n";
	  var_dump($str);
	  echo "</pre>\n";
   }

  function Close(){
       return mysql_close($this->conID);
  } 
}

Class DBA_Result{
 var $sql;
 var $result;
 var $conID;
 var $object;
 var $fetchMode;
 var $currentRow = -1;
 var $numRows;
 var $numFields;
 var $fields_init;
 var $fieldTable;
 var $EOF = true;
   
 function DBA_Result(&$object,$sql){
   $this->sql = $sql;
     
   $this->result =& $object->_query($this->sql);
  
         $this->object =& $object;
     
         $this->conID =& $object->conID;

   $this->fetchMode = $object->fetchMode;        
         $this->init();  
 }

 function init(){
    $this->numRows = mysql_num_rows($this->result);
    if($this->numRows > 0 ) $this->EOF = false;
       $this->numFields = mysql_num_fields($this->result);
   }
    
 function RecordCount(){
    if(!is_numeric($this->numRows)){
    $this->numRows = mysql_num_rows($this->result);
  }
  return $this->numRows;
 }
 
 function NumRows(){
    return $this->RecordCount();
 }

 function FetchRow(){             
    if($this->_Fetch()){
        $this->currentRow += 1;
		$this->EOF = false;
    }else{
        $this->EOF = true ;
		$this->currentRow = $this->numRows;
    }   
    return $this->fields;
 }

 function _Fetch(){
    $this->fields =  mysql_fetch_array($this->result,$this->fetchMode);
	return is_array($this->fields);
 }

 

function Seek($offset){
    if($this->numRows == 0) return false;
    else return mysql_data_seek($this->result,$offset);
 }
 
 function MoveNext(){
      if($this->_Fetch()){
      $this->currentRow += 1;
   $this->EOF = false;
   return true;
   }else{
      $this->currentRow = $this->numRows;
   $this->EOF = true;
   return false;
   }
 }

 function Fields(){
    
   $this->fieldTable = mysql_field_table($this->result,0);
      $arr = array();
   for($i=0;$i<$this->numFields;$i++){
       $arr[] = array( 'type'=>mysql_field_type($this->result,$i) ,
					   'name'=>mysql_field_name($this->result,$i),
					   'len'=>mysql_field_len($this->result,$i),
					   'flags'=>mysql_field_flags($this->result,$i)
					  );
   }
   return $arr;
 }   

  function Close(){
     return mysql_free_result($this->result);
 } 
}
?>