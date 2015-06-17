<?php

/**
* 缓存管理类
*/
namespace tcphp;
class cache
{
    
    //缓存类实例
    private $cacheInstance = null;
    function __construct($type='',$config=array())
    {
        if(empty($type)) $type = C("DATA_CACHE_TYPE");
        switch ($type) {
            case 'file':
                $classname = "vendor\\cache\\file";
                
                if(class_exists($classname)){
                    $this->cacheInstance = new $classname($config);
                }else{
                    die($classname."类不存在");
                }
                break;
            case "memcache":
                $classname = "vendor\cache\memcache.class.php";
                if(class_exists($classname)){
                    $this->cacheInstance = new $classname($config);
                }else{
                    die("$classname类不存在");
                }
                break;
         
        }
    }


    //获得缓存
    public function get($name){
        return $this->cacheInstance->get($name);
    }

    //设置缓存
    public function set($name, $value){
         return $this->cacheInstance->set($name, $value);
    }
    //删除指定缓存
    public function rm($name){
        return $this->cacheInstance->rm($name);
    }

    //清空缓存
    public function clear(){
        return $this->cacheInstance->clear();
    }

}

