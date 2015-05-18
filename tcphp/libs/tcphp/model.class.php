<?php
namespace tcphp;

/**
* model基类
*/
class model{


    //数据对象存储数组
    protected $data = array();
    
    
    /*
    *构造函数
    *实例化一个数据库访问实例
     */
    function __construct()
    {
        //todo
    }


    /*
    *获得表字段信息
     */
    public function get_fields(){

    }

    /*
     设置数据对象的值   
     */
    public function __set($name,$value){

    }

    /*
    获取数据对象的值
     */
    public function __get($name){

   
    }

    /*
    
    执行select查询
     */
    public function select($options=array()){



    }

    /*
    
    执行add
     */
    public function select($data='', $options=array(),$replace=false){



    }

    /*
    
    执行删除操作
     */
    public function update($options=array()){

    }

    /*
    
    执行更新操作
     */
    public function update($options = array()){

    }

    /*
    获得一条记录
     */
    public function get_one($options = array()){


    }

    /*
    计算记录数
     */
    public function count($options=array()){

    }









    
     

}


