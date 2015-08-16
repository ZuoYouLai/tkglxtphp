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
    <script type="text/javascript" src="statics/js/canvasFuns.js"></script>
    <script type="text/javascript" src="statics/js/common.js"></script>
	<script type="text/javascript" src="statics/plugin/layer-v1.8.2/layer.min.js"></script>
	<script type="text/javascript">
		/****上传图片相关的函数****/
		var imgsData = new Array(); // 选择的图片集合(data内存对象,已经通过convas缩放处理的了)
		var savimg_minwidth = 394; // 保存的图片限制的宽度(自动用canvas重绘图片)

		//弹出选择图片的控件
		function showSelectPic() {
	        $("#f_pic").click();
	    }
		
		$(document).ready(function () {
		    // 选择文件表单改变事件(表示选择了图片的)
		    $("#f_pic").on("change", function () {
		        var url = "";
		        var file = this.files[0];
		        url = getObjectURL(file);
		        var img = new Image();
		        $(img).load(function () {
		            var width = this.width, height = this.height;
		            var nw = 0, nh = 0;
		            var preview_width = $("#li_img div").width();
		            var preview_height = $("#li_img div").height();
		            if (preview_width / preview_height < width / height) {
		                // 图片超高,用宽度平铺,计算高度
		                nw = preview_width;
		                nh = nw * height / width;
		            } else {
		                nh = preview_height;
		                nw = nh * width / height;
		            }
		            var left = (preview_width - nw) / 2;
		            var top = (preview_height - nh) / 2;
		            var html = '<li style="float: left;width: 88px;height: 88px;margin-right: 8px;margin-bottom: 6px;overflow: hidden;">'+
		            		'		<div>'+
		            		'			<img src="statics/images/delete.png" style="width:15px;height:15px;position:absolute;" />'+
		            		'			<img src="' + $(this).attr("src") + '" style="margin-top:' + top + 'px;margin-left:' + left + 'px; width:' + nw + 'px;height:' + nh + 'px;"/>'+
		            		'		</div>'+
		            		'	</li>';

		            var addimg = $(html);
		            
		            var num = parseInt($("#img_num").html());
		            $("#img_num").html(num - 1);
		            $(addimg).click(function () {
		                var currI = $(this).index();
		                $(this).remove();
		                imgsData.splice(currI, 1);
		                if ($("#div_pic ul li").length < 10) {
		                	$("#li_img").show();
			            }
		                $("#img_num").html(parseInt($("#img_num").html()) + 1);
		                return false;
		            });
		
		            $("#li_img").before(addimg);
		            if ($("#div_pic ul li").length >= 10) {
		                $("#li_img").hide();
		            }
		
		            // 生成图片区域
		            var b_x = 0;
		            var b_y = 0;
		            var b_w = 0;
		            var b_h = 0;
		            var b_nw = 0;
		            var b_nh = 0;
		
		            if (width <= savimg_minwidth) {
		                b_w = width;
		                b_h = height;
		                b_nw = width;
		                b_nh = height;
		            } else {
		                b_nw = savimg_minwidth;
		                b_nh = height * b_nw / width;
		                b_w = width;
		                b_h = height;
		            }
		            imgsData[imgsData.length] = getImgToCanvasData(this, b_nw, b_nh);
		        });
		        $(img).attr("src", url);
		    });
		});
		

		// 建立一個可存取到該file的url,兼容各种浏览器的
		function getObjectURL(file) {
		    var url = null;
		    if (window.createObjectURL != undefined) { // basic
		        url = window.createObjectURL(file);
		    } else if (window.URL != undefined) { // mozilla(firefox)
		        url = window.URL.createObjectURL(file);
		    } else if (window.webkitURL != undefined) { // webkit or chrome
		        url = window.webkitURL.createObjectURL(file);
		    }
		    return url;
		}
		//验证form
		function checkForm(){
	        if ($("#circle_id").val()==null) {
	        	layer.msg("请选择圈子");
	            return false;
	        }
	        if ($("[name='create_id']").val()==null) {
	        	layer.msg("请选择发帖人");
	            return false;
	        }
	        if ($.trim($("#content").val()) == "") {
	        	layer.msg("请填写帖子内容");
	            return false;
	        }
	        //处理图片内容
	        if(imgsData.length>0){
	        	$("#picnum").val(imgsData.length);
	        	var datahtml = '';
	        	for (var i = 0; i < imgsData.length; i++) {
	                datahtml = datahtml + '<input type="hidden" name="image_data'+i+'" id="image_data'+i+'" value="'+imgsData[i]+'" />';
	            }
	            $("#div_data").append(datahtml);
	        }
		}
	</script>
