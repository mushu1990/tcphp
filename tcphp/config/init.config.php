<?php 
  //框架配置项
 	

  return array(
  		"SHOW_PAGE" => 1,
  		"PAGE" => 2,
  		"DEBUG" =>1,
  		//默认的MVC参数
  		"DEFAULT_MODULE" => "index",
  		"DEFAULT_CONTROL" => "index",
  		"DEFAULT_ACTION" => "init",
  		//控制器后缀
  		"CONTROL_FIX" =>"Controller",
  		"CLASS_FIX" =>".class.php",

        //默认的分页参数名称
        'VAR_PAGE'=>'p',

      'DEFAULT_M_LAYER'       =>  'model', // 默认的模型层名称
      'DEFAULT_C_LAYER'       =>  'controller', // 默认的控制器层名称
      'DEFAULT_V_LAYER'       =>  'view', // 默认的视图层名称

      //默认的mvc参数
      "VAR_MODULE"=>'m',
      "VAR_CONTROLLER"=>'c',
      "VAR_ACTION"=>'a',
      //兼容模式参数
      "VAR_PATHINFO"=>'s',
  		//pathinfo的分隔符,默认是/
      "URL_PATHINFO_DEPR"=>'/',
      //多模块
      "MULTI_MODULE"=>true,
      //允许的模块列表
      "MODULE_ALLOW_LIST"=>array(),
      //url模式
      'URL_MODEL'=>'',

      //路由
      'URL_MAP_RULES'=>array(),
      'URL_ROUTE_RULES'=>array(),
      //默认主题
      "DEFAULT_THEME"=>'default',
      //是否启用主题功能
      "THEME_ON"=>true,

      //默认编码
      "DEFAULT_CHARSET"=>'utf-8',
      //默认输出的内容类型
      "TMPL_CONTENT_TYPE"=>'text/html',
      //
  		//错误信息
  		//异常处理模板
  		"error_tpl" => PHP_PATH."/tpl/"."php_exception_tpl.php",
        //跳转成功显示模板
        "TMPL_ACTION_SUCCESS"=>PHP_PATH."/tpl/"."php_success_tpl.php",
        //跳转失败显示模板
        "TMPL_ACTION_ERROR"=>PHP_PATH."/tpl/"."php_error_tpl.php",

        //默认的缓存类型
        "DATA_CACHE_TYPE"=>'file',
        "DATA_CACHE_PATH"=>APP_PATH.'/temp/caches/',
        "DATA_CACHE_PREFIX"=>'tcphp',
        "DATA_CACHE_TIME"=>'0',
        "DATA_CACHE_COMPRESS"=>true,
        "DATA_CACHE_CHECK"=>true,
        "DEFAULT_AJAX_RETURN"=>"JSON",
        //上传文件类型
        "FILE_TYPE"=>array(  
            'image/jpg',  
            'image/jpeg',  
            'image/png',  
            'image/pjpeg',  
            'image/gif',  
            'image/bmp',  
            'image/x-png'  
        ),

  	);

 ?>


