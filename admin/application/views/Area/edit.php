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
</head>
<body>
    <div class="admin">
        <div class="tab">
            <div class="tab-head">
                <strong>修改权限</strong>
            </div>
            <div class="tab-body">
                <br />
                <div class="tab-panel active" id="tab-set">
                    <form method="post" class="form-x" action="index.php/Area/edit">
                    <div style="display:none;">
	                	<input type="hidden" name="cmd" value="submit" />
	                	<input type="hidden" name="Area_id" value="<?php echo $Area['id']; ?>" />
	                </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="sitename">地区名称</label></div>
                        <div class="field">
                            <input type="text" class="input" id="title" name="title" size="50" value="<?php echo $Area['title']; ?>" placeholder="地区名称" data-validate="required:请填写地区名称" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label"><label for="siteurl">类型</label></div>
                        <div class="field">
                            <select id="level" name="level" class="input">
	                            <option value="1" <?php echo $Area['level']=='1'?'selected="selected"':''; ?>>省/直辖市</option>
                            	<option value="2" <?php echo $Area['level']=='2'?'selected="selected"':''; ?>>市</option>
                            	<option value="3" <?php echo $Area['level']=='2'?'selected="selected"':''; ?>>县/区</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label"><label>父节点</label></div>
                        <div class="field">
                            <select id="pid" name="pid" class="input">
	                            	<option value="0">中国</option>
	                            <?php
                            	if ($parent){
	                            	foreach ($parent as $par){
			        					?>
			        					<option value="<?php echo $par['id']; ?>" <?php echo $Area['pid']==$par['id']?'selected="selected"':''; ?>><?php echo $par['level']=='1'?$par['title']:($par['level']=='2'?'&nbsp;&nbsp;&nbsp;&nbsp;|--'.$par['title']:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--'.$par['title']); ?></option>
			        					<?php 
			        				}
                            	}
                            	?>
                            </select>
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
