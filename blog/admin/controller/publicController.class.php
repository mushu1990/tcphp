<?php
/**
* 公共控制器
*/
namespace app\admin\controller;
use tcphp\controller;
use vendor\verify\verify;
use app\admin\model\usermodel;
class publicController extends controller
{
    
    

    function admin_login(){

        if(isset($_POST['dosubmit'])){
           $um = new usermodel("admin_user");
           //获得post数据
           $username = $_POST['username'];
           $password = $_POST['password'];
           $verifycode = $_POST['verifycode'];

           //验证post数据
           if(strtolower($_SESSION['verifycode']) != $verifycode){
             
              $this->jump("验证码不正确",0);
           }
           if(empty($username) || empty($password)){
              
               $this->jump("用户名密码为空",0);
           }
           //验证通过
           
           //md5密码验证
            
           $data = $um->excute("select * from admin_user where username='$username'");

           if($data[0]["password"] == md5($password)){
              $_SESSION['admin_user'] = array(
                "uid"=>$data[0]['userid'],
                "username"=>$data[0]['username']);
              $this->jump("登录成功",1,"/tcphp/index.php/admin/index/main");
           }else{
               $this->jump("用户名密码错误",0);
           }
        }else{
          $this->display();
        }
    }

    public function verify(){
        $verify = new verify();
        $verify->entry(1);
    }
}