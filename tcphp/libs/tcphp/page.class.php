<?php
//页码类
namespace tcphp;
/**
* 页码
*/
class page 
{
    public $firstRow;//起始行数
    public $listRows;//列表每页显示行数
    public $parameter;//分页跳转的参数
    public $totalRows;//总行数
    public $totalPages;//分页总页面数
    public $rollPage = 11;//分页栏每页显示的页数
    public $lastSuffix = true;//最后一页是否显示总页数

    private $p = 'p';//分页的参数名称
    private $url = '';//当前链接的url
    private $nowPage = 1;


    //分页显示定制
    private $config = array(
            'header'=>'<span class="rows">共 %TOTAL_ROW% 条记录</span>',
            'prev'=>'<<',
            'next'=>'>>',
            'first'=>'1',
            'last'=>'...%TOTAL_PAGE%',
            'theme'=>'%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%'
        );
    
    //架构函数
    function __construct($totalRows, $listRows=20, $parameter=array()){
        C('VAR_PAGE') && $this->p = C('VAR_PAGE');


        $this->totalRows = $totalRows;
        $this->listRows = $listRows;
        $this->parameter = empty($parameter) ? $_GET : $parameter;
        $this->totalPages = ceil($this->totalRows / $this->listRows);
        $this->nowPage = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        
        $this->nowPage = $this->nowPage>0?$this->nowPage : 1;
     
        $this->firstRow = $this->listRows * ($this->nowPage-1);
    
    
    }

    //定制分页链接设置
    private function setConfig($name, $value){
        if(isset($this->config[$name])){
            $this->config[$name] = $value;
        }
    }

    //生成链接url
    private function url($page){
        return str_replace('[PAGE]', $page, $this->url);
    }

    //获取当前分页的url
    private function setUrl() {
        $_url = $_SERVER["REQUEST_URI"];
        $_par = parse_url($_url);
        if (isset($_par['query'])) {
            parse_str($_par['query'],$_query);
            unset($_query[C('VAR_PAGE')]);
            $_url = $_par['path'].'?'.http_build_query($_query);
        }
        return $_url;
    }  

    //组装分页连接
    public function show(){
        if(0 === $this->totalRows) return '';

        //生成当前分页的url
        $this->url = $this->setUrl()."?&".C('VAR_PAGE')."=[PAGE]";
        //die($this->url);
        //考虑特殊情况：当前页大于总的页数
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages){
            $this->nowPage = $this->totalPages;
        }
        
        
        /*
        分页变量
        一般来说，分页的页码条会有  第一页  上一页 页码 下一页  尾页  总条数
        我们用rollPage变量来规定显示多少页码。默认为11个页码。
        也就是说我么要显示11页码。那么怎么显示，一般是显示当前页码的前5页和后5页，加上当前页总共11页。
        但是特殊情况是
        1：如果总页数比rollpage小，那我们把所有的页数全部显示出来
        2：如果当前页小于5，显示前面的11页
      
         */
        $now_cool_page      = $this->rollPage/2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->lastSuffix && $this->config['last'] = $this->totalPages;

        //上一页
        $up_row =$this->nowPage - 1;
        $up_page = $up_row >0 ? '<a class="prev" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>' : '';

        //下一页
        $down_row = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ? '<a class="next" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>' : '';
        dump($this->totalPages);

        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->rollPage - $now_cool_page) >=1){
            $the_first = '<a class="first" href="' . $this->url(1) . '">' . $this->config['first'] . '</a>';
        }

        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            $the_end = '<a class="end" href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a>';
        }

        $link_page = '';
        for ($i=1; $i<=$this->rollPage ; $i++) { 
            if(($this->nowPage-$now_cool_page)<=0){
                $page = $i;
            }elseif (($this->nowPage + $now_cool_page -1) >= $this->totalPages) {
                $page = $this->totalPages - $this->rollPage + $i;
            }else{
                $page = $this->nowPage - $now_cool_page_ceil + $i;
            }

            if($page>0 && $page!=$this->nowPage){
                if($page <= $this->totalPages){
                    $link_page .= '<a class="num" href="' . $this->url($page) . '">' . $page . '</a>';
                }else{
                    break;
                }
            }else{
                if($page>0 && $this->totalPages!=1){
                    $link_page .= '<span class="current">' . $page . '</span>';
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'), array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages), $this->config['theme']);

        return "<div>{$page_str}<div>";







    }

   
}