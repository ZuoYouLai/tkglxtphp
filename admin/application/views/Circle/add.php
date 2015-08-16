<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit" />
    <title>居俪网后台管理-后台管理</title>
    <base href="<?php echo base_url(); ?>" />
    <link type="text/css" rel="stylesheet" href="statics/css/pintuer.css" />
    <link type="text/css" rel="stylesheet" href="statics/css/admin.css" />
    <script type="text/javascript" src="statics/js/jquery.js"></script>
    <script type="text/javascript" src="statics/js/pintuer.js"></script>
    <script type="text/javascript" src="statics/js/respond.js"></script>
    <script type="text/javascript" src="statics/plugin/ckfinder/ckfinder.js"></script>
    <script language="javascript">
    	var $img=null;//预览图片
	    function BrowseServer(inputId){ 
		    var finder = new CKFinder() ; 
		    finder.basePath = 'statics/plugin/ckfinder/'; //导入CKFinder的路径 
		    finder.selectActionFunction = SetFileField; //设置文件被选中时的函数 
		    finder.selectActionData = inputId; //接收地址的input ID 
		    finder.popup() ; 
	    } 
	    //文件选中时执行 
	    function SetFileField(fileUrl,data){ 
	    	if($img){
				$($img).remove();
			}
	    	document.getElementById(data["selectActionData"]).value = fileUrl ; 
	    	$img=$('<img alt="" width="34" height="34" src="'+fileUrl+'">');
	    	$('.input-file').after($img);
	    }
</script>
    
</head>
<body>
    <div class="admin">
        <div class="tab">
            <div class="tab-head">
                <strong>添加圈子</strong>
            </div>
            <div class="tab-body">
                <br />
                <div class="tab-panel active" id="tab-set">
                    <form method="post" class="form-x" action="index.php/Circle/add">
                    <div style="display:none;">
	                	<input type="hidden" name="cmd" value="submit" />
	                </div>
	                <div class="form-group">
                        <div class="label"><label for="siteurl">所属分类</label></div>
                        <div class="field">
                            <select id="pid" name="pid" class="input">
                            	<?php 
                            	if ($parent){
                            		foreach ($parent as $item){
                            			?>
                            			<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                            			<?php 
                            		}
                            	}
                            	?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label"><label for="readme">圈子名称</label></div>
                        <div class="field">
                            <input type="text" class="input" maxlength="10" id="name" name="name" size="50" placeholder="分类名称" data-validate="required:请填写分类名称" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="logo">显示图片</label></div>
                        <div class="field">
                        	<input type="hidden" id="logo" name="logo" value="" />
                            <a class="button input-file" href="javascript:BrowseServer('logo');">+ 浏览文件</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="desc">简介</label></div>
                        <div class="field">
                            <textarea id="intro" name="intro" maxlength="100" class="input" rows="5" cols="50" placeholder="请填写圈子描述"></textarea>
                        </div>
                    </div>
                    <div class="form-button">
                        <button class="button bg-main" type="submit">提交</button></div>
                    </form>
                </div>
            </div>
        </div>
        <p class="text-right text-gray">基于<a class="text-gray" target="_blank" href="#">居俪网</a>构建</p>
    </div>
</body>
</html>
