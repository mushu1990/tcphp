<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统登录</title>
<link href="<?php echo ROOT;?>/public/admin/css/login.css" rel="stylesheet" rev="stylesheet" type="text/css" media="all" />
<link href="<?php echo ROOT;?>/public/admin/css/demo.css" rel="stylesheet" rev="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery1.42.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery.SuperSlide.js"></script>
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/Validform_v5.3.2_min.js"></script>

<script>
$(function(){

$(".i-text").focus(function(){
$(this).addClass('h-light');
});

$(".i-text").focusout(function(){
$(this).removeClass('h-light');
});

$("#username").focus(function(){
 var username = $(this).val();
 if(username=='输入账号'){
 $(this).val('');
 }
});

$("#username").focusout(function(){
 var username = $(this).val();
 if(username==''){
 $(this).val('输入账号');
 }
});


$("#password").focus(function(){
 var username = $(this).val();
 if(username=='输入密码'){
 $(this).val('');
 }
});


$("#yzm").focus(function(){
 var username = $(this).val();
 if(username=='输入验证码'){
 $(this).val('');
 }
});

$("#yzm").focusout(function(){
 var username = $(this).val();
 if(username==''){
 $(this).val('输入验证码');
 }
});

$(".yzm-img").click(function() {
    $(this).attr({
        src: '/tcphp/index.php/admin/public/verify'+'/random/'+Math.random()
       
    });
});





});




</script>


</head>

<body>


<div class="header">
  <h1 class="headerLogo"><a title="后台管理系统" target="_blank" href=""><img alt="logo" src="<?php echo ROOT;?>/public/admin/images/logo.gif"></a></h1>
	<div class="headerNav">
		<a target="_blank" href="http://sc.chinaz.com/">华软官网</a>
		<a target="_blank" href="http://sc.chinaz.com/">关于华软</a>
		<a target="_blank" href="http://sc.chinaz.com/">开发团队</a>
		<a target="_blank" href="http://sc.chinaz.com/">意见反馈</a>
		<a target="_blank" href="http://sc.chinaz.com/">帮助</a>	
	</div>
</div>

<div class="banner">

<div class="login-aside">
  <div id="o-box-up"></div>
  <div id="o-box-down"  style="table-layout:fixed;">
   <div class="error-box"></div>
   
   <form class="registerform" action="/tcphp/index.php/admin/public/admin_login" method="post">
   <div class="fm-item">
	   <label for="logonId" class="form-label">系统登陆：</label>
	   <input type="text" value="输入账号" maxlength="100" id="username" class="i-text" ajaxurl="demo/valid.jsp"  name="username" datatype="s6-18" errormsg="用户名至少6个字符,最多18个字符！"  >    
       <div class="ui-form-explain"></div>
  </div>
  
  <div class="fm-item">
	   <label for="logonId" class="form-label">登陆密码：</label>
	   <input type="password" value="" maxlength="100" id="password" class="i-text" datatype="*6-16" name="password" nullmsg="请设置密码！" errormsg="密码范围在6~16位之间！">    
       <div class="ui-form-explain"></div>
  </div>
  
  <div class="fm-item pos-r">
	   <label for="logonId" class="form-label">验证码</label>
	   <input type="text" name="verifycode" value="" maxlength="100" id="yzm" class="i-text yzm" nullmsg="请输入验证码！" >    
       <div class="ui-form-explain"><img src="/tcphp/index.php/admin/public/verify" class="yzm-img" /></div>
  
  </div>
  
  <div class="fm-item">
	   <label for="logonId" class="form-label"></label>
	   <input type="submit" value="1" tabindex="4" id="send-btn" class="btn-login" name="dosubmit"> 
       <div class="ui-form-explain"></div>
  </div>
  
  </form>
  
  </div>

</div>

	<div class="bd">
		<ul>
			<li style="background:url(<?php echo ROOT;?>/public/admin/images/theme-pic1.jpg) #CCE1F3 center 0 no-repeat;"><a target="_blank" href="http://sc.chinaz.com/"></a></li>
			<li style="background:url(<?php echo ROOT;?>/public/admin/images/theme-pic2.jpg) #BCE0FF center 0 no-repeat;"><a target="_blank" href="http://sc.chinaz.com/"></a></li>
		</ul>
	</div>

	<div class="hd"><ul></ul></div>
</div>
<script type="text/javascript">jQuery(".banner").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fold",  autoPlay:true, autoPage:true, trigger:"click" });</script>


<div class="banner-shadow"></div>

<div class="footer">
   <p>Copyright &copy; 2014.Company name All rights reserved.<a target="_blank" href="http://sc.chinaz.com/moban/">&#x7F51;&#x9875;&#x6A21;&#x677F;</a></p>
</div>
<div style="display:none"><script src='http://v7.cnzz.com/stat.php?id=155540&web_id=155540' language='JavaScript' charset='gb2312'></script></div>
</body>
</html>
