<!DOCTYPE html>
<html>
<head>
    <title>添加用户</title>
    <link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/public/admin/css/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT;?>/public/admin/css/icon.css">
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT;?>/public/admin/js/jquery.easyui.min.js"></script>
   
    <script  type="text/javascript" >
 function newUser(){
    $('#dlg').dialog('open').dialog('setTitle','New User');
    $('#fm').form('clear');
    url = 'save_user.php';
}


    </script>
</head>
<body>
<table id="dg" title="用户列表" class="easyui-datagrid" style=""
        url="/tcphp/index.php/admin/user/userlists"
        toolbar="#toolbar"
        rownumbers="true" fitColumns="true" singleSelect="true" pagination="true">
    <thead>
        <tr>
            <th field="userid" width="50">用户ID</th>
            <th field="username" width="50">用户名</th>
            <th field="email" width="50">邮箱</th>
            <th field="regip" width="50">注册IP</th>
            <th field="status" width="50">状态</th>
            <th field="rolename" width="50">角色</th>
        </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">添加用户</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">编辑用户</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">删除用户</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
        closed="true" buttons="#dlg-buttons">
    <div class="ftitle">用户操作</div>
    <form id="fm" method="post">
        <div class="fitem">
            <label>用户名:</label>
            <input name="username" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem">
            <label>密码:</label>
            <input name="password" class="easyui-validatebox" required="true">
        </div>
        <div class="fitem">
            <label>email:</label>
            <input name="phone">
        </div>
        <div class="fitem">
            <label>所属角色:</label>
           <select class="easyui-combobox" id="role" name="role" url="/tcphp/index.php/admin/user/useradd/role/1" valueField="roleid" textField="rolename">
           
           </select>
        </div>
    </form>
</div>
<div id="dlg-buttons">
    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">Save</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
</div>
</body>
</html>

