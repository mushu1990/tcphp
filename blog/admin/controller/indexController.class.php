<?php
/*
* @time: 2015-5-9
* @author: mushu
*/

//index控制器
namespace app\admin\controller;
use app\admin\model\menumodel;


class indexController extends adminController{


    //后台首页
    public function main(){
       //获取顶级菜单列表
       $menumodel = new menumodel("admin_menus");
       $data = $menumodel->getMenusByPid(0);
       $this->assign("menus",$data);
       $this->display();
    }

    //左侧菜单
    public function menus(){
        $menuid = $_GET['menuid'];
        $menumodel  = new menumodel("admin_menus");
        $data = $menumodel->getMenusByPid($menuid);
        //获取本身
        $self = $menumodel->excute("select * from admin_menus where menuid=$menuid");
        $this->assign("menus",$data);
        $this->assign("self",$self[0]['name']);
        $this->display();
    }
 	public  function  init(){
 		//trigger_error("Cannot divide by zero", E_USER_ERROR);
 			
 		echo "这是第一个控制器方法";

        $a = new tcphp\db();
 	}

    public function strreplace(){
        $str = "我是一个程序员";
        echo $str;
        echo replaceStar($str);
    }

    function test(){
        $test = array(
    '为星号的代码',
    'helloworld',
    '1453674625',
    '123',
    '1',
    '11',
    '星号',
    '星号的llowor为'
);

foreach($test as $v){
    echo preg_replace('/^(..).*(..)$/', '$1****$2', $v),"\n";
}
    }


    function strlen(){
        $str = "我是一个php程序员";
        $count = str_len($str);
        echo $count;
    }
    

    public function db(){
        //定义连接数据库配置文件
        $config =C('default');
        $db = new tcphp\db($config);
        $db->connect();
        $options = array('where'=>'cid>3','table'=>'blog_channel','field'=>'cid,ctitle');
        $result = $db->select($options);
        dump($result);



    }

    public  function model(){
        $model = new  tcphp\model('channel','blog_');
        $data = $model->get_fields();
        dump($data);
        $model->name = 'mushu';
        echo $model->name;
        $count  = $model->count(array('where'=>'cid>1'));
        echo $count;
    }
    public function file(){
        tcphp\file::createDir("C:\\Users\\mushu123\\Desktop\\asass\\asas");
    }

    public  function image(){
        
       $image = new  tcphp\Image();
      // $image->open("C:\Users\mushu123\Desktop\index.jpg");
      
       
       $image->crop(500,500);
      
      ob_end_clean();
       header("Content-type: image/jpeg");
      
       imagejpeg($image->img);
       

    }
    public function imagetest(){
        ob_end_clean();
      header("Content-type: image/png");

$im = @imagecreate(200, 200)or die("创建图像资源失败");

$bg = imagecolorallocate($im, 204, 204, 204);
$red = imagecolorallocate($im, 255, 0, 0);

imagearc($im, 100, 100, 150, 150, 0, 360, $red);

imagepng($im);
imagedestroy($im);
 

    }

    public function imagetest1(){
         ob_end_clean();
     // header("Content-type: image/jpeg");
    //  echo "asas";
        //创建一个图像流
        $img = imagecreatetruecolor(500, 500);
        //设置默认的颜色
        //$color  = imagecolorallocate($img, 255, 255, 255);
        //imagefill($img, 0, 0, $color);
        imagejpeg($img);
    }

    public function  aaaa(){
        $str = file_get_contents("http://rate.taobao.com/feedRateList.htm?_ksTS=1432380076267_1381&callback=jsonp_reviews_list&userNumId=2525838274&auctionNumId=45395667162&siteID=4&currentPageNum=1&rateType=&orderType=sort_weight&showContent=1&attribute=&ua=");
      
   $str = iconv("GBK", "UTF-8", $str);

  
        preg_match("/jsonp_reviews_list\((.*)\)/",$str,$matches);

   
      $str = $matches[1];
       $de_json = json_decode($str,TRUE);
      
       
       echo json_last_error_msg();
      var_dump($de_json);
        //indent();
    }

    public function image1(){
       
        $image = new tcphp\Image();
        $image->open("C:\Users\mushu123\Desktop\\1.jpg");
       
       
        //$image->crop(1000,1000,0,0);
        $image->water("C:\\Users\\mushu123\\Desktop\\favicon.png", 1, 80);
        $image->save("C:\Users\mushu123\Desktop\index2.jpg");

    }

    public function image2(){
        $im = imagecreatetruecolor(100, 100);

// 将背景设为红色
$red = imagecolorallocate($im, 0, 0, 0);
imagefill($im, 0, 0, $red);
ob_end_clean();
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);

    }

    public function text(){
        $info = imagettfbbox(16, 0, "D:\\phpStudy\\WWW\\phpcms\\phpcms\\libs\\data\\font\\Vineta.ttf", "my  name is a  php");
        dump($info);
    }

    public function text1(){
         $image = new tcphp\Image();
        $image->open("C:\Users\mushu123\Desktop\\1.jpg");
       
       
        //$image->crop(1000,1000,0,0);
        $image->text("asdksdsad", "D:\\phpStudy\\WWW\\phpcms\\phpcms\\libs\\data\\font\\Vineta.ttf",16);
        $image->save("C:\Users\mushu123\Desktop\index2.jpg");
    }

    public function page(){
        $page = new \tcphp\page("1000");
        echo $page->show();

        $_SESSION['name'] = 'asdsdsdsdsds';

    }
    public function ss(){
        echo $_SERVER['SCRIPT_NAME'];
        dump($_GET);
    }

    public function xml(){
        $array = array(
                "name"=>array("as","asasasas"),
                "age"=>15,
                20,
                30,
                40,
                new tcphp\Image()
                
            );
         $array1 = array(
                "name"=>"tom",
                "age"=>15,
                20,
                30,
                40,
            
            );
        echo xml_encode($array);
    }

    public function filecache(){
        $cache = new tcphp\cache("file");
        $cache->set("mushu","asasasasasasasasas");
        echo $cache->get("mushu");
        $cache->rm("mushu");
    }

    public function session(){
        session_start();
        //$data = array("asas","asasa");
        $_SESSION['data'] = new tcphp\Image();
    }
    public function session1(){
        //session_start();
        //$data = array("asas","asasa");
        dump($_SESSION['name']);
    }

    




 }