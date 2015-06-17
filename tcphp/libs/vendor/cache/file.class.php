<?php

/**
* 文件缓存
*/
namespace vendor\cache;
class file
{
    /*
    配置数组项：
    1：文件缓存存放路径
    2: 文件缓存前缀
    3：文件缓存过期时间
    4：文件缓存长度

     */
    function __construct($options = array())
    {
        
        //设置文件缓存路径
        $this->options['temp']  = (isset($options['temps']) && !empty($options['temps'])) ? $options['temp'] : C("DATA_CACHE_PATH");
        //设置文件前缀
         $this->options['prefix']  = (isset($options['prefix']) && !empty($options['prefix'])) ? $options['prefix'] : C("DATA_CACHE_PREFIX");
         //设置过期时间
          $this->options['expire']  = (isset($options['expire']) && !empty($options['expire'])) ? $options['expire'] : C("DATA_CACHE_TIME");
        //设置长度
         $this->options['length']  = (isset($options['length']) && !empty($options['temps'])) ? $options['length'] : 0;

         if(!is_dir($this->options['temp'])){
            mkdir($this->options['temp']);
         }
    }


    //取得变量对应的文件名
    public function filename($name){
        $name  = md5($name);
        $filename = $this->options['prefix'].$name.'.php';
        return $this->options['temp'].$filename;

    }

    //读取缓存
    /*
    先根据文件名MD5化组合出要读取的文件名
    取出文件内容
    从文件内容的第8个内容开始去的过期时间
    判断如果说当前时间大于文件时间和设置的过期时间的和说明文件已经过期，删除文件返回false，取不出文件。
    如果开启了数据校验的话，再次取得内容中存储的MD5串进行校验，校验失败的话返回
    全部通过后取得真正的内容
    如果内容进行了数据压缩的话，再对内容解压缩。
    再对内容反序列化得到结构化内容
    最后返回
     */
    public function get($name){
        $filename = $this->filename($name);
        if(!file_exists($filename)){
            die("缓存文件不存在");
            }
        $content = file_get_contents($filename);
        if(false !== $content){
        $expire = substr($content, 8, 12);
        if($expire!=0 && time() > filemtime($filename)+$expire){
            //文件过期 删除
            unlink($filename);
            return false;
            }
        if(C("DATA_CACHE_CHECK")){
            $check = substr($content, 20, 32);
            $content = substr($content, 52, -3);
            if($check!== md5($content)){
                return false;
            }

        }else{
                $content = substr($content, 20, -3);
        }
        if(C("DATA_CACHE_COMPRESS") && function_exists("gzcompress")){
            $content = gzuncompress($content);
        }
            return unserialize($content);
        }else{
            return false;
        }

    }

    //写缓存
    /*
    主要思路：
    先对$name进行MD5化，保证文件唯一
    通过$name和缓存文件夹名称组合出缓存的文件路径
    然后把$value写入文件中。
    在写入前，可以对内容做一下处理：
    1：序列化数据，这样可以存储多种格式的数据，便于还原
    2：压缩数据，使其体积更小
    3：MD5校验数据。把数据进行MD5加密，形成一个加密串，还有过期时间。
    4：把过期时间，加密穿和真正的内容组合起来放入文件中。
    这样做的目的：读取缓存的时候可以对内容进行MD5与原加密穿进行比较，以此来看内容是否被篡改。同时可以读取过期时间。看看数据是否过期。
    
    参数1：键
    参数2：值
    参数3：过期时间  0为永久
     */
    public function set($name, $value, $expire=null){
        if(is_null($expire)){
            $expire = $this->options['expire'];
        }
        $filename = $this->filename($name);
        $data = serialize($value);
        if(C("DATA_CACHE_COMPRESS") && function_exists('gzcompress')){
                $data = gzcompress($data,3);    
        }
        if(C("DATA_CACHE_CHECK")){
            $check = md5($data);
        }else{
            $check = '';
        }
        //真正要写入文件的字符串
        $data  = "<?php\n//".sprintf('%012d',$expire).$check.$data."\n?>";
        $result = file_put_contents($filename, $data);
        if($result){
            return true;
        }else{
            return false;
        }

    }

    //删除指定键的缓存
    public function rm($name){
        return unlink($this->filename($name));
    }

    //清除缓存路径下的所有缓存
    public function clear(){
        $path = $this->options['temp'];
        $files = scandir($path);
        if($files){
            foreach ($files as $key => $file) {
                if($file!='.' && $file!='..' && is_dir($path.$file)){
                    array_map('unlink', glob($path.$file.'/*.*'));
                }elseif(is_file($path.$file)){
                    unlink($path.$file);
                }
            }
            return true;

        }
        return false;
    }



}