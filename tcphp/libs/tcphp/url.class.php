<?php
/**
 * @Author: mushu
 * @Date:   2015-06-08 22:15:00
 * @Last Modified by:   mushu
 * @Last Modified time: 2015-06-09 11:07:59
 */
//url解析类
namespace tcphp;
class url{

    /*
    静态类
    url解析类
    负责从客户端的url中解析出mvc参数
    支持兼容模式，pathinfo模式和普通模式
     */
    public static function parse(){
        //从配置文件中得到mvc参数变量
        $m = C("VAR_MODULE");
        $c = C("VAR_CONTROLLER");
        $a = C("VAR_ACTION");
        //兼容模式的参数变量
        $s = C("VAR_PATHINFO");
        //如果不是一般模式，则再执行下面的代码
        if(!isset($_GET[$m]) || !isset($_GET[$c]) || !isset($_GET[$a])){
        //首先判断是否是兼容模式
        if(!empty($_GET[$s])){
            $_SERVER['PATH_INFO'] = $_GET[$s];
        }

        /*至此pathinfo里面应该是有值的，如果没有，说明服务器不支持pathinfo。
          解决办法：自己造一个pathinfo返回。
        */
        if(!isset($_SERVER['PATH_INFO'])){
            $types = explode(',', C("URL_PATHINFO_FETCH"));
            foreach ($types as $key => $type) {
                if(0 == strpos($type, ":")){
                    $_SERVER['PATH_INFO'] = call_user_func(substr($type, 1));
                    break;
                }elseif (!empty($_SERVER[$type])) {
                    $_SERVER['PATH_INFO'] = (0==strpos($_SERVER[$type], $_SERVER['SCRIPT_NAME'])) ? substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME'])) : $_SERVER[$type];
                }
            }
        }
       
        /*上面已经获得的pathinfo，下面开始分析pathinfo*/
        //再次检查pathinfo，如果还为空，说明为普通模式
        if(empty($_SERVER['PATH_INFO'])){
             $_SERVER['PATH_INFO'] = '';
            define('__INFO__','');
            define('__EXT__','');
        }else{
        //首选取得配置文件中pathinfo的分隔符
        $depr = C("URL_PATHINFO_DEPR");
        //去掉pathinfo前后的/
        define('__INFO__', trim($_SERVER['PATH_INFO'], '/'));
        //获取pathinfo的后缀，如果有的话
        define('__EXT__', strtolower(pathinfo($_SERVER['PATH_INFO'],PATHINFO_EXTENSION)));
        $_SERVER['PATH_INFO'] = __INFO__;
        
        //开始从pathinfo中分析出mvc参数,前提条件是你没有使用绑定模块，并且使用了多模块
        if(isset($_SERVER['PATH_INFO']) && !defined("BIND_MODULE") && C("MULTI_MODULE")){
            //解析出模块名称，约定pathinfo的第一个分割符前面的字符肯定是模块
            $paths = explode($depr, __INFO__, 2);
            //得到允许的模块列表
            $allowList = C("MODULE_ALLOW_LIST");

            //拿到模块名称后，对模块名称进行检查。要检查的内容包括是否是允许的模块列表
            if(empty($allowList) || (is_array($allowList) && in_array($paths[0], $allowList))){
                //合并module到get全局变量中
                $_GET[$m] = $paths[0];
                //更新pathinfo
                $_SERVER['PATH_INFO'] = isset($paths[1]) ? $paths[1] : '';

            }
        }
        }
     
        //到此为止，如果是pathinfo模式，那么解析出的module已经合并到get超全局变量中，还有一种情况是如果绑定了模块，
        //那么get中的模块名就要变了,这里使用一个MODULE_NAME常量存储真正的模块名称
        //除此之外，如果是普通模式的话，那么mvc自动就会在get超全局变量中。
        
        define("MODULE_NAME", defined("BIND_MODULE")?BIND_MODULE : $_GET[$m]);
        //到模块名称后，接下来要做的就是检测模块文件夹是否存在，存在的开始加载模块中的配置文件
        if(MODULE_NAME && is_dir(APPLICATION_PATH.'/'.MODULE_NAME)){
            //定义当前模块路径
            define('MODULE_PATH', APPLICATION_PATH.'/'.MODULE_NAME.'/');
            //定义当前的模块配置文件路径
            define("MODULE_PATH_CONFIG", MODULE_PATH.'/common/config/config.php');
            //定义当前模块的公共函数路径
            define("MODULE_PATH_FUNC", MODULE_PATH.'/common/func/functions.php');

            //加载配置文件和公共函数
            if(is_file(MODULE_PATH_CONFIG)) C(require(MODULE_PATH_CONFIG));
            if(is_file(MODULE_PATH_FUNC)) include MODULE_PATH_FUNC;

        }else{
            die(MODULE_NAME."不存在!");
        }

        //获取应用的入口文件
        /*
        这里不同的url模式下应用的入口文件不一样
        1：一般情况下： localhost/index.php
        2: 兼容模式下： localhost/index.php?s=
        3: 重写模式下：要去掉index.php
         */
        if(!defined('__APP__')){
           // die(_PHP_FILE_);
            $urlMode        =   C('URL_MODEL');
            if($urlMode == URL_COMPAT ){// 兼容模式判断
                define('PHP_FILE',_PHP_FILE_.'?'.$varPath.'=');
            }elseif($urlMode == URL_REWRITE ) {
                $url    =   dirname(_PHP_FILE_);
                if($url == '/' || $url == '\\')
                    $url    =   '';
                define('PHP_FILE',$url);
            }else {
                define('PHP_FILE',_PHP_FILE_);
            }
            // 当前应用地址
            define('__APP__',strip_tags(PHP_FILE));

        }

        //定义当前模块的url地址
        define('__MODULE__',(defined('BIND_MODULE') || !C('MULTI_MODULE'))? __APP__ : __APP__.'/'.MODULE_NAME);
        //路由检测
        if(''!=$_SERVER['PATH_INFO'] && (!C('URL_ROUTER_ON') || ! route::checkRoute())){
       $paths  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));
        //获取控制器
        if(!defined("BIND_CONTROLLER")){
            $_GET[$c]   =   array_shift($paths);
        }
        // 获取操作
        if(!defined('BIND_ACTION')){
                $_GET[$a]  =   array_shift($paths);
        }
       
        $var  =  array();
        //解析剩下的参数
        //这里使用php5.3以后的匿名函数和闭包特性
        preg_replace_callback('/(\w+)\/([^\/]+)/', function($matches)use(&$var){
                $var[$matches[1]] = strip_tags($matches[2]);
        }, implode('/', $paths));
        
        //合并到get超全局变量中
        $_GET = array_merge($var, $_GET);
        }
    }
       
        
        
        

        



        



    }
    
}