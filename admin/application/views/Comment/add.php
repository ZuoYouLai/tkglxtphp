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
    <link type="text/css" rel="stylesheet" href="statics/css/common.css" />
    <script type="text/javascript" src="statics/js/jquery.js"></script>
    <script type="text/javascript" src="statics/js/pintuer.js"></script>
    <script type="text/javascript" src="statics/js/respond.js"></script>
    <script type="text/javascript" src="statics/js/canvasFuns.js"></script>
    <script type="text/javascript" src="statics/js/common.js"></script>
	<script type="text/javascript" src="statics/plugin/layer-v1.8.2/layer.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('#pic_div').hide();//隐藏上传图片
			$('#faces_div').hide();//隐藏选择表情
			$('#friend_div').hide();//隐藏选择好友
		});
		//选择表情函数
	    function add_face(fname){
			var content=$("#content").val();//话题内容
			var newtext = content + "" + fname + " " ;
			$("#content").val(newtext);
			$('#faces_div').hide();
		}
		//选择图片弹框
		function select_pic(){
			$('#pic_div').show();
			$('#faces_div').hide();
			showSelectPic();
		}
		//选择表情弹框
		function select_face(){
			if($("#faces_div").is(":hidden")){
				$('#pic_div').hide();
				$('#faces_div').show();
			}else{
				$('#faces_div').hide();
			}
		}
		//选择好友弹框
		function select_friend(){
			if($("#friend_div").is(":hidden")){
				$('#friend_div').show();
				$('#pic_div').hide();
				$('#faces_div').hide();
			}else{
				$('#friend_div').hide();
			}
		}
		//选择好友函数
		function add_friend(fname){
			var content=$("#content").val();//话题内容
			if (content.indexOf(" @" + fname + " ") == -1) {
	    		// 不重复@好友
	    		var newtext = content + " @" + fname + " " ;
	    		$("#content").val(newtext);
			}
			$('#friend_div').hide();
		}
		//显示评论
		function show_comment(pid,pname){
			$("#pid").val(pid);
			$("[for='content']").html("@"+pname);
			window.location.hash='#reply';
		}
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
		                if ($("#pic_div ul li").length < 10) {
		                	$("#li_img").show();
			            }
		                $("#img_num").html(parseInt($("#img_num").html()) + 1);
		                return false;
		            });
		
		            $("#li_img").before(addimg);
		            if ($("#pic_div ul li").length >= 1) {
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
		            $("#image_data").val(getImgToCanvasData(this, b_nw, b_nh));
		            //imgsData[imgsData.length] = getImgToCanvasData(this, b_nw, b_nh);
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
	        if ($("[name='create_id']").val()==null) {
	        	layer.msg("请选择发帖人");
	            return false;
	        }
	        if ($.trim($("#content").val()) == "") {
	        	layer.msg("请填写评论内容");
	            return false;
	        }
		}
	</script>
