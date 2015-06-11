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
  		"CONTROL_FIX" =>"Control",
  		"CLASS_FIX" =>".class.php",

        //默认的分页参数名称
        'VAR_PAGE'=>'p',

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
      
  		//错误信息
  		//异常处理模板
  		"error_tpl" => PHP_PATH."/tpl/"."php_error_tpl.php",
  	);

 ?>


