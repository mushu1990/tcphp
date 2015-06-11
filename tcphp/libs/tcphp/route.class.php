<?php
/**
 * @Author: mushu
 * @Date:   2015-06-08 22:15:53
 * @Last Modified by:   mushu
 * @Last Modified time: 2015-06-08 22:16:33
 *
*/

//路由解析类
class route{
    //路由检测
    public static function checkRoute(){
        //获取配置文件中pathinfo分割符
        $depr = C("URL_PATHINFO_DEPR");
        //根据处理后的pathinfo得到一个待匹配的表达式
        $regx = trim($_SERVER['PATH_INFO'], $depr);
        //下面做路由匹配
        /*
        原理：
        使用regx和路由数组的键进行匹配，如果是静态路由，则完整匹配，如果是正则路由则正则匹配，如果匹配成功，取得键对应的值，进行替换组装成真正的pathinfo
         */
        //静态路由
        $maps = C("URL_MAP_RULES");
        if(isset($maps[$regx])){
            //解析键对应的值，值的格式是url普通模式,该静态方法的作用是解析出控制器和方法以及参数
            $var = self::parse_url($maps[$regx]);
            //合并到get超全局变量中
            $_GET = array_merge($var, $_GET);
            return true;

        }
        //正则路由
        $routes = C('URL_ROUTE_RULES');
        if(!empty($routes)){
            foreach ($routes as $rule => $route) {
                if(0 === strpos($rule, '/') && preg_match($rule, $regx, $matches)){
                        return self::parseRegex($matches,$route,$regx);
                }
            }
        }
    }

    //解析url，从url中解析出控制器，方法，以及参数
    //解析的url格式为正常格式
    public static function parse_url($url){
        $var  = array();
        //如果url中存在？ 说明是带有参数的
        if(false !== strpos($str, '?')){
            $info = parse_url($url);
            $path = explode("/", $info['path']);
            parse_str($info['query'],$var);
        }
        if(isset($path)){
            $var[C('VAR_ACTION')] = array_pop($path);
            if(!empty($path)){
                $var[C('VAR_CONTROLLER')] = array_pop($path);
            }
            if(!empty($path)){
                $var[C('VAR_MODULE')]  = array_pop($path);
            }
        }
        return $var;
    }


    //解析路由正则
    /*
    基本原理：
    在进行url地址与路由表达式匹配后得到$matches匹配数组结果，利用此数组替换掉$route路由地址的占位符得到真正的路由地址。解析路由地址得到mvc参数和附加参数。
     */
    // 解析正则路由
    // '路由正则'=>'[控制器/操作]?参数1=值1&参数2=值2...'
    // '路由正则'=>array('[控制器/操作]?参数1=值1&参数2=值2...','额外参数1=值1&额外参数2=值2...')
    // '路由正则'=>'外部地址'
    // '路由正则'=>array('外部地址','重定向代码')
    // 参数值和外部地址中可以用动态变量 采用 :1 :2 的方式
    // '/new\/(\d+)\/(\d+)/'=>array('News/read?id=:1&page=:2&cate=1','status=1'),
    // '/new\/(\d+)/'=>array('/new.php?id=:1&page=:2&status=1','301'), 重定向
    public static function parseRegex($matches, $route, $regx){
        //$route = is_array($route) ? $route[0] : $route;
        $url = preg_replace_callback('/:(\d+)/', function($match) use($matches){
            return $matches[$match[1]];
        }, $route);
        //重定向
        if(0===strpos($url, "/") || 0===strpos($url, "http")){
            header("Location: $url", true,(is_array($route) && isset($route[1]))?$route[1]:301);
            exit;
        }else{
            //开始解析真正的路由地址
            $var  = self::parse_url($url);

        }
        $_GET   =  array_merge($var,$_GET);
        return true;



    }
    
}
