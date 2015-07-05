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
      //定义应用
      define('APPLICATION_DIR','blog');
	  //定义网站主目录
	  defined('ROOT') or define('ROOT',dirname($_SERVER['SCRIPT_NAME']));
	  //框架主目录
	  define( "PHP_PATH", dirname(__FILE__));

	  //定义框架的资源目录
	  define('STATICS', APP_PATH. '/'. 'statics');

	  define('TEMP_PATH', APP_PATH.'/'.'temp');


	  // URL 模式定义
      const URL_COMMON        =   0;  //普通模式
      const URL_PATHINFO      =   1;  //PATHINFO模式
      const URL_REWRITE       =   2;  //REWRITE模式
      const URL_COMPAT        =   3;  // 兼容模式

	  define('_PHP_FILE_',    rtrim($_SERVER['SCRIPT_NAME'],'/'));
	 
	 
	  //加载编译文件
	  $runtime_file = TEMP_PATH . ' /runtime.php ' ;
	  if( is_file($runtime_file)){
	  	require $runtime_file;
	  }else {
	  	include PHP_PATH . ' /common/runtime.php ' ;
	  	runtime() ;
	  }
	  APP::run();
	  