</head>
<body>
    <div class="admin">
        <div class="tab">
            <div class="tab-head">
                <strong>发表评论/回复</strong>
            </div>
            <div class="tab-body">
			    <div class="container" style="padding-top:15px;">
			        <div class="cont_box">
			            <div class="box_layout center_v">
			                <div class="userImg">
			                    <img src="<?php echo $creater['thumb']; ?>"></div>
			                <div class="box_col basicInfo">
			                    <div class="userName">
			                    	<?php echo $creater['nick_name']; ?>
			                    </div>
			                    <div class="loc">
			                        <img src="statics/images/location.png" class="ilb">
			                        <span class="ilb"><?php echo $post['province'].$post['city']; ?></span>
			                    </div>
			                    <div class="time">
			                    	<?php echo date("Y-m-d H:i:s",$post['create_time']); ?>
			                    </div>
			                </div>
			            </div>
			            <div class="contDetail">
			                <p>
			                	<?php echo $post['content']; ?>
			                </p>
			                <?php 
			                if ($post_image){
			                	?>
			                	<div class="media_pic_list">
				                    <ul>
				                    <?php 
				                    foreach ($post_image as $item){
				                    	?>
				                    	<li pid="355" fid="1" fname="<?php echo $item['name']; ?>">
				                            <img style="width:100%;display:block;" src="<?php echo $item['thumb']; ?>"></li>
				                    	<?php 
				                    }
				                    ?>
				                    </ul>
				                </div>
			                	<?php 
			                }
			                ?>
			            </div>
			            <div class="moreDetail box_layout center_v">
			                <div class="box_col">
			                    <img src="statics/images/views.png" class="ilb">
			                    <span class="ilb"><?php echo $post['visit_num']; ?></span>
			               	</div>
			                <div class="box_col" onclick="show_comment(0,'<?php echo $creater['nick_name']; ?>');">
			                    <img src="statics/images/reply.png" class="ilb">
			                    <span class="ilb"><?php echo $post['reply_num']; ?></span>
			                </div>
			                <div class="box_col" id="zan_click" onclick="add_favorite();">
			                    <img src="statics/images/zan.png" class="ilb" id="zan_pic">
			                    <span class="ilb" id="zan_num"><?php echo $post['coll_num']; ?></span>
			                </div>
			            </div>
			        </div>
			    </div>
			    <div class="reply_wrapper">
			        <ul>
			        <?php 
				    if ($comments){
				    	foreach ($comments as $comment){
				    		?>
				    			<li>
				    			<?php 
				    			if ($comment['reply']){//有回复的评论
				    				?>
				    				<div class="reply_cont box_layout">
					            		<div class="reply_left">
					            			<div class="userImg"><img style="width:100%;display:block;" src="<?php echo $comment['creater']['thumb']; ?>"/></div>
					            			<div class="reply_order"><?php echo $comment['sort']; ?></div>
					            		</div>
						            	<div class="reply_right box_col">
						            		<div class="reply_box"><?php echo $comment['reply']['content']; ?>
							            		<?php 
							            		if ($comment['reply']['content_pic']!=''){
							            			?>
							            			<div class="media_pic_list">
							            				<ul>
							            					<li pid="<?php echo $post['id']; ?>" fid="1" fname="<?php echo $comment['reply']['content_pic']; ?>">
							            						<img style="width:100%;display:block;" style="height:76px;width:76px;" fid="<?php echo $comment['reply']['content_pic']; ?>" src="<?php echo $comment['reply']['thumb']; ?>">
							            					</li>
							            				</ul>
							            			</div>
							            			<?php 
							            		}
							            		?>
							            		<div class="reply_inside">
							            			@<?php echo $comment['member']['nick_name']; ?><div class="ilb"><?php echo $comment['content']; ?></div>
							            			<?php 
							            			if ($comment['content_pic']!=''){
							            				?>
							            				<div class="media_pic_list">
							            					<ul>
							            						<li pid="<?php echo $post['id']; ?>" fid="1" fname="<?php echo $comment['content_pic']; ?>">
							            							<img style="width:100%;display:block;" src="<?php echo $comment['thumb']; ?>">
							            						</li>
							            					</ul>
							            				</div>
							            				<?php 
							            			}
							            			?>
												</div>
								       		</div>
							           		<div class="name_time box_layout">
							            		<div class="userName"><?php echo $comment['creater']['nick_name']; ?></div>
							            		<div class="reply_time box_col"><?php echo date("Y-m-d",$comment['reply']['create_time']); ?></div>
							            		<div class="text_reply" onclick="show_comment(<?php echo $comment['reply']['id']; ?>,'<?php echo $comment['creater']['nick_name']; ?>');">回复</div>
							            	</div>
						            	</div>
					           		</div>
				    				<?php 
				    			}else{//没有被回复的一级评论
				    				?>
				    				<div class="reply_cont box_layout">
					            		<div class="reply_left">
					            			<div class="userImg"><img style="width:100%;display:block;" src="<?php echo $comment['member']['thumb']; ?>"/></div>
					            			<div class="reply_order"><?php echo $comment['sort']; ?></div>
					            		</div>
					            		<div class="reply_right box_col">
						            		<div class="reply_box"><?php echo $comment['content']; ?>
						            		<?php 
						            		if ($comment['content_pic']!=''){
						            			?>
						            			<div class="media_pic_list">
						            				<ul>
						            					<li style="height:76px;width:76px;" pid="<?php echo $post['id']; ?>" fid="1" fname="<?php echo $comment['content_pic']; ?>">
						            						<img style="width:100%;display:block;" fid="<?php echo $comment['content_pic']; ?>" src="<?php echo $comment['thumb']; ?>">
						            					</li>
						            				</ul>
						            			</div>
						            			<?php 
						            		}
						            		?>
						            		</div>
					            			<div class="name_time box_layout">
					            				<div class="userName"><?php echo $comment['member']['nick_name']; ?></div>
					            				<div class="reply_time box_col"><?php echo date("Y-m-d",$comment['create_time']); ?></div>
					            				<div class="text_reply" onclick="show_comment(<?php echo $comment['id']; ?>,'<?php echo $comment['member']['nick_name']; ?>');">回复</div>
					            			</div>
					            		</div>
					            	</div>
				    				<?php 
				    			}
				    			?>
				    			</li>
				    			<?php 
				    	}
				    }
				    ?>
			        </ul>
			    </div>
			</div>
            <div class="tab-body" id="#reply" name="#reply">
                <br />
                <div class="tab-panel active" id="tab-set">
                    <form method="post" class="form-x" action="index.php/Comment/add" onsubmit="return checkForm();">
                    <div style="display:none;" id="div_data">
	                	<input type="hidden" name="cmd" value="submit" />
	                	<input type="hidden" id="pid" name="pid" value="0" />
	                	<input type="hidden" id="action" name="action" value="<?php echo $action; ?>" />
	                	<input type="hidden" id="sub_id" name="sub_id" value="<?php echo $post['id']; ?>" />
	                	<input type="hidden" id="image_data" name="image_data" value="" />
	                </div>
                    <div class="form-group">
	                    <div class="label">
	                        <label for="sitename">评论人</label></div>
	                    <div class="field">
	                        <?php 
	                        if ($author){
	                        	?>
	                        	<ul style="list-style:none;margin: 0px;padding: 0px;width: auto;overflow-y:auto;height:100px;border:1px solid #d7d7d7;">
	                        	<?php 
	                        	for($i=0;$i<count($author);$i++){
	                        		?>
	                        		<li style="float:left;margin-right:20px;margin-top:10px;margin-left:10px;">
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
                            <label>评论内容</label></div>
                        <div class="field" for="content">
                            <?php echo '@'.$creater['nick_name']; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label>&nbsp;</label></div>
                        <div class="field">
                            <textarea class="input" rows="5" id="content" name="content" cols="50" placeholder="评论/回复内容"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="readme">&nbsp;</label></div>
                        <div class="field">
                            <img src="statics/images/btn_input_selected.png" style="max-height:30px;width:auto;display: -webkit-inline-box;" onclick="$('#content').focus();">
                            <img src="statics/images/btn_talk_local_pic_selected.png" style="max-height:30px;width:auto;display: -webkit-inline-box;" onclick="select_pic();">
                            <img src="statics/images/btn_face_selected.png" style="max-height:30px;width:auto;display: -webkit-inline-box;" onclick="select_face();">
                            <img src="statics/images/btn_at_friends_selected.png" style="max-height:30px;width:auto;display: -webkit-inline-box;" onclick="select_friend();">
                        </div>
                    </div>
                    <div class="form-group" id="pic_div">
				    	<div class="label"><label>&nbsp;</label></div>
				      	<div class="field">
				           	添加图片<font style="color:#ADA9A9;">(只可以上传<span id="img_num">1</span>张)</font>
							<ul style="list-style:none;padding:0;margin-top:15px;">
								<li  style="float: left;width: 88px;height: 88px;margin-right: 8px;margin-bottom: 6px;overflow: hidden;" id="li_img" onclick="showSelectPic();">
									<div><img src="statics/images/addPic.png" /></div>
								</li>
							</ul>
				     	</div>
				   	</div>
				   	<div class="form-group" id="faces_div">
				    	<div class="label"><label>&nbsp;</label></div>
				      	<div class="field">
				           	<?php 
	                        if ($icons){
	                        	?>
	                        	<ul style="list-style:none;margin: 0px;padding: 0px;width: auto;overflow-y:auto;height:180px;border:1px solid #d7d7d7;">
	                        	<?php 
	                        	for($i=0;$i<count($icons);$i++){
	                        		?>
	                        		<li style="float:left;margin-right:20px;margin-top:10px;">
	                                    <span><img onclick="add_face('<?php echo $icons[$i]['code']; ?>');" src="<?php echo $icons[$i]['name']; ?>" alt="<?php echo $icons[$i]['code']; ?>"></span>
	                        		</li>
	                        		<?php 
	                        	}
	                        	?>	
	                        	</ul>
	                        	<?php 
	                        }
	                        ?>
				     	</div>
				   	</div>
				   	<div class="form-group" id="friend_div">
				    	<div class="label"><label>&nbsp;</label></div>
				      	<div class="field">
				           	<?php 
	                        if ($sis){
	                        	?>
	                        	<ul style="list-style:none;margin: 0px;padding: 0px;width: auto;overflow-y:auto;height:180px;border:1px solid #d7d7d7;">
	                        	<?php 
	                        	for($i=0;$i<count($sis);$i++){
	                        		if ($sis[$i]['nick_name']!=''){
	                        			?>
		                        		<li style="float:left;margin-right:20px;margin-top:10px;" class="button">
		                                    <span onclick="add_friend('<?php echo $sis[$i]['nick_name']; ?>');"><?php echo $sis[$i]['nick_name']; ?></span>
		                        		</li>
		                        		<?php 
	                        		}
	                        	}
	                        	?>	
	                        	</ul>
	                        	<?php 
	                        }
	                        ?>
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