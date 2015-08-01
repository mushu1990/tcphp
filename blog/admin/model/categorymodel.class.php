<?php
/**
* 
*/

namespace app\admin\model;
use tcphp\model;
class categorymodel extends model
{

  public $tree = array();
  public function getCat($type="commotree",$menuids=array()){
    //查询顶级菜单
    if($type=='commotree'){
    $tree = $this->createTree(0,$menuids);
    }elseif ($type=='treegrid') {
        $tree = $this->createTreeGrid(0);
    }
    //array_push($tree, array('name'=>'hello','id'=>'s1','children'=>array('name'=>'c','id'=>'2')));
    return $tree;
   }


   //commobtree
   public function createTree($parentId,$menuids){
        $menus = $this->excute("select * from admin_cat where parentid = $parentId");
        $tree = array();
            if($menus){
                //遍历组装
                foreach ($menus as $menu){
                //先根据id查询是否有子集
                $catid = $menu['catid'];
                $childrenMenus = $this->excute("select * from admin_cat where parentid = '$catid'");
                $checked = in_array($catid, $menuids) ? true : false;

                    if($childrenMenus){
                    array_push($tree, array('id'=>$menu['catid'],'checked'=>$checked,'text'=>$menu['catname'],
                        'children'=>$this->createTree($menu['catid'],$menuids)));
                    } else {
                array_push($tree, array('id'=>$menu['catid'],'checked'=>$checked,'text'=>$menu['catname'],'children'=>$this->createTree($menu['catid'],$menuids)));
                    }
                }
            }
        return $tree;
    }

    //treegrid
    public function createTreeGrid($parentId){
        $menus = $this->excute("select * from admin_cat where parentid = $parentId");
        $tree = array();
            if($menus){
                //遍历组装
                foreach ($menus as $menu){
                //先根据id查询是否有子集
                $catid = $menu['catid'];
    $childrenMenus = $this->excute("select * from admin_cat where parentid = '$catid'");

                    if($childrenMenus){
                    array_push($tree, array('id'=>$menu['catid'],'catname'=>$menu['catname'],'isdisplay'=>$menu['isdisplay'],'description'=>$menu['description'],
                        'children'=>$this->createTreeGrid($menu['catid'])));
                    } else {
                 array_push($tree, array('id'=>$menu['catid'],'catname'=>$menu['catname'],'isdisplay'=>$menu['isdisplay'],'description'=>$menu['description'],'children'=>$this->createTreeGrid($menu['catid'])));
                    }
                }
            }
        return $tree;
    }

    //根据父菜单id找子菜单
    public function getMenusByPid($pid){
        $data = $this->excute("select * from admin_menus where pid = $pid order by sort desc");
        
        if($data){
            return $data;
        }
    }
    
}