</head>
<body>
    <div class="admin">
        <div class="tab">
            <div class="tab-head">
                <strong>发表帖子</strong>
            </div>
            <div class="tab-body">
                <br />
                <div class="tab-panel active" id="tab-set">
                    <form method="post" class="form-x" action="index.php/EditorPost/add" onsubmit="return checkForm();">
                    <div style="display:none;" id="div_data">
	                	<input type="hidden" name="cmd" value="submit" />
	                	<input type="hidden" id="picnum" name="picnum" value="0" />
	                	<input type="hidden" name="image_data" value="" />
	                </div>
	                <div class="form-group">
                        <div class="label"><label for="siteurl">选择圈子</label></div>
                        <div class="field">
                            <select id="circle_id" name="circle_id" class="input">
                            	<?php 
                            	if ($circle){
	                            	foreach ($circle as $item){
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
                        <div class="label">
                            <label>评论</label></div>
                        <div class="field">
                            <div class="button-group button-group-small radio">
                                <label class="button active"><input name="allow_reply" value="1" checked="checked" type="radio"><span class="icon icon-check"></span>开启</label>
                                <label class="button"><input name="allow_reply" value="0" type="radio"><span class="icon icon-times"></span>关闭</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
	                    <div class="label">
	                        <label for="sitename">发帖人</label></div>
	                    <div class="field">
	                        <?php 
	                        if ($author){
	                        	?>
	                        	<ul style="list-style:none;margin: 0px;padding: 0px;width: auto;">
	                        	<?php 
	                        	for($i=0;$i<count($author);$i++){
	                        		?>
	                        		<li style="float:left;margin-right:20px;">
		                        		<label class="button active">
	                                    <input name="create_id" value="<?php echo $author[$i]['id']; ?>" <?php echo $i==0?'checked="checked"':''; ?> type="radio"><?php echo $author[$i]['nick_name']; ?></label>
	                        		</li>
	                        		<?php 
	                        	}
	                        	?>	
	                        	</ul>
	                        	<?php 
	                        }else{
	                        	?>
	                        	暂无系统会员
	                        	<?php 
	                        }
	                        ?>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="siteurl">地理位置</label></div>
	                    <div class="field">
	                        <select id="province" name="province" class="input" style="width:30%;float: left;margin-right:15px;"></select> 
			                <select id="city" name="city" class="input" style="width:30%;float: left;margin-right:15px;"></select>
			                <select id="district" name="district" class="input" style="width:30%;float: left;margin-right:15px;"></select>
							<script type="text/javascript" src="statics/js/area.js"></script>
							<script type="text/javascript">
								_init_area();
							</script>
	                    </div>
	                </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="readme">帖子内容</label></div>
                        <div class="field">
                            <textarea class="input" rows="5" id="content" name="content" cols="50" placeholder="帖子内容"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
				    	<div class="label"><label>&nbsp;</label></div>
				      	<div class="field" id="div_pic">
				           	添加图片<font style="color:#ADA9A9;">(还可以添加<span id="img_num">9</span>张)</font>
							<ul style="list-style:none;padding:0;margin-top:15px;">
								<li  style="float: left;width: 88px;height: 88px;margin-right: 8px;margin-bottom: 6px;overflow: hidden;" id="li_img" onclick="showSelectPic();">
									<div><img src="statics/images/addPic.png" /></div>
								</li>
							</ul>
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
    <input type="file" id="f_pic" style="position: absolute; top: -100px;" />
</body>
</html>