<?php
namespace tcphp;

/**
* model基类
*/
class model{


    //数据对象存储数组
    protected $data = array();

    //数据库操作实例对象
    protected $db = null;

    //表名称
    protected $tablename = '';
    //表前缀
    protected $tablePrefix = '';
    //使用的连接数据库方案
    protected $dbconfig = '';
    
    
    
    /*
    *构造函数
    *实例化一个数据库访问实例
    *$model = new model();//如果不传任何操作，表示只是实例化一个AR 空model
    *$model = new model('user');//表示实例化一个model，此model关联的是数据库表user
    *$model = new usermodel();//表示实例化一个usermodel，关联的user表
     */
    function __construct($tablename = '',$tablePrefix = '',$dbconfig=''){
        if(!empty($tablename)) $this->tablename = $tablename;
        if(!empty($tablePrefix)) $this->tablePrefix = $tablePrefix;
        if(!empty($dbconfig)) $this->dbconfig = $dbconfig;

        $config = C($this->dbconfig);
        $this->db = new db($config);
    }
    

        
    


    /*
    *获得表字段信息
     */
    public function get_fields(){

        return $this->db->getFields($this->tablePrefix.$this->tablename);

    }

    /*
     设置数据对象的值   
     */
    public function __set($name,$value){

        $this->data[$name] = $value;

    }

    /*
    获取数据对象的值
     */
    public function __get($name){

        if(isset($this->data[$name])){
            return $this->data[$name];
        }else{
            return null;
        }
   
    }

    /*
    
    执行select查询
     */
    public function select($options=array()){

        return $this->db->select($options);


    }

    /*
    
    执行add
     */
    public function add($data='', $options=array(),$replace=false){
        if(empty($data)){
            $data = $this->data;
        }
        return $this->db->insert($data, $options));


    }

    /*
    
    执行删除操作
     */
    public function delete($options=array()){

        return $this->db->delete($options);

    }

    /*
    
    执行更新操作
     */
    public function update($options = array()){
         return $this->db->update($options);
    }

    /*
    *执行sql语句
     默认为查询模式，返回数据集
     当$select设置为false的时候表示执行模式，返回受影响的记录数
     */
    public function excute($sql,$select=true){
        if($select){
            return $this->db->query($sql);
        }else{
            return $this->db->excute($sql);
        }

    }

    /*
    获得一条记录
     */
    public function get_one($options = array()){
        $options['limit'] = " limit 1 ";
        return $this->select($options);

    }

    /*
    计算记录数
     */
    public function count($options=array()){
        $options['field'] = " count(*) as num ";
        return $this->get_one($options);

    }

    //返回分页数据和分页码
    public function listinfo($options=array(),$page=1,$pagesize=20){
        $count = $this->count($options);
        $page = max(intval($page),1);
        $offect = $pagesize*($page-1);
        $options['limit'] = "limit $offect,$pagesize";
       
        $array = array();
        
        if($count>0){
            $array[] = $this->select($options);
             //利用页码类获取页码
            $yemas = tcphp\page::page();
            $array[] = $yemas;
            return $array;
        }else{
            return false;
        }

    }









    
     

}


