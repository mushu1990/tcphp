<?php

//项目处理类
class APP{
	//模块
     static  $module ;
     //控制器
     static  $control ;
     //动作方法
     static  $action ;
     
      /**
        * run方法
        * @access public
        * @return void
        * 2015-5-8下午9:36:52
        */
      static function run () {
      	spl_autoload_register (array(__CLASS__ , "autoload"));
      	//register_shutdown_function( "APP::fatalError" );
      	//set_error_handler("APP::appError");
      	//set_exception_handler( "APP::appException" );
        self::init();
       
      		//组合出执行的文件路径
      		$control_file = MODULE_PATH . '/' . self::$module . '/' .self::$control . C('CONTROL_FIX').C('CLASS_FIX');
      		$class_file = self::$control . C('CONTROL_FIX');
      	
      	
      		loadFile($control_file);
      		$control_obj = O($class_file);
      		$action = self::$action;
      		$control_obj -> $action();
		
		
      }

      //初始化配置
      static function init(){
      	self:: config();
      	self::$module = self::module();
      	self::$control = self::control();
      	self::$action = self::action();

      }

      //初始化配置文件处理
      static function config(){
      	 $config_file = CONFIG_PATH. '/config.php';
      	 if( is_file($config_file)){
      	 	C(require $config_file);
      	 }

      }
      
       /**
         * 获得模块
         * @access private
         * @return void
         * 2015-5-9下午8:21:00
         */
      private  static function module(){
      	  if( isset($_GET['m']) && !empty($_GET['m'])){
      	  	  return $_GET['m'];
      	  }
      	  
      	  return C( "DEFAULT_MODULE" );
      	
      }
      
      /**
       * 获得控制器
       * @access private
       * @return void
       * 2015-5-9下午8:21:00
       */
      private  static function  control(){
      	if( isset($_GET['c']) && !empty($_GET['c'])){
      		return $_GET['c'];
      	}
      	 
      	return C( "DEFAULT_CONTROL" );
      }
      
      /**
       * 获得action
       * @access private
       * @return void
       * 2015-5-9下午8:21:00
       */
      private  static function  action(){
      	if( isset($_GET['a']) && !empty($_GET['a'])){
      		return $_GET['a'];
      	}
      	 
      	return C( "DEFAULT_ACTION" );
      }
      
       /**
         * 自动加载函数
         * @param string  $classname  类名称
         * @return void
         * 2015-5-8下午9:43:21
         */
      static  function  autoload ( $classname ){
      		$classfile = PHP_PATH . '/libs/bin/' . $classname. '.class.php' ;
          loadFile($classfile);
      		
      		
      	
      }
      
       /**
         * 用户错误处理函数
         * @access static   
         * @param int $type 错误类型
         * @param string $errstr 错误信息
         * @param stirng $errfile 错误文件
         * @param int $errline 错误行数
         * @return  void          
         * 2015-5-10上午11:26:15
         */
      static  function appError( $errno, $errstr, $errfile, $errline ){
		      
		          switch ($errno){
		          	case E_ERROR:
		          	case E_PARSE:
		          	case E_CORE_ERROR:
		          	case E_COMPILE_ERROR:
		          	case E_USER_ERROR:
		          		$errorStr = "$errstr ".$errfile." 第$errline 行";
		          		error($errorStr);
		          		break;
		          		
		          	default:
		          		$errorStr = "$errstr ".$errfile." 第$errline 行";
		          		error($errorStr);
		          		break;
		          		
		          }
      	
      }
      
       /**
         *  致命错误捕获
         * @access public    
         * 2015-5-10下午3:09:53
         */
      static  function fatalError(){
      	
      }
      
       /**
         * 自定义异常处理
         * @access public
         * @param mixed $e  异常对象
         * @return void
         * 2015-5-10下午3:10:20
         */
      static function appException( $e ){
      	
      }
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
}