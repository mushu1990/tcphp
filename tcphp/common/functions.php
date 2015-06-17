<?php

//公共函数库

//打印错误
function error($msg){
	  $error_trace = array();
	  
	  if(C("DEBUG")){
	  	if(!is_array($msg)){
	  		$traces = debug_backtrace();
	  		foreach ($traces as $trace){
	  			//$infos .= $msg . $trace['file'] . $trace['line']. $trace['function'].  $trace['args']. "</br>";
	  		}
	  		//echo $infos;
	  		  		
	  	}else {
	  		
	  	}
	  	include C("error_tpl");
	  }
}

 /**
   * 载入文件
   * @access public
   * @param string  $filename  文件名称
   * @return void
   * 2015-5-8下午6:46:36
   */
function loadFile($filename=''){
	
	static $fileArr = array();
	if(empty($filename)){
		return  $fileArr;
	}
	if(!isset($fileArr[$filename])){
		if(!is_file($filename)){
			$mag = $filename."文件不存在";
		}else{
			require $filename;
			$fileArr[$filename] = true;
			$mag =$filename. "文件载入成功";
		}
		call_user_func_array(array("tcphp\\debug","msg"), array($mag));
		
	}else{
		return $fileArr[$filename];
	}
	
}

 /**
   * 配置文件处理函数
  *处理以下几种情况：
 * 0:   C();获得所有的配置信息
 * 1:   C("tom"); //取tom的值
 * 2:   C("tom", '1'); //设置tom的值
 * 3:   C("tom.age", '2');  设置tom的年龄（二维数组）的值为2
 * 4:   C("tom.age");  取得tom的年龄
 * 5:   C(array("tom"=>2));  合并数组到主配置文件数组中
   * 2015-5-9上午10:14:42
   */
  function  C  ($name=null, $value = null ){
  	    static $config = array() ;
        if(is_null($name)){
          return $config;
        }
  	    if( is_string($name)){
  	    	$name  = strtolower( $name ) ;
  	    	if (!strstr( $name, ".")){
  	    		if (is_null($value)){
  	    			return isset($config[$name]) ? $config[$name] : null ;
  	    		}else{
  	    			$config[$name] = $value;
              return;
  	    		}
  	    	}else{
  	    		$name = explode(".", $name);
  	    		if(is_null($value)){
  	    			return isset($config[$name[0][1]]) ? $config[0][1] : null ;
  	    		}else{
  	    			$config[$name[0][1]] = $value;
              return;
  	    		}
  	    	}
  	    }

        if(is_array($name)){
          $config = array_merge($config, array_change_key_case($name));
          return true;
        }
  	    
  }
  
  //md5序列化加密
  function _md5( $var ){
  	return md5( serialize($var ));
  }

 /**
   * 实例化对象或者执行方法
   * @param1  stirng   $class  类
   * @param2  stirng   $method  方法
   * @param3  array    $args  参数
   * @return  返回值说明
   * 2015-5-9下午8:42:21
   */
  function O( $class, $method =null, $args=array()){
  	static $result = array();
  	$name = empty($args) ? $class.$method : $class.$method._md5($args);
  	if(!isset($result[$name])){
  		
  		$obj = new $class();
  		if(!is_null($method) && method_exists($obj, $method)){
  			if(!empty($args)){
  				$result[$name] = call_user_func_array(array($class, $method), array($args));
  			}else{
  				$result[$name] = $obj->$method();
  				
  			}
  		}else{
  			$result[$name] = $obj;
  		}
  	}
  		return $result[$name];
  	
  }
  
  
  // 浏览器友好的变量输出
  function dump($var, $echo=true, $label=null, $strict=true) {
  	$label = ($label === null) ? '' : rtrim($label) . ' ';
  	if (!$strict) {
  		if (ini_get('html_errors')) {
  			$output = print_r($var, true);
  			$output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
  		} else {
  			$output = $label . print_r($var, true);
  		}
  	} else {
  		ob_start();
  		var_dump($var);
  		$output = ob_get_clean();
  		if (!extension_loaded('xdebug')) {
  			$output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
  			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
  		}
  	}
  	if ($echo) {
  		echo($output);
  		return null;
  	}else
  		return $output;
  }
  
  //实例化控制器
  function  A( $control ){
  	
  }
  
  //格式化内容 去空白
  function  del_space(){
  	
  }

  //替换字符串中间的字符为*号,字节数少于4的时候返回整个字符串
  function replaceStar($str){
    
     return  preg_replace('/^(..).*(..)$/', '$1****$2', $str);
  }

  //计算字符串的长度(汉字按照两个字符长度计算，1个字符1字节)
  function str_len($str){
    //利用正则的ascii字符先把字符串中的英文等单字节替换成空，然后计算替换后的字符长度
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
  
    //$length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
    //如果长度大于0，说明$tsr中就含有汉字,那么长度的计算方法如下所示.
    ////因为汉字在utf8下为3字节，所以会先除以3
    if($length){
        return strlen($str) - $length + intval($length/3)*1;
    }else{
        return strlen($str);
    } 


  }

  //编译文件
  function compile($filename){
      

  }


  //json格式化输出
  /**
 * Indents a flat JSON string to make it more human-readable.
 * @param string $json The original JSON string to process.
 * @return string Indented version of the original JSON string.
 */
function indent ($json) {

$result = '';
$pos = 0;
$strLen = strlen($json);
$indentStr = '';
$newLine = "＼n";
$prevChar = '';
$outOfQuotes = true;

for ($i=0; $i<=$strLen; $i++) {

// Grab the next character in the string.
$char = substr($json, $i, 1);
// Are we inside a quoted string?
if ($char == '"' && $prevChar != '＼＼') {
$outOfQuotes = !$outOfQuotes;
// If this character is the end of an element,
// output a new line and indent the next line.
} else if(($char == '}' || $char == ']') && $outOfQuotes) {
$result .= $newLine;
$pos --;
for ($j=0; $j<$pos; $j++) {
$result .= $indentStr;
}
}
// Add the character to the result string.
$result .= $char;
// If the last character was the beginning of an element,
// output a new line and indent the next line.
if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
$result .= $newLine;
if ($char == '{' || $char == '[') {
$pos ++;
}
for ($j = 0; $j < $pos; $j++) {
$result .= $indentStr;
}
}
$prevChar = $char;
}

return $result;

}




   //数组转xml

   /**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id   数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root='think', $item='item', $attr='', $id='id', $encoding='utf-8') {
    if(is_array($attr)){
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        
        $attr = implode(' ', $_attr);
    }
    $attr   = trim($attr);
    $attr   = empty($attr) ? '' : " {$attr}";
    $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml   .= "<{$root}{$attr}>";
    $xml   .= data_to_xml($data, $item, $id);
    $xml   .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed  $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id   数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item='item', $id='id') {
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if(is_numeric($key)){
            $id && $attr = " {$id}=\"{$key}\"";
            $key  = $item;
        }
        $xml    .=  "<{$key}{$attr}>";
        $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
        $xml    .=  "</{$key}>";
    }
    return $xml;
}
























