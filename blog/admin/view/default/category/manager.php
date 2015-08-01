
<!DOCTYPE html>
<html>
<head>
    <title>类别管理</title>
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/public/admin/css/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/public/admin/css/icon.css">
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery.easyui.min.js">
</script>
</head>
<body>
<table id="menus" style=""></table>
<div id="dialog_1">  

</div>

<div id="updateMenusDialog" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"        closed="true" buttons="">
<form id="updateMenus" method="post">
  <div>上级菜单：<select id="cc" class="easyui-combotree" style="width:200px;"
        data-options="url:'/tcphp/index.php/admin/menu/getmenus'" name="menuid">
</select></div>
<div>菜单名称：<input class="easyui-validatebox" type="text" name="name" data-options="required:true" /></div>
<div>菜单url：<input class="easyui-validatebox" type="text" name="url" data-options="required:true" /></div>
<div>是否显示：<input type="radio" name="display" value="1"/>显示<input type="radio" name="display" value="0"/>隐藏</div>
<div>菜单描述：<input class="easyui-validatebox" type="text" name="tip" data-options="required:true" /></div> 

<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="menusMethod.updateMenus()">Save</a>
   
</div>
</form>
</div>


<script type="text/javascript">
    //公共方法
    var menusMethod = {
        //工具类
    addMenu:function(){
        var row = $('#menus').treegrid("getSelected");
        catid = row == null ? 0 : row.catid;
        $("#dialog_1").dialog({
            title: '添加栏目',
            width: '1200',
            height: '500',
            iconCls: 'icon-add',
            href: '/tcphp/index.php/admin/category/addcat/catid/'+catid,
            modal: true
        });

    },
    removeMenu:function(){
        var row = $('#menus').treegrid("getSelected");
        menu_id = row == null ? 0 : row.menuid;
        if (menu_id){
        $.messager.confirm('Confirm','是否删除此菜单?',function(r){
            if (r){
                $.post('/tcphp/index.php/admin/menu/menudelete',{menuid:menu_id},function(result){
                    if (result.successMsg){
                        $('#menus').treegrid('reload');    // reload the user data
                    } else {
                        $.messager.show({   // show error message
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    }
                },'json');
            }
        });
       }else{
           $.messager.show({   // show error message
                            title: '显示消息',
                            msg: "未选中行"
                        });
       }
    },
    editMenu: function(){
         var row = $('#menus').datagrid('getSelected');
         if (row){
        $('#updateMenusDialog').dialog('open').dialog('setTitle','编辑菜单');
          $('#updateMenus').form('load',row);
          url = '/tcphp/index.php/admin/menu/menuupdate/menuid/'+row.menuid;
         }
     },

    updateMenus:function(){
         $('#updateMenus').form('submit',{
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            var result = eval('('+result+')');
            if (result.errorMsg){
                $.messager.show({
                    title: 'Error',
                    msg: result.errorMsg
                });
            } else {
                $.messager.show({
                    title: '提示消息',
                    msg: result.successMsg
                });
                $('#updateMenusDialog').dialog('close');      // close the dialog
                $('#menus').treegrid('reload');    // reload the user data
            }
        }
    });
    }
     

    };

    //初始化
    $('#menus').treegrid({
    title:'栏目管理',
    url:'/tcphp/index.php/admin/category/catlist',
    idField:'catid',
    treeField:'catname',
    columns:[[
        {field:'catid',title:'栏目id',align:'center',width:20},
        {field:'catname',title:'栏目名称',align:'left',width:100},
        {field:'modelid',title:'所属模型',align:'center',width:200},
        {field:'display',title:'是否显示',width:50},
        {field:'description',title:'栏目描述',align:'center',width:200}
            ],],
    toolbar: [{
        iconCls: 'icon-add',
        handler: menusMethod.addMenu,
        text:"添加栏目"
    },{
        iconCls: 'icon-edit',
        handler: menusMethod.editMenu,
         text:"编辑栏目"
    },'-',{
        iconCls: 'icon-help',
        handler: function(){alert('help')}
    },{
        iconCls: 'icon-remove',
        handler: menusMethod.removeMenu,
         text:"移除栏目"
    }],
    nowrap:true,
    rownumbers: true,
    animate: true,
    
    fitColumns:true


});
</script>
</body>
</html>

