<form id="addMenus" method="post">
  <div>上级菜单：<select id="cc" class="easyui-combotree" style="width:200px;"
        data-options="url:'/tcphp/index.php/admin/menu/getmenus'" name="menuid">
</select></div>
<div>菜单名称：<input class="easyui-validatebox" type="text" name="name" data-options="required:true" /></div>
<div>菜单url：<input class="easyui-validatebox" type="text" name="url" data-options="required:true" /></div>
<div>是否显示：<input type="radio" name="display" value="1"/>显示<input type="radio" name="display" value="0"/>隐藏</div>
<div>菜单描述：<input class="easyui-validatebox" type="text" name="tip" data-options="required:true" /></div> 
<input type="hidden" name="dosubmit" value="1" />
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="addMenus()">Save</a>
   
</div>
</form>
<script type="text/javascript">
    function addMenus(){
        $('#addMenus').form('submit',{
        url: '/tcphp/index.php/admin/menu/addmenu',
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
                $('#dialog_1').dialog('close');      // close the dialog
                $('#menus').treegrid('reload');    // reload the user data
            }
        }
    });
    }

</script>

