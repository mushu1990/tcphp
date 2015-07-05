<!DOCTYPE html>
<html>
<head>
    <title>角色管理</title>
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/public/admin/css/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/public/admin/css/icon.css">
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery.easyui.min.js"></script>
   
    <script  type="text/javascript" >
 function newUser(){
    $('#dlg').dialog('open').dialog('setTitle','添加角色');
    $('#fm').form('clear');
    
}

function saveUser(){
    $('#fm').form('submit',{
        url: '/tcphp/index.php/admin/role/roleadd',
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
                $('#dlg').dialog('close');      // close the dialog
                $('#dg').datagrid('reload');    // reload the user data
            }
        }
    });
}

function editUser(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
    $('#dlgupdate').dialog('open').dialog('setTitle','编辑用户');
    $('#fmupdate').form('load',row);
    url = '/tcphp/index.php/admin/user/userupdate/userid/'+row.userid;
}
}

function updateUser(){
    $('#fmupdate').form('submit',{
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
                $('#dlgupdate').dialog('close');      // close the dialog
                $('#dg').datagrid('reload');    // reload the user data
            }
        }
    });
}
function destroyUser(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirm','是否删除此用户?',function(r){
            if (r){
                $.post('/tcphp/index.php/admin/user/userdelete',{userid:row.userid},function(result){
                    if (result.successMsg){
                        $('#dg').datagrid('reload');    // reload the user data
                    } else {
                        $.messager.show({   // show error message
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    }
                },'json');
            }
        });
    }
}



function userManage(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        window.location.href="/tcphp/index.php/admin/user/usermanager/roleid/"+row.roleid;
       
    }else{
       $.messager.show({
            title:'消息提示',
            msg:'未选中行',
            timeout:2000,
            showType:'slide',
            icon:'warning'
        });
    }

}
    </script>
</head>
<body>
<table id="dg" title="角色列表" class="easyui-datagrid" style=""
        url="/tcphp/index.php/admin/role/rolelists"
        toolbar="#toolbar"
        rownumbers="true" fitColumns="true" singleSelect="true" pagination="true">
    <thead>
        <tr>
            <th field="roleid" width="50">角色ID</th>
            <th field="rolename" width="50">角色名称</th>
            <th field="description" width="50">描述</th>            
            <th field="disabled" width="50">状态</th>
           
        </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">添加角色</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">编辑角色</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">删除角色</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="privEdit()">权限设置</a>
     <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="userManage()">成员管理</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
        closed="true" buttons="#dlg-buttons">
    <div class="ftitle">用户操作</div>
    <form id="fm" method="post">
        <div class="fitem">
            <label>角色名称:</label>
            <input name="rolename" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem">
            <label>角色描述:</label>            
            <input class="easyui-textbox" name="description" data-options="multiline:true" style="height:60px" required="true"></input>
        </div>
        <div class="fitem">
            <label>是否启用:</label>
           <input type="radio" name="disabled" value="1" checked="checked">启用
           <input type="radio" name="disabled" value="0" />禁用
        </div>
        <input type="hidden" name="dosubmit" value="1" />
    </form>


</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">Save</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
</div>
<div id="dlgupdate" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
        closed="true" buttons="#dlg-buttons">
    <div class="ftitle">用户操作</div>
    <form id="fmupdate" method="post">
        <div class="fitem">
            <label>用户名:</label>
            <input name="username" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem">
            <label>email:</label>
            <input name="email" class="easyui-validatebox" required="true" validType="email">
        </div>
        <div class="fitem">
            <label>所属角色:</label>
            <input id="cc" class="easyui-combobox" name="roleid"
    data-options="valueField:'roleid',textField:'rolename',url:'/tcphp/index.php/admin/user/useradd/role/1'">          
                  
        </div>
         <div class="fitem">
            <label>状态:</label>
            <select id="cc" class="easyui-combobox" name="status" style="width:200px;" value='1'>
        <option value="1">正常</option>
         <option value="0">禁用</option>
    </select>          
                  
        </div>
        <input type="hidden" name="dosubmit" value="1">
    </form>


</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="updateUser()">Save</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgupdate').dialog('close')">Cancel</a>
</div>

<!--权限设置-->

<div id="privEdit">

       <ul id="privEditUl">
           

       </ul>
   <input type="submit" value="提交" id="prevsubmit"/>
    </div>
<script  type="text/javascript" >
    function privEdit(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $("#privEdit").window('open');
        $("#privEditUl").tree({
           url:'/tcphp/index.php/admin/role/privedit/roleid/'+row['roleid'],
           checkbox:true
           
        });

    }else{
       $.messager.show({
            title:'消息提示',
            msg:'未选中行',
            timeout:2000,
            showType:'slide',
            icon:'warning'
        });
    }
    }

    $(function(){
        $('#privEdit').window({
            width:600,
            height:400,
            modal:true,
            closed:true,
            title:'权限设置'
        });
       
        $("#prevsubmit").click(function(){

          var nodes = $("#privEditUl").tree("getChecked", ["checked"]); 
          console.log(nodes);   
          var ids = getids(nodes);
          console.log(ids);
          var row = $('#dg').datagrid('getSelected');
          $.post('/tcphp/index.php/admin/role/privsave',{roleid:row.roleid,rulesids:ids},function(result){
                    if (result.successMsg){
                        $.messager.show({   
                            title: '消息',
                            msg: result.successMsg
                        }); 
                    } else {
                        $.messager.show({   
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    }
                },'json');
    

        });
        
       
    });

     //获得选中的id
     function getids(nodes){

        var ids = '';
            for(i=0; i< nodes.length; i++){
                if(nodes.children){
                    ids += getids(nodes.children)+',';
                }else{
                    ids += nodes[i]['id']+',';
                }
                
            }
        return ids.substring(0,ids.length-1);
     }
   
</script>
</body>
</html>

