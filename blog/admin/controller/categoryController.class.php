<?php
/**
* 
*/
namespace app\admin\controller;
use tcphp\controller;
use app\admin\model\categorymodel;
class categoryController extends adminController
{
    
   //栏目管理
   public function manager(){
      if(isset($_POST['dosubmit'])){

      }else{

        $this->display();
      }
   }

   //获取类别列表
   public function catlist(){
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

      

      $categorymodel = new categorymodel("admin_cat");
      $result["total"] = $categorymodel->count();
      $offset = $rows*($page-1);
      $data = $categorymodel->excute("select *  from admin_cat  order by catid desc limit $offset,$rows ");
      foreach ($data as $key => $row) {
          if($data[$key]['isdisplay'] == '1'){
            $data[$key]['isdisplay'] = '正常';
          }else{
            $data[$key]['isdisplay'] ='禁用';
          }

      }
      $result['rows'] = $data;
      $result = $categorymodel->getCat("treegrid");
      $this->ajaxReturn($result);

   }

  //添加栏目
   public function addcat(){
      if(isset($_GET['catid'])){
        $categorymodel = new categorymodel("admin_cat");
        $this->assign("catid",$_GET['catid']);
        $this->display();
            
      }elseif (isset($_POST['dosubmit'])) {
          //验证必须字段          
          $catname = trim($_POST['catname']); 
          if(empty($catname)){
            $returndata = array("errorMsg"=>'验证错误，栏目名称为空');
            $this->ajaxReturn($returndata);
          }
          //开始处理字段，空处理，格式转化，sql注入         
          $categorymodel = new categorymodel('admin_cat');

          //栏目名称唯一性验证
          $exsitcat = $categorymodel->excute("select catid from admin_cat where catname='$catname' limit 1");

          if(count($exsitcat)>0){
              $returndata = array("errorMsg"=>'栏目名称重复');
               $this->ajaxReturn($returndata);
          }
          //栏目名称
          $categorymodel->data['catname'] = $_POST['catname'];
          //父id
          if(isset($_POST['parentid'])){$categorymodel->data['parentid'] =intval($_POST['parentid']);}

        
          //栏目图片
          if(isset($_FILES['img'])){
            $status = fileUpload('img');
           
            if($status[0] == '0' ){
              $returndata = array("errorMsg"=>$status[1]);
               $this->ajaxReturn($returndata);
            }elseif ($status[0] == '1') {
               $image = $status[2];
            }
            $categorymodel->data['image'] = $image;
          }
          
          if(isset($_POST['style'])){$categorymodel->data['style'] = $_POST['style'];}

          if(isset($_POST['isdisplay'])){$categorymodel->data['isdisplay'] = $_POST['isdisplay'];}else{$categorymodel->data['isdisplay'] = 0;}

          if(isset($_POST['keywords'])){$categorymodel->data['keywords'] = $_POST['keywords'];}

        

          $row = $categorymodel->add();
          if($row>0){
             $returndata = array("successMsg"=>'栏目添加成功');
          }else{
             $returndata = array("errorMsg"=>'栏目添加失败');
          }
          
          $this->ajaxReturn($returndata);
      }
   }

   public function getcat(){
      $categorymodel = new categorymodel("admin_cat");
      $result = $categorymodel->getCat("commotree");
      $this->ajaxReturn($result);
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