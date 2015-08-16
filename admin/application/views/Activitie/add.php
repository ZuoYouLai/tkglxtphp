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
	<script type="text/javascript" src="statics/plugin/layer-v1.8.2/layer.min.js"></script>
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
				var index=parseInt($("[name='focus_index']").val());
				index++;
				$("[name='focus_index']").val(index);
			});
			//上一页
			$(".previous").click(function(){
				var index=parseInt($("[name='focus_index']").val());
				index--;
				$("[name='focus_index']").val(index);
			});
			//圆点跳转
			$("ol li").each(function(e){
				$(this).click(function(){
					var index=$(this).index()+1;
		        	$("[name='focus_index']").val(index);
				});
			});
        });
        var arr_focus=new Array();
    	var $img=null;//预览图片
	    function select_focus(inputId){ 
		    var finder = new CKFinder() ; 
		    finder.basePath = 'statics/plugin/ckfinder/'; //导入CKFinder的路径 
		    finder.selectActionFunction = set_focus; //设置文件被选中时的函数 
		    finder.selectActionData = inputId; //接收地址的input ID 
		    finder.popup() ; 
	    } 
	    //文件选中时执行 
	    function set_focus(fileUrl,data){ 
		    //将选择的图片保存到隐藏域
	    	document.getElementById(data["selectActionData"]).value = fileUrl ;
	    	//只有选择轮播图的时候才保存图片 
	    	var index=$("[name='focus_index']").val();
	    	$('.hero-gallery__cell--'+index).html('<img alt="" src="'+fileUrl+'">')
	    	//将图片保存到数组
	    	arr_focus[index-1]=fileUrl;
	    	//将数组图片值保存到隐藏域
	    	$("#focus").val(JSON.stringify(arr_focus));
	    }
	  	//删除图片
        function del_focus(){
        	var index=$("[name='focus_index']").val();
        	$('.hero-gallery__cell--'+index).html('<div class="content-wrap"><h1>'+index+'</h1></div>');
        	//删除数组元素
        	arr_focus.splice(index-1,1);
        	//将数组图片值保存到隐藏域
	    	$("#focus").val(JSON.stringify(arr_focus));
        }
        /************以上是轮播图的操作******************/
        var arr_pic=new Array();
        function select_pic(inputId){ 
		    var finder = new CKFinder() ; 
		    finder.basePath = 'statics/plugin/ckfinder/'; //导入CKFinder的路径 
		    finder.selectActionFunction = set_pic; //设置文件被选中时的函数 
		    finder.selectActionData = inputId; //接收地址的input ID 
		    finder.popup() ; 
	    } 
	    //文件选中时执行 
	    function set_pic(fileUrl,data){ 
		    //将选择的图片保存到隐藏域
	    	document.getElementById(data["selectActionData"]).value = fileUrl ;
	    	//只有选择轮播图的时候才保存图片
	    	var index=$("#"+data["selectActionData"]).attr("for-index");
	    	$("div[for='"+data["selectActionData"]+"']").html('<img alt="" src="'+fileUrl+'" width="246" height="246" />');//
	    	//先删除当前位置的图片，再将图片保存到数组
	    	arr_pic.splice(index-1,1);
	    	arr_pic.push(fileUrl);
	    	//将数组图片值保存到隐藏域
	    	$("#pic").val(JSON.stringify(arr_pic));
	    }
	  	//删除图片
        function del_pic(index){
        	//删除数组元素
        	arr_pic.remove($("#pic"+index).val());
        	$("div[for='pic"+index+"']").html('<label>花絮图片'+index+'</label>');
        	//将数组图片值保存到隐藏域
	    	$("#pic").val(JSON.stringify(arr_pic));
        }
        /******删除数组元素的函数*******/
		Array.prototype.indexOf = function(val) {
    		for (var i = 0; i < this.length; i++) {
        		if (this[i] == val) return i;
   			}
            return -1;
        };
        Array.prototype.remove = function(val) {
            var index = this.indexOf(val);
            if (index > -1) {
                this.splice(index, 1);
            }
        };
        //验证form
        function checkForm(){
            /*保存活动报道*/
            var str_article='[';
        	for(var i=1;i<5;i++){
				if($("#article"+i).val()!=''){
					if(str_article!='['){
						str_article+=',';
					}
					str_article+='{"title":"'+$("#article"+i).val()+'","url":"'+$("#url"+i).val()+'"}';
				}
			}
        	str_article+=']';
        	$("#article").val(str_article);
			var article=$.trim($("#article").val());
			var pic=$.trim($("#pic").val());
			var focus=$.trim($("#focus").val());
			if(focus==''){
				layer.msg("请设置轮播图");
				return false;
			}
			if(article==''){
				layer.msg("请设置相关报道");
				return false;
			}
			if(pic==''){
				layer.msg("请设置活动花絮");
				return false;
			}
        }
	</script>
