<?php
namespace tcphp;
//图像处理类

class Image{
    
    //定义图像资源
    public $img;

    //定义图像信息
    private $info;

    //构造方法，打开一个图像资源
    public function __construct($imgname=''){

    }

    //打开一个图像资源
    public function open($imagename){
        //首先检测文件不在不
        if(!is_file($imagename)) return false;
        $info = getimagesize($imagename);
        
        //检测图像的合法性，根据返回的图像信息来检查。如果是GIF文件的话返回错误
        if(false===$info || (IMAGETYPE_GIF===$info[2] && empty($info['bits']))){
            return false;
        }
        //设置图像信息到info成员变量中
        $this->info['width'] = $info[0];
        $this->info['height'] = $info[1];
        $this->info['type'] = image_type_to_extension($info[2],false);
        $this->info['mime'] = $info['mime'];
       

        //销毁图像
        empty($this->img) || imagedestroy($this->img);

        //打开图像
        $fun = "imagecreatefrom{$this->info['type']}";
        $this->img = $fun($imagename);



    }

    //返回图像的宽度
    public function width(){
        if(empty($this->img)) return false;
        return $this->info['width'];
    }

    //返回图像的高度
    public function height(){
        if(empty($this->img)) return false;
        return $this->info['height'];
    }
    //返回图像的类型
    public function type(){
        if(empty($this->img)) return false;
        return $this->info['type'];
    }

    //返回图像的mime类型
    public function mime(){
        if(empty($this->img)) return false;
        return $this->info['mime'];
    }

    //裁切图像
    /*
    * @param 剪切区域的宽度
    * @param 剪切区域的高度
    * @param 剪切区域的x坐标
    * @param 剪切区域的y坐标
    * @param 图像保存的宽度
    * @param 图像保存的高度

     */
    public function crop($w, $h, $x=0, $y=0, $width=null, $height=null){

       if(empty($this->img)) return false;
       

        empty($width) && $width = $w;
       empty($height) && $height = $h;

        //创建一个图像流
        $img = imagecreatetruecolor($width, $height);
        //设置默认的颜色
        $color  = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $color);
        //裁剪
        imagecopyresampled($img, $this->img, 0, 0, $x, $y, $width, $height, $w, $h);
        imagedestroy($this->img);
        //设置新图像
        $this->img = $img;

