<?php
//运行时文件

 /**
   * 创建运行时文件
   * 2015-5-8下午11:19:12
   */
  function mkdirs () {
      //判断目录是否存在
  	  if (!is_dir(TEMP_PATH) ){
  	  	@mkdir(TEMP_PATH, 0777);
  	  }

      //检测目录是否有写权限
  	  if (! is_writeable(TEMP_PATH)){
  	  		error( "目录没有写权限" );
  	  }
      if(!is_dir(CACHE_PATH)) mkdir(CACHE_PATH, 0777);
      if(!is_dir(LOG_PATH)) mkdir(LOG_PATH, 0777);
      if(!is_dir(CONFIG_PATH)) mkdir(CONFIG_PATH, 0777);
      if(!is_dir(TEMPLATE_PATH)) mkdir(TEMPLATE_PATH, 0777);
      if(!is_dir(TPL_PATH)) mkdir(TPL_PATH, 0777);

  }
  
   /**
     * 创建运行时缓存文件
       * 2015-5-8下午11:44:04
     */
  function runtime(){
    $files = require_once PHP_PATH . '/common/files.php';
    foreach ($files as $key => $value) {
      if(is_file($value)){
      
        require $value;
      }
    }
    
    mkdirs();
    C(require PHP_PATH.'/libs/etc/init.config.php');
   

  	
  }



