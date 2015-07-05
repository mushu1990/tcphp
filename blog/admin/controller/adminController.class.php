<?php
/**
 * @Author: mushu
 * @Date:   2015-06-17 13:56:58
 */

/**
* 
*/
namespace app\admin\controller;
use tcphp\controller;
class adminController extends controller
{
    public $uid = '';

    public function __construct(){
        parent::__construct();
        //检查用户是否登录
        $this->is_login();
        //检查用户权限
        //检查用户ip
        //输出用户登录日志
        //检查hash
        //
        
    }

    //检查用户是否登录
    final public function is_login(){
        $name = $_SESSION['admin_user'];
        if(empty($name)){
            header("Location: /tcphp/index.php/admin/public/admin_login");
        }else{
            $this->uid = $name['uid'];
        }

    } 

    //检查用户权限
    final public function check_priv(){

    }

    //检查用户ip
    final public function check_ip(){

    }

    //输出用户登录日志
    final public function admin_log(){

    }

    //
    
}