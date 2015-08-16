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
    <script type="text/javascript" src="statics/js/area.js"></script>
</head>
<body>
    <div class="admin">
	    <div class="tab">
	        <div class="tab-head">
	            <strong>修改名媛专题</strong>
	            <ul class="tab-nav">
	                <li class="active"><a href="#tab-set" onclick="$('#tab-focus').hide();">基本信息</a></li>
	            </ul>
	        </div>
	        <div class="tab-body">
	        <form method="post" class="form-x" action="index.php/Famous/edit">
		        <div style="display:none;">
		        	<input type="hidden" name="cmd" value="submit" />
		        	<input type="hidden" name="index" value="1" />
		        	<input type="hidden" name="id" value="<?php echo $famous['id']; ?>" />
		       	</div>
	            <div class="tab-panel active" id="tab-set">
	                <div class="form-group">
	                    <div class="label">
	                        <label>标题</label></div>
	                    <div class="field">
	                        <input type="text" class="input" id="title" name="title" value="<?php echo $famous['title']; ?>" size="50" placeholder="标题" data-validate="required:请填写标题" />
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="sitename">作者</label></div>
	                    <div class="field">
	                        <?php 
	                        if ($lady){
	                        	?>
	                        	<ul style="list-style:none;margin: 0px;padding: 0px;width: auto;">
	                        	<?php 
	                        	for($i=0;$i<count($lady);$i++){
	                        		?>
	                        		<li style="float:left;margin-right:20px;">
		                        		<label class="button <?php echo $famous['create_id']==$lady[$i]['id']?'active':''; ?>">
	                                    <input name="create_id" value="<?php echo $lady[$i]['id']; ?>" <?php echo $famous['create_id']==$lady[$i]['id']?'checked="checked"':''; ?> type="radio"><?php echo $lady[$i]['nick_name']; ?></label>
	                        		</li>
	                        		<?php 
	                        	}
	                        	?>	
	                        	</ul>
	                        	<?php 
	                        }else{
	                        	?>
	                        	暂无名媛
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
								opt0 = ["<?php echo $famous['province']; ?>","<?php echo $famous['city']; ?>","<?php echo $famous['district']; ?>"];
								_init_area();
							</script>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="siteurl">描述</label></div>
	                    <div class="field">
	                        <textarea id="description" name="description" maxlength="100" class="input" rows="5" cols="50" placeholder="请填写专题描述" data-validate="required:请填写专题描述,length#<200:小于200汉字"><?php echo $famous['description']; ?></textarea>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="label">
	                        <label for="title">内容</label></div>
	                    <div class="field">
	                        <!-- 加载编辑器的容器 -->
	                        <script id="content" name="content" type="text/plain"><?php echo html_entity_decode($famous['content']); ?></script>
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
	                            <label class="button <?php echo $famous['status']=='1'?'active':''; ?>">
	                                <input name="status" value="1" <?php echo $famous['status']=='1'?'checked="checked"':''; ?> type="radio" /><span class="icon icon-check"></span>前台展示</label>
	                            <label class="button <?php echo $famous['status']=='0'?'active':''; ?>">
	                                <input name="status" value="0" <?php echo $famous['status']=='0'?'checked="checked"':''; ?> type="radio" /><span class="icon icon-times"></span>保存草稿</label>
	                        </div>
	                    </div>
	                </div>
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