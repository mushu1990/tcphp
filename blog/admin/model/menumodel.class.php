<?php
/**
* 
*/

namespace app\admin\model;
use tcphp\model;
class menumodel extends model
{

  public $tree = array();
  public function getMenus($type="commotree",$menuids=array()){
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
        $menus = $this->excute("select * from admin_menus where pid = $parentId");
        $tree = array();
            if($menus){
                //遍历组装
                foreach ($menus as $menu){
                //先根据id查询是否有子集
                $menuid = $menu['menuid'];
                $childrenMenus = $this->excute("select * from admin_menus where pid = '$menuid'");
                $checked = in_array($menuid, $menuids) ? true : false;

                    if($childrenMenus){
                    array_push($tree, array('id'=>$menu['menuid'],'checked'=>$checked,'text'=>$menu['name'],
                        'children'=>$this->createTree($menu['menuid'],$menuids)));
                    } else {
                array_push($tree, array('id'=>$menu['menuid'],'checked'=>$checked,'text'=>$menu['name'],'children'=>$this->createTree($menu['menuid'],$menuids)));
                    }
                }
            }
        return $tree;
    }

    //treegrid
    public function createTreeGrid($parentId){
        $menus = $this->excute("select * from admin_menus where pid = $parentId");
        $tree = array();
            if($menus){
                //遍历组装
                foreach ($menus as $menu){
                //先根据id查询是否有子集
                $menuid = $menu['menuid'];
    $childrenMenus = $this->excute("select * from admin_menus where pid = '$menuid'");

                    if($childrenMenus){
                    array_push($tree, array('menuid'=>$menu['menuid'],'name'=>$menu['name'],'url'=>$menu['url'],'display'=>$menu['display'],'tip'=>$menu['tip'],
                        'children'=>$this->createTreeGrid($menu['menuid'])));
                    } else {
                 array_push($tree, array('menuid'=>$menu['menuid'],'name'=>$menu['name'],'url'=>$menu['url'],'display'=>$menu['display'],'tip'=>$menu['tip'],'children'=>$this->createTreeGrid($menu['menuid'])));
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