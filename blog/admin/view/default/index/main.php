<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="off">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>tcphp- 后台管理中心</title>
<link href="<?php echo ROOT;?>/public/admin/css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ROOT;?>/public/admin/css/system.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery.min.js"></script>


    

</head>
<body scroll="no">
<div class="header">
	<div class="logo lf"><a href="" target="_blank"><span class="invisible">tcphpcms内容管理系统</span></a></div>
    <div class="rt">
    	<div class="tab_style white cut_line text-r"><a href="javascript:;" onclick="lock_screen()"><img src="statics/images/icon/lockscreen.png"> 锁屏</a><span>|</span><a href="http://www.phpcms.cn" target="_blank">官方网站</a><span>|</span><a href="http://www.phpcms.cn/license/license.php" target="_blank">授权</a><span>|</span><a href="http://bbs.phpcms.cn" target="_blank">支持论坛</a><span>|</span><a href="http://v9.help.phpcms.cn" target="_blank">帮助？</a>
        </div>
        <div class="style_but"></div>
    </div>
    <div class="col-auto" style="overflow: visible">
    	<div class="log white cut_line">您好！phpcms  [超级管理员]<span>|</span><a href="?m=admin&c=index&a=public_logout">[退出]</a><span>|</span>
    		<a href="" target="_blank" id="site_homepage">站点首页</a><span>|</span>
    		<a href="?m=member" target="_blank">会员中心</a><span>|</span>
    		<a href="?m=search" target="_blank" id="site_search">全站搜索</a>
    	</div>
        <ul class="nav white" id="top_menu">
        <?php
            foreach ($menus as $key => $row) {                         
        ?>
            <li id="_M<?php echo $row['menuid'];?>" class="<?php if($key==0) echo "on"?> top_menu"><a href="javascript:_M(<?php echo $row['menuid']?>,'/tcphp/index.php/<?php  echo $row["url"]?>');" hidefocus="true" style="outline:none;"><?php  echo $row["name"]?></a></li>
        <?php  } ?>
        
        
       
        </ul>
    </div>
</div>
<div id="content">
	<div class="col-left left_menu">
    	<div id="leftMain">
        </div>
        <a href="javascript:;" id="openClose" style="outline-style: none; outline-color: invert; outline-width: medium;" hideFocus="hidefocus" class="open" title="展开与关闭"><span class="hidden">展开</span></a>
    </div>



	<div class="col-1 lf cat-menu" id="display_center_id" style="display:none" height="100%">
		<div class="content">
        	<iframe name="center_frame" id="center_frame" src="#" frameborder="false" scrolling="auto" style="border:none" width="100%" height="auto" allowtransparency="true"></iframe>
        </div>
    </div>





    <div class="col-auto mr8">
        <div class="crumbs">
            <div class="shortcut cu-span">
            	<a href="#" target="right"><span>生成首页</span></a>
                <a href="?m=admin&c=cache_all&a=init" target="right"><span>更新缓存</span></a>
                <a href="#"><span>后台地图</span></a></div>
        	当前位置：<span id="current_pos"></span>
            </div>
            <div class="col-1">
                <div class="content" style="position:relative; overflow:hidden">
                    <iframe name="right" id="rightMain" src="" frameborder="false" scrolling="auto" style="overflow-x:hidden;border:none; margin-bottom:30px" width="100%" height="auto" allowtransparency="true"></iframe>
                    <div class="fav-nav">
                        <div id="panellist"></div>
                        <div id="paneladd"><a class="panel-add" href="javascript:add_panel();"><em>添加</em></a></div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript"> 
//clientHeight-0; 空白值 iframe自适应高度
function windowW(){
	if($(window).width()<980){
			$('.header').css('width',980+'px');
			$('#content').css('width',980+'px');
			$('body').attr('scroll','');
			$('body').css('overflow','');
	}
}
windowW();
$(window).resize(function(){
	if($(window).width()<980){
		windowW();
	}else{
		$('.header').css('width','auto');
		$('#content').css('width','auto');
		$('body').attr('scroll','no');
		$('body').css('overflow','hidden');
		
	}
});
window.onresize = function(){
	var heights = document.documentElement.clientHeight-150;document.getElementById('rightMain').height = heights;
	var openClose = $("#rightMain").height()+39;
	$('#center_frame').height(openClose+9);
	$("#openClose").height(openClose+30);	
}
window.onresize();
//站点下拉菜单
$(function(){
	//默认载入左侧菜单
	//$("#leftMain").load("/tcphp/index.php/admin/index/menus/menuid/0");
})

//左侧开关
$("#openClose").click(function(){
	if($(this).data('clicknum')==1) {
		$("html").removeClass("on");
		$(".left_menu").removeClass("left_menu_on");
		$(this).removeClass("close");
		$(this).data('clicknum', 0);
	} else {
		$(".left_menu").addClass("left_menu_on");
		$(this).addClass("close");
		$("html").addClass("on");
		$(this).data('clicknum', 1);
	}
	return false;
});
function _M(menuid,targetUrl) {	

	$("#leftMain").load("/tcphp/index.php/admin/index/menus/menuid/"+menuid, {limit: 25}, function(){
           windowW();
         });

	$("#rightMain").attr('src', $("#_MP0 a").attr("data-url"));
     $('.top_menu').removeClass("on");
    $('#_M'+menuid).addClass("on");

	
}
function _MP(menuid,targetUrl) {
	$("#rightMain").attr('src', targetUrl);
	$('.sub_menu').removeClass("on fb blue");
	$('#_MP'+menuid).addClass("on fb blue");
    /*
	$.get("current_pos_"+menuid+".html", function(data){
		$("#current_pos").html(data+'<span id="current_pos_attr"></span>');
	});*/
	$("#current_pos").data('clicknum', 1);
}

</script>
</body>
</html>
