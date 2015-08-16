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
                <strong>添加会员</strong>
            </div>
            <div class="tab-body">
                <br />
                <div class="tab-panel active" id="tab-set">
                    <form method="post" class="form-x" action="index.php/EditorMember/add">
                    <div style="display:none;">
	                	<input type="hidden" name="cmd" value="submit" />
	                </div>
	                <div class="form-group">
                        <div class="label">
                            <label for="readme">会员昵称</label></div>
                        <div class="field">
                            <input type="text" class="input" id="nick_name" name="nick_name" size="50" placeholder="会员昵称" data-validate="required:请填写会员昵称" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="readme">手机号码</label></div>
                        <div class="field">
                            <input type="text" class="input" id="tel" name="tel" size="50" placeholder="手机号码" data-validate="required:请填写手机号码" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="label">
                            <label for="sitename">登录密码</label></div>
                        <div class="field">
                            <input type="password" class="input" id="password" name="password" size="50" placeholder="登录密码" data-validate="required:请填写登录密码" />
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
