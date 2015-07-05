<?php
/**
* 
*/
namespace app\admin\controller;
use tcphp\controller;
use app\admin\model\usermodel;
use app\admin\model\rolemodel;
use app\admin\model\menumodel;
class roleController extends adminController
{
    
   //角色管理
   public function rolemanager(){
      if(isset($_POST['dosubmit'])){

      }else{

        $this->display();
      }
   }

   //获取角色列表
   public function rolelists(){
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

      $usermodel = new usermodel("admin_role");
      $result["total"] = $usermodel->count();
      $offset = $rows*($page-1);
      $data = $usermodel->excute("select roleid,rolename,description,disabled from admin_role
       order by roleid desc limit $offset,$rows ");
      foreach ($data as $key => $row) {
          if($data[$key]['disabled'] == '1'){
            $data[$key]['disabled'] = '正常';
          }else{
            $data[$key]['disabled'] ='禁用';
          }
      }
      $result['rows'] = $data;
      $this->ajaxReturn($result);

   }

   //添加角色
   public function roleadd(){
      if (isset($_POST['dosubmit'])) {
          $rolename = trim($_POST['rolename']);          
          $rolemodel = new rolemodel('admin_role');
          $exsitrole = $rolemodel->excute("select * from admin_role where rolename='$rolename' limit 1");

          if(!empty($exsitrole[0]['roleid'])){
              $returndata = array("errorMsg"=>'角色名重复');
          }else{
          
          if(isset($_POST['rolename'])){$rolemodel->data['rolename'] = $_POST['rolename'];}
          if(isset($_POST['description'])){$rolemodel->data['description'] = $_POST['description'];}
          if(isset($_POST['disabled'])){$rolemodel->data['disabled'] = $_POST['disabled'];}
         
          $row = $rolemodel->add();
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

   public function privedit(){

    $roleid = isset($_GET['roleid']) ? intval($_GET['roleid']) : null;
    if($roleid){
       
        //获得角色中的权限ids
        $rolemodel = new rolemodel("admin_role");
        $ids = $rolemodel->excute("select rules from admin_role where roleid = $roleid");
        $ids = explode(',', $ids[0]['rules']);
         //获得所有的菜单
        $menumodel = new menumodel("admin_menus");
        $menus = $menumodel->getMenus('commotree',$ids);
        $this->ajaxReturn($menus);


    }
     

   }

   public function privsave(){
       $roleid = isset($_POST['roleid']) ? intval($_POST['roleid']) : null;
       $rulesids = isset($_POST['rulesids']) ? $_POST['rulesids'] : null;
       $rolemodel = new rolemodel("admin_role");
       $rows = $rolemodel->excute("update admin_role set rules = '$rulesids' where roleid = $roleid",false);
      if($rows<0){
             $returndata = array("errorMsg"=>'权限更新失败');
             
          }else{
            $returndata = array("successMsg"=>'权限更新成功');
          }
          $this->ajaxReturn($returndata);
   }

   public function usermanage(){
    
   }

}