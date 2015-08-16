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
        	$("#tab-focus").hide();
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
			});
			//上一页
			$(".previous").click(function(){
				var index=parseInt($("[name='index']").val());
				index--;
				$("[name='index']").val(index);
			});
			//圆点跳转
			$("ol li").each(function(e){
				$(this).click(function(){
					var index=$(this).index()+1;
		        	$("[name='index']").val(index);
				});
			});
        });
        var arr_pic=new Array();
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
		    //将选择的图片保存到隐藏域
	    	document.getElementById(data["selectActionData"]).value = fileUrl ;
	    	//只有选择轮播图的时候才保存图片 
	    	if(data["selectActionData"]!='head_pic'){
	    		//更换图片
		    	var index=$("[name='index']").val();
		    	$('.hero-gallery__cell--'+index).html('<img alt="" src="'+fileUrl+'">')
		    	//将图片保存到数组
		    	arr_pic[index-1]=fileUrl;
		    	//将数组图片值保存到隐藏域
		    	$("#focus").val(arr_pic);
		   	}
	    }
	  	//删除图片
        function del(){
        	var index=$("[name='index']").val();
        	$('.hero-gallery__cell--'+index).html('<div class="content-wrap"><h1>'+index+'</h1></div>');
        	//删除数组元素
        	arr_pic.splice(index-1,1);
        	//将数组图片值保存到隐藏域
	    	$("#focus").val(arr_pic);
        }
	</script>
</head>
<body>
    <div class="admin">
	    <div class="tab">
	        <div class="tab-head">
	            <strong>添加商品</strong>
	            <ul class="tab-nav">
	                <li class="active"><a href="#tab-set" onclick="$('#tab-focus').hide();">基本信息</a></li>
	                <li><a href="#tab-focus" onclick="$('#tab-focus').show();">轮播图</a></li>
	            </ul>
	        </div>
	        <div class="tab-body">
	        <form method="post" class="form-x" action="index.php/Product/add">
		        <div style="display:none;">
		        	<input type="hidden" name="cmd" value="submit" />
		        	<input type="hidden" name="index" value="1" />
		       	</div>
	            <div class="tab-panel active" id="tab-set">
	                <div class="form-group">
	                    <div class="label">
	                        <label>商品名称</label></div>
	                    <div class="field">
	                        <input type="text" class="input" id="title" name="title" size="50" placeholder="商品名称" data-validate="required:请填写商品名称" />
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="readme">缩略图</label></div>
	                    <div class="field">
	                        <input type="hidden" id="head_pic" name="head_pic" value="" />
	                        <a class="button input-file" href="javascript:BrowseServer('head_pic');">+ 浏览文件</a>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="sitename">商品编码</label></div>
	                    <div class="field">
	                        <input type="text" class="input" id="number" name="number" size="50" placeholder="商品编号" data-validate="required:请填写商品编号" />
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="siteurl">兑换所需积分</label></div>
	                    <div class="field">
	                        <input type="text" class="input" id="points" name="points" size="50" placeholder="请填写兑换所需积分" data-validate="required:请填写兑换所需积分,regexp#[0-9]:只能输入数字格式,length#<10:只能输入十位数字" />
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="logo">产品总数</label></div>
	                    <div class="field">
	                        <input type="text" class="input" id="stoSum" name="stoSum" size="50" placeholder="请填写产品总数" data-validate="required:请填写产品总数,regexp#[0-9]:只能输入数字格式,length#<10:只能输入十位数字" />
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="title">详细参数</label></div>
	                    <div class="field">
	                        <!-- 加载编辑器的容器 -->
	                        <script id="content" name="content" type="text/plain"></script>
	                        <!-- 配置文件 -->
	                        <script type="text/javascript" src="statics/plugin/ueditor1_4_3-utf8-php/ueditor.config.js"></script>
	                        <!-- 编辑器源码文件 -->
	                        <script type="text/javascript" src="statics/plugin/ueditor1_4_3-utf8-php/ueditor.all.js"></script>
	                        <!-- 实例化编辑器 -->
	                        <script type="text/javascript">
	                            var ue = UE.getEditor('content', {
	                                /*toolbars: [
	                                ['fullscreen', 'imagenone', 'link', 'unlink', 'bold']
	                                ],*/
	                                initialFrameWidth: '100%',
	                                initialFrameHeight: 500,
	                                autoHeightEnabled: true,
	                                autoFloatEnabled: true
	                            });
	                        </script>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label>状态</label></div>
	                    <div class="field">
	                        <div class="button-group button-group-small radio">
	                            <label class="button active">
	                                <input name="status" value="1" checked="checked" type="radio" /><span class="icon icon-check"></span>上架</label>
	                            <label class="button">
	                                <input name="status" value="0" type="radio" /><span class="icon icon-times"></span>下架</label>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div id="tab-focus">
	                <table class="table">
		                <tr>
		                    <td>
		                    	<div class="container">
							        <div class="hero-gallery js-flickity">
							            <div class="hero-gallery__cell hero-gallery__cell--1">
							            	<div class="content-wrap"><h1>1</h1></div>
							            </div>
							            <div class="hero-gallery__cell hero-gallery__cell--2">
							                <div class="content-wrap"><h1>2</h1></div>
							            </div>
							            <div class="hero-gallery__cell hero-gallery__cell--3">
							                <div class="content-wrap"><h1>3</h1></div>
							            </div>
							            <div class="hero-gallery__cell hero-gallery__cell--4">
							                <div class="content-wrap"><h1>4</h1></div>
							            </div>
							            <div class="hero-gallery__cell hero-gallery__cell--5">
							                <div class="content-wrap"><h1>5</h1></div>
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
			                        	<input type="hidden" id="focus" name="focus" value="" />
			                            <a class="button input-file" href="javascript:BrowseServer('focus');">+ 浏览文件</a>
			                            <a class="button input-file" href="javascript:del();">删除图片</a>
			                        </div>
			                    </div>
		                	</td>
		                </tr>
		            </table>
	            </div>
		        <div class="form-button" style="text-align:center;">
		        	<button class="button bg-main" type="submit">提交</button></div>
		   	</form>
	        </div>
	    </div>
	    <p class="text-right text-gray">基于<a class="text-gray" target="_blank" href="javascript:void(0);">居俪网</a>构建</p>
	</div>
</body>
</html>
