<?php
/**
 * @Author: mushu
 * @Date:   2015-06-08 22:17:36
 */
//控制器基类
namespace tcphp;
class controller{

   //view类实例
   private $view = null;

   //架构函数
   public function __construct(){
     //实例化view类
     $this->view = new tcphp\view();
   }

   //输出模板
   public function display($templateFile){

   }

   //输出html内容
   public function show($content,$charset='',$contentType=''){


   }
   //获取指定模板缓冲区的内容
   public function fetch($templateFile='', $content=''){

   }

   //变量赋值
   public function assign($name,$value){

   }

   //ajax返回
   public function ajaxReturn($data,$type){

   }
   //跳转方法
   public function jump(){
    
   }
    
}