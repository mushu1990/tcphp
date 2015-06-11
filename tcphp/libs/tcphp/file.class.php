<?php
//文件操作工具类
  namespace tcphp;
	class file
	{
        //检查目录或文件是否存在
		static function check_exist($filename)		
		{
			if(file_exists($filename))
			{
				return true;
			}
			else{
               return false;
           }
		}

        // 一次只能创建一级目录
		static function create_dir($dirname,$mode=0777)	
		{
			if(is_null($dirname) || $dirname=="")	return false;
			if(!is_dir($dirname))
			{
				return mkdir($dirname,$mode);
			}
		}

        //可同时创建多级目录
		 static function createDir($aimUrl)		
		{
        	$aimUrl = str_replace('\\', '/', $aimUrl);
        	$aimDir = '';
        	$arr = explode('/', $aimUrl);
        	foreach ($arr as $str)
        	{
            	$aimDir .= $str . '/';
            	if (!is_dir($aimDir))
            	{
                	mkdir($aimDir);
            	}
        	}
    	}

        //递归删除目录
		static function delete_dir($dirname)		
		{
			if(self::check_exist($dirname) and is_dir($dirname))
			{
				if(!$dirhandle=opendir($dirname)) return false;
				while(($file=readdir($dirhandle))!==false)
				{
					if($file=="." or $file=="..")	continue;
					$file=$dirname.DIRECTORY_SEPARATOR.$file;  //表示$file是$dir的子目录
					if(is_dir($file))
					{
						self::delete_dir($file);
					}
					else
					{
						unlink($file);
					}
				}
				closedir($dirhandle);
				return rmdir($dirname);
			}
			else	return false;
		}

        //复制目录
		static function copy_dir($dirfrom,$dirto)		
		{
			if(!is_dir($dirfrom))	return false;
			if(!is_dir($dirto))		mkdir($dirto);
			$dirhandle=opendir($dirfrom);
			if($dirhandle)
			{
				while(false!==($file=readdir($dirhandle)))
				{
					if($file=="." or $file=="..")	continue;
					$filefrom=$dirfrom.DIRECTORY_SEPARATOR.$file;  //表示$file是$dir的子目录
					$fileto=$dirto.DIRECTORY_SEPARATOR.$file;
					if(is_dir($filefrom))
					{
						self::copy_dir($filefrom,$fileto);
					}
					else
					{	if(!file_exists($fileto))
						copy($filefrom,$fileto);
					}
				}
			}
			closedir($dirhandle);
		}

        //获取目录大小
		static function getdir_size($dirname)		
		{
			if(!file_exists($dirname) or !is_dir($dirname))	 return false;
			if(!$handle=opendir($dirname)) 	return false;
			$size=0;
			while(false!==($file=readdir($handle)))
			{
				if($file=="." or $file=="..")	continue;
				$file=$dirname."/".$file;
				if(is_dir($file))
				{
					$size+=self::getdir_size($file);
				}
				else
				{
					$size+=filesize($file);
				}

			}
			closedir($handle);
			return $size;
		}

        // 单位自动转换函数
		static function getReal_size($size)	   
		{
			$kb=1024;
			$mb=$kb*1024;
			$gb=$mb*1024;
			$tb=$gb*1024;
			if($size<$kb)	return $size."B";
			if($size>=$kb and $size<$mb)	return round($size/$kb,2)."KB";
			if($size>=$mb and $size<$gb)	return round($size/$mb,2)."MB";
			if($size>=$gb and $size<$tb)	return round($size/$gb,2)."GB";
			if($size>=$tb)	return round($size/$tb,2)."TB";
		}

        //复制文件
		static function copy_file($srcfile,$dstfile)
		{
			if(is_file($srcfile))
			{
				if(!file_exists($dstfile))
				return copy($srcfile,$dstfile);
			}
			else	return false;
		}

        //删除文件
   	 	static function unlink_file($filename)		
   	 	{
   	 		if(self::check_exist($filename) and is_file($filename))
   	 		{
   	 			return unlink($filename);
   	 		}
   	 		else	return false;
   	 	}

        //获取文件名后缀
   	  static function getsuffix($filename)			
   	 	{
   	 		if(file_exists($filename) and is_file($filename))
   	 		{
   	 			return end(explode(".",$filename));
   	 		}
   	 	}

        //将字符串写入文件
   	 	static function input_content($filename,$str)		
   	 	{
   	 		if(function_exists(file_put_contents))
   	 		{
   	 			file_put_contents($filename,$str);
   	 		}
   	 		else
   	 		{
   	 			$fp=fopen($filename,"wb");
   	 			fwrite($fp,$str);
   	 			fclose($fp);
   	 		}
   	 	}

        //将整个文件内容读出到一个字符串中
   	 	static function output_content($filename)			
   	 	{
   	 		if(function_exists(file_get_contents))
   	 		{
   	 			return file_get_contents($filename);
   	 		}
   	 		else
   	 		{
   	 			$fp=fopen($filename,"rb");
   	 			$str=fread($fp,filesize($filename));
   	 			fclose($fp);
   	 			return $str;
   	 		}
   	 	}

        //将文件内容读出到一个数组中
   	 	static function output_to_array($filename)		
   	 	{
   	 		$file=file($filename);
   	 		$arr=array();
   	 		foreach($file as $value)
   	 		{
   	 			$arr[]=trim($value);
   	 		}
   	 		return $arr;
   	 	}


        /**
        * 转化 \ 为 /
        * 
        * @param	string	$path	路径
        * @return	string	路径
        */
        static function dir_path($path) {
        	$path = str_replace('\\', '/', $path);
        	if(substr($path, -1) != '/') $path = $path.'/';
        	return $path;
        }
        /**
        * 创建目录
        * 
        * @param	string	$path	路径
        * @param	string	$mode	属性  E:/phpstudy/WWW/phpcms/fldkl
        * @return	string	如果已经存在则返回true，否则为flase
        */
        static function dir_create($path, $mode = 0777) {
        	if(is_dir($path)) return TRUE;
        	$ftp_enable = 0;
        	$path = self::dir_path($path);
        	$temp = explode('/', $path);
        	$cur_dir = '';
        	$max = count($temp) - 1;
        	for($i=0; $i<$max; $i++) {    
        		$cur_dir .= $temp[$i].'/';
        		if (@is_dir($cur_dir)) continue;
        		@mkdir($cur_dir, 0777,true);
        		@chmod($cur_dir, 0777);
        	}
        	return is_dir($path);
        }
        /**
        * 拷贝目录及下面所有文件
        * 
        * @param	string	$fromdir	原路径
        * @param	string	$todir		目标路径
        * @return	string	如果目标路径不存在则返回false，否则为true
        */
       static function dir_copy($fromdir, $todir) {
        	$fromdir = self::dir_path($fromdir);
        	$todir = self::dir_path($todir);
        	if (!is_dir($fromdir)) return FALSE;
        	if (!is_dir($todir)) dir_create($todir);
        	$list = glob($fromdir.'*');
        	if (!empty($list)) {
        		foreach($list as $v) {
        			$path = $todir.basename($v);
        			if(is_dir($v)) {
        				self::dir_copy($v, $path);
        			} else {
        				copy($v, $path);
        				@chmod($path, 0777);
        			}
        		}
        	}
            return TRUE;
        }
        /**
        * 转换目录下面的所有文件编码格式
        * 
        * @param	string	$in_charset		原字符集
        * @param	string	$out_charset	目标字符集
        * @param	string	$dir			目录地址
        * @param	string	$fileexts		转换的文件格式
        * @return	string	如果原字符集和目标字符集相同则返回false，否则为true
        */
       static function dir_iconv($in_charset, $out_charset, $dir, $fileexts = 'php|html|htm|shtml|shtm|js|txt|xml') {
        	if($in_charset == $out_charset) return false;
        	$list = dir_list($dir);
        	foreach($list as $v) {
        		if (pathinfo($v, PATHINFO_EXTENSION) == $fileexts && is_file($v)){
        			file_put_contents($v, iconv($in_charset, $out_charset, file_get_contents($v)));
        		}
        	}
        	return true;
        }
        /**
        * 列出目录下所有文件
        * 
        * @param	string	$path		路径
        * @param	string	$exts		扩展名
        * @param	array	$list		增加的文件列表
        * @return	array	所有满足条件的文件
        */
       static function dir_list($path, $exts = '', $list= array()) {
        	$path = self::dir_path($path);
        	$files = glob($path.'*');
        	foreach($files as $v) {
        		if (!$exts || pathinfo($v, PATHINFO_EXTENSION) == $exts) {
        			$list[] = $v;
        			if (is_dir($v)) {
        				$list = self::dir_list($v, $exts, $list);
        			}
        		}
        	}
        	return $list;
        }
        /**
        * 设置目录下面的所有文件的访问和修改时间ful
        * 
        * @param	string	$path		路径
        * @param	int		$mtime		修改时间
        * @param	int		$atime		访问时间
        * @return	array	不是目录时返回false，否则返回 true
        */
        static function dir_touch($path, $mtime = TIME, $atime = TIME) {
        	if (!is_dir($path)) return false;
        	$path = dir_path($path);
        	if (!is_dir($path)) touch($path, $mtime, $atime);
        	$files = glob($path.'*');
        	foreach($files as $v) {
        		is_dir($v) ? self::dir_touch($v, $mtime, $atime) : touch($v, $mtime, $atime);
        	}
        	return true;
        }
        /**
        * 目录列表
        * 
        * @param	string	$dir		路径
        * @param	int		$parentid	父id
        * @param	array	$dirs		传入的目录
        * @return	array	返回目录列表
        */
       static function dir_tree($dir, $parentid = 0, $dirs = array()) {
        	global $id;
        	if ($parentid == 0) $id = 0;
        	$list = glob($dir.'*');
        	foreach($list as $v) {
        		if (is_dir($v)) {
                    $id++;
        			$dirs[$id] = array('id'=>$id,'parentid'=>$parentid, 'name'=>basename($v), 'dir'=>$v.'/');
        			$dirs = self::dir_tree($v.'/', $id, $dirs);
        		}
        	}
        	return $dirs;
        }

        /**
        * 删除目录及目录下面的所有文件
        * 
        * @param	string	$dir		路径
        * @return	bool	如果成功则返回 TRUE，失败则返回 FALSE
        */
       static  function dir_delete($dir) {
        	$dir = dir_path($dir);
        	if (!is_dir($dir)) return FALSE;
        	$list = glob($dir.'*');
        	foreach($list as $v) {
        		is_dir($v) ? self::dir_delete($v) : @unlink($v);
        	}
            return @rmdir($dir);
        }



	}
	

?>



