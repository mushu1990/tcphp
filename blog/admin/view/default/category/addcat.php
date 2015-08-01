
<form id="addcat" method="post" enctype="multipart/form-data" >
  <div class="easyui-tabs" style="width:1200px;height:400px;">
    <div title="基本选项" style="padding:10px;">
        <div>栏目名称：<input class="easyui-validatebox" type="text" name="catname" data-options="required:true" style="width:200px;" /></div>
        <br>
        <div>上级栏目：<select id="cc" class="easyui-combotree" style="width:200px;"
        data-options="url:'/tcphp/index.php/admin/category/getcat'" name="parentid">
        </select></div>
        <br>
        <div>栏目图片：<input class="easyui-filebox" name="img" data-options="prompt:'选择上传的图片...'" style="width:200px"></div>
        <br>
        <div>栏目描述：<input class="easyui-validatebox" type="text" name="description" data-options="required:true" /></div> 
        <br>
         <div>是否显示：
         <input class="easyui-validatebox" type="radio" name="isdisplay" value="1" data-options="required:true" checked="checked"  />是
         <input class="easyui-validatebox" type="radio" name="isdisplay" value="0" data-options="required:true" />否
       
         </div> 
        <input type="hidden" name="dosubmit" value="1" />
      
    </div>
    <div title=" 模板设置"  style="padding:10px;">
         <div>主题风格：
            <select name="style">
                <option value="default">默认风格</option>
               
            </select>
         </div>
        <br>
        <div>栏目首页模板：
            <select name="style">
                <option value="">默认模板</option>
               
            </select>
         </div>
        <br>
         <div>栏目列表页模板：
            <select name="style">
                <option value="">默认模板</option>
               
            </select>
         </div>
        <br>
         <div>栏目内容页模板：
            <select name="style">
                <option value="">默认模板</option>
               
            </select>
         </div>
        <br>
        <input type="hidden" name="dosubmit" value="1" />
        
    </div>
    <div title="SEO设置" iconCls="icon-reload"  style="padding:10px;">
      

        <div>栏目关键词：<input class="easyui-validatebox" type="text" name="keywords" data-options="required:true" style="width:200px;" /></div>
        <br>

       

        <input type="hidden" name="dosubmit" value="1" />
        
    </div>
   
</div>
 <div id="dlg-buttons">
        <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="addCat()">Save</a>
   
    </div>
</form>


<script type="text/javascript">
    function addCat(){
        $('#addcat').form('submit',{
        url: '/tcphp/index.php/admin/category/addcat',
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

