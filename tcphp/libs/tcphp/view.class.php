<?php
/**
 * @Author: anchen
 * @Date:   2015-06-11 10:54:57
 */
//视图类
//负责解析模板文件 输出html
//现在只支持php原生模板
namespace tcphp;
class view{

    //模板变量
    protected $vars = array();

    //模板主题
    protected $theme = '';

    //设置模板变量
    public function assign($name, $value=''){
        if(is_array($name)){
            $this->vars = array_merge($this->vars, $name);
        }else{
            $this->vars[$name] = $value;
        }
    }

    //取得模板变量的值
    public function get($name=''){
        if($name==''){
            return $this->vars;
        }else{
            if(isset($this->vars[$name])){
                return $this->vars[$name];
            }else{
                return false;
            }
        }

    }

    //侦测模板位置，加载模板获取内容，输出内容
    /*
    
     */
    public function display($templateFile='',$charset='', $contentType='', $content=''){
          if(empty($content)){
          //侦测模板文件的位置
          $templateFile = $this->parseTemplate($templateFile);
          //加载模板获得内容
          $content = $this->fetch($templateFile);
          
          }

          //输出模板内容
          $this->render($content,$charset,$contentType);

    }

    //侦测模板的位置
    private function parseTemplate($templateFile){
        //首先判断如果$templateFile是一个文件，直接include加载
        if(is_file($templateFile)){
            return $templateFile;
        }
        //下面开始拼接路径，1：获取主题 2：获取模块  3： 获取控制器  4：获取操作
        $theme_on = C('THEME_ON');
        $theme = '';
        //如果启用主题功能
        if($theme_on){
            if(!empty($this->theme)){
                $theme = $this->theme;
            }else{
                $theme = C("DEFAULT_THEME");
            }

        }

        //如果$templateFile为空，按照默认的模块 控制器  操作进行定位
        //如果不为空，那么$templateFile必须写全  例如   模块/控制器/操作
        if(empty($templateFile)){
            $filename = APPLICATION_PATH.'/'.$theme.'/'.MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME.'.php';
        }else{
            $filename = APPLICATION_PATH.'/'.$theme.'/'.$templateFile.'.php';
        }
        if(!is_file($filename)){
            die("模板文件名不存在");
        }else{
            return $filename;
        }
    }

    //根据模板文件加载模板内容
    public function fetch($templateFile){
        //这里只使用php原生模板
         ob_start();
         extract($this->vars, EXTR_OVERWRITE);
         include $templateFile;
         // 获取并清空缓存
        $content = ob_get_clean();
        return $content;

    }

    //输出模板内容方法
    public function render($content, $charset, $contentType){
        if(empty($charset))  $charset = C('DEFAULT_CHARSET');
        if(empty($contentType)) $contentType = C('TMPL_CONTENT_TYPE');
        // 网页字符编码
        header('Content-Type:'.$contentType.'; charset='.$charset);
       
        header('X-Powered-By:tcphp');
        // 输出模板文件
        echo $content;

    }
    
   

}