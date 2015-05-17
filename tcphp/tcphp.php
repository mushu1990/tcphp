<?php


      /*
		计算脚本运行时间函数
	  */
		function run_time($start, $end='', $decimial=3){
			static $times = array();
			if($end !=''){
				$times[$end] = microtime();
				return number_format($times[$end] - $times[$start], $decimial);

			}
			$times[$start] = microtime();
		}
	  //记录开始运行时间
	  run_time("start");
	  //项目初始化
	  defined('APP_PATH') or define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']));
	  
	  //框架主目录
	  define( "PHP_PATH", dirname(__FILE__));

	  //定义框架的资源目录
	  define('STATICS', APP_PATH. '/'. 'statics');

	  define('TEMP_PATH', APP_PATH.'/'.'temp');


	  
	 
	 
	 
	  //加载编译文件
	  $runtime_file = TEMP_PATH . ' /runtime.php ' ;
	  if( is_file($runtime_file)){
	  	require $runtime_file;
	  }else {
	  	include PHP_PATH . ' /common/runtime.php ' ;
	  	runtime() ;
	  }
	  APP::run();
	  