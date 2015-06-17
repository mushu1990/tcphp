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
   function __construct(){
     //实例化view类
     $this->view = new view();
    
   }

   //输出模板
   public function display($templateFile){

       $this->view->display($templateFile);

   }

   //输出html内容
   public function show($content,$charset='',$contentType=''){

        $this->view->display('', $charset, $contentType, $content);

   }
   //获取指定模板缓冲区的内容
   public function fetch($templateFile=''){

        return $this->view->fetch($templateFile);
   }

   //变量赋值
   public function assign($name,$value){
        $this->view->assign($name,$value);
   }

   //ajax返回
   /*
   ajax可以返回格式的数据，包括json，xml，jsonp，可执行的js脚本
    */
   public function ajaxReturn($data,$type=''){
      if(empty($type)) $type = C("DEFAULT_AJAX_RETURN");
      switch ($type) {
          case 'JSON':
              header('Content-Type:application/json; charset=utf-8');
              exit(json_encode($data));
              break;
          case 'XML':
              header('Content-Type:text/xml; charset=utf-8');
              exit(xml_encode($data));
              break;
          case "JSONP":
              header("Content-Type:application/json; charset=utf-8");
              $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
              exit($handler.'('.json_encode($data).');');
              break;
          case "EVAL":
              exit($data);
              break;
          
        
      }


   }
   //跳转方法
   public function jump($message, $status,$jumpUrl=''){
       //先assign变量
       if($status){//成功状态
          $this->assign('message',$message);
          $this->assign('waitSecond',1);
          if(empty($jumpUrl)){
            $this->assign("jumpUrl", $_SERVER['HTTP_REFERER']);
          } else{
            $this->assign("jumpUrl", $jumpUrl);
          }
          $this->display(C("TMPL_ACTION_SUCCESS"));

       }else{
            $this->assign('message',$message);
            //发生错误停留3秒
            $this->assign('waitSecond',3);
            //发生错误默认返回上页
            if(empty($jumpUrl)){
            $this->assign("jumpUrl", "javascript:history.back(-1);");
            } else{
            $this->assign("jumpUrl", $jumpUrl);
            }
            $this->display(C("TMPL_ACTION_ERROR"));
            exit;
            
       }
    
   }
    
}