        //重新设置新新图像的宽和高
        $this->info['width'] = $width;
        $this->info['height'] = $height;

        

    }

    //保存图像
    /*
    * @param 图像保存名称
    * @param 图像保存类型
    * @param 图像质量
    * @param 是否对jpeg图像进行各行扫描
     */
    public function save($imagename, $type=null,$quaity=80,$flag=true){
            if(empty($this->img)) return false;

            //自动获取图像的类型
            if(is_null($type)){
                $type = $this->info['type'];
            }else{
                $type = strtolower($type);
            }

            //保存图像
            if('jpeg' == $type || 'jpg'==$type){
                //jpeg设置各行扫描
                imageinterlace($this->img, $flag);
                
                imagejpeg($this->img, $imagename, $quaity);
            }else{
                $fun  = 'image'.$type;
                $fun($this->img, $imagename);
            }

   }

   /*
    生成缩略图
    @param 缩略图的最大宽度
    @param 缩略图的最大高度
    @param 缩略图的裁剪类型(1表示等比例缩放，2表示居中裁剪，3表示左上角裁剪，4表示右下角裁剪，5表示填充，6表示固定)
    */
   public function thumb($width, $height, $type=1){
        if(empty($this->img)){ return false;}

        //原图的宽度和高度
        $w = $this->info['width'];
        $h = $this->info['height'];

        //计算缩略图生成的必要参数
        switch ($type) {
            //等比缩放
            case 1:
                //如果原图尺寸小于缩略图的尺寸就不进行缩略
                if($w < $width && $h < $height) return ;
                //计算缩放比例
                $scale  = min($width/$w, $height/$h);
                //设置缩略图的宽度和坐标
                $x = $y = 0;
                $width = $w*$scale;
                $height = $h*$scale;
                break;
            //居中裁剪
            case 2:
                //计算缩放比例
                $scale  = max($width/$w, $height/$h);
                //设置缩略的坐标以及宽度和高度
                $w  = $width/$scale;
                $h = $height/$scale;
                $x = ($this->info['width'] - $w)/2;
                $y = ($this->info['height'] - $h)/2;
                break;
            //左上角裁剪
            case 3:
                $sacle  = max($width/$w, $height/$h);
                //设置缩略图的坐标以及长度和宽度
                $x = $y = 0;
                $w = $width/$scale;
                $h = $height/$scale;
                break;
            //右下角裁剪
            case 4:
                $scale = max($width/$w, $height/$h);
                //设置缩略图的坐标以及长度和宽度
                $w = $width/$scale;
                $h = $height/$scale;
                $x = $this->info['width'] - $w;
                $y = $this->info['height'] - $h;
                break;
            
            //填充
            case 5:

                break;
            default:
                # code...
                break;
        }
        $this->crop($w, $h, $x, $y, $width, $height);
        

   }

    //添加水印
    /*
    * @param  水印的图片路径
    * @param  水印的位置
    * @param  水印的透明度

     */
    public function water($watersource, $waterlocation, $wateralpha=80){

        //判断背景图是否存在
        if(empty($this->img)) return false;

        //判断水印图是否存在
        if(!file_exists($watersource))  return false;


       
        //得到水印的图像参数
        $info  = getimagesize($watersource);
        //得到水印的扩展名称
        $imagetext = image_type_to_extension($info[2],false);
        $image_fun  = 'imagecreatefrom'.$imagetext;
        
        //水印资源
        $water_img = $image_fun($watersource);
         //设置图像的混色模式
        imagealphablending($water_img, true);

        //设置水印的位置
        switch ($waterlocation) {
            //右下角
            case 1:
                $x = $this->info['width'] - $info[0];
                $y = $this->info['height'] - $info[1];
                break;
            //左下角
            case 2:
                $x = 0;
                $y = $this->info['height'] = $info[1];
                break;
            //左上角
            case 3:
                $x = $y = 0;
                break;
            //右上角
            case 4:
                $x = $this->info['width'] - $info[0];
                $y = 0;
                break;
            //居中水印
            case 5:
                $x = ($this->info['width'] - $info[0])/2;
                $y = ($this->info['height'] - $info[1])/2;
                break;
            //下居中水印
            case 6:
                $x = ($this->info['width'] - $info[0])/2;
                $y = $this->info['height'] - $info[1];
                break;
            //右距中水印
            case 7:
                $x = $this->info['width'] - $info[0];
                $y = ($this->info['height'] - $info[1])/2;
                break;
            /* 上居中水印 */
            case Image::IMAGE_WATER_NORTH:
                $x = ($this->info['width'] - $info[0])/2;
                $y = 0;
                break;

            /* 左居中水印 */
            case Image::IMAGE_WATER_WEST:
                $x = 0;
                $y = ($this->info['height'] - $info[1])/2;
                break;
            default:
                //自定义坐标位置
                if(is_array($waterlocation)){
                list($x, $y) = $waterlocation;
                }else{
                   return false;
                }
                
                
        }
        //开始添加水印
        //创建真彩图像
        $src = imagecreatetruecolor($info[0], $info[1]);
        //调整默认的颜色 设置为白色
        $color = imagecolorallocate($src, 255, 255, 255);
        imagefill($src, 0, 0, $color);
        imagecopy($src, $this->img, 0, 0, $x, $y, $info[0], $info[1]);
        imagecopy($src, $water_img, 0, 0, 0, 0, $info[0], $info[1]);
        imagecopymerge($this->img, $src, $x, $y, 0, 0, $info[0], $info[1], $wateralpha);

        //销毁临时图像
        imagedestroy($src);
        //销毁水印资源
        imagedestroy($water_img);

        

        
    }

    //图像添加文字
    /*
    @param  添加的文字
    @param  字体文件的路径
    @param  添加的文字
    @param  添加的文字
    @param  添加的文字
    @param  添加的文字
    @param  添加的文字
     */
    public function text($text, $font, $size, $color='#00000000', $locate=1, $offset=0, $angle=0){

        //资源检测
        if(empty($this->img)) return false;
        if(!is_file($font)) return false;
        
        //获取文字信息
        $info = imagettfbbox($size, $angle, $font, $text);
        $minx = min($info[0],  $info[2], $info[4], $info[6]);
        $maxx = max($info[0],  $info[2], $info[4], $info[6]);
        $miny = min($info[1], $info[3], $info[5], $info[7]);
        $maxy = max($info[1], $info[3], $info[5], $info[7]);

        //计算文字初始位置的坐标和尺寸
        $x = $minx;
        $y = abs($miny);
        $w = $maxx - $minx;
        $h = $maxy - $miny;

         /* 设定文字位置 */
        switch ($locate) {
            /* 右下角文字 */
            case 1:
                $x += $this->info['width']  - $w;
                $y += $this->info['height'] - $h;
                break;

            /* 左下角文字 */
            case 2:
                $y += $this->info['height'] - $h;
                break;

            /* 左上角文字 */
            case 3:
                // 起始坐标即为左上角坐标，无需调整
                break;

            /* 右上角文字 */
            case 4:
                $x += $this->info['width'] - $w;
                break;

            /* 居中文字 */
            case 5:
                $x += ($this->info['width']  - $w)/2;
                $y += ($this->info['height'] - $h)/2;
                break;

            /* 下居中文字 */
            case 6:
                $x += ($this->info['width'] - $w)/2;
                $y += $this->info['height'] - $h;
                break;

            /* 右居中文字 */
            case 7:
                $x += $this->info['width'] - $w;
                $y += ($this->info['height'] - $h)/2;
                break;

            /* 上居中文字 */
            case 8:
                $x += ($this->info['width'] - $w)/2;
                break;

            /* 左居中文字 */
            case 9:
                $y += ($this->info['height'] - $h)/2;
                break;

            default:
                /* 自定义文字坐标 */
                if(is_array($locate)){
                    list($posx, $posy) = $locate;
                    $x += $posx;
                    $y += $posy;
                } else {
                    E('不支持的文字位置类型');
                }
        }
        //设置偏移量
        if(is_array($offset)){
            $offset = array_map('intval', $offset);
            list($ox, $oy) = $offset;
        }else{
            $offset = intval($offset);
            $ox = $oy = $offset;
        }
        //设置颜色
        if(is_string($color) && 0===strpos($color, "#")){
            $color = str_split(substr($color, 1),2);
            $color = array_map('hexdec', $color);
            if(empty($color[3]) || $color[3]>127){
                $color[3] = 0;
            }                      
        }else if(!is_array($color)){
            return false;
        }

        //写入文字
        $col = imagecolorallocatealpha($this->img, $color[0], $color[1], $color[2], $color[3]);
        imagettftext($this->img, $size, $angle, $x+$ox, $y+$oy, $col, $font, $text);


    }





}