</head>
<body>
    <div class="admin">
	    <div class="tab">
	        <div class="tab-head">
	            <strong>添加活动专题</strong>
	            <ul class="tab-nav">
	                <li class="active"><a href="#tab-set" onclick="$('#tab-focus').hide();">基本信息</a></li>
	                <li><a href="#tab-focus" onclick="$('#tab-focus').show();">轮播图</a></li>
	                <li><a href="#tab-article" onclick="$('#tab-focus').hide();">相关报道</a></li>
	                <li><a href="#tab-pic" onclick="$('#tab-focus').hide();">活动花絮</a></li>
	            </ul>
	        </div>
	        <div class="tab-body">
		        <form method="post" class="form-x" action="index.php/Activitie/add" onsubmit="return checkForm();">
			        <div style="display:none;">
			        	<input type="hidden" name="cmd" value="submit" />
			        	<!-- 保存花絮图片 -->
			        	<input type="hidden" id="pic" name="pic" value="" />
			        	<!-- 保存相关文章 -->
			        	<input type="hidden" id="article" name="article" value="" />
			        	<!-- 保存轮播图  -->
			        	<input type="hidden" id="focus" name="focus" value="" />
			        	<input type="hidden" name="focus_index" value="1" />
			       	</div>
		            <div class="tab-panel active" id="tab-set" style="min-height: 300px;">
		                <div class="form-group">
		                    <div class="label">
		                        <label>活动标题</label></div>
		                    <div class="field">
		                        <input type="text" class="input" id="title" name="title" size="50" placeholder="活动标题" data-validate="required:请填写活动标题" />
		                    </div>
		                </div>
		                <div class="form-group">
		                    <div class="label">
		                        <label>视频链接</label></div>
		                    <div class="field">
		                        <input type="text" class="input" id="video" name="video" size="50" placeholder="视频链接" data-validate="required:请填写视频链接,url:视频链接填写错误" />
		                    </div>
		                </div>
		                <div class="form-group">
		                    <div class="label">
		                        <label for="siteurl">活动描述</label></div>
		                    <div class="field">
		                        <textarea id="description" name="description" maxlength="100" class="input" rows="5" cols="50" placeholder="请填写活动描述" data-validate="required:请填写活动描述"></textarea>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <div class="label">
		                        <label>状态</label></div>
		                    <div class="field">
		                        <div class="button-group button-group-small radio">
		                            <label class="button active">
		                                <input name="status" value="1" checked="checked" type="radio" /><span class="icon icon-check"></span>前台展示</label>
		                            <label class="button">
		                                <input name="status" value="0" type="radio" /><span class="icon icon-times"></span>保存草稿</label>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <div id="tab-focus" style="min-height: 300px;">
		                <table class="table">
			                <tr>
			                    <td style="border:0px;">
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
				                            <a class="button input-file" href="javascript:select_focus('focus');">+ 浏览文件</a>
				                            <a class="button input-file" href="javascript:del_focus();">删除图片</a>
				                        </div>
				                    </div>
			                	</td>
			                </tr>
			            </table>
		            </div>
		            <div class="tab-panel" id="tab-article" style="min-height: 300px;">
		            	<table class="table">
		            	<?php 
			            for ($i=1;$i<5;$i++){
			            	?>
			            	<tr>
		            			<td>
			            			<div class="form-group">
					                    <div class="label">
					                        <label>标题</label></div>
					                    <div class="field">
					                        <input type="text" class="input" id="article<?php echo $i; ?>" name="article<?php echo $i; ?>" size="50" placeholder="报道文章<?php echo $i; ?>标题" data-validate="required:请填写报道文章<?php echo $i; ?>标题" />
					                    </div>
					                </div>
		            			</td>
		            			<td>
			            			<div class="form-group">
					                    <div class="label">
					                        <label>链接</label></div>
					                    <div class="field">
					                        <input type="text" class="input" id="url<?php echo $i; ?>" name="url<?php echo $i; ?>" size="50" placeholder="报道文章<?php echo $i; ?>链接" data-validate="required:请填写报道文章<?php echo $i; ?>链接,url:报道文章<?php echo $i; ?>链接填写错误" />
					                    </div>
					                </div>
		            			</td>
		            		</tr>
			            	<?php 
			            }
			            ?>
		            	</table>
		            </div>
		            <div class="tab-panel" id="tab-pic" style="min-height: 300px;">
		            	<table class="table">
		            		<tr>
		            		<?php 
			            	for ($i=1;$i<7;$i++){
			            		?>
			            		<td style="border:0px;">
			            			<div class="form-group">
			            				<div for="pic<?php echo $i; ?>" style="width:246px;height:246px;border:1px solid #d7d7d7;text-align:center;">
				                            <label>花絮图片<?php echo $i; ?>:</label>
				                        </div>
			            			</div>
				            		<div class="form-group">
				                        <div class="field">
				                        	<input type="hidden" for-index="<?php echo $i; ?>" id="pic<?php echo $i; ?>" name="pic<?php echo $i; ?>" />
				                            <a class="button input-file" href="javascript:select_pic('pic<?php echo $i; ?>');">+ 浏览文件</a>
				                            <a class="button input-file" href="javascript:del_pic(<?php echo $i; ?>);">删除图片</a>
				                        </div>
				                	</div>
			            		</td>
			            		<?php 
			            	}
			            	?>	
		            		</tr>
		            	</table>
		            </div>
			        <div class="form-button" style="text-align:center;margin-top: 50px;">
			        	<button class="button bg-main" type="submit">提交</button></div>
			   	</form>
	        </div>
	    </div>
	    <p class="text-right text-gray">基于<a class="text-gray" target="_blank" href="javascript:void(0);">居俪网</a>构建</p>
	</div>
</body>
</html>