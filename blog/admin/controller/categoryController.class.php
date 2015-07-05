<?php
/**
* 
*/
namespace app\admin\controller;
use tcphp\controller;
class categoryController extends adminController
{
    
   //用户管理
   public function usermanager(){
      if(isset($_POST['dosubmit'])){

      }else{

        $this->display();
      }
   }

   //获取用户列表
   public function userlists(){
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

      if(isset($_GET['roleid'])){
            $roleid = intval($_GET['roleid']);
            $where = " where admin_role.roleid = $roleid";
      }else{
        $where = '';
      }

      $usermodel = new usermodel("admin_user");
      $result["total"] = $usermodel->count();
      $offset = $rows*($page-1);
      $data = $usermodel->excute("select userid,username,email,status,regip,rolename,admin_role.roleid from admin_user join admin_role on admin_user.roleid = admin_role.roleid $where order by userid desc limit $offset,$rows ");
      foreach ($data as $key => $row) {
          if($data[$key]['status'] == '1'){
            $data[$key]['status'] = '正常';
          }else{
            $data[$key]['status'] ='禁用';
          }
      }
      $result['rows'] = $data;
      $this->ajaxReturn($result);

   }

   //添加用户
   public function useradd(){
      if(isset($_GET['role'])){
        $rolemodel = new rolemodel("admin_role");
        $data = $rolemodel->excute("select roleid, rolename from admin_role");
        $this->ajaxReturn($data);

            
      }elseif (isset($_POST['dosubmit'])) {
          $username = trim($_POST['username']);          
          $usermodel = new usermodel('admin_user');
          $exsituser = $usermodel->excute("select userid from admin_user where username='$username' limit 1");

          if(!empty($exsituser[0]['userid'])){
              $returndata = array("errorMsg"=>'用户名重复');
          }else{
          $usermodel->data['lastlogintime'] = time();
          $usermodel->data['lastloginip'] = ip();
          $usermodel->data['regtime'] = time();
          $usermodel->data['regip'] = ip();
          if(isset($_POST['username'])){$usermodel->data['username'] = $_POST['username'];}
          if(isset($_POST['password'])){$usermodel->data['password'] = md5($_POST['username']);}
          if(isset($_POST['email'])){$usermodel->data['email'] = $_POST['email'];}
          if(isset($_POST['roleid'])){$usermodel->data['roleid'] = $_POST['roleid'];}
          $row = $usermodel->add();
          if($row>0){
             $returndata = array("successMsg"=>'用户添加成功');
          }else{
             $returndata = array("errorMsg"=>'用户添加失败');
          }
          }
          $this->ajaxReturn($returndata);
      }
   }

   //更新用户
   public function userupdate(){
     if (isset($_POST['dosubmit'])) {
          $usermodel = new usermodel('admin_user');
         
          if(isset($_POST['username'])){$data['username'] = $_POST['username'];}
         
          if(isset($_POST['email'])){$data['email'] = $_POST['email'];}
          if(isset($_POST['roleid'])){$data['roleid'] = $_POST['roleid'];}
          if(isset($_POST['status'])){$data['status'] = $_POST['status'];}
          $userid = $_GET['userid'];
          $row = $usermodel->update($data,array("where"=>"userid=$userid"));
          if($row<0){
             $returndata = array("errorMsg"=>'用户更新失败');
             
          }else{
            $returndata = array("successMsg"=>'用户更新成功');
          }
          $this->ajaxReturn($returndata);
      }
   }

   //删除用户
   public function userdelete(){
      if(isset($_POST['userid'])){
        $userid = intval($_POST['userid']);
      }
      $usermodel = new usermodel('admin_user');
      if($usermodel->delete(array('where'=>"userid=$userid"))){
         $returndata = array("successMsg"=>'用户删除成功');
      }else{
         $returndata = array("errorMsg"=>'用户删除失败');
      }
      $this->ajaxReturn($returndata);

   }

}