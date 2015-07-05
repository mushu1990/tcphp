<?php
/**
* 
*/
namespace app\admin\controller;
use tcphp\controller;
use app\admin\model\usermodel;
use app\admin\model\menumodel;
class menuController extends adminController
{
    
   //用户管理
   public function menumanager(){
      if(isset($_POST['dosubmit'])){

      }else{

        $this->display();
      }
   }

   //获取菜单列表
   public function menulists(){
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

      

      $menumodel = new menumodel("admin_menus");
      $result["total"] = $menumodel->count();
      $offset = $rows*($page-1);
      $data = $menumodel->excute("select *  from admin_menus  order by menuid desc limit $offset,$rows ");
      foreach ($data as $key => $row) {
          if($data[$key]['display'] == '1'){
            $data[$key]['display'] = '正常';
          }else{
            $data[$key]['display'] ='禁用';
          }

      }
      $result['rows'] = $data;
      $result = $menumodel->getMenus("treegrid");
      $this->ajaxReturn($result);

   }

   //添加菜单
   public function addmenu(){
      if(isset($_GET['menuid'])){
        $menumodel = new menumodel("admin_menus");
        $this->assign("menuid",$_GET['menuid']);

        $this->display();
            
      }elseif (isset($_POST['dosubmit'])) {
          $name = trim($_POST['name']);          
          $menumodel = new menumodel('admin_menus');
          $exsitmenu = $menumodel->excute("select menuid from admin_menus where name='$name' limit 1");

          if(!empty($exsitmenu[0]['menuid'])){
              $returndata = array("errorMsg"=>'菜单重复');
          }else{
          
          if(isset($_POST['name'])){$menumodel->data['name'] = $_POST['name'];}
          if(isset($_POST['url'])){$menumodel->data['url'] =$_POST['url'];}
          if(isset($_POST['display'])){$menumodel->data['display'] = $_POST['display'];}
          if(isset($_POST['tip'])){$menumodel->data['tip'] = $_POST['tip'];}
          if(isset($_POST['menuid'])){$menumodel->data['pid'] = $_POST['menuid'];}else{$menumodel->data['pid'] = 0;}
          $row = $menumodel->add();
          if($row>0){
             $returndata = array("successMsg"=>'菜单添加成功');
          }else{
             $returndata = array("errorMsg"=>'菜单添加失败');
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

   //删除菜单
   public function menudelete(){
      if(isset($_POST['menuid'])){
        $menuid = intval($_POST['menuid']);
      }
      $menumodel = new menumodel('admin_menus');
      if($menumodel->delete(array('where'=>"pid=$menuid or menuid=$menuid"))){
         $returndata = array("successMsg"=>'菜单删除成功');
      }else{
         $returndata = array("errorMsg"=>'菜单删除失败');
      }
      $this->ajaxReturn($returndata);

   }

   public function menuupdate(){
       $menuid = isset($_POST['menuid']) ? intval($_POST['menuid']) : null;
       if($menuid){
        $menumodel = new menumodel('admin_menus');
        $data  = $_POST;
        $rows = $menumodel->update($data,array('where'=>"menuid=$menuid"));
        if($rows<0){
          $returndata = array("errorMsg"=>'菜单更新失败');
        }else{
          $returndata = array("successMsg"=>'菜单更新成功');
        }
        $this->ajaxReturn($returndata);
       }

   }

   //获得菜单
   public function getmenus(){
      $menumodel = new menumodel("admin_menu");
      $data = $menumodel->getMenus();
      $this->ajaxReturn($data);
   }

}