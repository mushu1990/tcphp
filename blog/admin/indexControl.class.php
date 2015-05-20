<?php
/*
* @time: 2015-5-9
* @author: mushu
*/

//index控制器
use tcphp;
 class indexControl{
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


 }