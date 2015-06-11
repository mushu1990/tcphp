<?php

  //配置框架的目录结构
  define( "CACHE_DIR" , "caches"); //缓存目录
  define( "LOG_DIR", "log") ; //日志目录
  define( "CONFIG_DIR", "config") ; //配置目录
  define( "TEMPLATE_DIR", "template") ;  //模板目录
  define( "TPL_DIR", "tpl") ; //模板编译目录
   
  
  
  define( "CACHE_PATH", TEMP_PATH . '/' .CACHE_DIR) ; 
  defined("APPLICATION_PATH") or define("APPLICATION_PATH", APP_PATH.'/'.APPLICATION_DIR);
  define( "LOG_PATH", TEMP_PATH . '/' . LOG_DIR) ;
  define( "CONFIG_PATH", APPLICATION_PATH . '/'. CONFIG_DIR) ;
  define( "TEMPLATE_PATH", APP_PATH. '/' . TEMPLATE_DIR) ;
  define( "TPL_PATH", TEMP_PATH . '/' . TPL_DIR) ; 
