<?php
/*
* @time: 2015-5-8
* @author: mushu
*/

//调试处理类
namespace tcphp;
class debug{
	static $debug = array();//存储错误信息
	
	//存储错误信息到静态数组变量中
	public static function msg($msg){
		self::$debug[] = $msg;
		self:: show();
	}
	
	 /**
	   * 显示错误信息
	   * @access public
	   * @param 参数类型  参数  参数说明
	   * @return 返回值类型 返回值说明
	   * 2015-5-8下午7:17:25
	   */
	public static function show(){
		self::$debug[] = run_time("start", "end");
		echo "<div style='border:solid 2px #dcdcdc'>";
		foreach (self::$debug as $v){
			echo $v;
		}
		echo "</div>";
	}
	
	
	
	 
	
	 
	
}