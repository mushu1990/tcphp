<?php
		//mysql数据库链接类
     namespace tcphp;
     	class db{

     		//数据库配置信息
     		protected $config = '';

     		//存储数据库连接id的数组，存储数据库连接实例。有了这个后可以使用一个mysql实例建立多个mysql连接。
     		protected $linkID = array();

     		//定义是否是长连接
     		protected $pconnect = '';

     		//当前查询的id
     		protected $queryID = null;

     		//当前查询的字符串
     		protected $queryStr = null;

     		//当前数据库连接
     		protected $_linkID = null;
     		 // 返回或者影响记录数
            protected $numRows    = 0;

            // 查询表达式
    protected $selectSql  = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%COMMENT%';

     		//架构函数 读取数据库配置信息
     		public function __construct($config=""){
     			//首先判断是否支持mysql扩展
     			if(!extension_loaded('mysql')){
     				die("不支持mysql扩展");
     			}
     			if(!empty($config)){
     				$this->config = $config;     				
     			}
     		}

     		//连接数据库函数
     		/*
			@1: 连接数据库参数的数组
			@2: 连接实例序号。默认为0.
			@3：是否强制建立一个新的连接。为true的话就算$linkID中有旧的已经建立的连接也会重新建立。
     		*/	     		
     		public function connect($config="", $linkNum=0, $force=false){
     			    //如果$linkID中存在序号为$linkNum的实例，直接返回。否则就建立新的连接放入$linkID数组中
     				if(!isset($linkID[$linkNum])){
     					if(empty($config))  $config = $this->config;
     					//处理不带端口的socket连接情况
     					/*$host = $config['hostname'].($config['$hostport']?":{config['hostport']}":'');*/
     					$host = $config['hostname'];
     					//是否长连接
     					$pconnect  =  !empty($config['params']['persist'])?$config['params']['persist'] :$this->pconnect;
     					if($pconnect){
     						$this->linkID[$linkNum] = mysql_pconnect($host, $config['username'], $config['password'],131072);
     					}else{
     						$this->linkID[$linkNum] = mysql_connect($host, $config['username'],$config['password'],true,131072);
     					}
     					if(!$this->linkID[$linkNum] || (!empty($config['database']) && !mysql_select_db($config['database'],$this->linkID[$linkNum]))){
     						die("数据库错误");
     					}
     					//得到mysql服务器的版本信息
     					$dbversion = mysql_get_server_info($this->linkID[$linkNum]);
     					//设置存储数据库的编码
     					mysql_query("set names '".$config['charset']."'", $this->linkID[$linkNum]);
     					//设置mysql的模式
     					if($dbversion > '5.0.1'){
     						mysql_query("set sql_mode=''", $this->linkID[$linkNum]);
     					}
     				}
     				$this->_linkID = $this->linkID[$linkNum];
     				return $this->linkID[$linkNum];

     		}



     		/*
				主动释放查询结果所占用的内存
     		*/
			public function free(){
				mysql_free_result($this->queryID);
				$this->queryID = null;

			}

			/*
			执行查询，返回结果集
			@ sql指令
			*/
			public function query($str){
				$this->queryStr = $str;
				//释放上一次查询结果
				if($this->queryID) $this->free();
				$this->queryID = mysql_query($str,$this->_linkID);
				if($this->queryID ===  false){
					die(mysql_error());
				}else{
					$this->numRows = mysql_num_rows($this->queryID);
					$result = array();
					if($this->numRows >0){
						while ($row =  mysql_fetch_assoc($this->queryID)) {
							$result[] = $row;
						}

					}
					return $result;
				}
			}

			/*
				执行删除，增加，更新等语句，返回受影响的行数
			*/
			public function excute($str){
				$this->queryStr = $str;
				if($this->queryID) $this->free();
				$result = mysql_query($str,$this->_linkID);
				if(false === $result){
					die(mysql_error());
					return false;
				}else{
					$this->numRows = mysql_affected_rows($this->_linkID);
					return $this->numRows;
				}

			}

			/*	
			获得数据表的字段信息
			*/
			public function getFields($tableName){
               
				$sqlstr = "SHOW FULL COLUMNS FROM ".$tableName."";
				$result = $this->query($sqlstr);
				$info  =array();
				if($result){
					foreach ($result as $key => $val) {
						$info[$val['Field']] = array(
							'name' => $val['Field'],
							'type' => $val['Type'],
							'notnull' => $val['Null'],
							'default' => $val['Default'],
							'primary' => (strtolower($val['Key']) == 'pri'),
							'autoinc' => (strtolower($val['Extra']) == 'auto_increment'),


							);
					}
				}
				return $info;

			}

			/*
			获得数据库的表信息
			*/
			public function getTables($dbName=''){
				if(!empty($dbName)){
					$sql = "show tables from ".$dbName;
				}else{
					$sql = "show tables";
				}

				$result = $this->query($sql);
				
				if($result){
					foreach ($result as $key => $value) {
						$data[] = current($value);
					}
				}
				return $data;

			}


			/*
				数据库执行replace
				@1：数组（一定要有主键）
				@2: 参数表达式
			*/
				public function replace($data, $options=array()){

					foreach ($data as $key => $value) {
						$values[] = $this->parseValues($value);
						$fields[] = $this->parseKey($key);
					}
					$sql = "replace into ".$options['table']." (".implode(",", $fields).') values ('.implode(",", $values).")";
					die($sql);
					return $this->excute($sql);

				}


			/*
				关闭数据库
			*/
				public function close(){
					if($this->_lnkID){
						mysql_close($this->_linkID);
					}
					$this->_linkID = null;
				}

			/*
			过滤sql语句的特殊字符
			*/
			public function escapeString($sql){
				if($this->_linkID){
					return mysql_real_escape_string($sql,$this->_linkID);
				}else{
					return  mysql_escape_string($sql);
				}

			}

			/*
				给字段和表名自动化加上`
			*/
				public function parseKey($str=''){
					if(!empty($str)){
						$str = '`'.$str.'`';
					}
					return $str;
				}


			/*
			开启事务
			*/
			public function startTrants(){
				if(!$this->_linkID){ return  false;}
				mysql_query("start transaction", $this->_linkID);
				return;
			}

			/*
			事务提交
			*/
			public function commit(){
				$result  = mysql_query("commit", $this->_linkID);
				if(!$result){
					die(mysql_error());
				}
			}

			/*
			事务回滚
			*/
			public function rollback(){
				$result = mysql_query("rollback", $this->_linkID);
				if(!$result){
					die(mysql_error());
				}
			}


			/*
			分析fields,即把数组的fileds转换成字符串的fields,为后面的sql字符串组合做准备
			支持传入字符串形式
			如果是逗号分隔的，先转换成数组形式在组合成字符串形式
			支持当个字段
			支持*
			*/
			public function parseFileds($fields){
				//处理字符串中多个字段的情况
				if(is_string($fields) && strpos($fields, ",")){
					$fields = explode(',', $fields);
				}
				//开始处理数组的状况
				if(is_array($fields) && !empty($fields)){
					foreach ($fields as $key => $value) {
						if(!is_numeric($key)){
							$fieldsStr[] = $value.'AS'.$key;
						}else{
							$fieldsStr[] = $value;
						}
					}
					$fieldsStrs = implode(',', $fieldsStr);

				}else if(is_string($fields) && !empty($fields)){
					$fieldsStrs = $fields;
				}else{
					$fieldsStrs = "*";
				}
				return $fieldsStrs;

			}

            /*
                table分析
            */

            public function parseTable($tables){

            if(is_string($tables)){
            $tables  =  explode(',',$tables);
            array_walk($tables, array(&$this, 'parseKey'));
            }
            $tables = implode(',',$tables);
            return $tables;

            }

			/*
			分析values，即把数组的values转换成字符串的values
			$values的形式可以有多种，因为在sql中字段可以比value多
			*/
			public function parseValues($values){
				if(is_string($values)){
					$values = '\''.$values.'\'';
				}elseif (is_array($values)) {
					$values = implode(",", $values);
				}elseif (is_bool($values)) {
					$values = $values ? "1" : "0";
				}elseif(is_null($values)){
					$values = 'null';
				}

				return $values;

			}

			/*
			转换where
			*/
			public function parseWhere($where){
				$whereStr = '';
				if(is_string($where)){
					$whereStr = $where;

				}

                
                else{
                    /*
                    //使用数组形式
					//先判断使用的逻辑运算符
					$operate = isset($where['_logic'])?strtoupper($where['_logic']):'';
					if(in_array($operate, array('AND','OR','XOR'))){
						$operate = ' '.$operate.' ';
						unset($where['_logic']);
					}else{
						$operate = ' AND ';
					}

					foreach ($where as $key => $val) {
						if(is_numeric($key)){
							$key = '_complex';
						}
						if(0 === strpos($key, '_')){
							//解析特殊表达式
							$whereStr .= $this->parseThinkWhere($key,$val);
						}
					}
                    */

				}
                if(empty($whereStr)){
                  return "";
                }else{
                return " WHERE ".$whereStr;
                }

			}

			/*
			limit分析
			*/
			protected function parseLimit($limit){
				return !empty($limit) ? ' LIMIT '.$limit.' ':'';
			}

			/*
			join分析
			*/
			protected function parseJoin($join){
				$joinStr = '';
				if(!empty($join)){
					$joinStr = ' '.implode(' ', $join).' ';
				}
				return $joinStr;


			}

            protected function parseSet($data) {
        foreach ($data as $key => $value) {
           $set[]  =   $this->parseKey($key).'='.$this->parseValues($value);
                    
       
        }
           return ' SET '.implode(',',$set);      
    }

			/*
			order分析
			*/
			public function parseOrder($order){
				if(is_array($order)){
					$array = array();
					foreach ($order as $key => $val) {
						if(is_numeric($key)){
							$array[] = $this->parseKey($val);
						}else{
							$array[] = $this->parseKey($key).' '.$val;
						}
					}
					$order = implode(',', $array);
				}

				return !empty($order) ? " ORDER BY ".$order : '';
			}

			/*
			GRUOP分析
			*/
			protected function parseGroup($group){
				return !empty($group) ? " GROUP BY ".$group : "";
			}

			/*
			having分析
			*/
			protected function parseHaving($having){
				return !empty($having) ? " HAVING ".$having : "";
			}

			/*
			comment注释
			*/
			protected function parseComment($commet){
				return !empty($commet) ? ' /* '.$commet.' */ ' : '';
			}

			/*
			distinct分析
			*/
			protected function parseDistinct($distinct){
				return !empty($distinct) ? " distinct ".$distinct : "";
			}


			/*
			
			*/

			/*
			插入操作
            @param1: 要插入的数据，数组形式
            @param2: 各种条件(where,order等，数组形式)
            @param3: 是正常插入还是替换模式插入
			*/
			public function insert($data, $options=array(), $replace=false){
				$values = $fields = array();
				
				/*
				这里$data变量可以有两种形式
				第一种是$data = array('name'=>'tom')
			    第二种是$data['name'] = array('表达式'，‘表达式条件’);
				*/
				foreach ($data as $key => $val) {
					if(is_array($val) && 'exp'==$val[0]){
						$fields[] = $this->parseKey($key);
						$values[] = $val[1];   
					}elseif (is_scalar($val) || is_null($val)) {
						//这里过滤非标量类型。也就是说在第一种形式下只有$val是简单类型或者空才被处理
						$fields[] = $this->parseKey($key);
						$values[] = $this->parseValues($val);
					}
				}

				//至此为止，已经分析出了字段和值的数组
				$sql = ($replace ? 'REPLACE' : 'INSERT').' INTO '.$this->parseTable($options['table']).' ('.implode(',', $fields).' ) values ('.implode(',', $values).')';
				$sql .= $this->parseComment(!empty($options['commet'])?$options['commet']:'');
				return $this->excute($sql);


			}

			/*
			更新操作
            @param1: 要插入的数据，数组形式
            @param2: 各种条件(where,order等，数组形式)
			*/
			public function update($data,$options){
				//$this->model = $options['model'];
                //
				$sql = 'update '
				   .$this->parseTable($options['table'])
				   .$this->parseSet($data)
				   .$this->parseWhere(!empty($options['where'])?$options['where']:'')
				   .$this->parseOrder(!empty($options['order'])?$options['order']:'')
				   .$this->parseLimit(!empty($options['limit'])?$options['limit']:'')
				   .$this->parseComment(!empty($options['commet'])?$options['commet']:'');
				
                return $this->excute($sql);


			}

			/*
			删除操作

            @param1: 各种条件(where,order等，数组形式)
			*/
			public  function delete($options=array()){
				
				$sql = 'DELETE FROM '
				  .$this->parseTable($options['table'])
				  .$this->parseWhere(!empty($options['where'])?$options['where']:'')
				  .$this->parseOrder(!empty($options['order'])?$options['order']:'')
				  .$this->parseLimit(!empty($options['limit'])?$options['limit']:'')
				  .$this->parseComment(!empty($options['commet'])?$options['commet']:'');
                
				return $this->excute($sql);


			}


			/*
			select操作
			基本逻辑:
			根据传入的where,union,order的options数组来组合出select语句，然后利用query方法执行查询返回数据
			tp逻辑：
			先创建一个ptivate查询表达式 // 查询表达式
            protected $selectSql  = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%COMMENT%';
            然后利用str_replace把这个字符串中的标志位给替换掉，最后形成一个select查询字符串，然后调用query方法。
            此外select操作加入了以下业务：
            1：在buildSelectSql方法中加入了分页的判断，可以把分页的sql加入select语句中
            2：在buildselectsql方法中加入了sql缓存
			*/
			public function select($options=array()){
				//$this->model = $options['model'];
				$sql = $this->buildSelectSql($options);
                
				$result = $this->query($sql);
				return $result;

			}
			
			/*
			生成查询sql
			*/			
			public function buildSelectSql($options=array()){
				if(isset($options['page'])){
					//根据页数计算limit
					list($page, $listRows) = $options['page'];
					$page = $page>0 ? $page : 1;
					$listRows = $listRows>0 ? $listRows : (is_numeric($options['limit'])?$options['limit']:20);
					$offset = $listRows*($page-1);
					$options['limit'] = $offset.','.$listRows;

				}
				//sql缓存
				/*
				首先对$options进行md5加密，作为key去缓存中查是否有对应的value，有的话说明有sql缓存。
				*/
				/*
                if(C(DB_SQL_BUILD_CACHE)){
					$key = md5(serialize($options));
					$value = S($key);
					if(false!==$value){
						return $value;
					}
				}
                */

				$sql = $this->parseSql($this->selectSql, $options);
                /*
				if(!isset($key)){
					S($key,$sql,array('expire'=>0, 'length'=>C('DB_SQL_BUILD_LENGTH'),'quene'=>C('DB_SQL_BUILD_QUENE')));
				}
                */
				return $sql;


				
			}

			/*
			替换select语句表达式
			*/
			public function parseSql($sql,$options=array()){
				$sql  = str_replace(array('%TABLE%','%DISTINCT%','%FIELD%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%','%COMMENT%'),  array(
                $this->parseTable($options['table']),
                $this->parseDistinct(isset($options['distinct'])?$options['distinct']:false),
                $this->parseFileds(!empty($options['field'])?$options['field']:'*'),
                $this->parseJoin(!empty($options['join'])?$options['join']:''),
                $this->parseWhere(!empty($options['where'])?$options['where']:''),
                $this->parseGroup(!empty($options['group'])?$options['group']:''),
                $this->parseHaving(!empty($options['having'])?$options['having']:''),
                $this->parseOrder(!empty($options['order'])?$options['order']:''),
                $this->parseLimit(!empty($options['limit'])?$options['limit']:''),
                //$this->parseUnion(!empty($options['union'])?$options['union']:''),
                $this->parseComment(!empty($options['comment'])?$options['comment']:'')
            ), $sql);
              
				return $sql;

			}







     	}

?>