<?php
/**
* session管理类
*/
namespace tcphp;
class session
{
    
   //启动session
   //传入启动参数。
    /*   
   php 中 session的实现是给每个session 分配一个唯一id,称作session_id。这个id默认通过cookie存在客户端，cookie的name可能会叫做 PHPSESSID ，可以通过 session.name 配置。
   调用 session_start()时， php就会解析 PHPSESSID的值，拿到ID。根据ID去取session的值。
      session值默认是文件存放在 session.save_path目录下。
    对session 配置操作的函数比如 session_name()、session_set_cookie_params()、session_save_path() 都要在session_start() 前调用， 不然php 无法解析session的值。
   

    config参数：
    1：prefix ：session前缀 默认为空
    2：name ：  session_name()函数设置，用于在客户端cookie的sessionid的键名，默认是PHPSESSID
    3：path： 设置session的存储路径
    4：domain:  session跨域问题
    5：expire：session的过期时间
    6：type： session驱动

    */
   public static function start($config=array()){
        if(isset($config['name']))            session_name($config['name']);
        if(isset($config['path']))            session_save_path($config['path']);
        if(isset($config['domain']))          ini_set('session.cookie_domain', $config['domain']);
        if(isset($config['expire']))          ini_set('session.gc_maxlifetime', $config['expire']);
        if(isset($config['type'])){
            $classname = 'vendor\\session\\'.$config['type'];
            $hander = new $classname();
             session_set_save_handler(
                array(&$hander,"open"), 
                array(&$hander,"close"), 
                array(&$hander,"read"), 
                array(&$hander,"write"), 
                array(&$hander,"destroy"), 
                array(&$hander,"gc")); 
        }
       
        session_start();

   }

   //设置session
   public static function set($name, $value){
        $_SESSION[$name] = $value;

   }

   //获取session
   public static function get($name='')
   {
       if(empty($name)){
           return $_SESSION;
       }
       return $_SESSION[$name];

   }
   //暂停sesson
   public static function pause(){
       session_write_close();
   }

  

   //销毁session
   public static function destroy(){
       $_SESSION =  array();
       session_unset();
       session_destroy();
   }

   //重新生成id
   public static function regenerate(){
      session_regenerate_id();
   }
   




}