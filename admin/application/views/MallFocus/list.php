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
    <link rel="stylesheet" href="statics/plugin/jquery-carousel/css/flickity-docs.css" media="screen" />
    <script src="statics/plugin/jquery-carousel/js/flickity-docs.min.js"></script>
    <script type="text/javascript" src="statics/js/jquery.js"></script>
    <script type="text/javascript" src="statics/js/pintuer.js"></script>
    <script type="text/javascript" src="statics/js/respond.js"></script>
    <script type="text/javascript" src="statics/plugin/ckfinder/ckfinder.js"></script>
    <script language="javascript">
	    $(function(){
			$('#path').val($('.hero-gallery__cell--1').find('img').attr("src"));
			$('#url').val($('.hero-gallery__cell--1').find('img').attr("url"));
			//控制左右滑动的按钮不提交form
			$("button").click(function(){
				if($(this).attr('type')!='submit')
					return false;
			});
			//下一页
			$(".next").click(function(){
				var index=parseInt($("[name='index']").val());
				index++;
				$("[name='index']").val(index);
				$('#path').val($('.hero-gallery__cell--'+index).find('img').attr("src"));
	        	$('#url').val($('.hero-gallery__cell--'+index).find('img').attr("url"));
			});
			//上一页
			$(".previous").click(function(){
				var index=parseInt($("[name='index']").val());
				index--;
				$("[name='index']").val(index);
				$('#path').val($('.hero-gallery__cell--'+index).find('img').attr("src"));
	        	$('#url').val($('.hero-gallery__cell--'+index).find('img').attr("url"));
			});
			//圆点跳转
			$("li").each(function(e){
				$(this).click(function(){
					var index=$(this).index()+1;
					$('#path').val($('.hero-gallery__cell--'+index).find('img').attr("src"));
		        	$('#url').val($('.hero-gallery__cell--'+index).find('img').attr("url"));
		        	$("[name='index']").val(index);
				});
			});
	    });
	    //删除图片
	    function del(){
	    	$('#path').val('');
	    	$('#url').val('');
	    	$('.hero-gallery__cell--'+$("[name='index']").val()).html('<div class="content-wrap"><h1>'+$("[name='index']").val()+'</h1></div>');
	    }
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
	    	document.getElementById(data["selectActionData"]).value = fileUrl ; 
	    	$('.hero-gallery__cell--'+$("[name='index']").val()).find('img').attr("src",fileUrl)
	    }
	</script>
</head>
<body>
	<div class="admin">
        <form method="post" class="form-x" action="index.php/MallFocus/index">
        <div style="display:none;">
	    	<input type="hidden" name="cmd" value="submit" />
	    	<input type="hidden" name="index" value="1" />
	    </div>
        <div class="panel admin-panel">
            <div class="panel-head">
                <strong>帖子焦点图设置</strong></div>
            <table class="table">
                <tr>
                    <td>
                    	<div class="container">
					        <div class="hero-gallery js-flickity">
					            <div class="hero-gallery__cell hero-gallery__cell--1">
					            	<?php echo $focus[0]['path']==null?'<div class="content-wrap"><h1>1</h1></div>':'<img style="width:100%;" url="'.$focus[0]['url'].'" alt="" src="'.$focus[0]['path'].'">'; ?>
					            </div>
					            <div class="hero-gallery__cell hero-gallery__cell--2">
					                <?php echo $focus[1]['path']==null?'<div class="content-wrap"><h1>2</h1></div>':'<img style="width:100%;" url="'.$focus[1]['url'].'" alt="" src="'.$focus[1]['path'].'">'; ?>
					            </div>
					            <div class="hero-gallery__cell hero-gallery__cell--3">
					                <?php echo $focus[2]['path']==null?'<div class="content-wrap"><h1>3</h1></div>':'<img style="width:100%;" url="'.$focus[2]['url'].'" alt="" src="'.$focus[2]['path'].'">'; ?>
					            </div>
					            <div class="hero-gallery__cell hero-gallery__cell--4">
					                <?php echo $focus[3]['path']==null?'<div class="content-wrap"><h1>4</h1></div>':'<img style="width:100%;" url="'.$focus[3]['url'].'" alt="" src="'.$focus[3]['path'].'">'; ?>
					            </div>
					            <div class="hero-gallery__cell hero-gallery__cell--5">
					                <?php echo $focus[4]['path']==null?'<div class="content-wrap"><h1>5</h1></div>':'<img style="width:100%;" url="'.$focus[4]['url'].'" alt="" src="'.$focus[4]['path'].'">'; ?>
					            </div>
					        </div>
					    </div>
                    </td>
                </tr>
                <tr>
                	<td align="left">
	                	<div class="form-group">
	                        <div class="label">
	                            <label for="logo">显示图片</label></div>
	                        <div class="field">
	                        	<input type="hidden" id="path" name="path" value="" />
	                            <a class="button input-file" href="javascript:BrowseServer('path');">+ 浏览文件</a>
	                            <a class="button input-file" href="javascript:del();">删除图片</a>
	                            <a class="button input-file" style="color:red;" href="javascript:void(0);">&nbsp;&nbsp;&nbsp;&nbsp;添加、删除图片，一次只能保存一张</a>
	                        </div>
	                    </div>
                	</td>
                </tr>
                <tr>
	                <td align="left">
	                	<div class="form-group">
	                        <div class="label"><label for="readme">链接地址</label></div>
	                        <div class="field">
	                            <input type="text" class="input" id="url" name="url" size="50" placeholder="焦点图跳转地址" />
	                        </div>
	                    </div>
	                </td>
                </tr>
                <tr>
	                <td align="center">
	                	<div class="form-button">
	                        <button class="button bg-main" type="submit">提交</button></div>
	                </td>
                </tr>
            </table>
        </div>
        </form>
        <br />
        <p class="text-right text-gray">基于<a class="text-gray" target="_blank" href="#">居俪网</a>构建</p>
    </div>
</body>
</html>